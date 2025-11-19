<?php

namespace App\Repositories;

use App\Models\AttachmentLabel;
use App\Models\Bank;
use App\Models\City;
use App\Models\Country;
use App\Models\FacilityEmployeePosition;
use Illuminate\Support\Collection;

class GeneralDataRepository
{
    public function getCities(): Collection
    {
        return City::select('id', 'name_ar', 'name_en')
            ->get();
    }

    public function getCountries(): Collection
    {
        return Country::with('countinents')
            ->select('id', 'name_ar', 'name_en')
            ->get();
    }

    public function getBanks(): Collection
    {
        return Bank::select('id', 'name_ar', 'name_en')->get();
    }

    public function getFacilityEmployeePositions(): Collection
    {
        return FacilityEmployeePosition::select('id', 'name_ar', 'name_en')->get();
    }

    public function getAttachmentLabels($type): Collection
    {
        return AttachmentLabel::select('id', 'label', 'placeholder_ar', 'placeholder_en', 'extensions', 'is_required')
            ->type($type)
            ->get();
    }
}
