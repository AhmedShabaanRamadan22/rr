<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Traits\CrudOperationTrait;
use Illuminate\Http\Request;
use App\Models\ContactUs;

class ContactUsController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with('subject:id,name_ar,name_en')->orderByDesc('created_at');
        return datatables($query->get())
            // ->editColumn('subject',function($row){
            //     return $row->subject->name;
            // })
            ->editColumn('subject.name', function ($row) {
                return $row->subject->name ?? '-';
            })
            ->addColumn('action', function ($row) {
                return '
            <div class="d-flex justify-content-center">
                <a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>

                <a href="" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 show' . $this->table_name . '" data-model-id="' . $row->id . '"  data-bs-toggle="modal">
                        <i class="mdi mdi-eye"></i>
                   </a>
                <button
                class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete' . $this->table_name . '" data-model-id="' . $row->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>';
            })
            ->rawColumns(['color', 'action'])
            ->toJson();
    }
    //??================================================================
    public function show($id)
    {
        $contactUs = ContactUs::find($id);
        $subjectName = Subject::findOrFail($contactUs->subject_id)->name;
        $contactUs['subject_id'] = $subjectName;

        return response()->json($contactUs);
    }
    //??================================================================
    public function checkRelatives($delete_model)
    {
        // if($delete_model->{relation}->isNotEmpty()){
        //    return trans('translation.delete-{relation}-first');
        //}
        //return '';
    }
}
