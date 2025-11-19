<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\QuestionType;
use App\Traits\CrudOperationTrait;

class QuestionTypeController extends Controller
{
    use CrudOperationTrait;
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::orderByDesc('created_at')->get();
        return datatables($query)
            ->addColumn('has_option', function ($row) {
                return '<i class="' . ($row->has_option == 1 ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger') . ' "></i>';
            })
            ->addColumn('action', function ( $row) {
                return '<div class="d-flex justify-content-center">
                <a href="'.route((str_replace('_','-',$this->table_name)).'.edit',$row->id).'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-square-edit-outline"></i>
                </a>
                
                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deletequestion_types" data-model-id="' . $row->id . '">
                <i class="mdi mdi-delete"></i>
                </button>
                </div>';
            })
            ->editColumn('name', function ($row) {
                return $row->question_type_name;
            })
            ->rawColumns(['id', 'name', 'has_option', 'action'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model){
        if($delete_model->question_bank->isNotEmpty()){
            return trans('translation.delete-questions-first');
        }
        return '';
    }
}