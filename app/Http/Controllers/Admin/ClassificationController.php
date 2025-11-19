<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classification;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with('organization:id,name_ar,name_en')->orderByDesc('created_at')->get();
        return datatables($query)
            ->editColumn('organization_name',function($row){
                return $row->organization->name??trans("translation.no-selected-organization");
            })
            ->editColumn('guest_value_sar',function($row){
                return $row->guest_value_sar??'-';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="'.route((str_replace('_','-',$this->table_name)).'.edit',$row->id).'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>

              <button
              class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deleteclassifications" data-model-id="' . $row->id . '">
                  <i class="mdi mdi-delete"></i>
              </button>
          </div>';
            })
            ->rawColumns(['color', 'action'])
            ->toJson();
    }
    //??================================================================
    public function checkRelatives($delete_model){
        if($delete_model->sectors->isNotEmpty()){
            return trans('translation.delete-sectors-first');
        }
        return '';
    }
    //??================================================================
    public function store(Request $request)
    {
        $classification = Classification::create([
            'code' => $request->code,
            'description' => $request->description,
            'guest_value' =>$request->guest_value,
            'organization_id' => $request->organization_id,
        ])->save();
        return back()->with(['message'=> trans('translation.Added successfully'),'alert-type'=>'success']);
}
}
