<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        if($this->track_locationable && !is_null($this->track_locationable)){
                  
            $data = [
                'id' => $this->id,
                'device' => $this->device, 
                'user_id' => $this->user_id, 
                'longitude' => (double) $this->longitude, 
                'latitude' => (double) $this->latitude, 
                'order_sector' => new OrderSectorTrackResource($this->track_locationable->order_sector_obj()), 
                'details' => $this->details, 
                'action' => $this->action, 
                'action_time' => $this->created_at, 
                'device_info' => $this->device_info, 
                'track_locationable_id' => $this->track_locationable_id, 
                'track_locationable_type' => $this->track_locationable_type,
                'location_type' => class_basename($this->track_locationable_type),
                'user_info' => new UserResource($this->user), 
            ];
            $className = class_basename($this->track_locationable) . 'Resource';
            $resourceClass = "App\\Http\\Resources\\{$className}";
            $data['object'] =  new $resourceClass($this->track_locationable);

            return $data;
        }
        return [];
        
    }
}
