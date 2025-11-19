<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorResource extends JsonResource
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
            'code' => $this->code,
            'name' => ($this->name ?? '') . '-' . ($this->user?->phone ?? '') ,
        ];
        if($this->user){
            $data['user_info'] = new UserResource($this->user);
        }
        return $data;
    }
}
