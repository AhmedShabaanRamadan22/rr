<?php

namespace App\Http\Controllers\External\Wafir;

use App\Docs\Attributes\InternalServerErrorResponse;
use App\Docs\Attributes\NotFoundErrorResponse;
use App\Docs\Attributes\ThrottleRequestErrorResponse;
use App\Docs\Attributes\UnauthorizedErrorResponse;
use App\Docs\Attributes\ValidationErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\External\FacilityResource;
use App\Models\Facility;
use App\Models\User;
use App\Services\External\FacilityService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group("Wafir")]
#[Subgroup("Facility")]
#[UnauthorizedErrorResponse]
#[ThrottleRequestErrorResponse]
#[InternalServerErrorResponse]
class FacilityController extends Controller
{
    use ApiResponse;

    public function __construct(protected FacilityService $facilityService) {}


    #[BodyParam(name: 'page', type: 'integer', description: "Number of page for pagination", example: 1)]
    #[BodyParam(name: 'perPage', type: 'integer', description: "Number of records per page for pagination", example: 20)]
    #[ValidationErrorResponse([
        'page' => ['page should be integer.', 'page must be at least 1.', 'page must not be greater than 100',],
        'perPage' => ['perPage should be integer.', 'perPage must be at least 1.',]
    ])]
    public function index(Request $request)
    {
        $validated = $request->validate([
            'perPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ]);

        $facilities = $this->facilityService->getFacilitiesPaginated(
            $validated['perPage'] ?? 20,
            $validated['page'] ?? 1,
        );

        return $this->successPaginated(
            data: FacilityResource::collection($facilities),
            paginator: $facilities,
            message: "Facilities fetched successfully."
        );
    }

    #[BodyParam(name: 'page', type: 'integer', description: "Number of page for pagination", example: 1)]
    #[BodyParam(name: 'perPage', type: 'integer', description: "Number of records per page for pagination", example: 20)]
    #[NotFoundErrorResponse]
    #[ValidationErrorResponse([
        'page' => ['page should be integer.', 'page must be at least 1.', 'page must not be greater than 100',],
        'perPage' => ['perPage should be integer.', 'perPage must be at least 1.',]
    ])]
    public function indexByUser(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'perPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ]);

        $facilities = $this->facilityService->getFacilitiesByUser(
            $user->id,
            $validated['perPage'] ?? 20,
            $validated['page'] ?? 1,
        );

        return $this->successPaginated(
            data: FacilityResource::collection($facilities),
            paginator: $facilities,
            message: "Facilities fetched successfully."
        );
    }

    #[UrlParam(name: 'user', type: 'integer', description: "User's ID", example: 1)]
    #[UrlParam(name: 'facility', type: 'integer', description: "Facility's ID", example: 1)]
    #[ResponseFromFile('responses/facility_show.json')]
    #[NotFoundErrorResponse]
    public function show(User $user, Facility $facility): JsonResponse
    {
        $facility->load(['attachments', 'city', 'district']);
        return $this->success(new FacilityResource($facility), "Facility fetched successfully.");
    }
}
