<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BravoResource extends JsonResource
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
            'number' => $this->number,
            'code' => $this->code,
            'channel' => $this->channel,
            'name' => $this->name,
            'giver_name' => $this->giver_name,
            'user_name' => $this->user_name,
            'created_at' => $this->created_at,
        ];
    }
}
