<?php

namespace App\Http\Controllers\Admin;

use App\Models\Meal;
use App\Models\Status;
use App\Traits\PdfTrait;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use App\Traits\CrudOperationTrait;
use App\Http\Controllers\Controller;
use App\Models\MealOrganizationStage;
use App\Models\NationalityOrganization;
use App\Models\OrderSector;
use App\Models\Sector;
use Illuminate\Support\Carbon;

class MealController extends Controller
{
    use PdfTrait, CrudOperationTrait;

    protected $data;
    protected $view_path = 'admin.export.';
    protected $orientation = "P";
    protected $all_columns = false;

    public function __construct()
    {
        $this->set_model( $this::class );
        $this->data = array(
            'current_year'        => date( 'Y' ),
            'current_date'        => date( 'Y-m-d H:i:s' ),
            'attachment_label'    => 'تقرير ',
            'header_default_logo' => 'https://rakaya.co/images/logo/logo.png'
        );
    }
    //??=========================================================================================================
    public function store(Request $request){
        $existed_meals = [];
        $nonexisted_order_sector = [];
        $nationality_organization = NationalityOrganization::findOrFail($request->natioanlity_organization_id);
        $organization_service = $nationality_organization->organization?->organization_services?->firstWhere('service_id', 1);
        // dd($request->sector_id);
        foreach($request->sector_id as $sector_id){
            $sector = Sector::findOrFail($sector_id);
            $meal = Meal::where(['sector_id' => $sector->id, 'period_id' => $request->period_id, 'day_date' => $request->day_date])->whereNull('deleted_at');
            if($meal->exists()){
                array_push($existed_meals, $sector->label);
            }
            else{
                $order_sector = $sector->active_order_sector_service($organization_service->id)->first();
                if($order_sector){
                    $new_meal = Meal::create([
                        'sector_id' => $sector->id,
                        'period_id' => $request->period_id,
                        'day_date' => $request->day_date,
                        'start_time' => $request->start_time,
                        'end_time' => $request->end_time,
                        'status_id' => Status::OPENED_MEAL,
                        'order_sector_id' => $order_sector->id,
                    ]);
                    $new_meal->food_weights()->syncWithoutDetaching($request->food_weights);
                }
                else{
                    array_push($nonexisted_order_sector, $sector->label);
                }
            }
        }
        if(count($existed_meals) != 0){
            return back()->with(['message' => trans('translation.already-created') . implode(' - ', $existed_meals), 'alert-type' => 'error'], 400);
        }
        if(count($nonexisted_order_sector) != 0){
            return back()->with(['message' => trans('translation.not-existed-order-sectors') .  implode(' - ', $nonexisted_order_sector), 'alert-type' => 'error'], 400);
        }
        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success'], 200);
    }
    //??=========================================================================================================
    public function index_customized()
    {
        $this->all_columns = true;
        return $this->index();
    }
    //??=========================================================================================================
    public function show($id)
    {
        $meal =  $this->model::with('meal_organization_stages.organization_stage.questions.question_bank_organization.question_bank')
        ->findOrFail($id);
            // ->first();
        $columns = MealOrganizationStage::columnNames();
        $columnInputs = [];
        $done_status = Status::DONE_MEAL;
        $open_status = Status::OPENED_MEAL;
        $statuses = Status::meal_statuses()->whereNot('id', $done_status)->get();
        $pageTitle = 'العنوان';

        $order_sector = $meal->sector->active_order_sector_service($meal->sector->classification->organization->organization_services->where('service_id', 1)->first()->id)->first();//->sector;

        // return $meal;
        return view('admin.meals.show',compact('meal','columns','pageTitle', 'order_sector','statuses','done_status','open_status'));

    }
    //??=========================================================================================================
    public function status(Request $request)
    {
        try {
            $meal = $this->model::findOrFail($request->meal_id);
            $meal->update([
                'status_id' => $request->status_id
            ]);
            // if($request->status_id == Status::CLOSED_MEAL){
            //     if($meal->meal_organization_stage->status_id == Status::OPENED_MEAL_STAGE){
            //         $meal->meal_organization_stage->update(['status_id' => Status::CLOSED_MEAL_STAGE]);
            //     }
            // }
            // elseif($request->status_id == Status::OPENED_MEAL){
            //     if($meal->meal_organization_stage->status_id == Status::CLOSED_MEAL_STAGE){
            //         $meal->meal_organization_stage->update(['status_id' => Status::OPENED_MEAL_STAGE]);
            //     }
            // }
            return response()->json(['message' => trans('translation.Updated successfully')], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $done_status = Status::DONE_MEAL;
        $open_status = Status::OPENED_MEAL;
        $statuses    = Status::meal_statuses()->whereNot('id', $done_status)->get();

        $query = $this->model::query();

        if ( \request( 'organization_id' ) ) {
            $query->whereHas( 'sector.classification', function ( $q ) {
                $q->where( 'organization_id', \request( 'organization_id' ) );
            } );
        }

        if ( \request( 'organization_ids' ) ) {
            $query->whereHas( 'sector.classification', function ( $q ) {
                $q->whereIn( 'organization_id', \request( 'organization_ids' ) );
            } );
        }

        if (\request('order_sector')) {
            $query->whereHas('sector', function ($q1) {
                $q1->whereHas('order_sectors',function ($q2){
                    $q2->whereIn('sector_id',\request('order_sector'));
                });
            });
        }

        if ( \request( 'period' ) ) {
            $query->whereHas( 'period', function ( $q1 ) {
                $q1->whereIn( 'id', \request( 'period' ) );
            } );
        }
        if ( \request( 'day' ) ) {
            $query->whereIn( 'day_date', \request( 'day' ) );
        }
        if ( \request( 'meal_status' ) ) {
            $query->whereIn( 'status_id', \request( 'meal_status' ) );
        }

        if (\request('nationality')) {
            $query->whereHas('order_sector.sector.nationality_organization.nationality', function ($q) {
                $q->whereIn('id', \request('nationality'));
            });
        }
        if ( \request( 'all_columns' ) ) {
            $this->all_columns = true;
        }

        return $this->all_columns ? $this->customized_datatable($query,$statuses,$done_status,$open_status) : $this->normal_datatable($query,$statuses,$done_status,$open_status);
    }

    //??================================================================
    public function normal_datatable($query,$statuses,$done_status)
    {
        $query->with(
                'sector:id,label,classification_id,guest_quantity',
                'period:id,name',
                'sector.classification.organization.organization_services:id,service_id,organization_id',
                'status:id,name_ar,name_en,color',
                'order_sector:id,order_id,sector_id',
                'order_sector.order:id,facility_id,organization_service_id',
                'order_sector.order.facility:id,name',
                'order_sector.sector.nationality_organization.nationality:id,name',
                'meal_organization_stages:id,meal_id,organization_stage_id,status_id,duration,done_by,done_at,arrangement',
                'meal_organization_stage.organization_stage.stage_bank:id,name',
                'meal_organization_stage:id,meal_id,organization_stage_id,status_id,duration,done_by,done_at,arrangement,updated_at',
        );
        // ->leftJoin('sectors', 'meals.sector_id', '=', 'sectors.id')
        // ->orderByRaw("CAST(sectors.label AS UNSIGNED), LENGTH(sectors.label)")
        // ->select('meals.*');
        // ->get();

        return datatables( $query->orderBy('day_date')->orderBy('period_id')->get())//->sortByDesc( 'meal_organization_stage.updated_at' ) )
            ->editColumn('updated_at', function ($row) {
                if($row->meal_organization_stage){
                    $updated_at = $row->meal_organization_stage->updated_at;
                    return $updated_at . ' <br> (' . $updated_at->diffForHumans() .')';
                }
                $updated_at = $row->meal_organization_stages()->orderByDesc('arrangement')->first()->updated_at;

                return $updated_at . ' <br> (' . $updated_at->diffForHumans() .')';
            })
            ->editColumn('guest_quantity', function ($row) {
                return $row->sector->guest_quantity;
            })
            ->editColumn('sector_label', function($row){
                return $row->sector->label;
            })
            ->editColumn('organization_id', function ($row) {
                return $row->sector->classification->organization->id ?? '-';
            })
            ->editColumn( 'sector', function ( $row ) {
                return $row->sector->label . ' - ' . $row->order_sector?->order->facility->name . ' - ' . $row->sector->classification->organization->name;
            } )
            ->editColumn( 'status', function ( $row ) use ( $statuses, $done_status ) {
                return "<span class='badge ' style='background:" . $row->status->color . "' >" . $row->status->name . "</span>";

//                $html = '<div><select class="form-control selectpicker status-select" ' . ( $row->status->id == $done_status ? 'disabled' : '' ) . ' name="meal_id" style="background:' . $row->status->color . '" data-status-id="' . $row->status_id . '" data-meal-id="' . $row->id . '" onchange="changeSelectPickerMeal(this)"  >';
//                foreach ( $statuses as $status ) {
//                    $span = " data-content=\"<span class='badge ' style='background:" . $status->color . "' >" . $status->name . "</span>\" ";
//                    $html .= '<option ' . ( $status->id == $done_status ? 'disabled' : '' ) . ' value="' . $status->id . '" ' . ( $status->id == $row->status->id ? 'selected' : '' ) . ' ' . $span . ' >' . $status->name . '</option>';
//                }
                // $html .= "</select></div>";
                // return $html;


            } )
            ->editColumn('progress', function($row){
                $total_stages = $row->meal_organization_stages->count();
                $total_done_stages = $row->meal_organization_stages->whereNotNull('done_at')->count();
                $percentage = $total_stages == 0 ? "0.00" : number_format(($total_done_stages / $total_stages) * 100, 2);
                return $percentage . '%';
            } )
            ->editColumn( 'current-stage', function ( $row ) {
                return $current_stage = $row->meal_organization_stage->organization_stage->stage_bank->name??trans('translation.meal-finished');
                // $current_stage = $row->meal_organization_stages->whereNull( 'done_at' )->sortBy( 'arrangement' )->first();
                // if ( $current_stage ) {
                //     return $current_stage->organization_stage->stage_bank->name;
                // }
                // return trans( 'translation.meal-finished' );
            } )
            ->addColumn( 'action', function ( $row ) {
                return '<div class="d-flex justify-content-center">
                <a class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5" href="' . route( 'meals.show', $row->id ) . '" ><i class="mdi mdi-eye"></i></a>
                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deletemeals" data-model-id="' . $row->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
                    <a target="_blank"
                    class="btn btn-outline-primary btn-sm m-1 on-default "
                    href="' . (route('admin.meal.report', $row->uuid?? fakeUuid())) . '"
                    ><i class="mdi mdi-file-document-outline"></i>
                    </a>
                    <a target="_blank"
                    class="btn btn-outline-success btn-sm m-1 on-default "
                    href="' . ( route( 'admin.meal.report', [ $row->uuid?? fakeUuid(), 'D' ] ) ) . '"
                    ><i class="mdi mdi-download-outline"></i>
                    </a>
                </div>';
            } )
            ->editColumn('sector_nationality', function ($row) {
                return $row->order_sector?->sector->nationality_organization->nationality->name ?? '-';
            })
            ->rawColumns( [ 'sector','status','action','updated_at' ] )
            ->toJson();
    }

    //??================================================================
    public function customized_datatable($query,$statuses,$done_status,$open_status)
    {
        $query->with(
            'sector:id,label,classification_id',
            'period:id,name',
            'sector.classification.organization.organization_services:id,service_id,organization_id',
            'status:id,name_ar,name_en,color',
            'order_sector:id,order_id,sector_id',
            'order_sector.order:id,facility_id,organization_service_id',
            'order_sector.order.facility:id,name'
        )
        ->leftJoin('sectors', 'meals.sector_id', '=', 'sectors.id')
        ->orderByRaw("CAST(sectors.label AS UNSIGNED), LENGTH(sectors.label)")
        ->select('meals.*')
        ->get();

        return datatables( $query->orderByDesc( 'updated_at' )->get() )
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at . ' <br>(' . $row->updated_at->diffForHumans() .')';
            })
            ->editColumn( 'food_name', function ( $row ) {
                // return $row->foods->pluck('name')??trans('translation.no-food');
                if ( $row->food_weights->count() < 1 ) {
                    return trans( 'translation.no-food' );
                }
                $html      = '';
                $i         = 1;
                $last_item = $row->food_weights->last();
                foreach ( $row->food_weights as $food_weight ) {

                    $html .= '<span class="badge bg-primary mx-1 mb-2">' . $food_weight->food->name . ' ' . $food_weight->quantity . ' ' . $food_weight->unit . '</span>' . ( $last_item->id == $food_weight->id ? '' : ' | ' );
                    if ( $i++ % 3 == 0 ) {
                        $html .= '<br>';
                    }
                }
                return $html;
            } )
            ->editColumn('sector_label', function ($row) {
                return $row->sector->label;
            })
            ->editColumn( 'sector', function ( $row ) {
                return $row->sector->label . ' - ' . $row->order_sector?->order->facility->name . ' - ' . $row->sector->classification->organization->name;
            } )
            ->editColumn( 'actual_start_time', function ( $row ) {
                return $row->meal_organization_stages->where( 'arrangement', '1' )->first()->done_at ?? trans( 'translation.not-started-yet' );
            } )
            ->editColumn( 'actual_end_time', function ( $row ) {
                if($row->status_id == Status::CLOSED_MEAL){
                    return trans('translation.meal-closed-due-to-support');
                }
                return $row->meal_organization_stage->done_at ?? trans('translation.not-delivered' );
            } )
            ->editColumn( 'current-stage', function ( $row ) {
                return $current_stage = $row->meal_organization_stage->organization_stage->stage_bank->name ?? trans('translation.no-data');
            } )
            ->editColumn('sector_nationality', function ($row) {
                return $row->order_sector?->sector->nationality_organization->nationality->name ?? '-';
            })
            ->editColumn('organization_id', function ($row) {
                return $row->order_sector?->order->organization_service->organization->id ?? '-';
            })
            ->editColumn( 'start-status', function ( $row ) {
                $start_date_time = Carbon::parse( $row->day_date . ' ' . $row->start_time );
                $first_meal_stage = $row->meal_organization_stages->where( 'arrangement', '1' )->first() ?? null;
                if($first_meal_stage){
                    $start_date_time_with_latency_buffer = $start_date_time->addMinutes($first_meal_stage->duration);
                    if ( $first_meal_stage->done_at ) {
                        if ( $first_meal_stage->done_at > $start_date_time_with_latency_buffer ) {
                            return '<span class="badge bg-warning">' . trans( 'translation.started-late' ) . '</span>';
                        }
                        return '<span class="badge bg-success">' . trans( 'translation.on-time' ) . '</span>';
                    }
                    if ( Carbon::now() > $start_date_time_with_latency_buffer ) {
                        return '<span class="badge bg-danger">' . trans( 'translation.late' ) . '</span>';
                    }
                    return '<span class="badge bg-info">' . trans( 'translation.not-started-yet' ) . '</span>';
                }
                return '<span class="badge bg-info">' . trans( 'translation.no-first-stage' ) . '</span>';
            } )
            ->editColumn( 'stage-status', function ( $row ) {
                $current_stage = $row->meal_organization_stages->whereNull( 'done_at' )->first() ?? null;
                if ( $current_stage ) {
                    if ( $current_stage->arrangement == '1' ) {
                        return '<span class="badge bg-info">' . trans( 'translation.not-started-yet' ) . '</span>';
                    }
                    $previous_stage = MealOrganizationStage::where( 'arrangement', $current_stage->arrangement - 1 )->where( 'meal_id', $row->id )->get()->first();
                    if ( Carbon::parse( $previous_stage->done_at )->addMinutes( $current_stage->duration ?? 0 ) < Carbon::now() ) {
                        return '<span class="badge bg-danger">' . trans( 'translation.late' ) . '</span>';
                    }
                }
                return '<span class="badge bg-success">' . trans( 'translation.on-time' ) . '</span>';
            } )
            ->editColumn( 'deliver-status', function ( $row ) {
                $last_stage = $row->meal_organization_stages->where( 'arrangement', $row->meal_organization_stages->count() )->first()->done_at ?? null;

                if ( $last_stage ) {
                    if ( $last_stage > Carbon::parse( $row->day_date . ' ' . $row->end_time ) ) {
                        return '<span class="badge bg-warning">' . trans( 'translation.late' ) . '</span>';
                    }
                    return '<span class="badge bg-success">' . trans( 'translation.on-time' ) . '</span>';
                }
                if ( Carbon::now() > Carbon::parse( $row->day_date . ' ' . $row->end_time ) ) {
                    return '<span class="badge bg-danger">' . trans( 'translation.late-no-delivery' ) . '</span>';
                }
                return '<span class="badge bg-info">' . trans( 'translation.not-delivered' ) . '</span>';
            } )
            ->editColumn( 'status', function ( $row ) use ( $open_status, $statuses, $done_status ) {
//                return "<span class='badge ' style='background:" . $row->status->color . "' >" . $row->status->name . "</span>";
//
                $value = in_array($row->status_id, [$done_status]);
                $html = '';
                if ($value) {
                    $html .= "<span class='badge' style='background:" . $row->status->color . "' >" . $row->status->name . "</span>";
                }else{
                    $html .= '<div><select class="selectpicker status-select" name="meal_id" style="background:' . $row->status->color . '" data-status-id="' . $row->status_id . '" data-meal-id="' . $row->id . '" onchange="changeSelectPickerMeal(this)" >';
                    foreach ($statuses as $status) {
                        $span = " data-content=\"<span class='badge' style='background:" . $status->color . "' >" . $status->name . "</span>\" ";
                        $selected = $status->id == $row->status->id ? 'selected' : '';
                        $html .= '<option value="' . $status->id . '" ' . $selected . ' ' . $span . ' >' . $status->name . '</option>';
                    }
                    $html .= "</select></div>";
                }
                return $html;
            })
            ->editColumn('progress', function($row){
                $total_stages = $row->meal_organization_stages->count();
                $total_done_stages = $row->meal_organization_stages->whereNotNull('done_at')->count();
                $percentage = $total_stages == 0 ? "0.00" : number_format(($total_done_stages / $total_stages) * 100, 2);
                return $percentage . '%';

                // foreach ($row->meal_organization_stages as $meal_organization_stage) {
                //     $total_questions += count($meal_organization_stage->organization_stage->questions);
                // }
                // if ($total_questions > 0){
                //     $total_done_questions = count($row->meal_organization_stages->whereNotNull('done_at'));
                //     $percentage = number_format(($total_done_questions/$total_questions)*100,0);
                //     return $percentage . '%';
                // } else {
                //     $total_stages = count($row->meal_organization_stages);
                //     $total_done_stages = count($row->meal_organization_stages->whereNotNull('done_at'));
                //     if ($total_done_stages > 0) {
                //         $percentage = number_format(($total_done_stages/$total_stages)*100,0);
                //         return $percentage . '%';
                //     }
                //     return '0%';
                // }
                // return '
                //     <div class="progress progress-sm">
                //         <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                //     </div>';
            } )
            ->editColumn( 'duration', function ( $row ) use ( $open_status ) {
                // return $row->calculate_duration();
                $first_stage = $row->meal_organization_stages->where( 'arrangement', '1' )->first();
                if ( $row->status_id == $open_status ) {
                    if ( $first_stage->done_at ) {
                        $actual_duration = Carbon::parse( Carbon::now() )->diffInSeconds( $first_stage->done_at );
                        return '<span class="badge bg-warning mx-1 mb-2">' . CarbonInterval::seconds( $actual_duration )->cascade()->forHumans() . '</span>';
                    }
                    return '<span class="badge bg-info mx-1 mb-2">' . trans( 'translation.not-started-yet' ) . '</span>';
                }
                $final_stage       = $row->meal_organization_stages->where( 'arrangement', $row->meal_organization_stages->count() )->first();
                $actual_duration   = Carbon::parse( $final_stage->done_at )->diffInSeconds( $first_stage->done_at );
                $expetced_duration = Carbon::parse( $row->end_time )->diffInSeconds( $row->start_time );
                $badge_color = "success";
                if ( $actual_duration > $expetced_duration ) {
                    $badge_color = "danger";
                }
                return '<span class="badge bg-' . $badge_color . ' mx-1 mb-2">' . CarbonInterval::seconds( $actual_duration )->cascade()->forHumans() . '</span>';

                // TODO: handle calculating time when it in the next day
                // $expetced = Carbon::parse($row->end_time)->diffInSeconds($row->start_time);
                // $expetced_time = CarbonInterval::seconds($expetced)->cascade()->forHumans();

                // if($row->status_id == $open_status){//not finished meals
                //     $actual = Carbon::parse(Carbon::now())->diffInSeconds($row->start_time);
                //     $actual_time = CarbonInterval::seconds($actual)->cascade()->forHumans();
                //     // dd($actual_time , $expetced_time, $actual > $expetced);
                //     if($actual > $expetced){
                //         return $html = '<span class="badge bg-danger mx-1 mb-2">' . $actual_time . '</span>';
                //     }else{
                //         return $actual_time;
                //     }
                // }
                // else{//closed meal
                //     $total = 0;
                //     foreach ($row->meal_organization_stages as $meal_organization_stage) {
                //         if ($meal_organization_stage->calculate_duration()) {
                //             $total += $meal_organization_stage->calculate_duration();
                //         } else {
                //             $total += 0;
                //         }
                //     }
                //     if ($total > 0) {
                //         $actual_duration = CarbonInterval::seconds($total)->cascade()->forHumans();
                //         if($total > $expetced){
                //             return $html = '<span class="badge bg-warning mx-1 mb-2">' . $actual_duration . '</span>';
                //         }else{
                //             return $html = '<span class="badge bg-success mx-1 mb-2">' . $actual_duration . '</span>';
                //         }
                //     } else {
                //         return '-';
                //     }
                // }

                // return $row->calculate_duration();
            } )
            ->addColumn( 'action', function ( $row ) {
                return '<div class="d-flex justify-content-center">
                <a class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5" href="' . route( 'meals.show', $row->id ) . '" ><i class="mdi mdi-eye"></i></a>
                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deletemeals" data-model-id="' . $row->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
                                  <a target="_blank"
                class="btn btn-outline-primary btn-sm m-1 on-default "
                href="' . (route('admin.meal.report', $row->uuid?? fakeUuid())) . '"
                ><i class="mdi mdi-file-document-outline"></i>
                </a>


                    <a target="_blank"
                    class="btn btn-outline-success btn-sm m-1 on-default "
                    href="' . ( route( 'admin.meal.report', [ $row->uuid?? fakeUuid(), 'D' ] ) ) . '"
                    ><i class="mdi mdi-download-outline"></i>
                    </a>
                </div>';
            } )
            ->rawColumns( [ 'action','progress', 'status', 'food_name', 'duration', 'stage-status', 'start-status', 'deliver-status', 'updated_at' ] )
            ->toJson();
    }
    //??================================================================

