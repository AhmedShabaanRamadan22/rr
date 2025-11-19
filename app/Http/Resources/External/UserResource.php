<?php

namespace App\Http\Resources\External;

use App\Http\Resources\BravoResource;
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
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone_code . $this->phone,
            'email' => $this->email,
            'profile_photo' => $this->profile_photo,
            'address' => $this->address,
            'national_source' => $this->national_source_name,
            'birthday' => $this->birthday,
            'national_id_expired' => $this->national_id_expired,
            'national_id' => $this->national_id,
            'nationality' => $this->nationality_name,
            'attachmentUrl' => $this->whenLoaded('attachments', function () {
                return $this->attachment_url;
            }),
        ];
    }
}
