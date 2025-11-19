<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Throwable;
use App\Models\User;
use App\Models\Danger;
use App\Models\Sector;
use App\Models\Status;
use App\Models\Ticket;
use App\Traits\CodeTrait;
use App\Models\OrderSector;
use Illuminate\Support\Str;
use App\Models\Organization;
use App\Models\ReasonDanger;
use App\Models\TicketReason;
use Illuminate\Http\Request;
use App\Models\TrackLocation;
use App\Traits\WhatsappTrait;
use Illuminate\Support\Carbon;
use App\Traits\AttachmentTrait;
use App\Notifications\CrudNotify;
use App\Models\MonitorOrderSector;
use App\Models\TicketReasonDanger;
use Illuminate\Support\Facades\DB;
use App\Notifications\CreateTicket;
use App\Http\Requests\SectorRequest;
use App\Traits\LocationTrackerTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StatusResource;
use App\Http\Resources\TicketResource;
use App\Traits\SmsTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;

class TicketController extends Controller
{
    use AttachmentTrait, CodeTrait, LocationTrackerTrait, WhatsappTrait, Notifiable, SmsTrait;

    public function index(SectorRequest $request)
    {
        // if (request()->has('order_sector_id')) {
        $monitor_sectors = Auth::user()->monitor?->monitor_order_sectors;
        if ($monitor_sectors) {
            $exist = true;
            if(!Auth::user()->hasRole(['supervisor','boss'])){
                $exist = in_array(request()->order_sector_id, $monitor_sectors->pluck('order_sector_id')->toArray());
            }

            if ($exist) {
                $ticket = Ticket::with(['reason_danger.danger', 'status'])->where('order_sector_id', request()->order_sector_id)->orderBy('created_at', 'desc')->paginate($request->per_page ?? 5);
                return response()->json(['tickets' => TicketResource::collection($ticket), 'statuses' => $this->ticketStatuses()->original['statuses'], 'pages' => $ticket->lastPage()], 200);
            }
            return response()->json(['message' => trans('translation.No matched order sector with this id')], 401);
        }
        return response()->json(['message' => trans('translation.You dont have registerd sector please contact customer service')], 401);
        // } else {
        // return response()->json(['message' => trans('translation.No order sector provided')], 401);
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($request)
    {
        $ticket = Ticket::create([
            "reason_danger_id" => $request->reason_danger_id,
            "user_id" => $request->user()->id,
            "status_id" => Status::NEW_TICKET,
            "order_sector_id" => $request->order_sector_id,
        ]);
        if ($request->has("notes")) {
            $ticket->notes()->create(['content' => $request->notes, 'user_id' => auth()->user()->id]);
        }
        foreach ($request->attachments as $key => $attachment) {
            $this->store_attachment($attachment, $ticket, $key, null, $request->user()->id);
        }

        //'device', 'user_id', 'longitude', 'latitude', 'details', 'action'
        // if ($request->has('longitude', 'latitude')) {
        $action = trans('translation.Create ticket: ') . $ticket->code;
        $this->tracker($request, $ticket, $action);
        // }

        return $ticket;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sub_sector = OrderSector::find($request->order_sector_id);
        if ($sub_sector->has_parent()) {
            return response()->json(['message' => __("translation.Can't create ticket from a child order sector")]);
        }

        $this->attachments_validator($request->all())->validate();
        $ticket = null; //$this->findTicket($request);
        $notify_user = '';
        if ($ticket) { //if theres a ticket that has the same reason_danger_id, order_sector_id, user_id but not with the status closed
            if ($this->isTicketRaised($ticket)) { //if the ticket has been raised within the last 5 mins
                return response()->json(['message' => trans('translation.Ticket has raised in the last 5 minutes.')]);
            } else { //if the ticket riased since more than 5 mins
                $ticket->update(['updated_at' => now()]); //consider it updated and raise it in the list of tickets
                $ticket->save();

                // if ($request->has('longitude', 'latitude')) {
                $action = trans('translation.Re-raise ticket: ') . $ticket->id;
                $this->tracker($request, $ticket, $action);
                // }

                return response()->json(
                    [
                        'message' => trans('translation.Ticket already exist.'),
                        'ticket' => $ticket
                    ],
                    200
                );
            }
        }
        $ticket = $this->create($request); //no ticket matched, then create the ticket
        $user = $request->user();
        $message = trans('translation.send-whatsapp-create-new-ticket', ['user' => $user->name, 'code' => $ticket->code]);
        User::find($ticket->user->id)->notify(new CrudNotify($ticket, 'create'));
        $whatsapp_response = $this->send_message(null, $message, $user->phone_code . $user->phone);
        $sending_sms = $this->send_sms(null, $message, $user->phone, $user->phone_code);
        return response()->json(['ticket' => new TicketResource($ticket), 'whatsapp_response' => $whatsapp_response, 'sending_sms' => $sending_sms], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function ticketReasons(Organization $organization)
    {
        try {
            $selectedFields = ReasonDanger::with('danger')
                ->where('organization_id', $organization->id)
                ->where('operation_type_id', '1')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'danger' => [
                            'level' => $item->danger->level,
                            'color' => $item->danger->color ?? '',
                        ],
                    ];
                });

            return response()->json([
                'reasons' => $selectedFields,
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function ticketStatuses()
    {
        try {
            $ticketStatuses = Status::where('type', 'tickets')->get();
            return response()->json([
                'statuses' => StatusResource::collection($ticketStatuses),
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function findTicket($request)
    {
        return Ticket::where(([
            'reason_danger_id' => $request->reason_danger_id,
            'user_id' => $request->user()->id,
            // 'status_id' => Status::ticket_initial_status(), 
            'order_sector_id' => $request->order_sector_id,
        ]))
            ->whereNot('status_id', Status::CLOSED_TICKET)
            // ->whereDate('created_at','<',Carbon::now()->addMinute(5))
            ->first();
    }
    public function isTicketRaised($ticket)
    {
        $creationTime = new DateTime($ticket->updated_at);
        $currentTime = new DateTime();
        return $currentTime->getTimestamp() - $creationTime->getTimestamp() < 300;
    }

    public function cascadeDelete($ticket_id){
        // dd('enterd');
        try {
            DB::beginTransaction();
        
            $ticket = Ticket::find($ticket_id);
            if (is_null($ticket)) {
                return response()->json(['message' => trans('translation.something went wrong')], 400);
            }
        
            $ticket->attachments()->delete();
            $ticket->notes()->delete();
            $ticket->track_locations()->delete();
            $ticket->delete();
        
            DB::commit();

            return response()->json(['message' => trans('translation.deleted-successfully')], 400);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => trans('translation.delete-failed')], 500);
        }
    }
    public function hardDelete($ticket_id){
        try {
            DB::beginTransaction();
            $ticket = Ticket::withTrashed()->where('id', $ticket_id)->first();

            if (is_null($ticket)) {
                return response()->json(['message' => trans('translation.something went wrong')], 400);
            }
            $ticket->attachments()->withTrashed()->forceDelete();
            $ticket->notes()->withTrashed()->forceDelete();
            $ticket->track_locations()->withTrashed()->forceDelete();
            $ticket->forceDelete();
        
            DB::commit();

            return response()->json(['message' => trans('translation.deleted-successfully')], 400);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => trans('translation.delete-failed')], 500);
        }
    }
}
