<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\FoodWeight;

class FoodWeightController extends Controller
{

    public function store(Request $request)
    {
        $check_model = FoodWeight::where(['organization_id' => $request->organization_id, 'food_id' => $request->food_id, 'unit' => $request->unit, 'quantity' => $request->quantity])->get();
        // dd($check_model->isNotEmpty());
        if ($check_model->isNotEmpty()) {
            return back()->with(['message' => trans('translation.already-existed'), 'alert-type' => 'error']);
        }
        $new_model = FoodWeight::create($request->all());
        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
    }
    //??=========================================================================================================
    public function update(Request $request)
    {
        $model = FoodWeight::findOrFail($request->food_weight_id);
        $check_model = FoodWeight::where(['organization_id' => $model->organization_id, 'food_id' => $model->food_id, 'unit' => $request->unit, 'quantity' => $request->quantity])->get();
        if ($check_model->isNotEmpty()) {
            return response()->json(['message' => trans('translation.already-existed'), 'alert-type' => 'error'], 400);
        }
        $model->update(['unit' => $request->unit, 'quantity' => $request->quantity]);
        return response()->json(['message' => trans('translation.Updated successfully'), 'alert-type' => 'success'], 200);
    }
    //??=========================================================================================================
    public function destroy(string $id)
    {
        $delete_model = FoodWeight::findOrFail($id);
        if (($message =  $this->checkRelatives($delete_model)) != '') {
            return response(array('message' => $message, 'alert-type' => 'error'), 400);
        }
        $delete_model->delete();
        return response(array('message' => trans("translation.Deleted successfully"), 'alert-type' => 'success'), 200);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = FoodWeight::with('food:id,name,food_type_id','food.food_type:id,name')->where('organization_id', $request->organization_id)->orderByDesc('created_at');
        return datatables($query->get())
            ->editColumn('unit_name',function($row){
                return $row->unit_name ?? '-';
            })
            ->editColumn('food_type_name',function($row){
                return $row->food->food_type->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <button
                    class="btn btn-outline-secondary btn-sm m-1 on-default m-r-5 edit-button"
                    data-bs-target="#editFoodWeight"
                    data-bs-toggle="modal"
                    data-food-weight-id="' . $row->id . '">
                        <i class="mdi mdi-clipboard-edit-outline"></i>
                </button>

                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deletefood_weights" data-model-id="' . $row->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>';
            })
            ->rawColumns(['color', 'action'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->menus->isNotEmpty() || $delete_model->meals->isNotEmpty()) {
            return trans('translation.related-menus');
        }
        return '';
    }
}
