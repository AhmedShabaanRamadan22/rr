<?php

namespace App\Services;

use App\Models\Contract;
use App\Models\Danger;
use App\Models\Form;
use App\Models\Reason;
use App\Models\Stage;
use App\Models\StageBank;
use App\Models\Status;
use Carbon\Carbon;

class ChartService
{

    public static function get_model_options($model,$organization_id){
        $form_query = Form::with(
            'organization_service.organization:id,name_ar,name_en',
            'organization_service.service:id,name_ar,name_en',
            'organization_category.category:id,name',
        );
        if(!is_null($organization_id)){
            $form_query->whereHas('organization_service',function($q) use ($organization_id){
                $q->where('organization_id',$organization_id);
            });
        }
        $all_model = [
            "Order" => [
                Status::order_statuses(),
                'name',
            ],
            "Ticket" => [
                Status::ticket_statuses(),
                'name'
            ],
            "Ticket_dangers" => [
                Danger::query(),
                'level'
            ],
            "Support" => [
                Status::support_statuses(),
                'name'
            ],
            "Meal" => [
                StageBank::query(),
                'name'
            ],
            "SubmittedForm" => [
                $form_query,
                'form_full_name'
            ],
            
        ];

        return $all_model[$model];
    } 

    public static function getModelGroupedBySpecificAttribute($model,$model_name,$grouped_by_attribute,$organization_id = null){
        $data = [];
        
        $models_options = self::get_model_options($model_name,$organization_id);
        $model_grouped_by = $model->groupBy(function ($item) use ($models_options,$model_name,$grouped_by_attribute)  {
            return $item->{$grouped_by_attribute};
        });
        foreach($models_options[0]->get() as $item){
            if(isset($model_grouped_by[$item->id])){
                $data = array_merge($data,[$item[$models_options[1]] => count($model_grouped_by[$item->id])]);

            }else{
                $data = array_merge($data,[$item[$models_options[1]] => 0]);
            }
        }

        return $data;
    } 

    public static function getModelGroupedByDate($model, $dateCategories,$date_column)
    {
        $model_data = $model->CountBy(function ($date) use ($date_column) {
            return Carbon::parse($date->{$date_column})->setTimezone('Asia/Riyadh')->format('m-d-Y'); // grouping by years
        });
        $data = collect($dateCategories)
            ->map(function ($value, $key) use ($model_data) {
                return $model_data->get($value) ?? 0;
            })->flatten();

        return $data;
    }

    public static function getModelGroupedByDateAndSpecificAttribute($model, $model_name, $dateCategories,$grouped_by_attribute,$date_column ,$organization_id = null) {
        $data = [];
        $models_options = self::get_model_options($model_name,$organization_id);
        $model_group_by_models_options = $model->groupBy(function ($item) use ($models_options,$model_name,$grouped_by_attribute) {
            return $item->{$grouped_by_attribute};
        });
        foreach($models_options[0]->get() as $item){
            if(isset($model_group_by_models_options[$item->id])){
                $result_model_group_by_models_options = self::getModelGroupedByDate($model_group_by_models_options[$item->id], $dateCategories,$date_column);
                array_push($data,['name'=>$item[$models_options[1]],'data'=>$result_model_group_by_models_options]);

            }else{
                array_push($data,['name'=>$item[$models_options[1]],'data'=>array_fill(0,count($dateCategories),0)]);
            }
        }

        return $data;
    }
}
