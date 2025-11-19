<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\NationalityOrganization;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NationalityOrganizationController extends Controller
{

    public function store(Request $request)
    {
        $organization = Organization::findOrFail($request->organization_id);
        $organization->nationalities()->syncWithoutDetaching($request->nationality_id);
        $nationality_organization = NationalityOrganization::where(['organization_id' => $organization->id, 'nationality_id' => $request->nationality_id])->first();
        if ($request->food_weight_id) {
            foreach ($request->food_weight_id as $food_weight_id) {
                $menu = Menu::create([
                    "nationality_organization_id" => $nationality_organization->id,
                    "food_weight_id" => $food_weight_id,
                ]);
            }
        }

        return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success'));
    }
    //??=========================================================================================================
    public function update(Request $request, NationalityOrganization $nationalityOrganization)
    {
        //
        $nationalityOrganization->food_weights()->sync($request->menu);
        return response()->json(['message' => trans('translation.updated-successfully'), 'alert-type' => 'error'], 200);
    }
    //??=========================================================================================================
    public function destroy(NationalityOrganization $nationalityOrganization)
    {
        // check if has sector linked
        // dd($nationalityOrganization);
        if ($nationalityOrganization->sectors->isNotEmpty()) {
            return response()->json(['message' => trans('translation.delete-sector-first'), 'alert-type' => 'error'], 400);
        }
        $nationalityOrganization->menu()->delete();
        $nationalityOrganization->delete();

        return response()->json(['message' => trans('translation.delete-successfully'), 'alert-type' => 'error'], 200);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = NationalityOrganization::with('nationality:id,name,flag', 'food_weights:id,food_id,unit,quantity', 'food_weights.food:id,name,food_type_id','food_weights.food.food_type:id,name');
        // dd($query);

        if (\request('organization_id')) {
            $query->where('organization_id', $request->organization_id);
        }
        return datatables($query->orderByDesc('created_at')->get())
            ->editColumn('nationality-name', function (NationalityOrganization $nationality_organization) {
                return $nationality_organization->nationality->name ?? trans('translation.no-data');
            })
            ->editColumn('flag', function (NationalityOrganization $nationality_organization) {
                return $nationality_organization->nationality->flag_icon ?? trans('translation.no-data');
            })
            ->editColumn('menu', function (NationalityOrganization $nationality_organization) {
                if ($nationality_organization->food_weights->count() < 1) {
                    return trans('translation.no-data');
                }
                $html = '';
                $i = 1;
                foreach ($nationality_organization->food_weights as $food_weight) {
                    $html .= '<span class="badge bg-primary mx-1 mb-2 menu-' . $nationality_organization->id . '" data-food-weight-id=' . $food_weight->id . '>' . $food_weight->food_name . '</span>';
                    if ($i < $nationality_organization->food_weights->count()) {
                        $html .= ' | ';
                    }
                    if ($i++ % 3 == 0) {
                        $html .= '<br>';
                    }
                }
                return $html;
                // return '<span class="badge bg-primary my-1">' . $nationality_organization->food->implode('name', '</span> <span class="badge bg-primary my-1"> ') ?? trans('translation.no-data');
            })
            ->editColumn('action', function (NationalityOrganization $nationality_organization) {
                return '
                <button
                    class="btn btn-outline-secondary btn-sm m-1 on-default m-r-5 edit-button"
                    data-bs-target="#editNationalityOrganization"
                    data-bs-toggle="modal"
                    data-nationality-organization-id="' . $nationality_organization->id . '">
                        <i class="mdi mdi-clipboard-edit-outline"></i>
                </button>
                <button type="button" class=" btn btn-outline-danger btn-sm delete_nationality" value="' . $nationality_organization->id . '" data-nationality-id="' . $nationality_organization->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>';
            })
            ->rawColumns(['flag', 'menu', 'action'])
            ->toJson();

        // return datatables(Facility::all())->toJson();
    }
}