<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackLocationStatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'longitude' => (double) $this->longitude, 
            'latitude' => (double) $this->latitude, 
            'action' => $this->action, 
            'location_type' => class_basename($this->track_locationable_type),
        ];
        return $data;
    }
}
