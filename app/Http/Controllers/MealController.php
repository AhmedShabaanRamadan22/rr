<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Meal;
use App\Models\Period;
use App\Models\OrderSector;
use App\Http\Requests\MealRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SectorRequest;
use App\Http\Resources\MealResource;
use Illuminate\Support\Facades\Event;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MealRequest $request, SectorRequest $sectorRequest)
    {
        // TODO validate date and monitor permission
        $order_sector_id = $request->order_sector_id;
        $day = $request->day;
        $sector_id = OrderSector::find($order_sector_id)->sector->id;

        $meals = Meal::where([['sector_id', $sector_id], ['day_date', $day]])
            ->with([
                'period',
                'meal_organization_stages' => [
                    'meal.meal_organization_stages',
                    'organization_stage' => [
                        'stage_bank',
                    ]
                ],
                'food_weights.food.food_type'
            ])
            ->get();

        $periods = Period::whereHas('operation_type',function($q){
            $q->where('model','meals');
        })->get();

        $data = [];
        $types = [];

        foreach ($periods as $period) {
            $meal_period = $meals->where('period_id',$period->id)->first();
            $meal_organization_stages = $meals->where('period_id',$period->id)->first()?->meal_organization_stages;
            if ($meal_period){//$meal_organization_stages) {
                array_push ($data , new MealResource($meal_period)) ;//MealOrganizationStageResource::collection($meal_organization_stages);
                array_push($types , [
                    'id'=>$period->id??0,
                    'name' => $period->name??'not-found',
                 ]);
            }
        }

        return response()->json(['types'=>$types,'data' => $data], 200);

    }

    public function cascadeDelete($meal_id){
        try {
            DB::beginTransaction();
        
            $meal = Meal::find($meal_id);
            if (is_null($meal)) {
                return response()->json(['message' => trans('translation.something went wrong')], 300);
            }
            
            $meal->meal_organization_stages()->each( function($meal_organization_stage){
                $meal_organization_stage->answers()->each(function($answer){
                    $answer->attachments()->delete();
                });
                $meal_organization_stage->answers()->delete();
                $meal_organization_stage->track_locations()->delete();
            });
            $meal->meal_organization_stages()->delete();
            $meal->food_weight_meals()->delete();
            $meal->delete();
        
            DB::commit();

            return response()->json(['message' => trans('translation.deleted-successfully')], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => trans('translation.delete-failed')], 500);
        }
    }

    public function hardDelete($meal_id){
        try {
            DB::beginTransaction();
        
            $meal = Meal::withTrashed()->where('id', $meal_id)->first();
            if (is_null($meal)) {
                return response()->json(['message' => trans('translation.something went wrong')], 300);
            }
            
            $meal->meal_organization_stages()->withTrashed()->each( function($meal_organization_stage){
                $meal_organization_stage->answers()->withTrashed()->each(function($answer){
                    $answer->attachments()->withTrashed()->forceDelete();
                });
                $meal_organization_stage->answers()->withTrashed()->forceDelete();
                $meal_organization_stage->track_locations()->withTrashed()->forceDelete();
            });
            $meal->meal_organization_stages()->withTrashed()->forceDelete();
            $meal->food_weight_meals()->withTrashed()->forceDelete();
            $meal->forceDelete();
        
            DB::commit();

            return response()->json(['message' => trans('translation.deleted-successfully')], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => trans('translation.delete-failed')], 500);
        }
    }

}
