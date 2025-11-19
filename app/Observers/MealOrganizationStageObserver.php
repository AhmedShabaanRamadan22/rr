<?php

namespace App\Observers;

use App\Events\MealStageEvent;
use App\Events\ModelCRUDEvent;
use App\Models\MealOrganizationStage;

class MealOrganizationStageObserver
{
     /**
     * Handle the Meal "created" event.
     */
    public function created(MealOrganizationStage $meal_organization_stage): void
    {
        // $meal = $meal_organization_stage->meal;
        // event(new ModelCRUDEvent(class_basename($meal), $meal->id, 'created'));
    }
    
    /**
     * Handle the Meal "updated" event.
     */
    public function updated(MealOrganizationStage $meal_organization_stage): void
    {
        $meal = $meal_organization_stage->meal;
        event(new ModelCRUDEvent(class_basename($meal), $meal->id, 'updated'));
        event(new MealStageEvent($meal));
        //
    }
    
    /**
     * Handle the Meal "deleted" event.
     */
    public function deleted(MealOrganizationStage $meal_organization_stage): void
    {
        $meal = $meal_organization_stage->meal;
        event(new ModelCRUDEvent(class_basename($meal), $meal->id, 'deleted'));
        //
    }

    /**
     * Handle the Meal "restored" event.
     */
    public function restored(MealOrganizationStage $meal): void
    {
        //
    }

    /**
     * Handle the Meal "force deleted" event.
     */
    public function forceDeleted(MealOrganizationStage $meal): void
    {
        //
    }
}
