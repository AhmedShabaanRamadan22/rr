<?php

namespace App\Http\Controllers\External;

use App\Docs\Attributes\InternalServerErrorResponse;
use App\Docs\Attributes\NotFoundErrorResponse;
use App\Docs\Attributes\ThrottleRequestErrorResponse;
use App\Docs\Attributes\UnauthorizedErrorResponse;
use App\Docs\Attributes\ValidationErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\External\UserResource;
use App\Models\User;
use App\Services\External\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("External")]
#[Subgroup("Users")]
#[UnauthorizedErrorResponse]
#[ThrottleRequestErrorResponse]
#[InternalServerErrorResponse]
class UserController extends Controller
{
    use ApiResponse;

    public function __construct(protected UserService $service) {}

    #[ValidationErrorResponse([
        'page' => ['page should be integer.', 'page must be at least 1.', 'page must not be greater than 100',],
        'perPage' => ['perPage should be integer.', 'perPage must be at least 1.',]
    ])]
    #[BodyParam(name: 'page', type: 'integer', example: 1)]
    public function index(Request $request)
    {
        $validated = $request->validate([
            'perPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ]);

        $users = $this->service->getUsersPaginated(
            $validated['perPage'] ?? 20,
            $validated['page'] ?? 1,
        );

        return $this->successPaginated(
            data: UserResource::collection($users),
            paginator: $users,
            message: "Users fetched successfully."
        );
    }

    #[ResponseFromFile('responses/user_show.json')]
    #[NotFoundErrorResponse]
    public function show(User $user)
    {
        $user->load(['profile_photo_attachment', 'attachments', 'national_source_city']);
        return $this->success(new UserResource($user), "User fetched successfully.");
    }
}
