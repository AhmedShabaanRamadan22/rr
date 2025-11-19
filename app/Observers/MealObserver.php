<?php

namespace App\Observers;

use App\Models\Meal;
use App\Events\ModelCRUDEvent;
use App\Models\User;
use App\Models\Sector;
use App\Models\Status;
use App\Models\StageBank;
use App\Models\OrganizationStage;
use App\Notifications\CrudNotify;
use App\Models\MonitorOrderSector;
use App\Models\MealOrganizationStage;

class MealObserver
{
    /**
     * Handle the Meal "created" event.
     */
    public function created(Meal $meal): void
    {
        $organization_id = $meal->sector->nationality_organization->organization_id;
        $meal->update([
            'status_id'=> Status::OPENED_MEAL
        ]);
        // $order_sectors = MonitorOrderSector::whereIn('order_sector_id', Sector::find($meal->sector->id)->order_sectors->pluck('id'));
        // User::find($meal->user->id)->notify(new CrudNotify($meal, 'create'));
        $this->generate_meal_stages($organization_id,$meal->id);
        event(new ModelCRUDEvent(class_basename($meal), $meal->id, 'created'));
    }

    /**
     * Handle the Meal "updated" event.
     */
    public function updated(Meal $meal): void
    {
        event(new ModelCRUDEvent(class_basename($meal), $meal->id, 'updated'));
        //
    }

    /**
     * Handle the Meal "deleted" event.
     */
    public function deleted(Meal $meal): void
    {
        event(new ModelCRUDEvent(class_basename($meal), $meal->id, 'deleted'));
        //
    }

    /**
     * Handle the Meal "restored" event.
     */
    public function restored(Meal $meal): void
    {
        //
    }

    /**
     * Handle the Meal "force deleted" event.
     */
    public function forceDeleted(Meal $meal): void
    {
        //
    }

    public function generate_meal_stages($organization_id, $meal_id)
    {
        $organization_stages = OrganizationStage::where('organization_id',$organization_id)->orderBy('arrangement')->get();
        
        // if($organization_stages == null){
        //     $organization_stages = StageBank::orderBy('arrangement')->get();
        // }
        
        foreach ($organization_stages as $key => $organization_stage) {
            MealOrganizationStage::create([
                'organization_stage_id' => $organization_stage->id,
                'meal_id' => $meal_id,
                'status_id' => $key == 0 ? Status::OPENED_MEAL_STAGE : Status::CLOSED_MEAL_STAGE,
                'arrangement' => $organization_stage->arrangement,
                'duration' => $organization_stage->duration
            ]);
        }
    }
}