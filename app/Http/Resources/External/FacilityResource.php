<?php

namespace App\Http\Resources\External;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
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
            'registration_number' => $this->registration_number,
            'license' => $this->license,
            'name' => $this->name,
            'address' => $this->nationalAddress,
            'email' => $this->user->email,
            'phone' => $this->user->phone_code . $this->user->phone,
        ];

        if ($request->routeIs('wafir.users.facilities.show')) {
            $data['city'] = $this->whenLoaded('city', fn () => $this->city->name);
            $data['district'] = $this->whenLoaded('district', fn () => $this->district->name);
            $data['street_name'] = $this->street_name;
            $data['building_number'] = $this->building_number;
            $data['postal_code'] = $this->postal_code;
            $data['sub_number'] = $this->sub_number;

            $data['national_address'] = $this->when(
                $this->relationLoaded('city') && $this->relationLoaded('district'),
                fn() => $this->national_address
            );

            $data['attachmentUrl'] = $this->whenLoaded('attachments', fn () => $this->attachment_url);
        }

        return $data;
    }
}
