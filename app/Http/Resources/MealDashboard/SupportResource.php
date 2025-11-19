<?php

namespace App\Http\Resources\MealDashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type_name,
            'period' => $this->period->name,
            'reason' => $this->reason_danger->reason->name,
            'level_color' => $this->reason_danger->danger->color,
            'reporter_name' => $this->user->name,
            'quantity' => $this->quantity,
            'status' => $this->status->name,
            'status_color' => $this->status->color,
            'is_today' => Carbon::parse($this->created_at)->isToday(),
            'created_at' => $this->created_at->diffForHumans(),
            'details_link' => route('supports.show', $this),
        ];
    }
}
