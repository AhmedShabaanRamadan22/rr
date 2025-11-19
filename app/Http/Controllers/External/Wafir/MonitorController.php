<?php

namespace App\Http\Controllers\External\Wafir;

use App\Docs\Attributes\InternalServerErrorResponse;
use App\Docs\Attributes\ThrottleRequestErrorResponse;
use App\Docs\Attributes\UnauthorizedErrorResponse;
use App\Docs\Attributes\ValidationErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\External\Wafir\ShowMonitorRequest;
use App\Http\Resources\External\Wafir\MonitorResource;
use App\Models\User;
use App\Services\External\UserService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group("Wafir")]
#[Subgroup("Monitor")]
#[UnauthorizedErrorResponse]
#[ThrottleRequestErrorResponse]
#[InternalServerErrorResponse]
class MonitorController extends Controller
{
    use ApiResponse;

    public function __construct(protected UserService $userService) {}

    #[BodyParam(name: 'page', type: 'integer', description: "Number of page for pagination", example: 1)]
    #[BodyParam(name: 'perPage', type: 'integer', description: "Number of records per page for pagination", example: 20)]
    #[ValidationErrorResponse([
        'page' => ['page should be integer.', 'page must be at least 1.', 'page must not be greater than 100',],
        'perPage' => ['perPage should be integer.', 'perPage must be at least 1.',]
    ])]
    public function index (Request $request): JsonResponse
    {
        $validated = $request->validate([
            'perPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ]);

        $monitors = $this->userService->getMonitorsPaginated(
            $validated['perPage'] ?? 20,
            $validated['page'] ?? 1,
        );

        return $this->successPaginated(
            data: MonitorResource::collection($monitors),
            paginator: $monitors,
            message: "Monitors fetched successfully."
        );
     }

    #[UrlParam(name: 'monitor', type: 'integer', description: "User's ID", example: 1)]
    #[ResponseFromApiResource(MonitorResource::class,
        model: User::class,
        additional: ["message" => "Monitor fetched successfully", 'status' => 'success'],
    )]
    #[ValidationErrorResponse(['id' => ['The selected user is not a monitor.']])]
     public function show(ShowMonitorRequest $request, User $monitor)
    {
        $monitor->load(['country', 'roles', 'monitor']);

        return $this->success(new MonitorResource($monitor), "Monitor fetched successfully.");
    }
}
