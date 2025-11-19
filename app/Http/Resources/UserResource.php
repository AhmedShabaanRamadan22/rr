<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'phone_code' => $this->phone_code,
            'organization_id' => $this->organization_id,
            'email' => $this->email,
            'nationality_name' => $this->nationality_name,
            'profile_photo' => $this->profile_photo,
            'created_at' => $this->created_at,
            'bravo_info' => null,
        ];
        if ($this->bravo) {
            $data['bravo_info'] = new BravoResource($this->bravo);
        }
        return $data;
    }
}
