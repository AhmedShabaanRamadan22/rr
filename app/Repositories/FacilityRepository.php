<?php

namespace App\Repositories;

use App\Models\Facility;

class FacilityRepository
{
    /**
     * Base query for users with selected columns
     */
    protected function baseQuery()
    {
        return Facility::select(
            'id',
            'registration_number',
            'license',
            'name',
            'user_id',
            'street_name',
            'district_id',
            'city_id',
            'building_number',
            'postal_code',
            'sub_number',
        )->with([
            'user',
            'city',
            'district'
        ]);
    }

    public function all()
    {
        return $this->baseQuery()->get();
    }

    public function allPaginated(int $perPage = 20, int $page = 1)
    {
        return $this->baseQuery()->paginate($perPage, ['*'], 'page', $page);
    }

    public function allUserFacilitiesPaginated(int $userId, int $perPage = 20, int $page = 1)
    {
        return $this->baseQuery()
            ->where('user_id', $userId)
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data)
    {
        return Facility::create($data);
    }
}
