<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InterviewStandard;
use App\Models\InterviewStandardOrder;
use App\Models\Order;
use App\Models\Organization;
use App\Models\Service;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderInterviewController extends Controller
{
    public function index()
    {
        $domain = '';
        $organization = Organization::find(1);
        $organizations = Organization::all();
        $orders = $organization->orders;
        $services = Service::all();
        $statuses = Status::order_interview_statuses()->get();
        $columns = array(
            'id' => 'table_id',
            'code' => 'order-code',
            // 'organization_name' => 'organization-name',
            // 'service_name' => 'service-name',
            'status_name' => 'status-name',
            'user-name' => 'facility-user-name',
            'facility-name' => 'facility-name',
//            'user-phone' => 'facility-user-phone',
//            'user-national_id' => 'facility-user-national_id',
            'facility-registration_number' => 'facility-registration_number',
//            'since-version-date' => 'version-date',
            'facility-license' => 'facility-license',
//            'facility-capacity' => 'facility-capacity',
            'facility-tax_certificate' => 'facility-tax_certificate',
            'preference_nationality' => 'preference_nationality',
            'interview_standard_1' => 'interview_standard_1',
            'interview_standard_2' => 'interview_standard_2',
            'interview_standard_3' => 'interview_standard_3',
            'interview_standard_4' => 'interview_standard_4',
            'interview_standard_5' => 'interview_standard_5',
            'interview_standard_6' => 'interview_standard_6',
            'interview_standard_7' => 'interview_standard_7',
//            'facility-chefs_number' => 'facility-chefs_number',
//            'facility-kitchen_space' => 'facility-kitchen_space',
//            'facility-employee_number' => 'facility-employee_number',
            'interview_total_score_before_bonus' => 'interview_total_score',
            // 'interview_total_score_before_bonus' => 'interview_total_score_before_bonus',
            // 'interview_total_score_after_bonus' => 'interview_total_score_after_bonus',
//            'interview_status' => 'interview_status',
            'interview_status_name' => 'interview_status_name',
            'interview-standards' => 'interview-standards',
        );

        return view('admin.order_interviews.index', compact('orders', 'organization', 'statuses', 'organizations', 'services', 'columns'));
    }


    public function get_note(Request $request)
    {
        $order = Order::findorFail($request->order_id);
        // dd($order->notes);
        return response()->json(['interview_note' => $order->interview_note], 200);
    }

    public function update(Request $request, Order $order, $key = null, $value = null, $url = null)
    {
        $order->update(["$key" => $value]);

        if ($url != null) {
            return redirect($url);
        }
    }
    public function update_note(Request $request)
    {
        // dd($request->all());
        $order = Order::findOrFail($request->order_id);
        if(($notes = $request->notes) == null || $notes == "<br>"){
            $notes = null;
        }
        $order->update(['interview_note' => $notes]);
        // $this->update($request, $order, 'notes', $request->notes);

        return response(['message' => trans('translation.Updated successfuly')], 200);
    }

    public function update_interview_status(Request $request)
    {
        $order = Order::find($request->order_id);
        $this->update($request, $order, 'interview_status_id',$request->status_id );
        return response()->json(['message' => trans('translation.Updated successfuly')], 200);
    }

    public function datatable(Request $request)
    {

        $is_chairman = auth()->user() != null ? auth()->user()->hasRole('organization chairman') : false;
        $in_show_facility = isset($request->facility_id);
        // $organization_ids = Organization::all()->pluck('id')->toArray();
        // $organization_service_ids = OrganizationService::whereIn('organization_id', [1, 2])->pluck('id')->toArray();
        $query = Order::with([
            'interview_status:id,name_ar,name_en,color',
            'status:id,name_ar,name_en,color',
            'organization_service.organization:id,name_ar,name_en',
            'organization_service.service:id,name_ar,name_en',
            'user:id,name,nationality',
            'user.country:id,name_ar,name_en',
            'facility:id,name,registration_number,license,tax_certificate',
            'interview_standard_orders'
        ]);
        // $query->whereIn('organization_service_id', $organization_service_ids);
        $query->whereHas('facility', function ($q) {
            $q->whereNull('deleted_at');
        });

        if (\request('service_id')) {
            $query->whereHas('organization_service', function ($q) {
                $q->whereIn('service_id', \request('service_id'));
            });
        }
        if (\request('facility_id')) {
            $query->whereHas('facility', function ($q) {
                $q->whereIn('facility_id', \request('facility_id'));
            });
        }
        if (\request('organization_id')) {
            $query->whereHas('organization_service', function ($q) {

                $q->whereIn('organization_id', \request('organization_id'));
            });
        }
        if (\request('interview_status_id')) {

            $query->whereIn('interview_status_id', \request('interview_status_id'));
        }
        $statuses = Status::order_interview_statuses()->get();
        // dd($query->toSql());
        return datatables($query->orderByDesc('created_at')->get())
            // ->editColumn('organization_name', function (Order $order) {
            //     return $order->organization_service->organization->name ?? '-';
            // })
            // ->editColumn('service_name', function (Order $order) {
            //     return $order->organization_service->service->name ?? '-';
            // })

            ->addColumn('status_name', function (Order $order) {
                return $order->status->name;
            })
            ->editColumn('user-name', function (Order $order) {
                return $order->user->name ?? '-';
            })
            ->editColumn('facility-name', function (Order $order) use ($is_chairman) {
                if ($is_chairman) {
                    return  $order->facility->name;
                }
                return
                    '<a target="_blank" href="' . route('facilities.show', $order->facility->id) .  '">' . $order->facility->name . '</a>';
            })
            ->editColumn('code', function (Order $order) {
                $code = 'ORD' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
                return $code;
            })
            ->editColumn('user-nationality_name', function (Order $order) {
                return $order->user->nationality->name ?? '-';
            })
            ->editColumn('user-national_id', function (Order $order) {
                return $order->user->national_id ?? '-';
            })
            ->editColumn('user-national_source_name', function (Order $order) {
                return $order->user->national_source_name ?? '-';
            })
            ->editColumn('facility-registration_number', function (Order $order) {
                return $order->facility->registration_number ?? '-';
            })
            ->editColumn('facility-license', function (Order $order) {
                return $order->facility->license ?? '-';
            })
            ->editColumn('facility-tax_certificate', function (Order $order) {
                return $order->facility->tax_certificate ?? '-';
            })
            ->editColumn('interview_standard_1',function(Order $order){
                return $order->interview_standard_order(1)->value('score') ?? "-";
            })
            ->editColumn('interview_standard_2',function(Order $order){
                return $order->interview_standard_order(2)->value('score') ?? "-";
            })
            ->editColumn('interview_standard_3',function(Order $order){
                return $order->interview_standard_order(3)->value('score') ?? "-";
            })
            ->editColumn('interview_standard_4',function(Order $order){
                return $order->interview_standard_order(4)->value('score') ?? "-";
            })
            ->editColumn('interview_standard_5',function(Order $order){
                return $order->interview_standard_order(5)->value('score') ?? "-";
            })
            ->editColumn('interview_standard_6',function(Order $order){
                return $order->interview_standard_order(6)->value('score') ?? "-";
            })
            ->editColumn('interview_standard_7',function(Order $order){
                return $order->interview_standard_order(7)->value('score') ?? "-";
            })
            ->editColumn('interview_total_score_before_bonus', function (Order $order) {
                return $order->interview_total_score_before_bonus ?? '-';
            })
            // ->editColumn('interview_total_score_after_bonus', function (Order $order) {
            //     return $order->interview_total_score_after_bonus ?? '-';
            // })
            ->addColumn('interview_status_name', function (Order $order)  {
                return "<span class='badge ' style='background:" . ($order->interview_status->color??'') . "' >" . ($order->interview_status->name??'-') . "</span>";
            })
//            ->addColumn('interview_status', function (Order $order) use ($is_chairman, $in_show_facility, $statuses) {
//
//                $disabled = '';
//                // if ($order->status_id == Status::CANCELED_ORDER  || $is_chairman || $in_show_facility) {
//                //   return "<span class='badge ' style='background:" . $order->status->color . "' >" . $order->status->name . "</span>";
//                // }
//                // $disabled = ($order->status_id == Status::REJECTED_ORDER || $order->status_id == Status::ACCEPTED_ORDER) ? (auth()->user()->canChangeOrderStatus() ? '' : 'disabled ') : '';
//                // dd(auth()->user()->canChangeTicketStatus());
//                $html = '<div><select class="form-control selectpicker status-select"' . $disabled . ' name="service_id" style="background:' . $order->status->color . '" data-status-id="' . $order->status_id . '" data-order-id="' . $order->id . '" onchange="changeSelectPicker(this)"  >';
//                foreach ($statuses as $status) {
//                    $span = " data-content=\"<span class='badge ' style='background:" . $status->color . "' >" . $status->name . "</span>\" ";
//                    $html .= '<option value="' . $status->id . '" ' . ($status->id == ($order->interview_status->id ?? 0) ? 'selected' : '') . ' ' . $span . ' data-note-required="' . ($status->is_note_required) . '" >' . $status->name . '</option>';
//                }
//                $html .= "</select></div>";
//                return $html;
//                //     return '<div class="col text-center">
//                //     <a class="btn  text-light" style="background-color:' . $order->status->color . '" >' . $order->status_name . '</a>
//                // </div>';
//            })
            ->addColumn('preference_nationality',function(Order $order) {
                return $order->country_organization ? implode(', ',$order->country_organization->toArray()) : "-";
            })
            ->addColumn('interview-standards', function (Order $order) {
                // return '<button
                //   class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 notes-button"
                //   data-bs-target="#notesModal"
                //   data-order-id="' . $order->id . '"
                //   data-bs-toggle="modal"
                //   data-original-title="Edit">
                //       <i class="mdi mdi-clipboard-edit-outline"></i>
                // </button>';
                $interview_standard_route = url('order-interviews/' . ($order->has_interview_standard_orders ? 'create' : 'show') . '/' . $order->id);

                $has_notes = $order->interview_note ? 'primary':'secondary';
                return '<a class="btn btn-outline-secondary btn-sm m-1 on-default m-r-5" href="' . $interview_standard_route . '">
                  <i class="mdi mdi-' . ($order->has_interview_standard_orders ? 'plus' : 'eye-outline') . '"></i>
                </a>
                <button
                  class="btn btn-outline-'. $has_notes .' btn-sm m-1  on-default m-r-5 notes-button"
                  data-bs-target="#notesModal"
                  data-order-id="' . $order->id . '"
                  data-bs-toggle="modal"
                  data-original-title="Edit">
                      <i class="mdi mdi-clipboard-edit-outline"></i>
                </button>
        ';
            })

            ->rawColumns([ 'facility-name', 'interview-standards', 'interview_status','interview_status_name', 'code'])
            ->toJson();
    }


    public function create()
    {
        //
        $interview_standards = InterviewStandard::all();
        $order = Order::findOrFail(Request()->order_id);
        // $evaluation = InterviewStandardOrder::where('order_id', Request()->order_id);
        return view('admin.interview-standards-order.create', compact('order', 'interview_standards'));
    }
    //??=========================================================================================================
    public function store(Request $request)
    {
        $existingEntry = InterviewStandardOrder::where('order_id', $request->order_id)
            ->whereIn('interview_standard_id', array_keys($request->scores))
            ->exists();

        if ($existingEntry) {
            return back()->with(['message' => trans('translation.order interview already exists'), 'alert-type' => 'error']);
        }
        $order = Order::findOrFail($request->order_id);

        $scores = $request->scores;
        $max_scores = $request->max_scores;
        foreach ($scores as $key => $score) {
            InterviewStandardOrder::create([
                'order_id' => $request->order_id,
                'interview_standard_id' => $key,
                'score' => $scores[$key],
                'max_score' => $max_scores[$key],
            ]);
        }
        $order->update($request->only(['bonus']));

        return redirect()->route('order-interviews.show', $request->order_id)->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
    }
    //??=========================================================================================================
    public function show($order_id)
    {
        $order = Order::findOrFail($order_id);
        $statuses = Status::order_interview_statuses()->get();

        return view('admin.interview-standards-order.show', compact('order','statuses'));
    }
    //??=========================================================================================================
    public function edit($order_id)
    {
        $order = Order::findOrFail(Request()->order_id ?? 0);
        $order_interviews = $order->interview_standard_orders;
        // $evaluation = InterviewStandardOrder::where('order_id', Request()->order_id);
        return view('admin.interview-standards-order.edit', compact('order', 'order_interviews'));
    }
    //??=========================================================================================================
    public function update_scores(Request $request, Order $order)
    {
        $order->load('interview_standard_orders');
        // dd($request->all(),$order->interview_standard_orders->pluck('score')->toArray());
        foreach ($request->scores as $id => $order_interview_score) {
            InterviewStandardOrder::findOrFail($id)->update([
                'score' => $order_interview_score,
            ]);
        }
        $order->update($request->only(['bonus']));

        return redirect()->route('order-interviews.show', $order->id)->with(['message' => trans('translation.Updated successfully'), 'alert-type' => 'success']);
    }
    //??=========================================================================================================
}