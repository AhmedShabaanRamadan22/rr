<?php

namespace App\Http\Resources\MealDashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MealOrganizationStageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        $day = null;
        $donePreviousDay = '';
        $time = trans('translation.waiting-for-stage');

        if(isset($this->done_at))
        {
            $time = Carbon::parse($this->done_at)->format('h:i:sA');
            $day = Carbon::parse($this->done_at)->toDateString();
            $donePreviousDay = $day != $this->meal->day_date ? (' - ' . $day) : '';
        }

        $data = [
            'id' => $this->id,
            'name' => $this->organization_stage->stage_bank->name,
            'answers_route' => route('meal-organization-stages.questions', $this->id),
            'time' => $time,
            'day' => $donePreviousDay,
            'expected_end_time' => $this->expected_end_time,
            'time_status' => $this->time_status,
            'organization_stage_id' => $this->organization_stage_id,
            'period_id' => $this->meal->period_id,
        ];
        return $data;
    }
}
