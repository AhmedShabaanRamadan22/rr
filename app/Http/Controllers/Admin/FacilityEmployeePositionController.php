<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Traits\CrudOperationTrait;
use App\Http\Controllers\Controller;

class FacilityEmployeePositionController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??================================================================
    public function dataTable(Request $request)
      {
          $query = $this->model::query()->get();
          return datatables($query)
              ->addColumn('action', function ( $row) {
                  return '<div class="d-flex justify-content-center">
                  <a href="'.route((str_replace('_','-',$this->table_name)).'.edit',$row->id).'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                      <i class="mdi mdi-square-edit-outline"></i>
                  </a>
              </div>';
              })
              ->rawColumns(['action'])
              ->toJson();
      }
    //??================================================================
      public function checkRelatives($delete_model){
        if($delete_model->facilityEmployees->isNotEmpty()){
            return trans('translation.delete-facilty-employee-first');
        }
        return '';
    }
}
