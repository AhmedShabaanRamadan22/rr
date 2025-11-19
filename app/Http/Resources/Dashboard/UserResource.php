<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        $data = [
            'id' => $this->id,
            'role_name' => $this->role_name,
            'organization_id' => $this->organization_id,
            'created_at' => $this->created_at,
            'groupBy' => $this->organization_id,
            'chartGroupBy' => $this->status_id,
        ];
        return $data;
    }
}
