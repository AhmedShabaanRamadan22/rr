<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\FacilityEvaluation;

class FacilityEvaluationController extends Controller
{
    use CrudOperationTrait;

  public function __construct()
  {
      $this->set_model($this::class);
  }

    public function custom_validates(Request $request)
    {
        $inputs = ['season' => $request->season, 'facility_id' => $request->facility_id];
        $exists = FacilityEvaluation::with('facility')->where($inputs)->exists();
        if ($exists) {
            return trans('translation.Facility-has-already-evaluated-in-season',$inputs);
        }
        return null;
    }


    public function dataTable(Request $request)
    {
        $query = $this->model::with(['facility'])->orderByDesc('created_at');

        if (request('season')) {
            $query->whereIn('season', request('season'));
        }

        if (request('facility_id')) {
            $query->whereIn('facility_id', request('facility_id'));
        }

        return datatables($query->get())
        ->editColumn('season', function($row){
            return $this->model::SEASONS[$row->season];
        })
        ->editColumn('facility_id',function($row){
            return $row->facility->name ?? '-';
        })
        ->addColumn('action', function ( $row) {
                return '<div class="d-flex justify-content-center">
                <a href="'.route((str_replace('_','-',$this->table_name)).'.edit',$row->id).'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>

                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete' . $this->table_name . '" data-model-id="' . $row->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>';
            })
            ->rawColumns(['color','action'])
            ->toJson();
    }
    
    public function checkRelatives($delete_model){
        // if($delete_model->{relation}->isNotEmpty()){
        //    return trans('translation.delete-{relation}-first');
        //}
        //return '';
    }
}