<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StageResource extends JsonResource
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
            'name' => $this->organization_stage->stage_bank->name,
            'is_open' => $this->isOpen(),
            'is_done' => !is_null($this->done_at),
            'description' => null,
        ];

        return $data;

    }
}
