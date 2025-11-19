<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttachmentLabel;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\MobileInfo;

class MobileInfoController extends Controller
{
    use CrudOperationTrait;

  public function __construct()
  {
      $this->set_model($this::class);
  }

  public function dataTable(Request $request)
    {
        $query = $this->model::query()->orderByDesc('created_at');
        return datatables($query->get())
        ->addColumn('androidBundleFile', function ( $row) {
            $url =  $row->androidBundleFile->url ?? null ;
            if(!$url) return '-';
            return "<a href='$url'>$url</a>";
        })
        ->addColumn('iosBundleFile', function ( $row) {
            $url =  $row->iosBundleFile->url ?? null;
            if(!$url) return '-';
            return "<a href='$url'>$url</a>";
        })
        ->addColumn('action', function ( $row) {
                return '<div class="d-flex justify-content-center">
                <a href="'.route((str_replace('_','-',$this->table_name)).'.edit',$row->id).'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>

                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete' . $this->table_name . '" data-model-id="' . $row->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>';
            })
            ->rawColumns(['color','action'])
            ->toJson();
    }
    
    public function checkRelatives($delete_model){
        // if($delete_model->{relation}->isNotEmpty()){
        //    return trans('translation.delete-{relation}-first');
        //}
        //return '';
    }
}