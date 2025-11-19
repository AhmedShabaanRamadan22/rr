<?php

namespace App\Http\Resources\External\Wafir;

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
        return [
          'id' => $this->id,
          'name' => $this->name,
          'phone' => $this->phone_code . $this->phone,
          'nationality' => $this->nationality_name,
          'scrub_size' => $this->scrub_size,
          'address' => $this->address,
          'email'=> $this->email,
          'personal_photo'=> $this->profile_photo,
          'code'=> $this->monitor?->code
        ];
    }
}
