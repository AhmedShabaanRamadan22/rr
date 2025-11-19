<?php

namespace App\Services\External;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\AttachmentTrait;
use App\Traits\OtpTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserService
{
    use AttachmentTrait, OtpTrait;
    public function __construct(private UserRepository $repo) {}

    public function getUsersPaginated(int $perPage = 20, int $page = 1)
    {
        return $this->repo->allPaginated($perPage, $page);
    }

    public function getMonitorsPaginated(int $perPage = 20, int $page = 1)
    {
        return $this->repo->allMonitorsPaginated($perPage, $page);
    }

    public function create(array $data): User
    {
        return DB::transaction(function () use ($data)
        {
            $userData = Arr::except($data, ['attachments']);
            $attachments = $data['attachments'] ?? [];

            $user = $this->repo->create($userData);

            foreach ($attachments as $key => $attachment) {
                $this->store_attachment($attachment, $user, $key, null,  $user->id);
            }

            return $user;
        });
    }

    public function findByPhone($phoneCode, $phone)
    {
        return $this->repo->findByPhone($phoneCode, $phone);
    }
}
