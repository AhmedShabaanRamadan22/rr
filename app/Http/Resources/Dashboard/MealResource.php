<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        $data = [
            'id' => $this->id,
            'status_id' => $this->status_id,
            'order_sector_id' => $this->sector_id,
            'organization_id' => $this->sector->classification->organization_id,
            'guest_quantity' => $this->sector->guest_quantity,
            'created_at' => $this->created_at,
            'day_date' => $this->day_date,
            'groupBy' => $this->sector->classification->organization_id,
            'chartGroupBy' => $this->meal_organization_stage?->organization_stage?->stage_bank?->id ,
        ];
        return $data;
    }

    // public function getCurrentStage(){
    //     $current_stage = $this->meal_organization_stages->whereNull('done_at')->sortBy('arrangement')->first();
    //     if($current_stage){
    //         return $current_stage->organization_stage->stage_bank->id;
    //     }
    //     return $this->meal_organization_stages->sortBy('arrangement')->last()->organization_stage->stage_bank->id;
    // }
}