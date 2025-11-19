<?php

namespace App\Http\Resources\WebResources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'slug' => $this->slug,
            'name_ar' => $this->name_ar,
            'name_en' => $this->name_en,
            'name' => $this->name,
            'domain' => $this->domain,
            'about_us' => $this->about_us,
            // 'contract' => $this->contract,
            'policies' => $this->policies,
            'organization_news' => $this->organization_news,
            'phone' => $this->phone,
            'has_esnad' => $this->has_esnad,
            'close_registeration' => $this->close_registeration,
            'close_order' => $this->close_order,
            'primary_color' => $this->primary_color,
            'email' => $this->email,
            'support_phone' => $this->support_phone,
            'logo' => $this->logo,
            'background_image' => $this->background_image,
            'profile_file' => $this->profile_file,
        ];

        return $data;
    }
}
