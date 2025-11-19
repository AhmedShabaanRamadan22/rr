<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class PeriodController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with('operation_type:id,name_ar,name_en')->orderByDesc('created_at')->get();
        return datatables($query)
            ->editColumn('duration', function ($row) {
                return $row->duration ?? trans('translation.no-data');
            })
            ->editColumn('operation_type.name', function ($row) {
                return $row->operation_type->name ?? trans('translation.no-data');
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>

                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deleteperiods" data-model-id="' . $row->id . '">
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
        if ($delete_model->stages->isNotEmpty() || ($delete_model->support && $delete_model->support->exists)) {
            return trans('translation.delete-stages-or-support-first');
        }
        return '';
    }
}
