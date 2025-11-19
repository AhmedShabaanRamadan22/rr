<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\WebResources\OrganizationResource;
use App\Models\Organization;
use App\Models\Service;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    $organizations = Organization::with('organization_news')->get();
    $services = Service::all();

    if(request()->has('organizationDomain')){
      $organizations = Organization::with('organization_news')->where('domain',request()->organizationDomain)->first();
      if($organizations){
        return response()->json(['organizations' => new OrganizationResource($organizations)], 201);
      }
      return response(['message' => "Dom not found"],404);
    }

    return response()->json(['organizations' => OrganizationResource::collection($organizations)], 201);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
  }
}
