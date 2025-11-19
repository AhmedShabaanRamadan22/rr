<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        // return parent::toArray($request);


        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'phone_code' => $this->phone_code,
            'role_ids_array' => $this->role_ids_array ,
            'organization_id' => $this->organization_id ,
            // '' => $this-> ,
        ];

        return $data;

    }
}
