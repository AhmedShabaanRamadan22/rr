<?php

namespace App\Http\Resources\OrderReports\OperationSummary;

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
        return [
            'period' => $this->period->name,
            'meal_url' => route('admin.meal.report', $this->uuid ?? fakeUuid()),
            'status' => $this->status->name,
            'status_color' => $this->status->color,
            'time_status' => $this->current_stage->time_status,
            'has_support' => $this->supports->isNotEmpty(),
        ];
    }
}
