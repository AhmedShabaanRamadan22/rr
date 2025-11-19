<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileAppInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_version' => $this->current_version,
            'ota_flag' => config('app.ota_flag'), 
            'download_android_url' => $this->whenLoaded('androidBundleFile', function(){
                return $this->androidBundleFile->url;
            }),
            'download_ios_url' => $this->whenLoaded('iosBundleFile', function(){
                return $this->iosBundleFile->url;
            }),
        ];
    }
}
