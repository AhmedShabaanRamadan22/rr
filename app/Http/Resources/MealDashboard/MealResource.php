<?php

namespace App\Http\Resources\MealDashboard;

use App\Http\Resources\MealDashboard\MealOrganizationStageResource;
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
    public function toArray(Request $request = null): array
    {
        $data = [
            'id' => $this->id,
            'uuid' => $this->uuid ?? fakeUuid(),
            'monitors' => implode(' - ', $this->sector->monitors_name_array),
            'boss' => $this->sector->boss_name,
            'supervisor' => $this->sector->supervisor_name,
            'sector_label' => $this->sector->label,
            'sector_flag' => $this->sector->nationality_organization->nationality->flag_icon,
            'facility_name' => $this->order_sector->order->facility->name,
            'guest_quantity' => $this->sector->guest_quantity,
            'nationality' => $this->sector->nationality_organization->nationality->name,
            'period_id' => $this->period_id,
            'meal_route' => route( 'meals.show', $this->id ),
            'order_sector_id' => $this->order_sector_id,
            'sector_name' => $this->order_sector?->sector_name . ' - ' . $this->order_sector?->name,
            'stages' => MealOrganizationStageResource::collection($this->meal_organization_stages_arranged),
            'start_time' => Carbon::parse($this->start_time)->format('h:i:sA'),
            'end_time' => Carbon::parse($this->end_time)->format('h:i:sA'),
            'organization_color' => $this->sector->nationality_organization->organization->primary_color,
            'current_organization_stage_id' => $this->current_stage->organization_stage_id,
            'current_status' => $this->current_stage->time_status,
            'is_in_last_stage' => $this->current_stage->isLastStage(),
        ];
        return $data;
    }
}
