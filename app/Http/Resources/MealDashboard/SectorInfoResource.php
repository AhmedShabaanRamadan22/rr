<?php

namespace App\Http\Resources\MealDashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectorInfoResource extends JsonResource
{
    protected $meal;
    public function __construct($resource, $meal)
    {
        parent::__construct($resource);
        $this->meal = $meal;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request = null): array
    {
        $data = [
            ['label' => trans('translation.label'),'content' => $this->sector_name .' - '. $this->name ],
            ['label' => trans('translation.all-monitors'),'content' => implode(', ',$this->sector->monitors_name_array)],
            ['label' => trans('translation.boss-id'),'content' => $this->sector->boss_name],
            ['label' => trans('translation.supervisor-id'),'content' => $this->sector->supervisor_name],
            ['label' => trans('translation.menu'),'content' => $this->meal->food_weights->implode('food_name',' | ')],
            ['label' => trans('translation.nationality'),'content' => $this->sector->nationality_organization->nationality->name],
            ['label' => trans('translation.classification'),'content' => $this->sector->classification->code],
            ['label' => trans('translation.guest-quantity'),'content' => $this->sector->guest_quantity],
            ['label' => trans('translation.sight'),'content' => $this->sector->sight],
            ['label' => trans('translation.notes'),'content' => $this->sector->note],
        ];
        return $data;
    }
}
