<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\AttachmentLabel;
use App\Models\FacilityEmployee;
use App\Http\Controllers\Controller;

class FacilityEmployeeController extends Controller
{

    public function show(FacilityEmployee $facility_employee)
    {
        $remaining_attachments = AttachmentLabel::where('type', 'facility_employees')->whereNotIn('id', $facility_employee->attachments()->pluck('attachment_label_id')->toArray())->get();
        $attachments = $facility_employee->attachments()->get();
        return view('admin.facilities.facility_employees.show',compact('facility_employee', 'remaining_attachments', 'attachments'));
    }
    //??================================================================
    public function datatable(Request $request, $facility_id){
        $query = FacilityEmployee::where('facility_id', $facility_id)->with('attachments:id,name,path,attachment_label_id','facility_employee_position:id,name_ar,name_en','attachments.attachment_label:id,placeholder_ar,placeholder_en')->orderByDesc('created_at')->get();
        return datatables($query)
        ->editColumn('facility_employee_position', function (FacilityEmployee $facility_employee) {
            return $facility_employee->facility_employee_position->name;
        })
        ->editColumn('employee_attachments', function (FacilityEmployee $facility_employee) {
            if (($facility_employee->has('attachments'))) {
                return '<a class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5" href="' . (route('facility-employees.show', $facility_employee->id)) . '" target="_blank" ><i class="mdi mdi-eye"></i></a>';
            }
            return trans('translation.no-data');
        })

        ->rawColumns(['facility_employee_position', 'employee_attachments'])
        ->toJson();
    }
}