<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CloudflareCustomHostnameResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'hostname_txt_verification_record' => [
                'status' => $this['status'],
                'name' => $this['ownership_verification']['name'] ?? null,
                'value' => $this['ownership_verification']['value'] ?? null,
                'error' => $this['verification_errors'][0] ?? null
            ],
            'ssl_txt_verification_record' => [
                'status' => $this['ssl']['status'],
                'name' => $this['ssl']['txt_name'] ?? null,
                'value' => $this['ssl']['txt_value'] ?? null,
                'error' => $this['ssl']['validation_errors'][0]['message'] ?? null
            ],
        ];
    }
}
