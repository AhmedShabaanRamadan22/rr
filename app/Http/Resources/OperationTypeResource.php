<?php

namespace App\Http\Resources;

use App\Models\Period;
use App\Models\OrderSector;
use Illuminate\Http\Request;
use App\Models\FineOrganization;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $order_sector = OrderSector::find(request()->order_sector_id);
        $organization_id = $order_sector->order->organization_service->organization->id;
        $data = [
            'id' => $this->id,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'model' => $this->model,
            'name' => $this->name,
            'guest_quantity' => $order_sector->sector->guest_quantity,
            'has_quantities' => $this->has_quantities,
            'periods' => [],
            'reason_dangers' => [],
            'organization_fines' => [],
            'attachment_labels' => $this->attachments_labels(),
        ];
        if ($this->reason_dangers->isNotEmpty()) {
            $data['reason_dangers'] = ReasonDangerResource::collection($this->reason_dangers);
        }
        if ($this->periods->isNotEmpty()) {
            $data['periods'] = $this->periods;
        }
        if ($this->model == 'fines') {
            $organization_fines = FineOrganization::where('organization_id', $organization_id)->with('fine_bank:id,name')->get();
            $data['organization_fines'] = FineOrganizationResource::collection($organization_fines);
        }
        return $data;
    }
}
