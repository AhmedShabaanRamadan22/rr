<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractTemplate;
use App\Models\OrganizationService;
use App\Models\Order;
use App\Models\Organization;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Regex;
use App\Models\Service;
use App\Traits\OrganizationTrait;
use Illuminate\Http\Request;

class OrganizationServiceController extends Controller
{

    use OrganizationTrait;

    public function show(Organization $organization, Service $service)
    {
        $organization_service = OrganizationService::where(['service_id' => $service->id, 'organization_id' => $organization->id])->first();
        $columns = Question::columnNames();

        return view('admin.organization_service.index', compact('organization_service'));
    }
    //??=========================================================================================================
    public function store(Request $request)
    {
        // dd($request);
        $organization_service = OrganizationService::create($request->only('organization_id', 'service_id'));
        return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success'));
    }
    //??=========================================================================================================
    public function destroy(OrganizationService $organization_service)
    {

        if ($organization_service->questions->isNotEmpty()) {
            return response(['message' => trans('translation.There are questions related to this organization service. Please delete them first!')], 400);
        }
        if ($organization_service->orders->isNotEmpty()) {
            return response(['message' => trans('translation.related-orders')], 400);
        }
        if ($organization_service->has_contract_template) {
            if ($organization_service->contracts()->isNotEmpty()) {
                return response(['message' => trans('translation.related-contracts')], 400);
            }
            $organization_service->contract_template()->delete();
        }
        $organization_service->delete();
        return response()->json(['message' => "Deleted successfully"], 200);
    }
    //??=========================================================================================================
    public function datatable(Request $request, $organization_service_id)
    {

        $query = Question::with('options', 'regex');
        $query->whereHas('organization_service', function ($q) use ($organization_service_id) {
            $q->where('id', $organization_service_id);
        });

        return datatables($query->orderByDesc('created_at')->get())

            ->editColumn('regex', function (Question $question) {
                return $question->regex->name ?? "No regex";
            })
            ->editColumn('question_type_id', function (Question $question) {
                return $question->question_type->name ?? "-";
            })
            ->editColumn('actions', function (Question $question) {
                return
                    '<div class="d-flex justify-content-center">
                        <button
                        class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 edit-button"
                        data-bs-target="#editQuestion"
                        data-bs-toggle="modal"
                        data-original-title="Edit"
                        data-question-id="' . $question->id . '">
                            <i class="mdi mdi-square-edit-outline"></i>
                        </button>

                        <button
                        class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete-question-btn" data-question-id="' . $question->id . '">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }
}
