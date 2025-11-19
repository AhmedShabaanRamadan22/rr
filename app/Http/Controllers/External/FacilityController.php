<?php

namespace App\Http\Controllers\External;

use App\Docs\Attributes\ForbiddenErrorResponse;
use App\Docs\Attributes\InternalServerErrorResponse;
use App\Docs\Attributes\NotFoundErrorResponse;
use App\Docs\Attributes\ThrottleRequestErrorResponse;
use App\Docs\Attributes\UnauthorizedErrorResponse;
use App\Docs\Attributes\ValidationErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\External\StoreFacilityRequest;
use App\Http\Resources\External\FacilityResource;
use App\Models\Facility;
use App\Services\External\FacilityService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("External")]
#[Subgroup("Facility")]
#[ForbiddenErrorResponse]
#[UnauthorizedErrorResponse]
#[ThrottleRequestErrorResponse]
#[InternalServerErrorResponse]
class FacilityController extends Controller
{
    use ApiResponse;

    public function __construct(protected FacilityService $service) {}

    #[BodyParam(name: 'page', type: 'integer', example: 1)]
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

        $facilities = $this->service->getFacilitiesPaginated(
            $validated['perPage'] ?? 20,
            $validated['page'] ?? 1,
        );

        return $this->successPaginated(
            data: FacilityResource::collection($facilities),
            paginator: $facilities,
            message: "Facilities fetched successfully."
        );
    }

    #[Header('Authorization', 'Bearer {YOUR_USER_TOKEN}')]
    #[BodyParam(name: 'page', type: 'integer', example: 1)]
    #[ValidationErrorResponse([
        'page' => ['page should be integer.', 'page must be at least 1.', 'page must not be greater than 100',],
        'perPage' => ['perPage should be integer.', 'perPage must be at least 1.',]
    ])]
    public function indexMyFacilities(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'perPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ]);

        $facilities = $this->service->getFacilitiesByUser(
            auth()->id(),
            $validated['perPage'] ?? 20,
            $validated['page'] ?? 1,
        );

        return $this->successPaginated(
            data: FacilityResource::collection($facilities),
            paginator: $facilities,
            message: "Facilities fetched successfully."
        );
    }

    #[ResponseFromFile('responses/facility_show.json')]
    #[NotFoundErrorResponse]
    public function show(Facility $facility): JsonResponse
    {
        $facility->load(['attachments', 'city', 'district']);
        return $this->success(new FacilityResource($facility), "Facility fetched successfully.");
    }

    #[Header('Authorization', 'Bearer {YOUR_USER_TOKEN}')]
    #[ResponseFromApiResource(FacilityResource::class,
        model: Facility::class,
        additional: ["message" => "Facility created successfully", 'status' => 'success'])]
    #[ValidationErrorResponse]
    public function store(StoreFacilityRequest $request): JsonResponse
    {
        $facility = $this->service->create($request->validated());
        return $this->success(new FacilityResource($facility), "Facility created successfully.");
    }
}
