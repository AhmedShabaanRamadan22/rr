<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Organization;
use App\Models\OrganizationCategory;
use App\Models\OrganizationService;
use App\Models\Service;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = Form::with([
            'organization_service.organization',
            'organization_service.service',
            'organization_category.category'
        ])->orderByDesc('created_at')->get();
        $organization_services = OrganizationService::with(['service', 'organization',])->orderBy('organization_id')->get();
        $organizations = Organization::all();
        $organization_categories = OrganizationCategory::with(['organization', 'category'])->orderBy('organization_id')->get();
        // dd($organization_services);
        // $services = Service::all();
        $columnOptions = Form::columnOptions();
        return view('admin.forms.index', compact('organization_services', 'forms', 'organizations', 'organization_categories', 'columnOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $form = Form::where([
            'name' => $request->name,
            'organization_service_id' => $request->organization_service_id,
            'organization_category_id' => $request->organization_category_id
        ])->first();
        if($form){
            return back()->with(array('message'=> trans('translation.Form name already exist!'), 'alert-type' => 'error'));
        }

        $new_Form = Form::create([
            'name' => $request->name,
            'code' => $request->code,
            'display' => $request->display,
            'description' => $request->description,
            'organization_service_id' => $request->organization_service_id,
            'organization_category_id' => $request->organization_category_id,
            'is_visible' => $request->is_visible,
            'submissions_times'=> $request->submissions_times, 
            'submissions_by'=> $request->submissions_by,
        ]);
        return back()->with(array('message'=> trans('translation.Added successfully'), 'alert-type' => 'success'));
    }
    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form)
    {
        $form = Form::findOrFail($request->form_id);
        // dd($request->all(),$form);
        $form->update([
            "name" => $request->form_name,
            'code' => $request->code,
            'description' => $request->description,
            "is_visible" => $request->form_visible,
            "submissions_times" => $request->submissions_times_edit,
            "submissions_by" => $request->submissions_by_edit,
            "display" => $request->display_edit,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        if ($form->sections->isNotEmpty()) {
            return response(['message' => trans('translation.Form has sections, please delete them first!')], 400);
        }

        $form->delete();

        return response(['message' => 'Form has deleted!'], 200);
    }


  public function dataTable(Request $request)
  {
      $query = Form::query()->orderByDesc('created_at');
      if (\request('organization_id')) {
        $query->where('organization_id', \request('organization_id'));
      }
      return datatables($query->get())
          ->addColumn('action', function ($row) {
              return '<div class="d-flex justify-content-center">
              <a href="'.route((str_replace('_','-','forms')).'.edit',$row->id).'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
              <i class="mdi mdi-square-edit-outline"></i>
          </a>

              <button
              class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deleteforms" data-model-id="' . $row->id . '">
                  <i class="mdi mdi-delete"></i>
              </button>
          </div>';
          })
          ->rawColumns(['color','action'])
          ->toJson();
  }
}