<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    use CrudOperationTrait;


    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::select(['id','name', 'food_type_id'])->with('food_type:id,name')->orderByDesc('name')->get();
        return datatables($query)
            ->editColumn('food_type_name',function($row){
                return $row->food_type->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="'.route((str_replace('_','-',$this->table_name)).'.edit',$row->id).'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>

              <button
              class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deletefood" data-model-id="' . $row->id . '">
                  <i class="mdi mdi-delete"></i>
              </button>
          </div>';
            })
            ->rawColumns(['color', 'action'])
            ->toJson();
    }
    //??=========================================================================================================
    public function custom_validates(Request $request){
        $exists = Food::where(['name' => $request->name, 'food_type_id' => $request->food_type_id])->exists();
        if($exists){
            return trans('translation.food-has-already-exists');
        }
        return null;
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model){
        if( $delete_model->food_weights->isNotEmpty()){
            return trans('translation.delete-food_meals-or-menus-first');
        }
        return '';
    }
}