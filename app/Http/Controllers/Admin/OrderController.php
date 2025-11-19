<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\AttachmentLabel;
use App\Models\Organization;
use App\Models\OrganizationService;
use App\Models\Order;
use App\Models\Service;
use App\Models\Question;
use App\Models\Section;
use App\Models\Status;
use App\Models\User;
use App\Traits\NoteTrait;
use App\Traits\OrganizationTrait;
use App\Traits\PdfTrait;
use App\Traits\SmsTrait;
use App\Traits\WhatsappTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

  use OrganizationTrait, PdfTrait, NoteTrait, WhatsappTrait, SmsTrait;
  protected $status_id_default = 2;
  protected $all_columns = false;

  public function index()
  {
    $domain = '';
    $organization = Organization::find(1);
    $organizations = Organization::all();
    $orders = $organization->orders;
    $services = Service::all();
    $statuses = Status::order_statuses()->get();
    $can_all_columns = $this->all_columns;
    $columns =  $can_all_columns ?
      Order::columnNames() :
      array(
        'id' => 'table_id',
        'code' => 'order-code',
        'organization_name' => 'organization-name',
        'service_name' => 'service-name',
        'user-name' => 'facility-user-name',
        'facility-name' => 'facility-name',
        'status_name' => 'status-name',
        'created_at' => 'order-created_at',
        'updated_at' => 'order-updated_at',
        'action' => 'action'
      );


    return view('admin.orders.index', compact('orders', 'organization', 'statuses', 'organizations', 'services', 'columns', 'can_all_columns'));
  }
  //??=========================================================================================================
  public function index_customized()
  {
    $this->all_columns = true;
    return $this->index();
  }
  //??=========================================================================================================
  public function show($id)
  {
    
    $order = Order::findOrFail($id);
    $this->authorize('view', $order);
    
    $progress_statuses = Order::progress_statuses();
    $order_statuses = Status::order_statuses()->get();
    $is_atatus_disabled = ($order->status_id == Status::REJECTED_ORDER || $order->status_id == Status::ACCEPTED_ORDER) ? ((!is_null(auth()->user()) && auth()->user()->canChangeOrderStatus()) ? '' : 'disabled ') : '';
    $user = $order->user;
    $facility = $order->facility;
    $audits = $order->audits;
    $audits = $audits->merge($facility->audits);
    $audits = $audits->merge($facility->user()->withTrashed()->get()->pluck('audits')->flatten());
    $audits = $audits->merge($facility->attachments()->withTrashed()->get()->pluck('audits')->flatten());
    $audits = $audits->merge($facility->user->attachments()->withTrashed()->get()->pluck('audits')->flatten());
    $audits = $audits->merge($facility->facility_employees()->withTrashed()->get()->pluck('audits')->flatten());
    $audits = $audits->merge($facility->iban()->withTrashed()->get()->pluck('audits')->flatten());
    $audits = $audits->sortByDesc('created_at');
    return view('admin.orders.show', compact('order', 'user', 'progress_statuses', 'audits','is_atatus_disabled','order_statuses'));
  }
  //??=========================================================================================================
  public function update(Request $request, Order $order, $key = null, $value = null, $url = null)
  {
    $order->update(["$key" => $value]);

    if ($url != null) {
      return redirect($url);
    }
  }
  //??=========================================================================================================
  public function validate_change_status($request, $status, Order $order)
  {
    // dd($request->old_status_id);
    if ($request->old_status_id == Status::ACCEPTED_ORDER || $request->old_status_id == Status::REJECTED_ORDER) {
      if (auth()->user()->checkOrderStatusPermissions($status, $request->old_status_id)) {
        return true;
      }
      return false;
    }
    $this->change_status($request, $status, $order);
    return true;
  }
  //??=========================================================================================================
  public function change_status($request, $status, Order $order)
  {
    $this->update($request, $order, 'status_id', $status);
  }
  //??=========================================================================================================
  public function cancel(Request $request, Order $order)
  {
    $result = "";
    if (!in_array($order->status_id, [Status::CANCELED_ORDER]) && $order->user_id == auth()->user()->id) {
      $result = $this->change_status($request, Status::CANCELED_ORDER, $order);
    }
    return redirect()->route('orders.index')->with(['success' => "Order ($order->id) has been canceled", 'result' => $result]);
  }
  //??=========================================================================================================
  public function update_status(Request $request)
  {
    $order = Order::find($request->order_id);
    if ($this->validate_change_status($request, $request->status_id, $order)) {
      if ($request->old_status_id == Status::ACCEPTED_ORDER) {
        $order_sectors = $order->order_sectors;
        if ($order_sectors != null) {
          foreach ($order_sectors as $order_sector) {
            if (($order_sector->is_active && $order_sector->contract != null) || ($order_sector->parent != null  && $order_sector->parent->contract != null)) {
              return response()->json(['message' => trans('translation.order-has-contracts')], 400);
            }
          }
        }
      }
      $this->change_status($request, $request->status_id, $order);
      if (isset($request->note['note'])) {
        $this->store_note($order, $request->note['note'], auth()->user()->id ?? 0);
      }
      $statusMessages = [
        Status::REJECTED_ORDER => 'send-whatsapp-reject-order',
        Status::PROCESSING_ORDER => 'send-whatsapp-processing-order',
        Status::CONFIRMED_ORDER => 'send-whatsapp-verified-order',
        Status::APPROVED_ORDER => 'send-whatsapp-approved-order',
      ];
      $messageKey = $statusMessages[$request->status_id] ?? 'send-whatsapp-change-order-status';
      $message = trans("translation.$messageKey", ['code' => $order->code, 'status' => $order->status->name ,'user'=> $order->user()->value('name')]);

      // if ($request->status_id == Status::REJECTED_ORDER) {
      //   $message = trans("translation.send-whatsapp-reject-order", ['user' => $order->user->name]);
      // } elseif($request->status_id == Status::PROCESSING_ORDER) {
      //   $message = trans("translation.send-whatsapp-processing-order", ['code' => $order->code, 'status' => $order->status->name]);
      // } elseif($request->status_id == Status::CONFIRMED_ORDER) {
      //   $message = trans("translation.send-whatsapp-verified-order", ['code' => $order->code, 'status' => $order->status->name]);
      // } elseif($request->status_id == Status::APPROVED_ORDER) {
      //   $message = trans("translation.send-whatsapp-approved-order", ['code' => $order->code, 'status' => $order->status->name]);
      // } else {
      //   $message = trans("translation.send-whatsapp-change-order-status", ['code' => $order->code, 'status' => $order->status->name]);
      // }

      $sending_sms = $this->send_sms($order->organization_service->organization->sender, $message, $order->user->phone, $order->user->phone_code);
      $whatsapp_response = $this->send_message($order->organization_service->organization->sender, $message, $order->user->phone_code . $order->user->phone);
      return response()->json(['message' => trans('translation.Updated successfuly'), 'whatsapp_response' => $whatsapp_response, 'sending_sms' => $sending_sms], 200);
    };
    return response()->json(['message' => trans('translation.You dont have permission')], 400);
  }
  //??=========================================================================================================
  public function getOrder($request)
  {
    $order = Order::find($request->order_id);
    if (!$order) {
      $order = Order::create([
        "organization_service_id" => $request->organization_service_id,
        "user_id" => auth()->user()->id,
        "status_id" => Status::NEW_ORDER, // Status: New
      ]);
    }
    return $order;
  }
  //??=========================================================================================================
  public function get_notes(Request $request)
  {
    $order = Order::with([
      'notes.user',
      'notes' => function ($q) {
        return $q->orderBy('notes.created_at', 'DESC');
      }
    ])->findorFail($request->order_id);
    // dd($order->notes);
    return response()->json(['notes' => $order->notes], 200);
  }
  //??=========================================================================================================
  public function pdfReport($order_uuid, $output = "I")
  {
    $order = Order::where('uuid', $order_uuid)->firstOrFail();
    $facility = $order->facility;
    $attachments_label = AttachmentLabel::where('type', 'facility_employees')->get();
    $this->setPdfData([
      'attachment_label' => 'تقرير عن حالة طلب',
      'organization_data' => $order->organization_service->organization,
      'body_content' => $order,
      'facility' => $facility,
      'employee_attachment_lables' => $attachments_label,
    ]);
    $mpdf = $this->mPdfInit('order.order');
    return $mpdf->Output($order->code . '.pdf', $output);
  }
  //??=========================================================================================================
  public function datatable(Request $request)
  {

    $is_chairman = auth()->user() != null ? auth()->user()->hasRole('organization chairman') : false;
    $is_admin = auth()->user() != null ? auth()->user()->hasRole('admin') : false;
    $in_show_facility = isset($request->facility_id);
    // $organization_ids = Organization::all()->pluck('id')->toArray();
    // $organization_service_ids = OrganizationService::whereIn('organization_id', [1, 2])->pluck('id')->toArray();
    // $query = Order::with(
    $query = Order::query();
    // with([
    //   'organization_service.organization:id,name_ar,name_en',
    //   'organization_service.service:id,name_ar,name_en',
    //   'user:id,name,phone,created_at,updated_at,email,national_id,national_id_expired,nationality,birthday',
    //   // 'facility:id,name,registration_number,registration_source,license,capacity,tax_certificate,street_name,building_number,postal_code,sub_number,chefs_number,kitchen_space,employee_number,version_date,version_date_hj,end_date,end_date_hj,license_expired,license_expired_hj,district_id,created_at,updated_at',
    //   'facility' => function($q){
    //     $q->with([
    //       'iban:id,account_name,iban,bank_id',
    //       'iban.bank:id,name_ar,name_en',
    //       'district:id,name_ar,name_en',
    //       'city:id,name_ar,name_en',
    //       'registration_source:id,name_ar,name_en',
    //     ]);
    //   },
    //   // 'facility.iban:id,account_name,iban,bank_id',
    //   // 'facility.iban.bank:id,name_ar,name_en',
    //   'status:id,name_ar,name_en,color',
    //   'user.country:id,name_ar,name_en',
    //   // 'facility.district:id,name_ar,name_en',
    //   // 'facility.city:id,name_ar,name_en',
    //   // 'facility.registration_source:id,name_ar,name_en',
    // ]);
    // $query->whereIn('organization_service_id', $organization_service_ids);
    $query->whereHas('facility', function ($q) {
      $q->whereNull('deleted_at');
    });

    if (\request('service_id')) {
      $query->whereHas('organization_service', function ($q) {
        $q->whereIn('service_id', \request('service_id'));
      });
    }

    if (\request('facility_id')) {
      $query->whereHas('facility', function ($q) {
        $q->whereIn('facility_id', \request('facility_id'));
      });
    }

    if (\request('organization_id')) {
      $query->whereHas('organization_service', function ($q) {

        $q->whereIn('organization_id', \request('organization_id'));
      });
    }

    if (\request('status_id')) {

      $query->whereIn('status_id', \request('status_id'));
    }

    if (\request('interview_status_id')) {

      $query->whereIn('interview_status_id', \request('interview_status_id'));
    }

    if (\request('all_columns')) {
      $this->all_columns = true;
    }

    if($is_admin){
      $query->assignee(auth()->user());
    }

    // dd($query->toSql());
    // return  $this->all_columns ? $this->datatable_customized($query,$is_chairman, $in_show_facility) : $this->datatable_normal($query,$is_chairman, $in_show_facility);
    return  $this->all_columns ? $this->datatable_customized($query, $in_show_facility) : $this->datatable_normal($query);
  }
  //??=========================================================================================================
  public function datatable_normal($query)
  {
    $query->with([
      'status:id,name_en,name_ar,color',
      'organization_service.organization:id,name_ar,name_en',
      'organization_service.service:id,name_ar,name_en',
      'user:id,name',
      'facility:id,name'
    ]);
    return datatables($query->orderByDesc('created_at')->get())
      ->editColumn('code', function (Order $order) {
        $code = 'ORD' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        return $code;
      })
      ->editColumn('organization_name', function (Order $order) {
        return $order->organization_service->organization->name ?? '-';
      })
      ->editColumn('organization_id', function (Order $order) {
        return $order->organization_service->organization->id ?? '-';
      })
      ->editColumn('service_name', function (Order $order) {
        return $order->organization_service->service->name ?? '-';
      })
      ->editColumn('user-name', function (Order $order) {
        return $order->user->name ?? '-';
      })
      ->editColumn('facility-name', function (Order $order) { //  use ($is_chairman) {
        // if ($is_chairman) {
        return  $order->facility->name;
        // }
        // return
        //   '<a target="_blank" href="' . route('facilities.show', $order->facility->id) .  '">' . $order->facility->name . '</a>';
      })
      ->editColumn('user-phone', function (Order $order) {
        return $order->user->phone ?? '-';
      })
      ->editColumn('facility-registration_number', function (Order $order) {
        return $order->facility->registration_number ?? '-';
      })
      ->addColumn('status_name', function (Order $order) {
        return "<span class='badge ' style='background:" . $order->status->color . "' >" . $order->status->name . "</span>";
      })
      ->editColumn('created_at', function (Order $order) {
        if ($order->created_at != null) {
          return $order->created_at->toDateString() . ' - ' . $order->created_at->toTimeString();
        }
        return '';
      })
      ->editColumn('updated_at', function (Order $order) {
        if ($order->updated_at != null) {
          return $order->updated_at->toDateString() . ' - ' . $order->updated_at->toTimeString();
        }
        return '';
      })
      ->addColumn('action', function (Order $order) { // use ($is_chairman) {
        // <button
        //           class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 notes-button"
        //           data-bs-target="#notesModal"
        //           data-order-id="' . $order->id . '"
        //           data-bs-toggle="modal"
        //           data-original-title="Edit">
        //               <i class="mdi mdi-clipboard-edit-outline"></i>
        //         </button>
        $html = // $is_chairman ? '' :
          '<a
            class="btn btn-outline-secondary btn-sm m-1 on-default "
            href="' . (route('orders.show', $order->id)) . '"
            ><i class="mdi mdi-eye"></i>
          </a>';
        if ($order->status_id != Status::CANCELED_ORDER) {
          $html .=
            '<a
              class="btn btn-outline-primary btn-sm m-1 on-default "
              href="' . (route('admin.orders.report', $order->uuid ?? fakeUuid())) . '"
              target="_blank"
              ><i class="mdi mdi-file-document-outline"></i>
            </a>' .
            ( //$is_chairman ? '' :
              '<a target="_blank"
              class="btn btn-outline-success btn-sm m-1 on-default "
              href="' . (route('admin.orders.report', [$order->uuid ?? fakeUuid(), 'D'])) . '"
              ><i class="mdi mdi-download-outline"></i>
            </a>
            ');
        } else {
          $html .= ''; //$is_chairman ? trans('translation.have-no-action') : '';
        }
        return $html;
      })
      ->rawColumns(['action', 'status', 'status_name', 'section_progress', 'facility-name', 'facility-is_updated', 'user-is_updated', 'code'])
      ->toJson();
  }
  //??=========================================================================================================
  public function datatable_customized($query, $in_show_facility)
  {

    // $query->with(
    //   'facility:id,name,registration_number,registration_source,license,capacity,tax_certificate,street_name,building_number,postal_code,sub_number,chefs_number,kitchen_space,employee_number,version_date,version_date_hj,end_date,end_date_hj,license_expired,license_expired_hj,district_id,created_at,updated_at',
    //   'facility.district:id,name_ar,name_en',
    //   'facility.bank_information:id,account_name,owner_national_id,iban,bank_id,ibanable_id,ibanable_type',
    //   'facility.bank_information.bank:id,name_ar,name_en',
    //   'user:id,name,phone,email,national_id,nationality,national_source,created_at,updated_at',
    //   'user.country:id,name_ar,name_en',
    //   'user.national_source_city:id,name_ar,name_en',
      
    // );

    $query->with([
      'organization_service.organization:id,name_ar,name_en',
      'organization_service.service:id,name_ar,name_en',
      'status:id,name_ar,name_en,color',
      'facility' => function($q){
        $q->with([
          'iban',
          'iban.bank',
          'district:id,name_ar,name_en',
          'city:id,name_ar,name_en',
          'registration_src:id,name_ar,name_en',
        ]);
      },
      'user' => function($q){
        $q->with([
          'country',
          'national_source_city:id,name_ar,name_en',
        ]);
      },
    ]);
    $statuses = Status::order_statuses()->whereNot('id', Status::CANCELED_ORDER)->get();

    return datatables($query->orderByDesc('created_at')->get())

      ->editColumn('code', function (Order $order) {
        $code = 'ORD' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        return $code;
      })
      ->editColumn('facility-is_updated', function (Order $order) {
        $created_at = Carbon::parse($order->facility->created_at)->format('Y-m-d H:i');
        $updated_at = Carbon::parse($order->facility->updated_at)->format('Y-m-d H:i');
        return '<i class="' . ($updated_at != $created_at ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger') . '"></i>';
      })
      ->editColumn('user-is_updated', function (Order $order) {
        $today = Carbon::parse(today())->format('Y-m-d');
        $updated_at = Carbon::parse($order->user->updated_at)->format('Y-m-d');
        return '<i class="' . ($updated_at == $today ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger') . '"></i>';
      })
      ->editColumn('organization_name', function (Order $order) {
        return $order->organization_service->organization->name ?? '-';
      })
      ->editColumn('organization_id', function (Order $order) {
        return $order->organization_service->organization->id ?? '-';
      })
      ->editColumn('service_name', function (Order $order) {
        return $order->organization_service->service->name ?? '-';
      })
      ->editColumn('user-name', function (Order $order) {
        return $order->user->name ?? '-';
      })
      ->editColumn('facility-name', function (Order $order) { // use ($is_chairman) {
        // if ($is_chairman) {
        //   return  $order->facility->name;
        // }
        return
          '<a target="_blank" href="' . route('facilities.show', $order->facility->id) .  '">' . $order->facility->name . '</a>';
      })
      ->editColumn('user-phone', function (Order $order) {
        return $order->user->phone ?? '-';
      })
      ->editColumn('user-email', function (Order $order) {
        return $order->user->email ?? '-';
      })
      ->editColumn('user-nationality_name', function (Order $order) {
        return $order->user->country->name ?? '-';
      })
      ->editColumn('user-national_id', function (Order $order) {
        return $order->user->national_id ?? '-';
      })
      ->editColumn('user-national_source_name', function (Order $order) {
        return $order->user->national_source_city->name ?? '-';
      })
      ->editColumn('facility-registration_number', function (Order $order) {
        return $order->facility->registration_number ?? '-';
      })
      ->editColumn('facility-registration_source_name', function (Order $order) {
        return $order->facility->registration_src->name ?? '-';
      })
      ->editColumn('facility-license', function (Order $order) {
        return $order->facility->license ?? '-';
      })
      ->editColumn('facility-capacity', function (Order $order) {
        return $order->facility->capacity ?? '-';
      })
      ->editColumn('facility-tax_certificate', function (Order $order) {
        return $order->facility->tax_certificate ?? '-';
      })
      ->editColumn('facility-national_address', function (Order $order) {
        return $order->facility->national_address ?? '-';
      })
      ->editColumn('facility-bank_information-bank_name', function (Order $order) {
        return $order->facility->iban->bank->name ?? '-';
      })
      ->editColumn('facility-bank_information-account_name', function (Order $order) {
        return $order->facility->iban->account_name ?? '-';
      })
      ->editColumn('facility-bank_information-iban', function (Order $order) {
        return $order->facility->iban->iban ?? '-';
      })
      ->editColumn('facility-chefs_number', function (Order $order) {
        return $order->facility->chefs_number ?? '-';
      })
      ->editColumn('facility-kitchen_space', function (Order $order) {
        return $order->facility->kitchen_space ?? '-';
      })
      ->editColumn('facility-employee_number', function (Order $order) {
        return $order->facility->employee_number ?? '-';
      })
      ->editColumn('version_date', function (Order $order) {
        return $order->facility->version_date . ' ' . trans('translation.equals-hj-date') . ' ' . $order->facility->version_date_hj;
      })
      ->editColumn('end_date', function (Order $order) {
        return $order->facility->end_date . ' ' . trans('translation.equals-hj-date') . ' ' . $order->facility->end_date_hj;
      })
      ->editColumn('license_expired', function (Order $order) {
        return $order->facility->license_expired . ' ' . trans('translation.equals-hj-date') . ' ' . $order->facility->license_expired_hj;
      })
      ->editColumn('user-national_id_expired', function (Order $order) {
        return $order->user->national_id_expired; //. ' ' . trans('translation.equals-hj-date') . ' ' . $order->user->national_id_expired_hj;
      })
      ->editColumn('user-birthday', function (Order $order) {
        return $order->user->birthday . ' ' . trans('translation.equals-hj-date') . ' ' . $order->user->birthday_hj;
      })
      ->addColumn('status_name', function (Order $order) {
        return "<span class='badge ' style='background:" . $order->status->color . "' >" . $order->status->name . "</span>";
      })
      ->addColumn('status', function (Order $order) use ($in_show_facility, $statuses) {

        if ($order->status_id == Status::CANCELED_ORDER  || $in_show_facility || request('in_dashboard') ) {
          return "<span class='badge ' style='background:" . $order->status->color . "' >" . $order->status->name . "</span>";
        }
        $disabled = ($order->status_id == Status::REJECTED_ORDER || $order->status_id == Status::ACCEPTED_ORDER) ? ((!is_null(auth()->user()) && auth()->user()->canChangeOrderStatus()) ? '' : 'disabled ') : '';
        // dd(auth()->user()->canChangeTicketStatus());
        $html = '<div><select class="form-control selectpicker status-select"' . $disabled . ' name="service_id" style="background:' . $order->status->color . '" data-status-id="' . $order->status_id . '" data-order-id="' . $order->id . '" onchange="changeSelectPicker(this)"  >';
        foreach ($statuses as $status) {
          $span = " data-content=\"<span class='badge ' style='background:" . $status->color . "' >" . $status->name . "</span>\" ";
          $html .= '<option value="' . $status->id . '" ' . ($status->id == $order->status->id ? 'selected' : '') . ' ' . $span . ' data-note-required="' . ($status->is_note_required) . '" >' . $status->name . '</option>';
        }
        $html .= "</select></div>";
        return $html;
        //     return '<div class="col text-center">
        //     <a class="btn  text-light" style="background-color:' . $order->status->color . '" >' . $order->status_name . '</a>
        // </div>';
      })
      ->addColumn('since-version-date', function (Order $order) {
        return $order->facility->version_date . ' (' . Carbon::parse($order->facility->version_date)->diffForHumans() . ')';
      })
      ->editColumn('user-created_at', function (Order $order) {
        if ($order->user->created_at != null) {
          return $order->user->created_at->toDateString() . ' - ' . $order->user->created_at->toTimeString();
        }
        return '';
      })
      ->editColumn('user-updated_at', function (Order $order) {
        if ($order->user->updated_at != null) {
          return $order->user->updated_at->toDateString() . ' - ' . $order->user->updated_at->toTimeString();
        }
        return '';
      })
      ->editColumn('facility-created_at', function (Order $order) {
        if ($order->facility->created_at != null) {
          return $order->facility->created_at->toDateString() . ' - ' . $order->facility->created_at->toTimeString();
        }
        return '';
      })
      ->editColumn('facility-updated_at', function (Order $order) {
        if ($order->facility->updated_at != null) {
          return $order->facility->updated_at->toDateString() . ' - ' . $order->facility->updated_at->toTimeString();
        }
        return '';
      })
      ->editColumn('created_at', function (Order $order) {
        if ($order->created_at != null) {
          return $order->created_at->toDateString() . ' - ' . $order->created_at->toTimeString();
        }
        return '';
      })
      ->editColumn('updated_at', function (Order $order) {
        if ($order->updated_at != null) {
          return $order->updated_at->toDateString() . ' - ' . $order->updated_at->toTimeString();
        }
        return '';
      })
      ->addColumn('action', function (Order $order) { //use ($is_chairman) {
        // <button
        //           class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 notes-button"
        //           data-bs-target="#notesModal"
        //           data-order-id="' . $order->id . '"
        //           data-bs-toggle="modal"
        //           data-original-title="Edit">
        //               <i class="mdi mdi-clipboard-edit-outline"></i>
        //         </button>
        $html =  // $is_chairman ? '' :
          '<a
            class="btn btn-outline-secondary btn-sm m-1 on-default "
            href="' . (route('orders.show', $order->id)) . '"
            ><i class="mdi mdi-eye"></i>
          </a>';
        if ($order->status_id != Status::CANCELED_ORDER) {
          $html .=
            '<a
              class="btn btn-outline-primary btn-sm m-1 on-default "
              href="' . (route('admin.orders.report', $order->uuid ?? fakeUuid())) . '"
              target="_blank"
              ><i class="mdi mdi-file-document-outline"></i>
            </a>' .
            ( //$is_chairman ? '' :
              '<a target="_blank"
              class="btn btn-outline-success btn-sm m-1 on-default "
              href="' . (route('admin.orders.report', [$order->uuid ?? fakeUuid(), 'D'])) . '"
              ><i class="mdi mdi-download-outline"></i>
            </a>
            ');
        } else {
          $html .= ''; //$is_chairman ? trans('translation.have-no-action') : '';
        }
        return $html;
      })
      ->rawColumns(['action', 'status', 'status_name', 'section_progress', 'facility-name', 'facility-is_updated', 'user-is_updated', 'code'])
      ->toJson();
  }
}
