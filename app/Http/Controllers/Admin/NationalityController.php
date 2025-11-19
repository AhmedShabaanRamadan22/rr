<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nationality;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class NationalityController extends Controller
{
    use CrudOperationTrait;
    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::select(['id', 'name', 'flag'])->orderByDesc('created_at')->get();
        // dd($query->orderByDesc('created_at')->get()->toArray());
        return datatables($query)
            ->editColumn('flag_icon', function ($row) {
                return $row->flag_icon;
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>

              <button
              class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deletenationalities" data-model-id="' . $row->id . '">
                  <i class="mdi mdi-delete"></i>
              </button>
          </div>';
            })
            ->rawColumns(['action', 'flag_icon'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->nationality_organizations->isNotEmpty()) {
            return trans('translation.delete-natinoality-organization-first');
        }
        return '';
    }
}
