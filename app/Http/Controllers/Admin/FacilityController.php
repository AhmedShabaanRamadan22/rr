<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Status;
use App\Models\Service;
use App\Models\District;
use App\Models\Facility;
use App\Traits\PdfTrait;
use App\Traits\SmsTrait;
use App\Models\Attachment;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Traits\WhatsappTrait;
use App\Models\AttachmentLabel;
use App\Traits\AttachmentTrait;
use App\Models\FacilityEmployee;
use App\Traits\OrganizationTrait;
use App\Http\Controllers\Controller;
use App\Models\FacilityEvaluation;

class FacilityController extends Controller
{
    use AttachmentTrait, OrganizationTrait, WhatsappTrait, PdfTrait, SmsTrait;

    public function index()
    {
        // $facilities = Facility::all();
        $users = User::all();
        $services = Service::all();
        $organizations = Organization::all();
        ($columns = Facility::columnNames());
        //        $columnInputs = Facility::columnInputs();
        //        $columnOptions = Facility::columnOptions();
        $hijriDateColumns = Facility::hijriDateColumns();
        //        $districts = District::all();
        $required_attachments = AttachmentLabel::where('type', 'facilities')->get();
        return view('admin.facilities.index', compact('users', 'services', 'organizations', 'columns',   'required_attachments', 'hijriDateColumns'));
    }
    //??================================================================
    public function store(Request $request)
    {
        //
        // dd($request->all());
        $user = User::find($request->user_id);
        // dd($request->all());
        $facility = Facility::create($request->only([
            'registration_number',
            'name',
            'version_date',
            'version_date_hj',
            'end_date',
            'end_date_hj',
            'registration_source',
            'license',
            'capacity',
            'license_expired',
            'license_expired_hj',
            'tax_certificate',
            'employee_number',
            'chefs_number',
            'kitchen_space',
            'building_number',
            'street_name',
            'district_id',
            'city_id',
            'postal_code',
            'sub_number',
        ]) + ['user_id' => $user->id]);
        if ($request->has('attachments')) {
            foreach ($request->attachments as $key => $attachment) {
                $new_attachment = $this->store_attachment($attachment, $facility, $key, null, $user->id);
            }
        }
        if ($request->has('iban')) {
            $facility->iban()->create(['account_name' => $request->account_name, 'bank_id' => $request->bank, 'iban' => $request->iban]);
        }

        $message = trans('translation.send-whatsapp-add-new-facility', ['facility_name' => $facility->name, 'name' => $facility->user->name, 'support' => $this->getSender()->organization->support_phone ?? 570044066]);
        $whatsapp_response = $this->send_message($this->getSender(), $message, $user->phone_code . $user->phone);
        $sending_sms = $this->send_sms($this->getSender(), $message, $facility->user->phone, $facility->user->phone_code);
        // $sending_result = $this->send_message($this->getSender(), trans('translation.send-whatsapp-add-new-facility', ['facility_name' => $facility->name, 'registration_number' => $facility->registration_number]), $user->phone_code, $user->phone);

        return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success'));
    }
    //??================================================================
    public function show(Facility $facility)
    {
        $this->authorize('view', $facility);

        $remaining_attachments = AttachmentLabel::where('type', 'facilities')->whereNotIn('id', $facility->attachments()->pluck('attachment_label_id')->toArray())->get();
        $facility_employees = $facility->facility_employees;
        $employees_column = FacilityEmployee::columnNames();
        $facility_orders = $facility->orders;
        $order_columns = array(
            'id' => 'table_id',
            'code' => 'order-code',
            'organization_name' => 'organization-name',
            'service_name' => 'service-name',
            'status_name' => 'status-name',
            'created_at' => 'order-created_at',
            'updated_at' => 'order-updated_at',
            'action' => 'action',
        );
        $facility_owner_national_id = Attachment::where(['attachmentable_type' => 'App\Models\Facility', 'attachmentable_id' => $facility->id, 'attachment_label_id' => AttachmentLabel::OWNER_ID_LABEL])->first();
        $audits = $facility->audits;
        $audits = $audits->merge($facility->user()->withTrashed()->get()->pluck('audits')->flatten());
        $audits = $audits->merge($facility->user->attachments()->withTrashed()->get()->pluck('audits')->flatten());
        $audits = $audits->merge($facility->attachments()->withTrashed()->get()->pluck('audits')->flatten());
        $audits = $audits->merge($facility->facility_employees()->withTrashed()->get()->pluck('audits')->flatten());
        $audits = $audits->merge($facility->iban()->withTrashed()->get()->pluck('audits')->flatten());
        $audits = $audits->sortByDesc('created_at');

        return view('admin.facilities.show', compact('facility', 'facility_employees', 'employees_column', 'facility_orders', 'order_columns', 'audits', 'facility_owner_national_id', 'remaining_attachments'));
    }
    //??================================================================
    public function edit(Facility $facility)
    {
        $this->authorize('edit', $facility);

        $columnOptions = Facility::columnOptions();
        $hijriDateColumns = Facility::hijriDateColumns();
        $districts = District::all();
        return view('admin.facilities.edit', compact('facility', 'columnOptions', 'districts', 'hijriDateColumns'));
    }
    //??================================================================
    public function update(Request $request, Facility $facility)
    {
        //
        // dd($request->all());
        $facility->update($request->except(['iban', 'bank', 'account_name']));
        $facility->iban()->update(['iban' => $request->iban, 'account_name' => $request->account_name, 'bank_id' => $request->bank]);
        return response()->json(['message' => 'OrganizationFacility was updated successfuly!', 'alert-type' => 'success'], 200);
    }
    //??================================================================
    public function destroy(Facility $facility)
    {
        $facility->iban()->delete();

        $facility->attachments()->delete();

        $facility->facility_employees->each(function ($employee) {
            $employee->attachments()->delete();
        });
        $facility->facility_employees()->delete();

        $facility->orders()->update(['status_id' => Status::CANCELED_ORDER]);

        $facility->delete();
        return response(array('message' => trans('translation.deleted successfully'), 'alert-type' => 'success'), 200);
    }
    //??=============================================================================
    public function pdfReport($facility_uuid, $output = "I")
    {
        $facility = Facility::where('uuid', $facility_uuid)->firstOrFail();
        $attachments_label = AttachmentLabel::where('type', 'facility_employees')->get();
        $this->setPdfData([
            'attachment_label' => 'تقرير عن معلومات المنشأة',
            'organization_data' => $facility,
            'body_content' => $facility,
            'employee_attachment_lables' => $attachments_label,
        ]);
        $mpdf = $this->mPdfInit('facility.facility');
        return $mpdf->Output($facility->id . '.pdf', $output);
    }
    //??================================================================
    public function dataTable(Request $request)
    {
        $is_admin = auth()->user() != null ? auth()->user()->hasRole('admin') : false;
        $rawColumns = ['user_phone_number', 'more_details', 'service_name', 'organization_name'];

        $query = Facility::select([
            'id',
            'name',
            'user_id',
            'registration_number',
            'license',
            'version_date',
            'version_date_hj',
            'end_date',
            'end_date_hj',
            'registration_source',
            'uuid',
            'street_name',
            'postal_code',
            'sub_number',
            'building_number',
            'city_id',
            'district_id',
        ])->with(
            [
                'user' => [
                    'country',
                    'national_source_city'
                ],
                'city',
                'district',
                'facility_services',
                'facility_evaluations',
                'orders.organization_service.service:id,name_ar,name_en',
                'orders.organization_service.organization:id,name_ar,name_en'
            ]
        );

        if (\request('user_id')) {
            $query->whereIn('user_id', \request('user_id'));
        }
        if (\request('service_id')) {
            $query->whereHas('orders', function ($q) {
                $q->whereHas('organization_service', function ($q2) {
                    $q2->whereIn('service_id', \request('service_id'));
                });
            });
        }
        if (\request('organization_id')) {
            $query->whereHas('orders', function ($q) {
                $q->whereHas('organization_service', function ($q2) {
                    $q2->whereIn('organization_id', \request('organization_id'));
                });
            });
        }

        if ($is_admin) {
            $query->whereHas('orders', function ($q) {
                $q->assignee(auth()->user());
            });
        }
        // dd($query->get());
        $datatable =  datatables($query->orderByDesc('created_at')->get())
            ->editColumn('user_name', function (Facility $facility) {
                return $facility->user->name ?? '-';
            })
            ->editColumn('user_email', function (Facility $facility) {
                return $facility->user->email ?? '-';
            })
            ->editColumn('user_birthday', function (Facility $facility) {
                return $facility->user->birthday . ' ' . trans('translation.equals-hj-date') . ' ' . $facility->user->birthday_hj;
            })
            ->editColumn('user_nationality', function (Facility $facility) {
                return $facility->user->country->name_ar;
            })
            ->editColumn('user_national_id', function (Facility $facility) {
                return $facility->user->national_id;
            })
            ->editColumn('user_national_id_issue_city', function (Facility $facility) {
                return $facility->user->national_source_city->name_ar;
            })
            ->editColumn('version_date', function (Facility $facility) {
                return $facility->version_date . ' ' . trans('translation.equals-hj-date') . ' ' . $facility->version_date_hj;
            })
            ->editColumn('end_date', function (Facility $facility) {
                return $facility->end_date . ' ' . trans('translation.equals-hj-date') . ' ' . $facility->end_date_hj;
            })
            ->editColumn('registration_source', function (Facility $facility) {
                return $facility->registrationSourceName;
            })
            ->editColumn('national_address', function (Facility $facility) {
                return $facility->national_address;
            })
            // ->editColumn('remain-capacity', function (Facility $facility) {
            //     return $facility->remain_capacity  ?? '-';
            // })
            ->editColumn('user_phone_number', function (Facility $facility) {
                return '<a href="https://api.whatsapp.com/send?phone=966' . $facility->user->phone . '" target="_blank">' . $facility->user->phone . '</a>';
                // return $facility->user->phone ?? '-';
            })
            ->editColumn('service_name', function (Facility $facility) {
                // $html = ' <span class="badge bg-primary mx-1">';
                // $html .=  $facility->facility_services->implode('service.name', ' </span><span class="badge bg-primary mx-1">');
                // $html .= '</span>';
                // return $html;
                // dd(implode(',',$facility->orders->pluck('organization_service.service_name')->unique()->toArray()));
                return implode(',', $facility->orders->pluck('organization_service.service_name')->unique()->toArray());
            })
            // ->editColumn('label', function (Facility $facility) {
            //     dd($facility->orders->pluck('sectors'));
            //     return $facility->orders->implode('sectors.label', ',');
            // })
            ->editColumn('organization_name', function (Facility $facility) {
                // $html = ' <span class="badge bg-primary mx-1">';
                // $html .=  $facility->orders->implode('organization_service.organization.name_ar', ' </span><span class="badge bg-primary mx-1">');
                // $html .= '</span>';
                // return $html;
                // dd($facility->orders->pluck('organization_service.organization.name_ar')->unique());
                // return $facility->orders->implode('organization_service.organization.name_ar', ',')->unique();
                return $facility->orders->pluck('organization_service.organization.name')->unique();
            })
            ->editColumn('more_details', function (Facility $facility) {
                return '<a class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5" href="facilities/' . $facility->id . '" ><i class="mdi mdi-eye"></i></a>
                <a class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5" href="facilities/' . $facility->id . '/edit" ><i class="mdi mdi-square-edit-outline"></i></a>
                <a target="_blank"
                class="btn btn-outline-primary btn-sm m-1 on-default "
                href="' . (route('admin.facilities.report', $facility->uuid ?? fakeUuid())) . '"
                ><i class="mdi mdi-file-document-outline"></i>
                </a>
                <button class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete-facility" data-facility-id="' . $facility->id . '"><i class="mdi mdi-delete"></i></button>';
            })
            ;

        foreach (FacilityEvaluation::SEASONS as $season) {
            $seasonKey = "$season-h";
            $rawColumns[] = $seasonKey;

            $datatable->editColumn($seasonKey, function (Facility $facility) use ($season) {
                // نحاول إيجاد التقييم لهذا الموسم من العلاقة
                $evaluation = $facility->facility_evaluations->firstWhere('season', $season);
                if( !$evaluation?->evaluation_season ) return "-";
                return "<span class='badge bg-primary' title='" . $evaluation?->details . "' >" . $evaluation?->mark_percentage  . "</span>";
            });
        }
        return $datatable->rawColumns($rawColumns)->toJson();
        // return datatables(Facility::all())->toJson();
    }
}
