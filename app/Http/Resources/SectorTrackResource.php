<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\OrganizationService;
use Illuminate\Http\Resources\Json\JsonResource;

class SectorTrackResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    private static $serviceId;
    private static $organizationServiceId = [];
    public function toArray(Request $request): array
    {
        if (is_null(self::$serviceId)) {
            self::$serviceId = Service::where('name_en', 'Catering')->first()->id;
        }

        $organization_service = self::$organizationServiceId[$this->classification->organization->id]
        ?? $this->classification->organization->organization_services
            ->where('service_id', self::$serviceId)
            ->first()->id;

        self::$organizationServiceId[$this->classification->organization->id] = $organization_service;

        $data = [
            'id' => $this->id,
            'organization_name' => $this->classification->organization->name,
            'organization_id' => $this->classification->organization->id,
            'label' => $this->label,
            'sight' => $this->sight,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'arafah_longitude' => $this->arafah_longitude,
            'arafah_latitude' => $this->arafah_latitude,
            'kitchen_quantity' => $this->kitchen_quantity,
            'facility_name' => $this->active_order_sector_service($organization_service)->first()?->order->facility->name,
            'nationality' => $this->nationality_organization->nationality->name,
            'flag_icon' => $this->nationality_organization->nationality->icon,
            'supervisor' => $this->supervisor->name,
            'boss' => $this->boss->name,
            'guest_quantity' => $this->guest_quantity,
        ];
        return $data;

    }
}
