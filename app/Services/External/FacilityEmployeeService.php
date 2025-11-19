<?php

namespace App\Services\External;

use App\Models\FacilityEmployee;
use App\Repositories\FacilityEmployeeRepository;
use App\Traits\AttachmentTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FacilityEmployeeService
{
    use AttachmentTrait;
    public function __construct(private FacilityEmployeeRepository $repo) {}

    public function getEmployeesForFacility(int $facilityId, int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        return $this->repo->allForFacilityPaginated($facilityId, $perPage, $page);
    }

    public function create(array $data): FacilityEmployee
    {
        return DB::transaction(function () use ($data)
        {
            $employeeData = Arr::except($data, ['attachments']);
            $attachments = $data['attachments'] ?? [];

            $employee = $this->repo->create($employeeData);

            foreach ($attachments as $key => $attachment) {
                $this->store_attachment($attachment, $employee, $key, null,  $data['user_id']);
            }

            return $employee;
        });
    }
}
