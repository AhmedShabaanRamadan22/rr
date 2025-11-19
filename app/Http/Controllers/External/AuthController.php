<?php

namespace App\Http\Controllers\External;

use App\Docs\Attributes\InternalServerErrorResponse;
use App\Docs\Attributes\NotFoundErrorResponse;
use App\Docs\Attributes\ThrottleRequestErrorResponse;
use App\Docs\Attributes\UnauthorizedErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\External\LoginRequest;
use App\Http\Requests\External\StoreUserRequest;
use App\Http\Requests\OtpRequest;
use App\Http\Resources\External\UserResource;
use App\Models\User;
use App\Services\External\AuthService;
use App\Services\External\UserService;
use App\Traits\ApiResponse;
use App\Traits\OtpTrait;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('External')]
#[Subgroup("Auth")]
#[UnauthorizedErrorResponse]
#[ThrottleRequestErrorResponse]
#[InternalServerErrorResponse]
class AuthController extends Controller
{
    use ApiResponse, OtpTrait;

    public function __construct(
        protected UserService $userService,
        protected AuthService $otpService,
    ) {}

    #[Response(content: ["flag" => true, "status" => "success", "message" => "User created successfully"])]
    public function register(StoreUserRequest $request): JsonResponse
    {
        $this->userService->create($request->validated());

        return $this->success(message: "User created successfully");
    }

    #[Response(
        content: [
            "message" => "Otp has sent to: +966512345678",
            "verification" => "stp tmp",
            "sending_result" => "Whatsapp response",
            "sending_sms" => "SMS response",
        ]
    )]
    #[NotFoundErrorResponse]
    public function createOtp(OtpRequest $request)
    {
        return $this->sendOtp($request);
    }

    #[ResponseFromApiResource(UserResource::class,
        model: User::class,
        additional: ["message" => "Correct OTP"],
    )]
    #[Response(content: '{"message": "Phone number not has any active OTP"}', status: 401,)]
    #[Response(content: '{"message": "Incorrect OTP"}', status: 401,)]
    #[Response(content: '{"message": "Expired OTP"}', status: 401,)]
    #[Response(content: '{"message": "User not found"}', status: 401,)]
    public function login(LoginRequest $request)
    {
        $result = $this->otpService->validateOtp($request->phone, $request->phone_code, $request->otp);

        if ($result !== true) {
            $this->error($result, 401, trans('translation.Please contact customer service'));
        }

        $user = $this->userService->findByPhone($request->phone_code, $request->phone);
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(
            data: new UserResource($user),
            message: "User logged in successfully",
            additional: ['token' => $token]);
    }

    #[Header('Authorization', 'Bearer {YOUR_USER_TOKEN}')]
    #[Response(content: ["flag" => true, "status" => "success", "message" => "Logged out successfully"])]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(message: "Logged out successfully");
    }
}

