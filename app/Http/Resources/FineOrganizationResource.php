<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FineOrganizationResource extends JsonResource
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
            'name' => $this->fine_bank->name,
            'fine_bank_id' => $this->fine_bank_id,
            'organization_id' => $this->organization_id,
            'price' => $this->price,
            'description' => $this->description,
        ];
        return $data;
    }
}
