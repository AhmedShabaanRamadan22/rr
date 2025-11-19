<?php

namespace App\Repositories;

use App\Models\FacilityEmployee;

class FacilityEmployeeRepository
{
    public function allForFacilityPaginated(int $facilityId, int $perPage = 20, int $page = 1)
    {
        return FacilityEmployee::with([
            'facility',
            'facility_employee_position',
        ])
            ->facility($facilityId)
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data)
    {
        return FacilityEmployee::create($data);
    }
}
