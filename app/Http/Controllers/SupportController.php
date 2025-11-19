<?php

namespace App\Http\Controllers;

use DateTime;
use Exception;
use Throwable;
use App\Models\User;
use App\Models\Sector;
use App\Models\Status;
use App\Models\Support;
use App\Models\OrderSector;
use Illuminate\Http\Request;
use App\Models\OperationType;
use App\Traits\WhatsappTrait;
use App\Traits\AttachmentTrait;
use App\Notifications\CrudNotify;
use App\Models\MonitorOrderSector;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SectorRequest;
use App\Traits\LocationTrackerTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\StatusResource;
use App\Http\Resources\SupportResource;
use Illuminate\Notifications\Notifiable;
use App\Http\Resources\OperationTypeResource;
use App\Models\Meal;
use App\Models\NoteTitle;
use App\Traits\SmsTrait;

class SupportController extends Controller
{
    use AttachmentTrait, LocationTrackerTrait, WhatsappTrait, Notifiable, SmsTrait;

    public function index(SectorRequest $request)
    {
        $monitor_sectors = Auth::user()->monitor?->monitor_order_sectors;
        if ($monitor_sectors) {
            $exist = true;
            if(!Auth::user()->hasRole(['supervisor','boss'])){
                $exist = in_array(request()->order_sector_id, $monitor_sectors->pluck('order_sector_id')->toArray());
            }
            if ($exist) {
                $supports = Support::
                with([
                    'reason_danger' => [
                        'reason',
                        'danger'
                    ],
                    'status',
                    'assists' => [
                        'status',
                        'attachments',
                        'assistant' => [
                            'profile_photo_attachment',
                            'country',
                        ],
                        'assigner' => [
                            'profile_photo_attachment',
                            'country'
                        ]
                    ],
                    'period',
                    'order_sector' => [
                        'order',
                        'sector.classification.organization',
                    ],
                    'notes.user',
                    'attachments.attachment_label',
                ])->
                where('order_sector_id', request()->order_sector_id)->orderBy('created_at', 'desc')->paginate($request->per_page ?? 5);
                return response()->json(['supports' => SupportResource::collection($supports), 'statuses' => $this->supportStatuses()->original['statuses'], 'pages' => $supports->lastPage()], 200);
            }
            return response()->json(['message' => trans('translation.No matched order sector with this id')], 401);
        }
        return response()->json(['message' => trans('translation.You dont have registerd sector please contact customer service')], 401);
    }

    public function create($request)
    {
        $order_sector = OrderSector::find(request()->order_sector_id);
        $support = Support::create([
            "reason_danger_id" => $request->reason_danger_id,
            "quantity" => $request->quantity, // > $order_sector->sector->guest_quantity ? $order_sector->sector->guest_quantity : $request->quantity,
            "type" => $request->operation_type_id,
            "user_id" => $request->user()->id,
            "status_id" => Status::NEW_SUPPORT,
            "order_sector_id" => $request->order_sector_id,
            "period_id" => $request->period_id,
        ]);
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
        if ($request->has("notes")) {
            $support->notes()->create(['content' => $request->notes, 'user_id' => auth()->user()->id]);
        }
        foreach ($request->attachments as $key => $attachment) {
            $new_attachment = $this->store_attachment($attachment, $support, $key, null, $request->user()->id);
        }
        $action = trans('translation.Create support: ')  .  $support->type_name  . ':' . $support->code;
        $this->tracker($request, $support, $action);

        return $support;
    }

    public function store(SectorRequest $request)
    {
        $this->attachments_validator($request->all())->validate();

        $support = null; //$this->findSupport($request);

        if ($support) {
            $creationTime = new DateTime($support->created_at);
            $currentTime = new DateTime();
            if ($currentTime->getTimestamp() - $creationTime->getTimestamp() < 300) {
                return response()->json(['message' => trans('translation.Support has raised in the last 5 minutes')]);
            } else {
                $support->update(['updated_at' => now()]);
                $support->save();

                $action = trans('translation.Re-raise support: ') .  $support->type_name . ': ' . $support->code;
                $this->tracker($request, $support, $action);

                return response()->json(['message' => trans('translation.Support already exist.'), 'support' => new SupportResource($support)], 200);
            }
        }

        $support = $this->create($request);

        $user = $request->user();
        $message = trans('translation.send-whatsapp-create-new-support', ['user' => $user->name, 'code' => $support->code]);
        $whatsapp_response = $this->send_message(null, $message, $user->phone_code . $user->phone);
        $sending_sms = $this->send_sms(null, $message, $user->phone, $user->phone_code);
        User::find(Auth::user()->id)->notify(new CrudNotify($support, 'create'));
        return response()->json(["support" => new SupportResource($support->load('assists')), "whatsapp_response" => $whatsapp_response, "sending_sms" => $sending_sms], 200);
    }

