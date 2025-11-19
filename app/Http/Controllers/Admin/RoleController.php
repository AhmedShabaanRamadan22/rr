<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\CrudOperationTrait;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function update(Request $request, $id)
    {
        $model_item = $this->model::find($id);
        $model_item->update(['name' => $request->name]);
        $model_item->permissions()->sync($request->permissions);
        return back()->with('message', trans('translation.Updated successfully'));
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with('permissions:id,name')->select('id', 'name', 'guard_name')->orderByDesc('created_at')->get();
        return datatables($query)
            ->editColumn('permission_name', function ($row) {
                if ($row->permissions->count() < 1) {
                    return trans('translation.no-data');
                }
                $html = '';
                $i = 1;
                foreach ($row->permissions as $permission) {
                    $html .= '<span class="badge bg-primary mx-1 mb-2">' . $permission->name . '</span>';
                    if ($i < $row->permissions->count()) {
                        $html .= ' | ';
                    }
                    if ($i++ % 9 == 0) {
                        $html .= '<br>';
                    }
                }
                return $html;
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>

              <button
              class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 deleteroles" data-model-id="' . $row->id . '">
                  <i class="mdi mdi-delete"></i>
              </button>
          </div>';
            })
            ->rawColumns(['action', 'permission_name'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if (User::role($delete_model)->get()->isNotEmpty() || $delete_model->permissions->isNotEmpty()) {
            return trans('translation.related-users-or-permissions');
        }
        return '';
    }
}