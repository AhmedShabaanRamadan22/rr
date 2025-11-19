<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Regex;
use App\Models\Question;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use App\Traits\OrganizationTrait;
use App\Models\OrganizationService;
use App\Http\Controllers\Controller;
use App\Http\Resources\WebResources\OrganizationServiceResource;

class OrganizationServiceController extends Controller
{

    use OrganizationTrait;

    public function index()
    {
        $this->validateOrganization();
        $organization = $this->getOrganization();
        $organization_services = $organization->organization_services;
        return response()->json(['organization_services' => OrganizationServiceResource::collection($organization_services)], 200);
    }

    public function show(OrganizationService $organization_service)
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function destroy(OrganizationService $organization_service)
    {
        //
    }
}
