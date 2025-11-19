<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;

class DangerController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::select('id', 'level', 'color')->orderByDesc('created_at')->get();
        ini_set('memory_limit', '512M');
        return datatables($query)
            ->addColumn('color', function ($row) {
                return '<div class="" width="100px" style="background-color:' . $row->color . '"> ' . $row->color . ' </div>';
            })->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>

                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deletedangers" data-model-id="' . $row->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>';
            })
            ->rawColumns(['color', 'action'])
            ->toJson();
    }
    //??================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->reason_dangers->isNotEmpty()) {
            return trans('translation.delete-reason_dangers-first');
        }
        return '';
    }
}
