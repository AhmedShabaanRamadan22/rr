<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Assist;
use App\Models\Status;
use App\Models\Support;
use App\Traits\SmsTrait;
use App\Traits\NoteTrait;
use Illuminate\Http\Request;
use Ramsey\Uuid\Type\Integer;
use App\Notifications\CrudNotify;
use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use App\Traits\WhatsappTrait;

class AssistController extends Controller
{
    use NoteTrait, SmsTrait, WhatsappTrait, CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    
    public function store(Request $request)
    {
        if($request->quantity <= 0){
            return back()->with(['message' => trans('translation.quantity-is-zero'), 'alert-type' => 'error']);
        }

        $support = Support::find($request->support_id);
        if ($support->remaining_quantity < $request->quantity) {
            return back()->with(['message' => trans('translation.quantity-is-more'), 'alert-type' => 'error']);
        }
        if ($support->status_id == Status::HAS_ENOUGH_SUPPORT) {
            return back()->with(['message' => trans('translation.support-has-enough'), 'alert-type' => 'error']);
        }
        $assist = Assist::create([
            'quantity' => $request->quantity,
            'support_id' => $support->id,
            'assigner_id' => auth()->user()->id,
            'assistant_id' => $request->assistant_id,
            'assist_sector_id' => $request->assist_from,
            'status_id' => Status::IN_PROGRESS_ASSIST
        ]);
        
        $assist->support()->update(['status_id' => Status::IN_PROGRESS_SUPPORT]);
        $type = $support->type == 2 ? 'meals': 'shrinks';
        $message = trans('translation.create-assist', ['type_name' => $support->type_name  , 'quantity' => $request->quantity,  'type' => trans('translation.'.$type), 'label' => $support->order_sector->sector->label, 'organization' => $support->order_sector->sector->classification->organization->name, 'assist_from' => $assist->assist_from]);
        $whatsapp_response = $this->send_message(null, $message, $assist->assistant->phone_code . $assist->assistant->phone);
        $sending_sms = $this->send_sms(null, $message, $assist->assistant->phone, $assist->assistant->phone_code);
        User::find($support->user->id)->notify(new CrudNotify($assist, 'create'));
        //تم اسناد دعم {{النوع}} لتوصيل {{الكمية}} وجبة\شرنكة الى قطاع {{ليبل القطاع}} 
        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
        // dd($request->all());
    }

    //??================================================================
    public function update(Request $request){

        if($request->quantity <= 0){
            return response()->json(['message' => trans('translation.quantity-is-zero')], 400);
        }

        if(!($request->filled('assist_id') && $request->filled('assist_from_id') && $request->filled('quantity') && $request->filled('assistant_id'))){
            return response()->json(['message' => trans('translation.all-field-required')], 400);
        }
        $assist = Assist::findOrFail($request->assist_id);
        // dd($request->all());
        $assist->update([
            'assist_sector_id' => $request->assist_from_id,
            'quantity' => $request->quantity,
            'assistant_id' => $request->assistant_id,
        ]);
        return response()->json(['message' => trans('translation.updated-successfully')], 200);
    }
    //??================================================================


