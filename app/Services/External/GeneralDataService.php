<?php

namespace App\Services\External;

use App\Models\City;
use App\Repositories\GeneralDataRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GeneralDataService
{
    public function __construct(private GeneralDataRepository $repo) {}

    public function getCities(): Collection
    {
        return Cache::remember('general:cities', 3600, fn() => $this->repo->getCities());
    }

    public function getCountries(): Collection
    {
        return Cache::remember('general:countries', 3600, fn() => $this->repo->getCountries());
    }

    public function getDistricts(City $city): Collection
    {
        return Cache::remember("general:districts-{$city}", 3600, fn() => $city->districts()->get());
    }

    public function getBanks(): Collection
    {
        return Cache::remember('general:banks', 3600, fn() => $this->repo->getBanks());
    }

    public function getFacilityEmployeePositions(): Collection
    {
        return Cache::remember(
            'general:facility-employee-positions',
            3600,
            fn() => $this->repo->getFacilityEmployeePositions()
        );
    }

    public function getAttachmentLabels($type): Collection
    {
        return Cache::remember(
            "general:attachment-labels-{$type}",
            3600,
            fn() => $this->repo->getAttachmentLabels($type)
        );
    }
}
