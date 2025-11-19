<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CrudOperationTrait;

class QuestionBankController extends Controller
{
    use CrudOperationTrait;
    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with('question_type:id,name', 'regex:id,name')->orderByDesc('created_at')->get();
        return datatables($query)
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>

              <button
              class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deletequestion_banks" data-model-id="' . $row->id . '">
                  <i class="mdi mdi-delete"></i>
              </button>
          </div>';
            })
            ->editColumn('placeholder', function ($row) {
                return $row->placeholder ?? trans('translation.no-data');
            })
            ->editColumn('question_type', function ($row) {
                return $row->question_type->question_type_name ?? trans('translation.no-data');
            })
            ->rawColumns(['action', 'flag_icon'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->question_bank_oraganization->isNotEmpty()) {
            return trans('translation.delete-questions-first');
        }
        return '';
    }
}