    public function cancel(Request $request)
    {
        //
        $support = Support::find($request->support_id);
        $assist = Assist::find($request->assist_id);
        if ($assist->status->id == Status::DELIVERED_ASSIST) {
            return response()->json(['message' => trans('translation.already-submitted')], 400);
        }
        if (isset($request->note)) {
            $assist->update(['status_id' => Status::CANCELED_ASSIST]);
            if($support->assists()->whereNotIn('status_id',[Status::CANCELED_ASSIST])->count() == 0){
                $support->update(['status_id' => Status::PROCESSING_SUPPORT]);
            }
            $this->store_note($support, $request->note, auth()->user()->id ?? 0);
            $message = trans('translation.whatsapp-cancel-assist', ['type_name' => $support->type_name, 'label' => $support->order_sector->sector->label]);
            $whatsapp_response = $this->send_message(null, $message, $assist->assistant->phone_code . $assist->assistant->phone);
            $sending_sms = $this->send_sms(null, $message, $assist->assistant->phone, $assist->assistant->phone_code);
            User::find($support->user->id)->notify(new CrudNotify($assist, 'changeStatus'));
            return response()->json(['message' => trans('translation.assist-canceled')], 200);
        }
        return response()->json(['message' => trans('translation.note-required')], 400);
    }
    public function datatable(Request $request)
    {
        $query = Assist::with(
            'support:id,status_id,type,order_sector_id,period_id,has_enough_quantity,quantity,user_id,reason_danger_id,created_at,updated_at,uuid',
            'support.status:id,name_en,name_ar,color',
            'support.period:id,name',
            'support.order_sector.sector:id,label,sight,boss_id,supervisor_id',
            'support.order_sector.order.facility:id,name',
            'support.order_sector.order.organization_service.organization:id,slug',
            'support.order_sector.monitor_order_sectors.monitor.user:id,name',
            'support.user:id,name',
            'support.status:id,name_ar,name_en,color',
        );
        $statuses = Status::admin_support_statuses()->get();
        if ($request->has('order_sector_id')) {
            // dd($request->order_sector_id);
            $query->whereHas('support', function ($q) use ($request) {
                $q->whereIn('order_sector_id', $request->order_sector_id);
            });
        }
        return datatables($query->orderByDesc('created_at')->get())
            ->editColumn('support_id', function (Assist $assist) {
                return $assist->support->id ?? '-';
            })
            ->editColumn('type_name', function (Assist $assist) {
                return $assist->support->type_name ?? '-';
            })
            ->editColumn('code', function (Assist $assist) {
                $code = $assist->support->order_sector->order->organization_service->organization->slug .
                    '-SP' . str_pad($assist->support->id, 4, '0', STR_PAD_LEFT) . '-' . 'OR' .
                    str_pad($assist->support->order_sector->order->id, 3, '0', STR_PAD_LEFT);
                return $code;
            })
            ->editColumn('periods', function (Assist $assist) {
                return trans('translation.' . $assist->support->period->name) ?? '-';
            })
            ->editColumn('label', function (Assist $assist) {
                return $assist->support->order_sector->sector->label ?? '-';
            })
            ->editColumn('reason', function (Assist $assist) {
                return $assist->support->reason_danger->reason->name ?? '-';
            })
            ->editColumn('providor', function (Assist $assist) {
                return $assist->support->order_sector->order->facility->name ?? '-';
            })
            ->editColumn('reporter-name', function (Assist $assist) {
                return $assist->support->user->name ?? '-';
            })
            ->editColumn('monitor-name', function (Assist $assist) {
                return $assist->support->order_sector->monitors_name;
            })
            ->editColumn('supervisor-name', function (Assist $assist) {
                return $assist->support->order_sector->sector->supervisor->name ?? trans('translation.no-data');
            })
            ->editColumn('boss-name', function (Assist $assist) {
                return $assist->support->order_sector->sector->boss->name ?? trans('translation.no-data');
            })
            ->editColumn('delivered-quantity', function (Assist $assist) {
                return $assist->support->delivered_quantity ?? trans('translation.no-data');
            })
            ->editColumn('all-notes', function (Assist $assist) {
                $notes = '';
                foreach ($assist->support->notes as $note) {
                    if ($note->content != '' && $note->content != '-') {
                        $notes .= '(' . ($note->user_name ?? '-') . ': ' . ($note->content ?? '-') . ')<br><br>';
                    }
                }

                if ($notes == '') {
                    $notes = trans('translation.no-notes');
                }

                return $notes;
            })
            ->editColumn('support-create-time', function (Assist $assist) {
                if ($assist->support->created_at != null) {
                    return $assist->support->created_at . ' (' . $assist->support->created_at->diffForHumans() . ')';
                }
                return '';
            })
            ->editColumn('support-update-time', function (Assist $assist) {
                if ($assist->support->updated_at != null) {
                    return $assist->support->updated_at . ' (' . $assist->support->updated_at->diffForHumans() . ')';
                }
                return '';
            })
            ->editColumn('create-time', function (Assist $assist) {
                if ($assist->created_at != null) {
                    return $assist->created_at . ' (' . $assist->created_at->diffForHumans() . ')';
                }
                return '';
            })
            ->editColumn('update-time', function (Assist $assist) {
                if ($assist->updated_at != null) {
                    return $assist->updated_at . ' (' . $assist->updated_at->diffForHumans() . ')';
                }
                return '';
            })
            ->addColumn('support_status', function (Assist $assist) use ($statuses) {
                return "<span class='badge ' style='background:" . $assist->support->status->color . "' >" . $assist->support->status->name . "</span>";
            })
            ->editColumn('assist_status', function (Assist $assist) use ($statuses) {
                return "<span class='badge ' style='background:" . $assist->status->color . "' >" . $assist->status->name . "</span>";
            })
            ->addColumn('action', function (Assist $assist) {
                return '<a class="btn btn-outline-secondary btn-sm m-1 on-default "
                      href="' . (route('supports.show', $assist->support->id)) . '"
                      ><i class="mdi mdi-eye"></i>
                    </a>
                    <a target="_blank" class="btn btn-outline-primary btn-sm m-1 on-default "
                      href="' . (route('admin.supports.report', $assist->support->uuid ?? fakeUuid())) . '"
                      ><i class="mdi mdi-file-document-outline"></i>
                    </a>
                    ';
            })
            ->editColumn('assigner', function(Assist $assist){
                return $assist->assigner->name;
            })
            ->editColumn('assistant', function(Assist $assist){
                return $assist->assistant->name;
            })
            ->editColumn('assist_sector', function(Assist $assist){
                return $assist->assist_from;
            })
            ->rawColumns(['action', 'support_status', 'periods', 'all-notes', 'assist_status'])
            ->toJson();
    }
}