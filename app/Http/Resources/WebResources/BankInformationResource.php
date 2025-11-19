<?php

namespace App\Http\Resources\WebResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BankInformationResource extends JsonResource
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
            'account_name' => $this->account_name,
            'iban' => $this->iban,
            'bank_id' => $this->bank_id,
            'owner_national_id' => $this->owner_national_id,
            'bank_name' => $this->bank_name,
            'bank' =>  new BankResource($this->bank),
        ];

        return $data;
    }
}
