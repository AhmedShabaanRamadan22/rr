<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $data = [
            'id' => $this->id,
            'name' => $this->period->name??'-',
            'period_id' => $this->period->id??'-',
            'start_time' => Carbon::parse($this->day_date .' '. $this->start_time),
            'end_time' => Carbon::parse($this->day_date .' '. $this->end_time),
            'day_date' => $this->day_date,
            'created_at' => $this->created_at,
            'food' => FoodWeightMealResource::collection($this->food_weights),
            'stages' => MealOrganizationStageResource::collection($this?->meal_organization_stages),
        ];

        return $data;
    }
}