    public function supportStatuses()
    {
        try {
            return response()->json([
                'statuses' => StatusResource::collection(Status::where('type', 'supports')->get()),
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function supportType()
    {
        try {
            $types = array_filter(Support::types());
            // dd($types); 
            return response()->json([
                'types' => OperationTypeResource::collection(OperationType::with('reason_dangers')->whereIn('id', [2, 3])->get()),
            ], 200);
        } catch (Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function findSupport($request)
    {
        $order_sector = OrderSector::find(request()->order_sector_id);
        $support = Support::where([
            "quantity" => $request->quantity > $order_sector->sector->guest_quantity ? $order_sector->sector->guest_quantity : $request->quantity,
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

    public function cancelSupport(SectorRequest $sectorRequest)
    {
        $support = Support::find(request()->support_id);
        if (!$support) {
            return response()->json(['message' => trans('translation.Must provide support id')], 400);
        }
        if ($support->order_sector_id != request()->order_sector_id) {
            return response()->json(['message' => trans('translation.Support doesnt belong to this order secotr')], 400);
        }
        if ($support->cancelable()) {
            if ($support->status_id == Status::CANCELED_SUPPORT) {
                return response()->json(['message' => trans('translation.Support already canceled')], 400);
            }
            $support->update(['status_id' => Status::CANCELED_SUPPORT,]);
            $action = trans('translation.Cancel support: ') .  $support->type_name . ': ' .  $support->code;
            $this->tracker($sectorRequest, $support, $action);
            $sectors = MonitorOrderSector::where('order_sector_id', $support->order_sector_id)->get(); //->pluck('monitor_id');
            foreach ($sectors as $sector) {
                User::find($sector->monitor->user->id)->notify(new CrudNotify($support, 'changeStatus'));
            }
            return response()->json(['message' => trans('translation.Support canceled successfully')], 200);
        }
        return response()->json(['message' => trans('translation.Support cant be canceled due to active assists')], 400);
    }

    public function hasEnough(SectorRequest $sectorRequest)
    {
        $support = Support::find(request()->support_id);
        if (!$support) {
            return response()->json(['message' => trans('translation.Must provide support id')], 400);
        }
        if (in_array($support->status_id, [Status::CLOSED_SUPPORT, Status::CANCELED_SUPPORT])) {
            return response()->json(['message' => trans('translation.Support is closed or cancelled')], 400);
        }
        $support->update([
            'has_enough' => '1',
            'has_enough_quantity' => $support->delivered_quantity,
            'status_id' => Status::HAS_ENOUGH_SUPPORT,
        ]);

        if($note = $sectorRequest->has_enough_note){
            $support->notes()->create(['content' => $note, 'user_id' => auth()->user()->id , 'note_title_id' => NoteTitle::HAS_ENOUGH_TITLE ]);
        }

        if ($support->quantity == $support->delivered_quantity) { //all assigned assists are deleiverd
            $support->update(['status_id' => Status::CLOSED_SUPPORT]);
        }
        $sectors = MonitorOrderSector::where('order_sector_id', $support->order_sector_id)->get(); //->pluck('monitor_id');
        foreach ($sectors as $sector) {
            User::find($sector->monitor->user->id)->notify(new CrudNotify($support, 'changeStatus'));
        }
        $action = trans('translation.Has enough support: ') . $support->type_name . ': ' .  $support->code;
        $this->tracker($sectorRequest, $support, $action);
        return response()->json(['message' => trans('translation.Support updated successfully'), 'support' => new SupportResource($support)], 200);
    }

    public function cascadeDelete($support_id){
        try {
            DB::beginTransaction();
        
            $support = Support::find($support_id);
            if (is_null($support)) {
                return response()->json(['message' => trans('translation.something went wrong')], 400);
            }
        
            $support->attachments()->delete();
            $support->assists->each(function ($assist){
                $assist->attachments()->delete();
                $assist->track_locations()->delete();
            });
            $support->assists()->delete();
            $support->notes()->delete();
            $support->track_locations()->delete();
            $support->delete();
        
            DB::commit();

            return response()->json(['message' => trans('translation.deleted-successfully')], 400);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => trans('translation.delete-failed')], 500);
        }
    }
    public function hardDelete($support_id){
        try {
            DB::beginTransaction();
        
            $support = Support::withTrashed()->where('id', $support_id)->first();
            if (is_null($support)) {
                return response()->json(['message' => trans('translation.something went wrong')], 400);
            }
            $support->attachments()->withTrashed()->forceDelete();
            $support->assists()->withTrashed()->each(function ($assist){
                $assist->attachments()->withTrashed()->forceDelete();
                $assist->track_locations()->withTrashed()->forceDelete();
            });
            $support->assists()->withTrashed()->forceDelete();
            $support->notes()->withTrashed()->forceDelete();
            $support->track_locations()->withTrashed()->forceDelete();
            $support->forceDelete();
        
            DB::commit();

            return response()->json(['message' => trans('translation.deleted-successfully')], 400);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => trans('translation.delete-failed')], 500);
        }
    }
}