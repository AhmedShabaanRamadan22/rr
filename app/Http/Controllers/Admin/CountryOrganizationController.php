<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CountryOrganization;
use App\Models\Regex;
use Illuminate\Http\Request;

class CountryOrganizationController extends Controller
{
  public function store(Request $request)
  {
    foreach ($request->country_id as $country_id) {
      $country_organization = CountryOrganization::where(['organization_id' => $request->organization_id, 'country_id' => $country_id])->first();
      if (!$country_organization) {
        // dd(['organization_id' => $request->organization_id, 'country_id' => $request->country_id]);
        CountryOrganization::create([
          'organization_id' => $request->organization_id,
          'country_id' => $country_id
        ]);
      }
    }

    return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
  }
  //??================================================================
  public function destroy(CountryOrganization $organization_country)
  {
    if ($organization_country->has_orders) {
      return response(['message' => trans('translation.delete-order-related-first')], 400);
    }
    $organization_country->delete();

    return response(['message' => trans('translation.delete-successfully')]);
  }
}
