<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Audit;
use App\Models\Danger;
use App\Models\Reason;
use App\Models\Sector;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\Monitor;
use App\Traits\PdfTrait;
use App\Models\OrderSector;
use App\Models\Organization;
use App\Models\ReasonDanger;
use Illuminate\Http\Request;
use App\Models\OperationType;
use App\Traits\WhatsappTrait;
use App\Models\AttachmentLabel;
use function PHPSTORM_META\type;
use App\Notifications\CrudNotify;
use App\Models\MonitorOrderSector;
use App\Models\TicketReasonDanger;
use App\Traits\CrudOperationTrait;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Services\MailService;
use App\Jobs\GenerateAndSendPDFJob;
use App\Traits\AttachmentTrait;
use App\Traits\LocationTrackerTrait;
use App\Traits\SmsTrait;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    use PdfTrait, WhatsappTrait, CrudOperationTrait, LocationTrackerTrait, AttachmentTrait, SmsTrait;
    //??=========================================================================================================
    public function index(Request $request)
    {
        //
        $columns = Ticket::columnNames();
        $statuses = Status::where('type', 'tickets')->select('id', 'name_ar', 'name_en')->get();
        $dangers = Danger::select('id', 'level')->get();
        // $ticket_reasons = Reason::all();
        $reason_dangers = ReasonDanger::with([
            'organization:id,name_ar,name_en',
            'reason:id,name',
        ])->where('operation_type_id', OperationType::RAISE_TICKET)->get()->sortBy('organization_id');
        $sectors = Sector::with([
            'classification.organization:id,name_ar,name_en',
        ])->select('id', 'label', 'sight', 'classification_id')->get()->sortBy('classification.organization.name_ar');
        $organizations = Organization::select('id', 'name_ar', 'name_en')->get();

        return view('admin.tickets.index', compact('statuses', 'dangers', 'reason_dangers', 'sectors', 'organizations', 'columns'));
    }
    //??=========================================================================================================
    public function create($request)
    {
        // dd($request);
        $ticket = Ticket::create([
            "reason_danger_id" => $request->reason_danger,
            "user_id" => $request->monitor,
            "status_id" => Status::NEW_TICKET,
            "order_sector_id" => $request->order_sector,
        ]);
        if ($request->has("notes")) {
            $ticket->notes()->create(['content' => $request->notes, 'user_id' => $request->monitor]);
        }
        foreach ($request->attachments as $key => $attachment) {
            $this->store_attachment($attachment, $ticket, $key, null, $request->monitor);
        }
        $action = trans('translation.Create ticket: ') . $ticket->code;
        $this->tracker($request, $ticket, $action);
        return $ticket;
    }
    //??=========================================================================================================
    public function store(Request $request)
    {
        $ticket = $this->create($request);
        $user = User::find($request->monitor);
        $order_sector = OrderSector::findOrFail($request->order_sector);
        $sector = Sector::findOrFail($order_sector->sector_id);
        $message = trans('translation.send-whatsapp-create-new-ticket-admin', ['user' => $user->name, 'code' => $ticket->code, 'sector' => $sector->label, 'user_name' => auth()->user()->name]);
        $sender = null; //
        $whatsapp_response = $this->send_message($sender, $message, $user->phone_code . $user->phone);
        $sending_sms = $this->send_sms($sender, $message, $user->phone, $user->phone_code);

        $user->notify(new CrudNotify($ticket, 'create'));
        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
    }
    //??=========================================================================================================
    public function show(Ticket $ticket)
    {
        $ticket->load('order_sector.sector.classification.organization', 'order_sector.sector', 'user', 'reason_danger.reason', 'status', 'order_sector.order', 'user.bravo', 'attachments', 'attachments.attachment_label', 'order_sector.monitor_order_sectors.monitor.user', 'notes', 'notes.user');
        $statuses = Status::where('type', 'tickets')->get();
        $progress_statuses = $statuses->where('id', '!=', Status::FALSE_TICKET);
        // $notes = Audit::all()->where('auditable_type', 'App\Models\Ticket')->where('auditable_id', $ticket->id)->sortByDesc('updated_at');
        // if(!$notes){
        //     $notes = 'لا يوجد ملاحظات';
        // }

        // order_sector_id = $ticket->sector->id;
        // $current_monitor = Monitor::whereHas('monitor_sector', function($q) use(order_sector_id){
        //     $q->where('active', 1)->where('order_sector_id', order_sector_id);
        // })->first();

        // $notes = $notes->filter(function ($audit) {
        //     $oldValues = json_decode($audit->old_values, true);
        //     $newValues = json_decode($audit->new_values, true);

        //     // Check if "notes" has changed
        //     return isset($oldValues['notes']) || isset($newValues['notes'])
        //     && $oldValues['notes'] !== $newValues['notes'];
        // });
        $closed_status = Status::CLOSED_TICKET;
        $false_status = Status::FALSE_TICKET;
        $canChangeTicketStatus = auth()->user()->canChangeTicketStatus();
        return view('admin.tickets.show', compact('ticket', 'statuses', 'closed_status', 'canChangeTicketStatus', 'false_status', 'progress_statuses'));
    }
    //??=========================================================================================================
    public function update(Request $request, Ticket $ticket, $key = null, $value = null, $url = null)
    {
        $ticket->update(["$key" => $value]);

        if ($url != null) {
            return redirect($url);
        }
    }
    //??=========================================================================================================
    public function validate_change_status($request, $status, Ticket $ticket)
    {
        if ($request->old_status_id == Status::CLOSED_TICKET || $request->old_status_id == Status::FALSE_TICKET) {
            // dd(auth()->user()->checkTicketStatusPermissions($status));
            if (auth()->user()->checkTicketStatusPermissions($status)) {
                $this->change_status($request, $status, $ticket);
                return true;
            }
            return false;
        }
        $this->change_status($request, $status, $ticket);
        return true;
    }
    //??=========================================================================================================
    public function change_status($request, $status, Ticket $ticket)
    {
        $this->update($request, $ticket, 'status_id', $status);

        $query = Status::all()->where('name', 'مغلق')->where('type', 'tickets')->first();
        $this->update($request, $ticket, 'closed_at', ($status == Status::CLOSED_TICKET || $status == Status::FALSE_TICKET) ? Carbon::now() : null);
    }
    //??=========================================================================================================
    public function update_status(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);
        if ($this->validate_change_status($request, $request->status_id, $ticket)) {
            $message = trans("translation.send-whatsapp-change-ticket-status", ['code' => $ticket->code, 'status' => $ticket->status->name]);
            $sectors = MonitorOrderSector::where('order_sector_id', $ticket->order_sector_id)->get(); //->pluck('monitor_id');
            foreach ($sectors as $sector) {
                $sender = null; // $ticket->order_sector->order->organization->sender
                User::find($sector->monitor->user->id)->notify(new CrudNotify($ticket, 'changeStatus'));
                $whatsapp_response = $this->send_message($sender, $message, $sector->monitor->user->phone_code . $sector->monitor->user->phone);
                $sending_sms = $this->send_sms($sender, $message, $sector->monitor->user->phone, $sector->monitor->user->phone_code);
            }
            if (
                config('app.email_flag') &&
                (
                    (
                        $request->old_status_id == Status::PROCESSING_TICKET &&
                        $request->status_id == Status::IN_PROGRESS_TICKET
                    ) ||
                    (
                        $request->old_status_id == Status::IN_PROGRESS_TICKET &&
                        $request->status_id == Status::CLOSED_TICKET
                    )
                )
            ) {
                $organization = $ticket->order_sector->order->organization_service->organization;
                $statuses = Status::where('type', 'tickets')->get();
                $danger_levels = Danger::get();
                $pdfData = [
                    'attachment_label' => 'تقرير بلاغ',
                    'organization_data' => $organization,
                    'sector' => $ticket->order_sector->sector,
                    'body_content' => $ticket,
                    'statuses' => $statuses,
                    'danger_levels' => $danger_levels
                ];

                $statusFrom = $statuses->firstWhere('id',$request->old_status_id);
                $statusTo = $statuses->firstWhere('id',$request->status_id);
                $templateData = [
                    'ticketCode' => $ticket->code,
                    'statusFrom' => $statusFrom?->name_ar,
                    'statusFromColor' => $statusFrom?->color,
                    'statusTo' =>  $statusTo?->name_ar,
                    'statusToColor' =>  $statusTo?->color,
                ];

                GenerateAndSendPDFJob::dispatch(
                    organization: $organization,
                    pdfName: $ticket->code . ' - ' . $ticket->order_sector->order->facility->name . ' - ' . Carbon::now() . '.pdf',
                    pdfTemplate: 'ticket.ticket-template',
                    pdfData: $pdfData,
                    mailTopic: 'تغيير حالة بلاغ',
                    mailTemplate: 'mails.templates.ticket-template',
                    mailTemplateData: $templateData,
                );
            }
            return response()->json(['message' => 'save status successful'], 200);
        };
        return response()->json(['message' => 'You do not have permission'], 400);
    }
    //??=========================================================================================================
    public function update_notes(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);
        // $this->update($request, $ticket, 'notes', $request->new_notes);
        $ticket->notes()->create(['content' => $request->notes, 'user_id' => auth()->user()->id]);

        return response()->json(['message' => 'save notes successful', 'alert-type' => 'success'], 200);
    }
    //??=========================================================================================================
    public function datatable(Request $request)
    {

        $in_dashboard = request('in_dashboard') ?? false;
        $query = Ticket::with([
            'user:id,name,bravo_id',
            'user.bravo:id,number',
            'reason_danger.danger:id,level,color',
            'reason_danger.reason:id,name',
            'order_sector.sector:id,label,sight',
            // 'order_sector.monitor_order_sectors.monitor:id,monitor_id',
            'order_sector.monitor_order_sectors.monitor.user:id,name',
            'order_sector.order.organization_service.organization:id,name_en,name_ar,slug',
            'order_sector.order.facility:id,name',
            'status:id,name_ar,name_en,color',
        ]);
        $statuses = Status::all()->where('type', 'tickets');
        $closed_status = Status::CLOSED_TICKET;
        $false_status = Status::FALSE_TICKET;
        $canChangeTicketStatus = auth()->user() ? auth()->user()->canChangeTicketStatus() : null;
        // dd($query->whereHas('order_sector.order.organization')->get());
        if (\request('organization_id')) {
            $query->whereHas('order_sector.sector.classification', function ($q) {
                $q->whereIn('organization_id', \request('organization_id'));
            });
        }
        if (\request('sector_id')) {
            $query->whereHas('order_sector', function ($q1) {
                $q1->whereIn('sector_id', \request('sector_id'));
            });
        }
        if (\request('reason_id')) {
            $query->whereIn('reason_danger_id', \request('reason_id'));
        }
        if (\request('status_id')) {
            $query->whereIn('status_id', \request('status_id'));
        }
        if (\request('danger_id')) {
            $query->whereHas('reason_danger', function ($q) {
                $q->whereIn('danger_id', \request('danger_id'));
            });
        }

        $query->orderByDesc('created_at');

        if(request()->input('isPaginated', false))
        {
            $length = $request->input('length', 10);
            $start = $request->input('start', 0);
            $page = ($start / $length) + 1;

            $paginatedQuery = $query->paginate($length, ['*'], 'page', $page);
            $finalQuery = $paginatedQuery->getCollection();
            $recordsTotal = $paginatedQuery->total();
            $recordsFiltered = $paginatedQuery->total();
        }
        else{
            $finalQuery = $query->get();
            $recordsTotal = $query->count();
            $recordsFiltered = $query->count();
        }

        $transformedData = $finalQuery->map(function ($ticket) use ($in_dashboard) {
            $code = $ticket->order_sector->order->organization_service->organization->slug .
                '-TK' . str_pad($ticket->id, 4, '0', STR_PAD_LEFT) . '-' . 'OR' .
                str_pad($ticket->order_sector->order->id, 3, '0', STR_PAD_LEFT);

            return [
                'id' => $ticket->id,
                'ticket_reason_id' => $ticket->reason_danger->reason->name ?? "-",
                'code' => $code,
                'label' => $ticket->order_sector->sector->label ?? "-",
                'provider_name' => $ticket->order_sector->order->facility->name ?? "-",
                'sight' => $ticket->order_sector->sector->sight ?? "-",
                'level' => "<span class='badge ' style='background:" . $ticket->reason_danger->danger->color . "' >" . $ticket->reason_danger->danger->level . "</span>",
                'reporter_name' => $ticket->user->name ?? "-",
                'monitor' => $ticket->order_sector->monitors_name,
                'bravo' => $ticket->user->bravo->number ?? "-",
                'organization_name' => $ticket->order_sector->order->organization_service->organization->name ?? "-",
                'organization_id' => $ticket->order_sector->order->organization_service->organization->id ?? "-",
                'status_id' => "<span class='badge ' style='background:" . $ticket->status->color . "' >" . $ticket->status->name . "</span>",
                'created_at' => isset($ticket->created_at) ? $ticket->created_at . ' (' . $ticket->created_at->diffForHumans() . ')' : '',
                'updated_at' => isset($ticket->updated_at) ? $ticket->updated_at . ' (' . $ticket->updated_at->diffForHumans() . ')' : '',
                'closed_at' => isset($ticket->closed_at) ? (is_string($ticket->closed_at) ? $ticket->closed_at . ' (' . \Carbon\Carbon::parse($ticket->closed_at)->diffForHumans() . ')' : $ticket->closed_at) : '',
                'action' => $in_dashboard ? trans('translation.have-no-action') : '<a class="btn btn-outline-secondary btn-sm on-default "
                          href="' . (route('tickets.show', $ticket->id)) . '"
                          ><i class="mdi mdi-eye"></i>
                        </a>
                        <a class="btn btn-outline-primary btn-sm m-1 on-default " target="_blank" href="' . route('admin.ticket.report', $ticket->uuid ?? fakeUuid()) . '"><i class="mdi mdi-file-document-outline"></i></a>
                        ',
            ];
        });

        return response()->json([
            'data' => $transformedData,
            'draw' => intval(request()->input('draw', 1)), // Required for DataTables
            'recordsTotal' => $recordsTotal, // Total records
            'recordsFiltered' => $recordsFiltered, // Filtered records (adjust if you apply filters)
        ]);
    }
    //??=========================================================================================================
    public function pdfReport($ticket_uuid, $output = "I")
    {
        // dd($ticket_uuid);
        $ticket = Ticket::where('uuid', $ticket_uuid)->firstOrFail();
        $statuses = Status::where('type', 'tickets')->get();
        $danger_levels = Danger::get();
        $this->setPdfData([
            'attachment_label' => 'تقرير بلاغ',
            'organization_data' => $ticket->order_sector->order->organization_service->organization,
            'sector' => $ticket->order_sector->sector,
            'body_content' => $ticket,
            'statuses' => $statuses,
            'danger_levels' => $danger_levels
        ]);
        $mpdf = $this->mPdfInit('ticket.ticket-template');
        return $mpdf->Output($ticket->code . ' - ' . $ticket->order_sector->order->facility->name . ' - ' . Carbon::now() . '.pdf', $output);

        // return $this->getBladeTemplate($contract);
    }
}
