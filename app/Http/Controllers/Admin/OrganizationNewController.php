<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationNew;
use Illuminate\Http\Request;

class OrganizationNewController extends Controller
{

    public function store(Request $request)
    {
        $request->merge(["new" => $request->the_new]);
        OrganizationNew::create($request->only('organization_id', 'new'));
        return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success'));
    }
    //??=========================================================================================================
    public function destroy(OrganizationNew $organization_news)
    {
        // return $id;
        $organization_news->delete();

        // OrganizationNew::;
        return response((['message' => trans('translation.delete-successfully')]), 200);
    }
}
