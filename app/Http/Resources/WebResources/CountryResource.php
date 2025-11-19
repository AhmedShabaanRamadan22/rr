<?php

namespace App\Http\Resources\WebResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'name' => $this->name,
            'continent' => $this->continent,
            'phone_code' => $this->phone_code,
            'code' => $this->code,
            'iso3' => $this->iso3,
        ];

        return $data;
    }
}
