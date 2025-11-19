<?php

namespace App\Http\Controllers\Admin;

use App\Models\Organization;
use App\Models\User;
use App\Models\Status;
use App\Models\Candidate;
use Illuminate\Http\Request;
use App\Traits\WhatsappTrait;
use App\Models\AttachmentLabel;
use App\Traits\AttachmentTrait;
use App\Traits\CrudOperationTrait;
use App\Http\Controllers\Controller;
use App\Traits\SmsTrait;
use Illuminate\Support\Facades\DB;

class CandidateController extends Controller
{
    use CrudOperationTrait, WhatsappTrait, AttachmentTrait, SmsTrait;
    protected $all_columns = false;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??================================================================
    public function index_customized()
    {
        $this->all_columns = true;
        return $this->index();
    }
    //??================================================================
    public function dataTableApi(Request $request)
    {
        $staticToken = 'HT8LpbLSpiRNbvqo0Z0ieDMMWFv2nlcNekZOgNxPtKdkwIssxfGe';
        $token = $request->query('token');
    
        if ($token != $staticToken) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->dataTable($request);
    }
    //??================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with(
            'department:id,slug,name_ar,name_en',
            'status:id,color,name_ar,name_en',
        );

        if (\request('all_columns')) {
            $this->all_columns = true;
        }

        if (\request('department_name')) {
            $query->whereHas('department', function ($q1) {
                $q1->whereIn('id', \request('department_name'));
            });
        }

        if (\request('status_id')) {
            $query->whereHas('status', function ($q1) {
                $q1->whereIn('id', \request('status_id'));
            });
        }

        if (\request('years_of_experience')) {
            $query->whereIn('years_of_experience', \request('years_of_experience'));
        }

        if (\request('resident_status')) {
            $query->whereIn('resident_status', \request('resident_status'));
        }
        if (\request('gender')) {
            $query->whereIn('gender', \request('gender'));
        }
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $search = $search['value'];
            // dd($search['value']);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // $query->orderByDesc('created_at');

        return $this->all_columns ? 
            $this->customized_datatable($query) : 
            $this->normal_datatable($query->orderByDesc('created_at')->paginate(request()->input('per_page', 50))) ;
    }
    //??================================================================
    public function normal_datatable($query){
        $transformedData = $query->getCollection()->map(function ($candidate) {
            return [
                'id' => $candidate->id,
                'code' => ('CDT-' . $candidate->department->slug . str_pad($candidate->id, 5, '0', STR_PAD_LEFT)),
                'name' => $candidate->name,
                'gender' => $candidate->gender_name,
                'email' => '<a href="mailto:' . $candidate->email . '" target="_blank">' . $candidate->email . '</a>',
                'phone' => $candidate->phone != '-' ? ('<a href="https://api.whatsapp.com/send?phone=' . $candidate->phone_code . $candidate->phone . '" target="_blank">' . $candidate->phone_code . $candidate->phone . '</a>') : trans('translation.no-data'),
                'status' => "<span class='badge ' style='background:" . $candidate->status->color . "' >" . $candidate->status->name . "</span>",
                'department_name' => $candidate->department->name,
                'years_of_experience_name' => $candidate->years_of_experience_name ?? trans('translation.no-data'),
                'resident_status_name' => $candidate->resident_status_name ?? trans('translation.no-data'),
                'job_category_name' => $candidate->job_category_name,
                'created_at' => $candidate->created_at != null ? $candidate->created_at->diffForHumans() : '',
                'updated_at' => $candidate->updated_at != null ? $candidate->updated_at->diffForHumans() : '',
                'action' => '<a class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5" href="' . (route('candidates.show', $candidate->id)) . '" target="_blank" ><i class="mdi mdi-eye"></i></a>',
            ];
        });

        return response()->json([
            'data' => $transformedData,
            'draw' => intval(request()->input('draw')), // Required for DataTables
            'recordsTotal' => $query->total(), // Total records
            'recordsFiltered' => $query->total(), // Filtered records (adjust if you apply filters)
        ]);
    }
    //??================================================================
    public function customized_datatable($query)
    {
        $query->whereHas('status', function ($q1) {
            $q1->whereIn('id', [
                Status::COMPLETED_DATA_CANDIDATE,
                Status::ACCEPTED_CANDIDATE,
            ]);
        });

        $query->with([
            'country',
            'iban.bank',
            'attachment_candidate_cv',
            'attachment_candidate_portfolio',
            'attachment_candidate_profile_personal',
            'attachment_candidate_national_id',
            'attachment_candidate_iban',
            'attachment_candidate_education_certificate',
            'attachment_candidate_course_certificate',
            'attachment_candidate_experience_certificate',
            'attachment_candidate_cv_en',
            'attachment_candidate_passport',
            'attachment_candidate_driving_license',
            'attachment_candidate_national_address',
        ]);

        $query_paginated = $query->orderByDesc('updated_at')->paginate(request()->input('per_page', 50));

        $transformedData = $query_paginated->getCollection()->map(function ($candidate) {
            return [
                'id' => $candidate->id,
                'code' => 'CDT-' . $candidate->department->slug . str_pad($candidate->id, 5, '0', STR_PAD_LEFT),
                'name' => $candidate->name,
                'gender' => $candidate->gender_name,
                'email' => '<a href="mailto:' . $candidate->email . '" target="_blank">' . $candidate->email . '</a>',
                'phone' => $candidate->phone != '-' ? '<a href="https://api.whatsapp.com/send?phone=' . $candidate->phone_code . $candidate->phone . '" target="_blank">' . $candidate->phone_code . $candidate->phone . '</a>' : trans('translation.no-data'),
                'status' => "<span class='badge' style='background:" . $candidate->status->color . "'>" . $candidate->status->name . '</span>',
                'department_name' => optional($candidate->department)->name,
                'years_of_experience_name' => $candidate->years_of_experience_name ?? trans('translation.no-data'),
                'resident_status_name' => $candidate->resident_status_name ?? trans('translation.no-data'),
                'job_category_name' => $candidate->job_category_name ?? trans('translation.no-data'),
                'created_at' => $candidate->created_at ? $candidate->created_at->diffForHumans() : '',
                'updated_at' => $candidate->updated_at ? $candidate->updated_at->diffForHumans() : '',
                'action' => '<a class="btn btn-outline-secondary btn-sm m-1 on-default m-r-5" href="' . route('candidates.show', $candidate->id) . '" target="_blank"><i class="mdi mdi-eye"></i></a>',
                // Extended Fields
                'qualification_name' => $candidate->qualification_name ?? trans('translation.no-data'),
                'self_description' => $candidate->self_description ?? trans('translation.no-data'),
                'marital_status_name' => $candidate->marital_status_name ?? trans('translation.no-data'),
                'salary_expectation' => $candidate->salary_expectation ?? trans('translation.no-data'),
                'iban_number' => $candidate->iban_number ?? trans('translation.no-data'),
                'bank_name' => $candidate->bank_name ?? trans('translation.no-data'),
                'account_name' => $candidate->account_name ?? trans('translation.no-data'),
                'owner_national_id' => $candidate->owner_national_id ?? trans('translation.no-data'),
                'candidate_profile_personal_attachment_url' => $candidate->candidate_profile_personal_attachment_url ?? trans('translation.no-data'),
                'candidate_cv_attachment_url' => $candidate->candidate_cv_attachment_url ?? trans('translation.no-data'),
                'candidate_portfolio_attachment_url' => $candidate->candidate_portfolio_attachment_url ?? trans('translation.no-data'),
                'candidate_iban_attachment_url' => $candidate->candidate_iban_attachment_url ?? trans('translation.no-data'),
                'candidate_national_id_attachment_url' => $candidate->candidate_national_id_attachment_url ?? trans('translation.no-data'),
                'candidate_education_certificate_attachment_url' => $candidate->candidate_education_certificate_attachment_url ?? trans('translation.no-data'),
                'candidate_course_certificate_attachment_url' => $candidate->candidate_course_certificate_attachment_url ?? trans('translation.no-data'),
                'candidate_experience_certificate_attachment_url' => $candidate->candidate_experience_certificate_attachment_url ?? trans('translation.no-data'),
                'candidate_cv_en_attachment_url' => $candidate->candidate_cv_en_attachment_url ?? trans('translation.no-data'),
                'candidate_passport_attachment_url' => $candidate->candidate_passport_attachment_url ?? trans('translation.no-data'),
                'candidate_driving_license_attachment_url' => $candidate->candidate_driving_license_attachment_url ?? trans('translation.no-data'),
                'candidate_national_address_attachment_url' => $candidate->candidate_national_address_attachment_url ?? trans('translation.no-data'),
                'national_id' => $candidate->national_id ?? trans('translation.no-data'),
                'nationality_name' => $candidate->nationality_name ?? trans('translation.no-data'),
                'birthdate' => $candidate->birthdate ?? trans('translation.no-data'),
                'birthdate_hj' => $candidate->birthdate_hj ?? trans('translation.no-data'),
                'address' => $candidate->address ?? trans('translation.no-data'),
                'previously_work_at_rakaya' => $candidate->previously_work_at_rakaya ?? trans('translation.no-data'),
                'has_relative' => $candidate->has_relative ?? trans('translation.no-data'),
                'scrub_size_name' => $candidate->scrub_size_name ?? trans('translation.no-data'),
            ];
        });

        return response()->json([
            'data' => $transformedData,
            'draw' => intval(request()->input('draw')),
            'recordsTotal' => $query_paginated->total(),
            'recordsFiltered' => $query_paginated->total(),
        ]);
    }

    //??================================================================
    /* public function customized_datatable($query){
      $query->whereHas('status', function ($q1) {
          $q1->whereIn('id', [
              Status::COMPLETED_DATA_CANDIDATE,
              Status::ACCEPTED_CANDIDATE,        
          ]);
      });
      // ini_set('memory_limit', '512M');
      return datatables($query->orderByDesc('updated_at')->get())
      ->editColumn('code', function ($row) {
          return 'CDT-' . $row->department->slug . str_pad($row->id, 5, '0', STR_PAD_LEFT);
      })
      ->editColumn('email', function ($row) {
          return '<a href="mailto:' . $row->email . '" target="_blank">' . $row->email . '</a>';
      })
      ->editColumn('gender', function ($row) {
          return $row->gender_name;
      })
      ->editColumn('department_name', function ($row) {
          return $row->department->name;
      })
      ->editColumn('job_category_name', function ($row) {
          return $row->job_category_name;
      })
      ->editColumn('phone', function ($row) {
          if (($row->phone != '-')) {
              return '<a href="https://api.whatsapp.com/send?phone=' . $row->phone_code . $row->phone . '" target="_blank">' . $row->phone_code . $row->phone . '</a>';
          }
          return trans('translation.no-data');
      })
      ->editColumn('years_of_experience_name', function ($row) {
          return $row->years_of_experience_name ?? trans('translation.no-data');
      })
      ->editColumn('national_id', function ($row) {
          return $row->national_id ?? trans('translation.no-data');
      })
      ->editColumn('birthdate', function ($row) {
          return $row->birthdate ?? trans('translation.no-data');
      })
      ->editColumn('birthdate_hj', function ($row) {
          return $row->birthdate_hj ?? trans('translation.no-data');
      })
      ->editColumn('address', function ($row) {
          return $row->address ?? trans('translation.no-data');
      })
      ->editColumn('previously_work_at_rakaya', function ($row) {
          return $row->previously_work_at_rakaya ?? trans('translation.no-data');
      })
      ->editColumn('has_relative', function ($row) {
          return $row->has_relative ?? trans('translation.no-data');
      })
      ->editColumn('status', function ($row) {
          return "<span class='badge ' style='background:" . $row->status->color . "' >" . $row->status->name . "</span>";
      })
      ->editColumn('qualification_name', function ($row) {
          return $row->qualification_name ?? trans('translation.no-data');
      })
      ->editColumn('resident_status_name', function ($row) {
          return $row->resident_status_name ?? trans('translation.no-data');
      })
      ->editColumn('marital_status_name', function ($row) {
          return $row->marital_status_name ?? trans('translation.no-data');
      })
      ->editColumn('bank_name', function ($row) {
          return $row->bank_name ?? trans('translation.no-data');
      })
      ->editColumn('iban_number', function ($row) {
          return $row->iban_number ?? trans('translation.no-data');
      })
      ->editColumn('account_name', function ($row) {
          return $row->account_name ?? trans('translation.no-data');
      })
      ->editColumn('owner_national_id', function ($row) {
          return $row->owner_national_id ?? trans('translation.no-data');
      })
      ->editColumn('candidate_cv_attachment_url', function ($row) {
          return $row->candidate_cv_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_profile_personal_attachment_url', function ($row) {
          return $row->candidate_profile_personal_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_portfolio_attachment_url', function ($row) {
          return $row->candidate_portfolio_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_education_certificate_attachment_url', function ($row) {
          return $row->candidate_education_certificate_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_course_certificate_attachment_url', function ($row) {
          return $row->candidate_course_certificate_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_experience_certificate_attachment_url', function ($row) {
          return $row->candidate_experience_certificate_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_cv_en_attachment_url', function ($row) {
          return $row->candidate_cv_en_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_passport_attachment_url', function ($row) {
          return $row->candidate_passport_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_driving_license_attachment_url', function ($row) {
          return $row->candidate_driving_license_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_national_address_attachment_url', function ($row) {
          return $row->candidate_national_address_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_iban_attachment_url', function ($row) {
          return $row->candidate_iban_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('candidate_national_id_attachment_url', function ($row) {
          return $row->candidate_national_id_attachment_url ?? trans('translation.no-data');
      })
      ->editColumn('nationality_name', function ($row) {
          return $row->nationality_name ?? trans('translation.no-data');
      })
      ->editColumn('scrub_size_name', function ($row) {
          return $row->scrub_size_name ?? trans('translation.no-data');
      })
      ->editColumn('created_at', function ($row) {
          if ($row->created_at != null) {
              return $row->created_at->diffForHumans();
          }
          return '';
      })
      ->editColumn('updated_at', function ($row) {
          if ($row->updated_at != null) {
              return $row->updated_at->diffForHumans();
          }
          return '';
      })
      ->addColumn('action', function (Candidate $candidate) {
          return '<a class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5" href="' . (route('candidates.show', $candidate->id)) . '" target="_blank" ><i class="mdi mdi-eye"></i></a>';
      })
      ->rawColumns(['phone', 'action', 'status', 'email', 'code'])
      ->toJson();
    }
    */

    //??================================================================
    public function sendMessage(Request $request)
    {
        $candidate = Candidate::findOrFail($request->candidate_id);
        if ($candidate->status_id != Status::APPROVED_CANDIDATE) {
            return response()->json(['message' => trans('translation.wrong-status')], 400);
        }
        $messageKey = 'send-whatsapp-approved-candidate';
        $message = trans("translation.$messageKey", ['code' => $candidate->code, 'name' => $candidate->name, 'uuid' => $candidate->uuid]);
        $sending_whatsapp = $this->send_message(null, $message, $candidate->phone_code . $candidate->phone);
        // $sending_sms = $this->send_sms(null, $message, $candidate->phone, $candidate->phone_code);
        $candidate->update(['status_id' => Status::AWAITING_DATA_COMPLETION_CANDIDATE]);
        // return response()->json(['message' => trans('translation.Updated successfully'), 'sending_whatsapp' => $sending_whatsapp, 'sending_sms' => $sending_sms], 200);
        return response()->json(['message' => trans('translation.Updated successfully'), 'sending_whatsapp' => $sending_whatsapp], 200);
    }
    //??================================================================
    public function CloneCandidate(Request $request)
    {
        $candidate = Candidate::findOrFail($request->candidate_id);
        if ($candidate->status_id != Status::COMPLETED_DATA_CANDIDATE && $candidate->is_cloned) {
            return response()->json(['message' => trans('translation.wrong-status')], 400);
        }

        $organization = Organization::find(3);
        $existed_user = User::where('email', $candidate->email)
            ->orWhere('phone', $candidate->phone)
            ->orWhere('national_id', $candidate->national_id)
            ->first();

        if ($existed_user) {
            return response()->json(['message' => trans('translation.user-exist')], 400);
        }
        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $candidate->name,
                'email' => $candidate->email,
                'phone' => $candidate->phone,
                'phone_code' => '+'.$candidate->phone_code,
                'nationality' => $candidate->nationality,
                'national_id' => $candidate->national_id,
                'national_id_expired' => '1111-11-11',
                'birthday' => $candidate->birthdate,
                'birthday_hj' => $candidate->birthdate_hj,
                'national_source' => 1,
                'salary' => $candidate->salary_expectation,
                'address' => $candidate->address,
                'scrub_size' => $candidate->scrub_size,
                'organization_id' => $organization->id,
            ]);

            if($candidate->iban){
                $user->iban()->create($candidate->iban->toArray());
                $this->replicateAttachment($candidate->attachment_candidate_iban, $user->id, AttachmentLabel::USER_IBAN_LABEL);
            }
            $this->replicateAttachment($candidate->attachment_candidate_profile_personal, $user->id, AttachmentLabel::PROFILE_PHOTO_LABEL);
            $this->replicateAttachment($candidate->attachment_candidate_national_id, $user->id, AttachmentLabel::NATIONAL_ID_LABEL);

            $candidate->update(['is_cloned' => '1']);

            DB::commit();
            return response()->json(['message' => trans('translation.cloned successfully')], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => trans('translation.something went wrong')], 500);
        }
    }
    //??================================================================
    public function changeStatus()
    {
        //!! need to validation rule request

        $candidate = Candidate::findOrFail(request()->candidate_id);
        $candidate->update(['status_id' => request()->status_id]);
        if(request()->status_id == Status::REJECTED_CANDIDATE ){
            $message = trans("translation.rejecting-candidate-message", ['name' => $candidate->name ?? '', ]);
            $sending_whatsapp = $this->send_message(null, $message, $candidate->phone_code . $candidate->phone);

        }
        $message = "status changed succesfully";

        return response(compact('message'), 200);
    }
    //??================================================================
    public function show(Candidate $candidate)
    {
        $this->all_columns = true;
        $statuses = Status::candidate_statuses()->get();
        $remaining_attachments = AttachmentLabel::where('type', 'candidates')->whereNotIn('id', $candidate->attachments()->pluck('attachment_label_id')->toArray())->get();

        return view('admin.candidates.show', compact('candidate', 'statuses', 'remaining_attachments'));
    }
    //??================================================================
    public function checkRelatives($delete_model)
    {
        //
    }
    //??================================================================
    function replicateAttachment($attachment, $attachmentable_id, $labelId) {
        if ($attachment) {
            $newAttachment = $attachment->replicate();
            $newAttachment->save();
            $newAttachment->update([
                'attachmentable_id' => $attachmentable_id,
                'attachmentable_type' => "App\Models\User",
                'attachment_label_id' => $labelId,
            ]);
        }
    }
}
