<?php

namespace App\Http\Controllers\External;

use App\Docs\Attributes\InternalServerErrorResponse;
use App\Docs\Attributes\NotFoundErrorResponse;
use App\Docs\Attributes\ThrottleRequestErrorResponse;
use App\Docs\Attributes\UnauthorizedErrorResponse;
use App\Docs\Attributes\ValidationErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\External\StoreFacilityEmployeeRequest;
use App\Http\Resources\External\FacilityEmployeeResource;
use App\Models\Facility;
use App\Models\FacilityEmployee;
use App\Services\External\FacilityEmployeeService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromFile;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group("External")]
#[Subgroup("Facility Employee")]
#[UnauthorizedErrorResponse]
#[ThrottleRequestErrorResponse]
#[InternalServerErrorResponse]
class FacilityEmployeeController extends Controller
{
    use ApiResponse;

    public function __construct(protected FacilityEmployeeService $service) {}

    #[BodyParam(name: 'page', type: 'integer', example: 1)]
    #[ValidationErrorResponse([
        'page' => ['page should be integer.', 'page must be at least 1.', 'page must not be greater than 100',],
        'perPage' => ['perPage should be integer.', 'perPage must be at least 1.',]
    ])]
    #[NotFoundErrorResponse]
    public function indexByFacility(Request $request, Facility $facility): JsonResponse
    {
        $validated = $request->validate([
            'perPage' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
        ]);

        $employees = $this->service->getEmployeesForFacility(
            $facility->id,
            $validated['perPage'] ?? 20,
            $validated['page'] ?? 1,
        );

        return $this->successPaginated(
            data: FacilityEmployeeResource::collection($employees),
            paginator: $employees,
            message: "Employees fetched successfully."
        );
    }

    #[ResponseFromFile('responses/facility_employee_show.json')]
    #[NotFoundErrorResponse]
    public function show(Facility $facility, FacilityEmployee $employee): JsonResponse
    {
        if ($employee->facility_id !== $facility->id) {
            abort(404, 'Employee does not belong to this facility.');
        }

        $employee->load(['facility', 'facility_employee_position', 'attachments']);
        return $this->success(new FacilityEmployeeResource($employee), "Employee retrieved successfully.");
    }

    #[Header('Authorization', 'Bearer {YOUR_USER_TOKEN}')]
    #[Response(["flag" => true, "status" => "success", "message" => "Employee created successfully.",])]
    #[ValidationErrorResponse]
    public function store(StoreFacilityEmployeeRequest $request): JsonResponse
    {
        $this->service->create($request->validated());
        return $this->success(message: "Employee created successfully.");
    }
}
