<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::select('id', 'name_ar', 'name_en', 'type', 'color', 'description');

        if ( \request( 'type' ) ) {
            $query->whereIn( 'type', \request( 'type' ) );
        }

        return datatables($query->orderByDesc('created_at')->get())
            ->addColumn('color', function ($row) {
                return '<div class="" width="100px" style="background-color:' . $row->color . '"> ' . $row->color . ' </div>';
            })
            ->addColumn('description', function ($row) {
                return $row->description ?? trans('translation.no-data');
            })
            ->editColumn('name', function ($row) {
                return $row->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>
            </div>';
            })
            ->rawColumns(['color', 'action'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if (
            $delete_model->orders->isNotEmpty() ||
            $delete_model->supports->isNotEmpty()
        ) {
            return trans('translation.delete-order-or-support-first');
        }
        return '';
    }
}