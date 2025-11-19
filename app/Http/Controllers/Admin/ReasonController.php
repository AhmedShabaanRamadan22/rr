<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;

class ReasonController extends Controller
{

    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::orderBy('name')->get();
        return datatables($query)
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>

                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deletereasons" data-model-id="' . $row->id . '">
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
        if ($delete_model->reason_dangers->isNotEmpty()) {
            return trans('translation.delete-reason_dangers-first');
        }
        return '';
    }
}
