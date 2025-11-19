<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Regex;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;

class RegexController extends Controller
{

    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::select('id', 'name', 'description', 'value')->orderByDesc('created_at')->get();;
        return datatables($query)
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>

                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deleteregexes" data-model-id="' . $row->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->question_bank->whereNull('deleted_at')->isNotEmpty()) {
            return trans('translation.delete-questions-first');
        }
        return '';
    }
}
