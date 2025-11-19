<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\Iban;
use App\Models\Facility;
use App\Models\User;

class IbanController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function store(Request $request)
    {
        $model = app('App\Models\\' . $request->ibanable_type)::findOrFail($request->ibanable_id);
        $model->iban()->create($request->only(['account_name', 'iban', 'bank_id','owner_national_id']));
        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::select(['id', 'account_name', 'owner_national_id', 'iban', 'bank_id', 'ibanable_id', 'ibanable_type'])->with('bank:id,name_ar,name_en', 'ibanable:id,name')->orderByDesc('created_at');
        return datatables($query->get())
            ->editColumn('ibanable_type', function ($row) {
                $type = explode('\\', $row->ibanable_type);
                $type = end($type);
                return trans('translation.' . $type);
            })
            ->editColumn('ibanable_id', function ($row) {
                return $row->ibanable->name ?? trans('translation.not-found');
            })
            ->editColumn('bank_name', function ($row) {
                return $row->bank->name; //::findOrFail($row->ibanable_id)->name;
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
        // if($delete_model->{relation}->isNotEmpty()){
        //    return trans('translation.delete-{relation}-first');
        //}
        //return '';
    }
}
