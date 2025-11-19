<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Danger;
use App\Models\Meal;
use App\Models\Organization;
use App\Models\Status;
use App\Models\SubmittedForm;
use App\Models\Support;
use App\Http\Resources\MealDashboard\SectorInfoResource;
use App\Models\FoodWeight;
use App\Models\Nationality;
use App\Models\OrderSector;
use App\Services\ChartService;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
        public function root()
        {

            $datatable_columns = [
                'orders' => array(
                    'id' => 'table_id',
                    'code' => 'order-code',
                    'organization_name' => 'organization-name',
                    'service_name' => 'service-name',
                    'user-name' => 'facility-user-name',
                    'facility-name' => 'facility-name',
                    'status_name' => 'status',
                    'created_at' => 'order-created_at',
                    'updated_at' => 'order-updated_at',
                ),
                'meals' => Meal::columnNames(),
                'tickets' => Ticket::columnNames(),
                'supports' => Support::columnNames(),
                'submitted_forms' => SubmittedForm::columnNames(),
            ];

            $chart_colors = [
                'order_statuses_color' =>  Status::order_statuses()->pluck('color'),
                'ticket_statuses_color' => Status::ticket_statuses()->pluck('color'),
                'support_statuses_color' => Status::support_statuses()->pluck('color'),
                'danger_colors' => Danger::query()->pluck('color'),

            ];
            $dateCategories = $this->getDateCategory();//[

            $support_status_id_passed = Status::all_support_statuses_without_cancel();

            $all_organizations = Organization::withCount(['nationality_organizations','food_weights'])->get();
            $all_organization = $this->getFakeOrganizationAll();
            $all_organizations->prepend($all_organization);
            $current_organization = null;
            if(!auth()->user()->hasRole(['superadmin','admin','government'])){
                $current_organization = auth()->user()?->organization;
                if($current_organization){
                    $all_organizations = Organization::withCount(['nationality_organizations','food_weights'])->where('id',$current_organization->id)->get();
                }else {
                    abort(427,'Unassigned Organization');
                }



            }

            return view('admin.dashboard.admin.index', compact('all_organizations','datatable_columns','dateCategories','chart_colors','current_organization','support_status_id_passed'));//, 'monitorCount', 'tickets', 'organizationData', 'organizations', 'dangerData', 'dangerColor', 'orderLabels', 'orderData', 'orderColors', 'mealTarget', 'seriesData', 'dateCategories', 'activities'));
        }

        //??=========================================================================================================
        public function dashboard_data(){
            $name = request('model');
            $model = app('App\Models\\' . $name);
            if(!($model && auth()->check())){
                return response(['message'=>'m not found,or unauth'],404);
            }

            $models = [
                "Facility" => [
                    "with" => null,
                    "has_chart" => false,
                ],
                "Sector" => [
                    "with" => ["classification"],
                    "has_chart" => false,
                ],
                "User" => [
                    "with" => ["roles"],
                    "has_chart" => false,
                ],
                "Ticket" => [
                    "with" => ["reason_danger","reason_danger.danger"],
                    "has_chart" => true,
                ],
                "Support" => [
                    "with" => ["assists","order_sector",'order_sector.sector','order_sector.sector.classification','status'],
                    "has_chart" => true,
                ],
                "Meal" => [
                    "with" => ["sector","sector.classification","meal_organization_stage.organization_stage.stage_bank","meal_organization_stages.organization_stage.stage_bank","meal_organization_stages_arranged.organization_stage.stage_bank"],
                    "has_chart" => true,
                ],
                "SubmittedForm" => [
                    "with" => ["order_sector","order_sector.sector","order_sector.sector.classification",],
                    "has_chart" => true,
                ],
                "Order" => [
                    "with" => ["organization_service","status"],
                    "has_chart" => true,
                ],
            ];
            $with = $models[$name]['with'];
            $model_has_chart = $models[$name]['has_chart'];
            $tableName = $model->getTable();
            $model_query = $model::query();

            if($with){
                $model_query->with($with);
            }
            $model_result = $model_query->get();
            $model_resource_class = 'App\\Http\\Resources\\Dashboard\\' . $name . 'Resource';
            if(class_exists($model_resource_class)){
                $model_result =  collect( json_decode( json_encode( $model_resource_class::collection($model_result))));
            }

            $dateCategories = $this->getDateCategory();
            $charts = null;

            $model_result_before_groupd = $model_result ;
            if(isset($model_result->first()->groupBy)){
                $model_result = $model_result->groupBy(function ($item) {
                    return $item->groupBy;
                });

            }

            $current_organization = null;
            if($is_not_super_admin = !auth()->user()->hasRole(['superadmin','government'])){
                $current_organization = auth()->user()?->organization;
                if($current_organization && $model_result->isNotEmpty()){
                    $model_result = $model_result->filter(function($result,$key) use ($current_organization){
                        return $key == $current_organization->id;
                    });
                }
            }
            if($model_has_chart){
                $charts = $this->getChartData($name, $model_result, $model_result_before_groupd, $dateCategories, '', $is_not_super_admin);
                if(in_array($name,['Ticket'])){
                    $charts = array_merge($charts,$this->getChartData($name . '_dangers', $model_result, $model_result_before_groupd, $dateCategories, 'Danger', $is_not_super_admin));
                }

            }

            return response([$name => $model_result,"tableName" => $tableName,"charts" => $charts],200);

        }
        //??=========================================================================================================
        public function sector_info(Request $request){
            $order_sector_id = $request->order_sector_id;
            $meal_id = $request->meal_id;

            $order_sector = OrderSector::findOrFail($order_sector_id);
            $meal = Meal::with('food_weights.food.food_type')->findOrFail($meal_id);
            return new SectorInfoResource($order_sector, $meal);
        }
        //??=========================================================================================================

        public function getChartData($name, $model_result, $model_result_before_groupd, $dateCategories, $attribute, $is_not_super_admin){
            $ChartService = new ChartService();
            $return_attribute = $attribute == '' ? '' : strtolower($attribute) .'_';
            $date_column = in_array($name,['Meal']) ? 'day_date' : 'created_at';
            $pie_chart_series = $model_result->map(function($result,$organization_id) use ($name,$attribute,$ChartService){
                return collect($ChartService->getModelGroupedBySpecificAttribute($result, $name, 'chartGroupBy'.$attribute, $organization_id));

            });
            $stacked_column_chart_series = $model_result->map(function($result,$organization_id) use ($name,$dateCategories,$attribute,$date_column,$ChartService) {
                return $ChartService::getModelGroupedByDateAndSpecificAttribute($result, $name, $dateCategories, 'chartGroupBy'.$attribute, $date_column , $organization_id);
            });
            if(!$is_not_super_admin){
                $pie_chart_series[0] = collect($ChartService::getModelGroupedBySpecificAttribute($model_result_before_groupd, $name, 'chartGroupBy'.$attribute));
                $stacked_column_chart_series[0] = ($ChartService::getModelGroupedByDateAndSpecificAttribute($model_result_before_groupd, $name, $dateCategories, 'chartGroupBy'.$attribute, $date_column ));
            }
            $charts = [
                $return_attribute . 'pie_chart_series' => $pie_chart_series,
                $return_attribute . 'stacked_column_chart_series' => $stacked_column_chart_series,
            ];
            return $charts;

        }
        //??=========================================================================================================
        public function getDateCategory(){
            $previous_days = 6;
            $next_days = 3 ;
            return dates_range($previous_days, $next_days,'m-d-Y');
        }
        //??=========================================================================================================
        public function getFakeOrganizationAll(){
            $all_organization = new Organization([
                "slug" => "ALL",
                "name_ar" => "الكل",
                "name_en" => "All",
                "domain" => "http://localhost:5173",
                "about_us" => null,
                "contract" => null,
                "policies" => null,
                "phone" => null,
                "has_esnad" => "0",
                "close_registeration" => "0",
                "close_order" => "0",
                "primary_color" => null,
                "sender_id" => null,
                "city_id" => null,
                "district_id" => null,
                "postal_code" => null,
                "building_number" => null,
                "sub_number" => null,
                "release_date" => null,
                "email" => null,
                "release_date_hj" => null,
                "street_name" => null,
                "registration_number" => null,
                "registration_source" => null,
                "support_phone" => null,
                "created_at" => "2024-03-18 14:28:49",
                "updated_at" => "2024-03-18 14:28:49",
                "deleted_at" => null,
            ]);
            $all_organization->id = 0;
            $all_organization->nationality_organizations_count = Nationality::all()->count();
            $all_organization->food_weights_count = FoodWeight::all()->count();
            return $all_organization;
        }
        //??=========================================================================================================

        public function dashboard_looker_studio(){
            $looker_studio_embded_url = "https://lookerstudio.google.com/embed/reporting/68ddc911-f86d-4fc1-bd3e-f56c49bc07a9/page/K2gCF";

            return view('admin.dashboard-looker-studio.index',compact('looker_studio_embded_url'));
        }

        //??=========================================================================================================

        public function dashboard_filament()
            {
                $filament_embedded_url = config('services.filament_url');

                if (!$filament_embedded_url) {
                    Log::warning('Missing or empty filament_url in config/services.php');
                }

            return view('admin.dashboard-filament.index', compact('filament_embedded_url'));
        }

        //??=========================================================================================================

}
