<?php

namespace App\Http\Controllers\External;

use App\Docs\Attributes\ForbiddenErrorResponse;
use App\Docs\Attributes\InternalServerErrorResponse;
use App\Docs\Attributes\NotFoundErrorResponse;
use App\Docs\Attributes\ThrottleRequestErrorResponse;
use App\Docs\Attributes\UnauthorizedErrorResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\External\AttachmentLabelResource;
use App\Http\Resources\External\BankResource;
use App\Http\Resources\External\CityResource;
use App\Http\Resources\External\CountryResource;
use App\Http\Resources\External\DistrictResource;
use App\Http\Resources\External\FacilityEmployeePositionResource;
use App\Models\City;
use App\Services\External\GeneralDataService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;

#[Group('External')]
#[Subgroup('General')]
#[ForbiddenErrorResponse]
#[UnauthorizedErrorResponse]
#[ThrottleRequestErrorResponse]
#[InternalServerErrorResponse]
class GeneralDataController extends Controller
{
    use ApiResponse;
    public function __construct(protected GeneralDataService $service){}

    public function cities(Request $request): JsonResponse
    {
        $cities = $this->service->getCities();
        return $this->success(CityResource::collection($cities), "Cities retrieved successfully.");
    }

    public function countries(Request $request): JsonResponse
    {
        $cities = $this->service->getCountries();
        return $this->success(CountryResource::collection($cities), "Countries retrieved successfully.");
    }

    #[Group('Wafir')]
    #[Subgroup('General')]
    #[NotFoundErrorResponse]
    public function districts(Request $request, City $city): JsonResponse
    {
        $districts = $this->service->getDistricts($city);
        return $this->success(DistrictResource::collection($districts), "Districts retrieved successfully.");
    }

    public function banks(Request $request): JsonResponse
    {
        $banks = $this->service->getBanks();
        return $this->success(BankResource::collection($banks), "Banks retrieved successfully.");
    }

    public function facilityEmployeePositions(Request $request): JsonResponse
    {
        $banks = $this->service->getFacilityEmployeePositions();
        return $this->success(
            FacilityEmployeePositionResource::collection($banks),
            "Positions retrieved successfully."
        );
    }

    public function attachmentLabels(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['string', 'required', 'in:facilities,users,facility_employees'],
        ]);

        $attachmentLabels = $this->service->getAttachmentLabels($request->input("type"));
        return $this->success(
            AttachmentLabelResource::collection($attachmentLabels),
            "Attachment labels retrieved successfully."
        );
    }
}
