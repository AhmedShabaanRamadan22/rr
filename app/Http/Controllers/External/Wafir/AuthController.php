<?php

namespace App\Http\Controllers\External\Wafir;

use App\Docs\Attributes\InternalServerErrorResponse;
use App\Docs\Attributes\NotFoundErrorResponse;
use App\Docs\Attributes\ThrottleRequestErrorResponse;
use App\Docs\Attributes\UnauthorizedErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\External\Wafir\CheckUserRequest;
use App\Http\Resources\External\Wafir\UserResource;
use App\Models\User;
use App\Services\External\UserService;
use App\Traits\ApiResponse;
use App\Traits\OtpTrait;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("Wafir")]
#[Subgroup("Auth")]
#[UnauthorizedErrorResponse]
#[ThrottleRequestErrorResponse]
#[InternalServerErrorResponse]
class AuthController extends Controller
{

    use OtpTrait, ApiResponse;

    public function __construct(protected UserService $userService,) {}

    #[BodyParam("phone_code", description: "Country phone code",example: "+966")]
    #[BodyParam("phone", type: "numeric", description: "User's phone number, without country code", example: "512345678")]
    #[ResponseFromApiResource(UserResource::class,
        model: User::class,
        status: 200,
        additional: ["message" => "User retrieved successfully"],
    )]
    #[NotFoundErrorResponse]
    public function checkUser(CheckUserRequest $request): JsonResponse
    {
        $user = $this->userService->findByPhone($request->phone_code, $request->phone);

        if($user) {
            return $this->success(new UserResource($user), "User retrieved successfully");
        }

        return $this->error("User not found", 404);
    }
}
