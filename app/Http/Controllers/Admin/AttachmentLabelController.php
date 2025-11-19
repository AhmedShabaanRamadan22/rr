<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\AttachmentLabel;
use Illuminate\Support\Facades\DB;

class AttachmentLabelController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::query()->orderByDesc('created_at');
        if (\request('type')) {
            $query->whereIn('type', \request('type'));
        }
        return datatables($query->get())
            ->editColumn('is_required', function ($row) {
                return '<i class="' . ($row->is_required == 1 ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger') . ' "></i>';
            })
            ->editColumn('arrangement', function ($row) {
                return $row->arrangement ?? trans('translation.No arrangement');
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>
          </div>';
            })
            ->rawColumns(['is_required', 'action'])
            ->toJson();
    }
    //??================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->attachments->isNotEmpty() || $delete_model->organization_attachments->isNotEmpty()) {
            return trans('translation.delete-attachments-first');
        }
        return '';
    }
}
