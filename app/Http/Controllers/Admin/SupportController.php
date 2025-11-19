<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Assist;
use App\Models\Period;
use App\Models\Sector;
use App\Models\Status;
use App\Models\Support;
use App\Traits\PdfTrait;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Traits\WhatsappTrait;
use App\Notifications\CrudNotify;
use App\Models\MonitorOrderSector;
use App\Http\Controllers\Controller;
use App\Models\OperationType;
use App\Http\Requests\SectorRequest;
use App\Http\Services\MailService;
use App\Jobs\GenerateAndSendPDFJob;
use App\Models\Danger;
use App\Models\Meal;
use App\Models\OrderSector;
use App\Services\AnswerService;
use App\Traits\AttachmentTrait;
use App\Traits\CrudOperationTrait;
use App\Traits\LocationTrackerTrait;
use App\Traits\SmsTrait;
use DateTime;
use Illuminate\Support\Carbon;

class SupportController extends Controller
{
    use PdfTrait, WhatsappTrait, CrudOperationTrait, LocationTrackerTrait, AttachmentTrait, SmsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $type = request()->type;
        $types = array_filter(Support::types());
        $periods = Period::whereIn('operation_type_id', [OperationType::FOOD_SUPPORT, OperationType::WATER_SUPPORT])->select('id', 'name')->get();
        $sectors = Sector::with('classification.organization',)->select('id', 'classification_id', 'label', 'sight')->get();
        $statuses = Status::support_statuses()->get();
        $columns = Support::columnNames();

