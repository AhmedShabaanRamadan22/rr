<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\CrudOperationTrait;
use App\Models\QuestionBankOrganization;

class QuestionBankOrganizationController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with(
            'question_bank:id,content,question_type_id',
            'question_bank.question_type:id,name'
        )->orderByDesc('created_at');
        if (\request('organization_id')) {
            $query->where('organization_id', \request('organization_id'));
        }

        return datatables($query->get())
            ->editColumn('question_bank.content', function (QuestionBankOrganization $question) {
                // dd($this->table_name);
                return $question->question_bank->content ?? '-';
            })
            ->editColumn('question_bank.question_type.name', function (QuestionBankOrganization $question) {
                return $question->question_bank->question_type->question_type_name ?? '-';
            })
            ->addColumn('description', function ($row) {
                return $row->description != null ? '<div class="data-description">' . $row->description . '</div>' : '-';
            })
            ->addColumn('is_visible', function ($row) {
                return '<i class="' . ($row->is_visible == 1 ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger') . ' data-visible"></i>';
            })
            ->addColumn('is_required', function ($row) {
                return '<i class="' . ($row->is_required == 1 ? 'ri-check-fill text-success icon-bigger' : 'ri-close-fill text-danger icon-bigger') . ' data-required"></i>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center">
                <a href="'.route(str_replace('_','-',($this->table_name)).'.edit',$row->id).'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 " data-question-bank-organization-id="{{$row->id}}" data-bs-target="#editQuestionBankOrg" data-bs-toggle="modal">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>
              <button
              class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete-button" data-question-id="' . $row->id . '">
                  <i class="mdi mdi-delete"></i>
              </button>
          </div>';
            })
            ->rawColumns(['action', 'is_visible', 'is_required', 'description'])
            ->toJson();
    }
    //??=========================================================================================================
    //! convert to be use return_update_response()
    public function update(Request $request,$id)
    {
        $model_item = $this->model::find($id);
        $new_model = $model_item->update($request->only($this->model->getFillable()));
        if(method_exists($model_item, 'linkRelative')){
            $linkRelative = $model_item->linkRelative($request);
        }
        return response(['message'=>trans('translation.Updated successfully')],200);
    }
    //??=========================================================================================================
    public function custom_validates($request)
    {
        $exists = QuestionBankOrganization::where(['organization_id' => $request->organization_id ,'question_bank_id' => $request->question_bank_id])->exists();
        if($exists){
            return trans('translation.question-has-already-exists');
        }
        return null;
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        // dd($delete_model->questions->isNotEmpty());
        if ($delete_model->questions->isNotEmpty()) {
            return trans('translation.cannot delete, this question is used in form');
        }
        return '';
    }
}
