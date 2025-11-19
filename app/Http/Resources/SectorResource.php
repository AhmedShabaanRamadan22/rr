<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\OrganizationService;
use Illuminate\Http\Resources\Json\JsonResource;

class SectorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $service_id = 1; //Catering service
        $organization_service = $this->classification->organization->organization_services->firstWhere('service_id', $service_id)->id;
        
        $data = [
            'id' => $this->id,
            'label' => $this->label,
            'sight' => $this->sight,
            'guest_quantity' => $this->guest_quantity,
            'classification_id' => $this->classification_id,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'arafah_longitude' => $this->arafah_longitude,
            'arafah_latitude' => $this->arafah_latitude,
            'kitchen_quantity' => $this->kitchen_quantity,
            'location' => $this->location,
            'arafah_location' => $this->arafah_location,
            'note' => $this->note,
            'nationality_organization_id' => $this->nationality_organization_id,
            'flag_icon' => $this->nationality_organization->nationality->icon,
            'facility_name' => ($this->active_order_sector_service($organization_service)->first()?->order->facility->name ?? '('.trans('translation.no-related-sector') .')'),
            'nationality' => $this->nationality_organization->nationality->name,
            'monitors' =>  MonitorResource::collection(($this->active_order_sector_service($organization_service)?->first()?->sector_monitors ?? [] )),
            'manager_id' => $this->manager_id,
            'boss_id' => $this->boss_id,
            'supervisor_id' => $this->supervisor_id,
            'created_at' => $this->created_at,
            'organization_name' => $this->organization_name,
            'cost_all' => $this->cost_all,
            'boss_name' => $this->boss_name,
            'supervisor_name' => $this->supervisor_name,
            'supervisor' => null,
            'boss'=> null,
            'sight_photo'=> $this->attachment->url ?? null,
            'organization'=> new SectorOrganizationResource($this->classification->organization),    
        ];
        if($this->supervisor){
            $data['supervisor'] = new UserResource($this->supervisor);
        }
        if($this->boss){
            $data['boss'] = new UserResource($this->boss);
        }
        return $data;
        // parent::toArray($request);
    }
}
