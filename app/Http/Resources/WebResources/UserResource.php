<?php

namespace App\Http\Resources\WebResources;

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
            'nationality' => $this->nationality,
            'national_id' => $this->national_id,
            'national_id_expired' => $this->national_id_expired,
            'national_id_expired_hj' => $this->national_id_expired_hj,
            'birthday' => $this->birthday,
            'birthday_hj' => $this->birthday_hj,
            'address' => $this->address,
            'email' => $this->email,
            'boss_supervisor_flag' => $this->is_boss_or_supervisor,
            'is_verified' => $this->is_verified,
            'national_source' => $this->national_source,
            'nationality_name' => $this->nationality_name,
            'national_source_name' => $this->national_source_name,
            'national_id_attachment' => $this->national_id_attachment,
            'profile_photo' => $this->profile_photo,
            'attachmentUrl' => $this->attachmentUrl,
            'country' => new CountryResource($this->country),
        ];

        return $data;
    }
}