    //  TODO: delete meals should be relative with stages not food
    public function checkRelatives($delete_model)
    {
        if ($delete_model->meal_organization_stages->isNotEmpty()) {
            foreach ($delete_model->meal_organization_stages as $mealOrganizationStage) {
                if (!is_null($mealOrganizationStage->done_at)) {
                    return trans('translation.meal-has-answers');
                }
            }
            $delete_model->meal_organization_stages()->delete();
        }
        return '';
    }

    //??=========================================================================================================
    public function pdfReport($meal_uuid, $output = "I")
    {
        $meals = Meal::where('uuid', $meal_uuid)->firstOrFail();
        $order_sector = OrderSector::withArchived()->findOrFail($meals->order_sector_id);
        $statuses = Status::all();
        $meal_statuses = $statuses->where('type', 'meals');
        $support_statuses = $statuses->where('type', 'supports');
        $this->setPdfData([
            'attachment_label' => 'تقرير عن الوجبة',
            'organization_data' => $meals->sector->classification->organization,
            'sector' => $meals->sector,
            'body_content' => $meals,
            'order_sector' => $order_sector,
            'meal_statuses' => $meal_statuses,
            'support_statuses' => $support_statuses,
        ]);

        $mpdf = $this->mPdfInit('meal.meal');
        return $mpdf->Output($meals->period->name . trans('translation.day') . $meals->day_date . ' - ' . $order_sector->order->facility->name . ' - ' . Carbon::now() . '.pdf', $output);
    }
}
