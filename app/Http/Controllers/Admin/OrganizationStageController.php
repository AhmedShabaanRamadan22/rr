<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\OrganizationStage;
use App\Models\StageBank;

class OrganizationStageController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function store(Request $request)
    {
        $count = $this->model::where('organization_id', $request->organization_id)->count();
        $duration = StageBank::find($request->stage_bank_id)->duration;

        $this->model::create([
            'organization_id' => $request->organization_id,
            'stage_bank_id' => $request->stage_bank_id,
            'duration' => $request->duration ?? $duration,
            'arrangement' => $count + 1
        ]);

        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
    }
    //??=========================================================================================================
    public function show($organization_stage_id)
    {
        $organization_stage = OrganizationStage::find($organization_stage_id);
        $questions = $organization_stage->questions;
        return view('admin.organization_stages.show', compact('organization_stage','questions'));
    }
    //??=========================================================================================================
    public function update(Request $request, $id)
    {
        $model_item = $this->model::find($id);
        $new_model = $model_item->update($request->only($this->model->getFillable()));
        return back()->with('message', trans('translation.Updated successfully'));
        // return redirect()->route('route_name', ['key' => $value])->with('message', trans('translation.Updated successfully'));
    }
    //??=========================================================================================================
    public function destroy(OrganizationStage $organizationStage)
    {

        // TODO To be completed
        if (method_exists($this, 'checkRelatives')) {
            if (($message =  $this->checkRelatives($organizationStage)) != '') {
                return response(array('message' => $message, 'alert-type' => 'error'), 400);
            }
        }
        $org_id = $organizationStage->organization_id;
        $organizationStage->delete();

        $organizationStages = $this->model::where('organization_id', $org_id)->orderBy('arrangement')->get();

        foreach ($organizationStages as $key => $stage) {
            $stage->update([
                'arrangement' => $key + 1
            ]);
        }

        return response()->json(['message' => trans('translation.Organization Stage has deleted!')], 200);
    }
    //??=========================================================================================================
    public function sort(Request $request)
    {
        $items = explode(',', $request->items);
        foreach ($items as $key => $item) {
            $stage = $this->model::find($item);
            $stage->update([
                'arrangement' => $key + 1
            ]);
        }

        return back()->with(['message'=> trans('translation.rearrangment successfully'),'alert-type'=>'success']);
    }

    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with('stage_bank')->where('organization_id', $request->input('organization_id'));
        return datatables($query->orderBy('arrangement')->get())

            ->editColumn('stage-bank-name', function ($row) {
                return $row->stage_bank->name ?? '-';
            })
            ->addColumn('questions', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route('organization-stages.show', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-eye"></i>
                </a>
            </div>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>

                <button
                    class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete-' . $this->table_name . '" data-model-id="' . $row->id . '">
                        <i class="mdi mdi-delete"></i>
                </button>
            </div>';
            })
            ->rawColumns(['color', 'action', 'questions'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->meal_organization_stages->isNotEmpty()) {
            return trans('translation.delete-Meals-first');
        }
        return '';
    }
}
