<?php

namespace App\Http\Controllers;

use App\Http\Resources\WebResources\CountryOrganizationResource;
use Illuminate\Http\Request;
use App\Traits\OrganizationTrait;
use App\Models\CountryOrganization;

class CountryOrganizationController extends Controller
{
    use OrganizationTrait;
    public function index()
    {
        $this->validateOrganization();
        $countryOrganization = CountryOrganization::where('organization_id', request()->organization_id)->select('id', 'country_id')->get();
        return response()->json(['country_organization' => CountryOrganizationResource::collection($countryOrganization)], 200);
    }
}
