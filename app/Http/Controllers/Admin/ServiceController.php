<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Group;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
  public function index()
  {
    // $groups = Group::all();
    // $services = collect([]);
    $services = Service::with('organizations.organization_services')->get();
    $columnInputs = Service::columnInputs();

    return view('admin.services.index', compact('services', 'columnInputs'));
  }
  //??=========================================================================================================
  public function store(Request $request)
  {
    $service = Service::where('name_ar', $request->name_ar)->orwhere('name_en', $request->name_en)->first();
    // check duplicate in name
    if ($service) {
      return back()->with(array('message' => "Service exist already!", 'alert-type' => 'error'));
    }

    $new_service = Service::create($request->only('name_ar', 'name_en', 'price'));

    return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success'));
  }
  //??=========================================================================================================
  public function update(Request $request, $id)
  {
    $service = Service::findOrFail($id);
    $service->update($request->only('name_ar', 'name_en', 'price'));

    return back()->with(array('message' => trans('translation.updating successfully'), 'alert-type' => 'success'));
  }
  //??=========================================================================================================
  public function destroy(Service $service)
  {
    if ($service->organization_services->isNotEmpty()) {
      return response()->json(['message' => 'Service has connected with organization/s, please delete it/them first!'], 400);
    }
    $service->facility_services()->delete();
    $service->delete();

    return response()->json(['message' => 'Service has deleted!'], 200);
  }
}
