<?php

namespace App\Http\Resources\MealDashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        $code = $this->order_sector->order->organization_service->organization->slug .
            '-TK' . str_pad($this->id, 4, '0', STR_PAD_LEFT) . '-' . 'OR'.
            str_pad($this->order_sector->order->id, 3, '0', STR_PAD_LEFT) ;

        return [
            'id' => $this->id,
            'code' => $code,
            'status' => $this->status->name,
            'status_color' => $this->status->color,
            'reason' => $this->reason_danger->reason->name,
            'level_color' => $this->reason_danger->danger->color,
            'reporter_name' => $this->user->name,
            'is_today' => Carbon::parse($this->created_at)->isToday(),
            'created_at' => $this->created_at->diffForHumans(),
            'details_link' => route('tickets.show', $this),
        ];
    }
}
