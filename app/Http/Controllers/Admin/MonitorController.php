<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Sector;
use App\Models\Monitor;
use Illuminate\Http\Request;
use App\Traits\CrudOperationTrait;
use App\Http\Controllers\Controller;
use App\Models\Meal;
use App\Models\MealOrganizationStage;
use App\Models\Organization;
use App\Models\SubmittedForm;
use App\Models\Support;
use App\Models\Ticket;
use App\Services\AnswerService;
use App\Traits\PdfTrait;
use Carbon\Carbon;

class MonitorController extends Controller
{
    use PdfTrait, CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
        $this->data = array(
            'current_year' => date('Y'),
            'current_date' => date('Y-m-d H:i:s'),
            'attachment_label' => 'تقرير ',
            'header_default_logo' => 'https://rakaya.co/images/logo/logo.png'
        );
    }
    //??=========================================================================================================
    public function setRoles(Request $request)
    {
        $user = Monitor::find($request->monitor_id)->user;
        $roles = [
            Role::SUPERVISOR => 'supervisor',
            Role::BOSS => 'boss',
        ];
        foreach ($roles as $role => $field) {
            if ($user->hasRole($role) && $request->$field == "0") { // has the role but wants to delete it
                $sectors = Sector::where("{$field}_id", $user->id)->get();
                if ($sectors->isNotEmpty()) {
                    return back()->with(['message' => trans('translation.Unassign monitor from sectors first'), 'alert-type' => 'error'], 400);
                }
                $user->removeRole($role);
            }elseif(!$user->hasRole($role) && $request->$field == "1"){
                $user->assignRole($field);
            }
        }
        return back()->with(['message' => trans('translation.Role updated successfully'), 'alert-type' => 'success'], 200);
    
    }
    //??=========================================================================================================
    public function store(Request $request)
    {
        $user = User::find($request->monitor_name);
        $err = $user->assignRole('monitor');
        if ($err) {
            return back()->with(array('message' => $err->getData()->message, 'alert-type' =>  $err->getData()->{'alert-type'}));
        }
        $existed_monitor = Monitor::where('user_id', $request->monitor_name);//->orWhere('code', $request->code);
        if ($existed_monitor->count() != 0) {
            return back()->with(['message' => trans('translation.already a monitor or code exist'), 'alert-type' => 'error']);
        }
        $monitor = Monitor::create(['user_id' => $request->monitor_name]);
        $code = 'RKY-MR' . str_pad($monitor->id, 3, '0', STR_PAD_LEFT) . '-USR' . str_pad($monitor->user_id, 4, '0', STR_PAD_LEFT);
        $monitor->update(['code' => $code]);
        // User::find($monitor->user_id)->assignRole('monitor');
        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
        // return response(array('message' => trans("translation.Deleted successfully"), 'alert-type' => 'success'), 200);
    }
    //??=========================================================================================================
    public function edit(Monitor $monitor)
    {
        $assigned_sectors =  $monitor->monitor_order_sectors->implode('order_sector.order_sector_name', ',');
        $columnOptions = Monitor::columnOptions();
        return view('admin.monitors.edit', compact('monitor', 'assigned_sectors', 'columnOptions'));
    }
    //??=========================================================================================================
    public function mealsPdfReport($monitor_id, $output = "I")
    {
        $monitor = User::findOrFail($monitor_id);
        $meal_organization_stages = MealOrganizationStage::where('done_by', $monitor->id)->pluck('id')->toArray();        
        $meals = Meal::whereIn('id', $meal_organization_stages)->get();
        $organization = Organization::findOrFail(Organization::RAKAYA);
        $this->setPdfData([
            'attachment_label' => 'تقرير المراقب - الوجبات',
            'organization_data' => $organization,
            'meals' => $meals,
            'model' => $monitor,
        ]);
        $mpdf = $this->mPdfInit('monitor.meals');
        return $mpdf->Output('تقرير وجبات المراقب - ' . $monitor->name . ' - ' . Carbon::now() . '.pdf', $output);
    }
    //??=========================================================================================================
    public function supportsPdfReport($monitor_id, $output = "I")
    {
        $monitor = User::findOrFail($monitor_id);
        $supports = Support::where('user_id', $monitor->id)->get();
        $organization = Organization::findOrFail(Organization::RAKAYA);
        $this->setPdfData([
            'attachment_label' => 'تقرير المراقب - الإسناد',
            'organization_data' => $organization,
            'supports' => $supports,
            'model' => $monitor,
        ]);
        $mpdf = $this->mPdfInit('monitor.supports');
        return $mpdf->Output('تقرير إسناد المراقب - ' . $monitor->name . ' - ' . Carbon::now() . '.pdf', $output);
    }
    //??=========================================================================================================
    public function ticketsPdfReport($monitor_id, $output = "I")
    {
        $monitor = User::findOrFail($monitor_id);
        $tickets = Ticket::where('user_id', $monitor->id)->get();
        $organization = Organization::findOrFail(Organization::RAKAYA);
        $this->setPdfData([
            'attachment_label' => 'تقرير المراقب - البلاغات',
            'organization_data' => $organization,
            'tickets' => $tickets,
            'model' => $monitor,
        ]);
        $mpdf = $this->mPdfInit('monitor.tickets');
        return $mpdf->Output('تقرير بلاغات المراقب - ' . $monitor->name . ' - ' . Carbon::now() . '.pdf', $output);
    }
    //??=========================================================================================================
    public function submittedFormsPdfReport($submitted_form_id, $output = "I")
    {
        $submitted_form = SubmittedForm::findOrFail($submitted_form_id);
        $answer_service = new AnswerService();
        $organization = Organization::findOrFail(Organization::RAKAYA);
        $this->setPdfData([
            'attachment_label' => 'تقرير المراقب - الاستمارات المسلمة',
            'organization_data' => $organization,
            'submitted_form' => $submitted_form,
            'answer_service' => $answer_service,
            'model' => $submitted_form,
        ]);
        $mpdf = $this->mPdfInit('monitor.submitted-forms');
        return $mpdf->Output('تقرير استمارة ' . $submitted_form->form->name . ' للمراقب - ' . $submitted_form->user->name . ' - ' . Carbon::now() . '.pdf', $output);
    }
    //??=========================================================================================================
    public function infoPdfReport($monitor_id, $output = "I")
    {
        $monitor = User::findOrFail($monitor_id);
        $organization = Organization::findOrFail(Organization::RAKAYA);
        $this->setPdfData([
            'attachment_label' => 'تقرير المراقب',
            'organization_data' => $organization,
            'monitor' => $monitor,
            'model' => $monitor,
        ]);
        $mpdf = $this->mPdfInit('monitor.general-info');
        return $mpdf->Output('تقرير المعلومات العامة للمراقب - ' . $monitor->name . ' - ' . Carbon::now() . '.pdf', $output);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::select([
            'id','code','user_id'
        ])
        ->with(
            'monitor_order_sectors.order_sector.sector:id,label',
            'monitor_order_sectors.order_sector.order.facility:id,name',
            'monitor_order_sectors.order_sector.order.organization_service.service:id,name_ar,name_en',
            'monitor_order_sectors.order_sector.order.organization_service.organization:id,name_ar,name_en',
            'user:id,name,phone,bravo_id',
            'user.roles:id,name',
            'user.bravo:id,number,code',

        );

        return datatables($query->orderByDesc('created_at')->get())
            ->editColumn('monitor', function ($monitor) {
                return $monitor->user->name;
            })
            ->editColumn('role-name', function ($monitor) {
                $i = 1;
                $html = '';
                foreach ($roles = $monitor->user->roles as $role) {
                    $html .= '<span class="badge bg-primary mx-1 mb-2">' . $role->name . '</span>';
                    if($i < $roles->count()){
                        $html .= ' | ';
                    }
                    if ($i++ % 3 == 0) {
                        $html .= '<br>';
                    }
                }
                // if(!in_array($monitor->user->roles->pluck('id')->toArray(),[Role::BOSS, Role::SUPERVISOR]) && $monitor->monitor_order_sectors->isEmpty()){
                    $html .= '<button type="button" class="btn btn-sm col-auto mx-1 btn-outline-primary" data-bs-target="#monitorRole" data-bs-toggle="modal" data-monitor-id=" '. $monitor->id .' " data-boss= "'. ($monitor->user->hasRole('boss') ? 1 : 0) . '" data-supervisor= "'. ($monitor->user->hasRole('supervisor') ? 1 : 0). '">
                                    <i class="mdi mdi-plus"></i>
                                </button>';
                // }
                return $html;
                // return $monitor->user->roles->implode('name', ',');
            })
            ->editColumn('phone', function ($monitor) {
                return '<a href="https://api.whatsapp.com/send?phone=966' . $monitor->user->phone . '" target="_blank">' . $monitor->user->phone . '</a>';
            })
            ->editColumn('bravo_number', function ($monitor) {
                if ($monitor->user->bravo != null) {
                    return $monitor->user->bravo->number;
                }
                return '-';
            })
            ->editColumn('bravo_code', function ($monitor) {
                if ($monitor->user->bravo != null) {
                    return $monitor->user->bravo->code;
                }
                return '-';
            })
            ->editColumn('order_sectors', function ($monitor) {
                $i = 1;
                $html = '';
                if ($monitor->monitor_order_sectors->count() < 1) {
                    return trans('translation.no-data');
                }
                $last_item = $monitor->monitor_order_sectors->last();
                foreach ($monitor->monitor_order_sectors as $monitor_order_sector) {
                    $html .= '<span class="badge bg-primary mx-1 mb-2">' . $monitor_order_sector->order_sector->order_sector_name . '</span>' . ($last_item->id == $monitor_order_sector->id? '':' | ');
                    if ($i++ % 3 == 0) {
                        $html .= '<br>';
                    }
                }
                return $html;
            })
            ->editColumn('reports', function($monitor){
                return
                '
                <div class="flex-row">
                    <span class="badge bg-primary">' . trans('translation.general-info') . '</span>
                    <a
                    class="btn btn-outline-primary btn-sm m-1 on-default "
                    href="' . (route('admin.monitors.info-report', [$monitor->user->id])) . '"
                    target="_blank"
                    ><i class="mdi mdi-file-document-outline"></i>
                </a>
                <a target="_blank"
                    class="btn btn-outline-success btn-sm m-1 on-default "
                    href="' . (route('admin.monitors.info-report', [$monitor->user->id, 'D'])) . '"
                    ><i class="mdi mdi-download-outline"></i>
                </a>
                </div>
                <div class="flex-row">
                    <span class="badge bg-primary">' . trans('translation.tickets') . '</span>
                    <a
                    class="btn btn-outline-primary btn-sm m-1 on-default "
                    href="' . (route('admin.monitors.tickets-report', [$monitor->user->id])) . '"
                    target="_blank"
                    ><i class="mdi mdi-file-document-outline"></i>
                </a>
                <a target="_blank"
                    class="btn btn-outline-success btn-sm m-1 on-default "
                    href="' . (route('admin.monitors.tickets-report', [$monitor->user->id, 'D'])) . '"
                    ><i class="mdi mdi-download-outline"></i>
                </a>
                </div>
                <div class="flex-row">
                    <span class="badge bg-primary">' . trans('translation.meals') . '</span>
                    <a
                    class="btn btn-outline-primary btn-sm m-1 on-default "
                    href="' . (route('admin.monitors.meals-report', [$monitor->user->id])) . '"
                    target="_blank"
                    ><i class="mdi mdi-file-document-outline"></i>
                </a>
                <a target="_blank"
                    class="btn btn-outline-success btn-sm m-1 on-default "
                    href="' . (route('admin.monitors.meals-report', [$monitor->user->id, 'D'])) . '"
                    ><i class="mdi mdi-download-outline"></i>
                </a>
                </div>
                <div class="flex-row">
                    <span class="badge bg-primary">' . trans('translation.supports') . '</span>
                    <a
                    class="btn btn-outline-primary btn-sm m-1 on-default "
                    href="' . (route('admin.monitors.supports-report', [$monitor->user->id])) . '"
                    target="_blank"
                    ><i class="mdi mdi-file-document-outline"></i>
                </a>
                <a target="_blank"
                    class="btn btn-outline-success btn-sm m-1 on-default "
                    href="' . (route('admin.monitors.supports-report', [$monitor->user->id, 'D'])) . '"
                    ><i class="mdi mdi-download-outline"></i>
                </a>
                </div>
                <div class="flex-row">
                    <a
                    class="btn btn-outline-primary btn-sm m-1 on-default "
                    href="' . (route('admin.submitted-forms.store-user-id', [$monitor->user->id])) . '"
                    target="_blank"
                    >' . trans('translation.submitted-forms') . '
                </a>
                </div>
                ';
            })
            ->addColumn('action', function ($monitor) {
                // $action_btn = '';
                // if(! $monitor->user->hasRole(['supervisor', 'boss'])){
                    $action_btn =  '<a href="' . route((str_replace('_', '-', $this->table_name)) . '.edit', $monitor->id) . '" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 ">
                    <i class="mdi mdi-square-edit-outline"></i>
                </a>';
                // }
                return '<div class="d-flex justify-content-center">
                '.$action_btn.'
                <button class="btn btn-outline-danger btn-sm m-1 on-default m-r-5 deletemonitors" data-model-id="' . $monitor->id . '">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>';
            })
            ->rawColumns(['bravo-number', 'bravo-code', 'phone', 'order_sectors', 'role-name', 'action', 'reports'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if ($delete_model->monitor_order_sectors->isNotEmpty() || $delete_model->user->supervisor_sectors->isNotEmpty() || $delete_model->user->boss_sectors->isNotEmpty()) {
            return trans('translation.related-order-sector');
        }
        $user = User::find($delete_model->user_id);
        $user->removeRole('monitor');
        $user->hasRole('boss') ? $user->removeRole('boss') : '';
        $user->hasRole('supervisor') ? $user->removeRole('supervisor') : '';
        return '';
    }
}