        return view('admin.supports.index', compact('periods', 'sectors', 'statuses', 'columns', 'type', 'types'));
    }
    //??=========================================================================================================
    public function show(Support $support)
    {
        $support->load('order_sector.sector.classification.organization', 'order_sector.monitor_order_sectors.monitor.user','meal');
        $assists = $support->assists->load('answers');
        $assist_options = Assist::columnOption($support, $support->order_sector->sector->classification->organization);
        $assist_subtext_options = Assist::columnSubtextOption($support, $support->order_sector->sector->classification->organization);
        $in_progress_assist = Status::IN_PROGRESS_ASSIST;
        $delivered_assist = Status::DELIVERED_ASSIST;
        $has_enough_support = Status::HAS_ENOUGH_SUPPORT;
        $cancelled_support = Status::CANCELED_SUPPORT;
        $closed_statuses = [Status::HAS_ENOUGH_SUPPORT, Status::CLOSED_SUPPORT, Status::CANCELED_SUPPORT];
        $statuses = Status::where('type', 'supports')->whereNotIn('id', $closed_statuses)->get();
        $answer_service = new AnswerService();
        return view('admin.supports.show', compact('support', 'statuses', 'assists', 'assist_options', 'in_progress_assist', 'closed_statuses', 'assist_subtext_options', 'delivered_assist', 'has_enough_support', 'answer_service'));
    }
    //??=========================================================================================================
    public function changeStatus(Request $request)
    {
        $support = Support::find($request->support_id);
        $old_support_status_id = $support->status_id;
        $closedStatuses = [Status::HAS_ENOUGH_SUPPORT, Status::CLOSED_SUPPORT, Status::CANCELED_SUPPORT];

        if (in_array($support->status->id, $closedStatuses)) {
            return response()->json(['message' => trans('translation.You dont have permission')], 400);
        }

        $support->update(['status_id' => $request->status_id]);
        $support->refresh();
        $message = trans("translation.send-whatsapp-change-support-status", [
            'code' => $support->code,
            'status' => $support->status->name
        ]);

        $sectors = MonitorOrderSector::where('order_sector_id', $support->order_sector_id)->get();

        foreach ($sectors as $sector) {
            $phone = $sector->monitor->user->phone_code . $sector->monitor->user->phone;
            $whatsapp_response = $this->send_message(null, $message, $phone);
            $sending_sms = $this->send_sms(null, $message, $sector->monitor->user->phone, $sector->monitor->user->phone_code);

            $sector->monitor->user->notify(new CrudNotify($support, 'changeStatus'));
        }

        if (
            config('app.email_flag') &&
            $old_support_status_id == Status::PROCESSING_SUPPORT &&
            $request->status_id == Status::IN_PROGRESS_SUPPORT
        ) {
            $organization = $support->order_sector->order->organization_service->organization;
            $statuses = Status::where('type', 'supports')->get();
            $danger_levels = Danger::get();
            $answer_service = new AnswerService();
            $pdfData = [
                'attachment_label' => 'تقرير عن إسناد',
                'organization_data' => $organization,
                'body_content' => $support,
                'statuses' => $statuses,
                'danger_levels' => $danger_levels,
                'answer_service' => $answer_service,
            ];

            $statusFrom = $statuses->firstWhere('id', $old_support_status_id);
            $statusTo = $statuses->firstWhere('id', $request->status_id);
            $templateData = [
                'supportCode' => $support->code,
                'statusFrom' => $statusFrom?->name_ar,
                'statusFromColor' => $statusFrom?->color,
                'statusTo' =>  $statusTo?->name_ar,
                'statusToColor' =>  $statusTo?->color,
            ];

            GenerateAndSendPDFJob::dispatch(
                organization: $organization,
                pdfName: $support->id . ' - ' . $support->order_sector->order->facility->name . ' - ' . Carbon::now() . '.pdf',
                pdfTemplate: 'support.support',
                pdfData: $pdfData,
                mailTopic: 'تغيير حالة عملية اسناد',
                mailTemplate: 'mails.templates.support-template',
                mailTemplateData: $templateData,
            );
        }

        return response()->json(['message' => trans("translation.status changed succesfully")], 200);
    }

    //??=========================================================================================================
    public function create($request)
    {
        // dd($request);
        $support = Support::create([
            "reason_danger_id" => $request->reason_danger,
            "quantity" => $request->quantity,
            "type" => $request->operation_type_id,
            "user_id" => $request->monitor,
            "status_id" => Status::NEW_SUPPORT,
            "order_sector_id" => $request->order_sector,
            "period_id" => $request->period,
        ]);
        if ($request->has("notes")) {
            $support->notes()->create(['content' => $request->notes, 'user_id' => $request->monitor]);
        }
        foreach ($request->attachments as $key => $attachment) {
            $new_attachment = $this->store_attachment($attachment, $support, $key, null, $request->monitor);
        }
        $action = trans('translation.Create support: ')  .  $support->type_name  . ':' . $support->code;
        $this->tracker($request, $support, $action);

        return $support;
    }
    //??=========================================================================================================
    public function store(Request $request)
    {
        // dd($request->all());
        $support = $this->create($request);
        if ($support->period->operation_type_id == OperationType::FOOD_SUPPORT) {
            $meal = Meal::where([
                'period_id' => $support->link_meal_period($support->period_id),
                'sector_id' => $support->order_sector->sector_id,
                'day_date' => $support->created_at->format('Y-m-d')
            ])->first();
            if ($meal) {
                $support->update(['meal_id' => $meal->id]);
            }
        }
        $user = User::find($request->monitor);
        $sender = null;
        $message = trans('translation.send-whatsapp-create-new-support-admin', ['user' => $user->name, 'code' => $support->code, 'user_name' => auth()->user()->name, 'sector' => $support->order_sector->sector->label, 'type' => $support->type_name]);
        $whatsapp_response = $this->send_message($sender, $message, $user->phone_code . $user->phone);
        $sending_sms = $this->send_sms($sender, $message, $user->phone, $user->phone_code);
        $user->notify(new CrudNotify($support, 'create'));
        return back()->with(['message' => trans('translation.Support Added successfully'), 'alert-type' => 'success']);
    }
    //??=========================================================================================================
    public function findSupport($request)
    {
        $order_sector = OrderSector::find(request()->order_sector_id);
        $support = Support::where([
            'period_id' => $request->period_id,
            'type' => $request->operation_type_id,
            'reason_danger_id' => $request->reason_danger_id,
            'user_id' => $request->user()->id,
            // 'status_id' => Status::NEW_SUPPORT,
            'order_sector_id' => $request->order_sector_id
        ])
            ->whereNotIn('status_id', [Status::CLOSED_SUPPORT, Status::CANCELED_SUPPORT, Status::HAS_ENOUGH_SUPPORT])
            ->first();
        return $support;
    }
    //??=========================================================================================================
    public function update_notes(Request $request)
    {
        $support = Support::find($request->support_id);
        $support->notes()->create(['content' => $request->notes, 'user_id' => auth()->user()->id]);
        return response()->json(['message' => 'save notes successful', 'alert-type' => 'success'], 200);
    }
    //??=========================================================================================================
    public function pdfReport($support_uuid, $output = "I")
    {
        $support = Support::where('uuid', $support_uuid)->firstOrFail();
        $statuses = Status::where('type', 'supports')->get();
        $danger_levels = Danger::get();
        $answer_service = new AnswerService();
        $this->setPdfData([
            'attachment_label' => 'تقرير عن إسناد',
            'organization_data' => $support->order_sector->order->organization_service->organization,
            'body_content' => $support,
            'statuses' => $statuses,
            'danger_levels' => $danger_levels,
            'answer_service' => $answer_service,

        ]);
        $mpdf = $this->mPdfInit('support.support');
        return $mpdf->Output($support->id . '.pdf', $output);
    }
    //??=========================================================================================================
    public function datatable(Request $request)
    {
        $query = Support::with(
            'period:id,name',
            'order_sector.sector:id,label,sight,boss_id,supervisor_id',
            'order_sector.order.facility:id,name',
            'order_sector.order.organization_service.organization:id,slug',
            'notes.user',
            // 'order_sector.monitor_order_sectors:id,monitor_id,order_sector_id',
            'order_sector.sector.supervisor',
            'order_sector.sector.boss',
            'reason_danger.reason',
            'order_sector.monitor_order_sectors.monitor.user:id,name',
            'user:id,name',
            'status:id,name_ar,name_en,color',
            'assists'
        );

        $in_dashboard = request('in_dashboard') ?? false;
        // $query = Support::with('period', 'order_sector', 'user');
        if (($type = $request->type) != null) {
            $query->whereIn('type', $type);
        }

        if (request()->has('period_id')) {
            $query->whereIn('period_id', request()->period_id);
        }

        if (\request('sector_id')) {
            $query->whereHas('order_sector', function ($q1) {
                $q1->whereIn('sector_id', \request('sector_id'));
            });
        }
        if (request()->has('status_id')) {
            $query->whereIn('status_id', request()->status_id);
        }

        if (\request('organization_id')) {
            $query->whereHas('order_sector.sector.classification', function ($q) {
                $q->whereIn('organization_id', \request('organization_id'));
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

        $transformedData = $finalQuery->map(function ($support) use ($in_dashboard) {
            $code = $support->order_sector->order->organization_service->organization->slug .
                '-SP' . str_pad($support->id, 4, '0', STR_PAD_LEFT) . '-' . 'OR' .
                str_pad($support->order_sector->order->id, 3, '0', STR_PAD_LEFT);

            $notes = '';
            foreach ($support->notes as $note) {
                if ($note->content != '' && $note->content != '-') {
                    $notes .= '(' . ($note->user->name ?? '-') . ': ' . ($note->content ?? '-') . ')<br><br>';
                }
            }

            if ($notes == '') {
                $notes = trans('translation.no-notes');
            }

            return [
                'type_name' => $support->type_name ?? '-',
                'code' => $code,
                'periods' => trans('translation.' . $support->period->name) ?? '-',
                'label' => $support->order_sector->sector->label ?? '-',
                'organization_id' => $support->order_sector->order->organization_service->organization->id ?? '-',
                'reason' => $support->reason_danger->reason->name ?? '-',
                'sight' => $support->order_sector->sector->sight ?? '-',
                'providor' => $support->order_sector->order->facility->name ?? '-',
                'reporter-name' => $support->user->name ?? '-',
                'monitor-name' => $support->order_sector->monitors_name,
                'supervisor-name' => $support->order_sector->sector->supervisor->name ?? trans('translation.no-data'),
                'boss-name' => $support->order_sector->sector->boss->name ?? trans('translation.no-data'),
                'delivered-quantity' => $support->delivered_quantity ?? trans('translation.no-data'),
                'all-notes' => $notes,
                'quantity' => $support->quantity,
                'has_enough_quantity' => $support->has_enough_quantity,
                'create-time' => isset($support->created_at) ? $support->created_at . ' (' . $support->created_at->diffForHumans() . ')' : '',
                'update-time' => isset($support->updated_at) ? $support->updated_at . ' (' . $support->updated_at->diffForHumans() . ')' : '',
                'status' => "<span class='badge ' style='background:" . $support->status->color . "' >" . $support->status->name . "</span>",
                'action' => $in_dashboard ? trans('translation.have-no-action') :
                    '<a class="btn btn-outline-secondary btn-sm m-1 on-default "
                      href="' . (route('supports.show', $support->id)) . '"
                      ><i class="mdi mdi-eye"></i>
                    </a>
                    <a target="_blank" class="btn btn-outline-primary btn-sm m-1 on-default "
                      href="' . (route('admin.supports.report', $support->uuid ?? fakeUuid())) . '"
                      ><i class="mdi mdi-file-document-outline"></i>
                    </a>
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
}
