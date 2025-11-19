<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Base query for users with selected columns
     */
    protected function baseQuery()
    {
        return User::select(
            'id',
            'name',
            'email',
            'phone',
            'phone_code',
            'nationality',
            'national_id',
            'national_id_expired',
            'birthday',
            'national_source',
            'address'
        )->with([
            'country',
            'national_source_city',
            'profile_photo_attachment',
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

    public function allMonitorsPaginated(int $perPage = 20, int $page = 1)
    {
        return $this->baseQuery()
            ->with('monitor')
            ->wherehas('roles', fn($q) => $q->where('name', 'monitor'))
            ->paginate($perPage, ['*'], 'page', $page);
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function findByPhone($phoneCode, $phone)
    {
        return User::where([
            'phone_code' => $phoneCode,
            'phone' => $phone,
        ])->first();
    }

    public function create(array $data)
    {
        return User::create($data);
    }
}
