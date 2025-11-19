<?php

namespace App\Services\External;

use App\Models\Facility;
use App\Repositories\FacilityRepository;
use App\Traits\AttachmentTrait;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FacilityService
{
    use AttachmentTrait;
    public function __construct(private FacilityRepository $repo) {}

    /**
     * Get a paginated list of all facilities.
     */
    public function getFacilitiesPaginated(int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        return $this->repo->allPaginated($perPage, $page);
    }

    /**
     * Get a paginated list of facilities for a specific user.
     */
    public function getFacilitiesByUser(int $userId, int $perPage = 20, int $page = 1): LengthAwarePaginator
    {
        return $this->repo->allUserFacilitiesPaginated($userId, $perPage, $page);
    }

    public function create(array $data): Facility
    {
        return DB::transaction(function () use ($data)
        {
            $facilityData = Arr::except($data, ['attachments']);
            $attachments = $data['attachments'] ?? [];

            $facility = $this->repo->create($facilityData);

            foreach ($attachments as $key => $attachment) {
                $this->store_attachment($attachment, $facility, $key, null,  $data['user_id']);
            }

            if (!empty($data['iban'])) {
                $facility->iban()->create([
                    'account_name' => $data['account_name'],
                    'bank_id' => $data['bank_id'],
                    'iban' => $data['iban'],
                ]);
            }

            $facility->load('user');

            return $facility;
        });
    }
}
