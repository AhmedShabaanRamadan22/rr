<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\FineOrganization;


class FineOrganizationController extends Controller
{
    use CrudOperationTrait;
    protected $update_return_type = 'json';

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with('fine_bank:id,name,price');
        if ($request->has('organization_id')) {
            $query->where('organization_id', $request->input('organization_id'));
        }
        // dd($query->orderByDesc('created_at')->get()->toArray());
        return datatables($query->orderByDesc('created_at')->get())
            ->editColumn('fine_bank_name',function($row){
                return $row->fine_bank->name ?? trans('translation.not-found') ;
            })
            ->addColumn('action', function ($fine_organization) {
                return '<div class="d-flex justify-content-center">
                <a class="btn btn-outline-secondary btn-sm m-1 on-default m-r-5"  data-fine-organization-id="' . $fine_organization->id . '" data-bs-target="#editFineOrganization" data-bs-toggle="modal">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>
                <button class="btn btn-outline-danger btn-sm m-1 on-default m-r-5 deletefine_organizations " data-model-id="' . $fine_organization->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>';
            })
            ->rawColumns(['fine_bank_id', 'action'])
            ->toJson();
    }
    //??=========================================================================================================
    public function return_update_response()
    {
        return response(['message' => trans('translation.updated-successfully')], 200);
    }
    //??================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->fines->isNotEmpty()) {
            return trans('translation.delete-fine-first');
        }
        return '';
    }
}
