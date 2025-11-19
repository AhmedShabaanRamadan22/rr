<?php

namespace App\Http\Controllers\Admin;

use App\Models\Option;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Traits\CrudOperationTrait;
use App\Models\OrganizationService;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionController extends Controller
{

    use SoftDeletes;
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function store(Request $request)
    {
        // dd($request->all());
        $questionable_id = $request->questionableId;
        $questionable_type = 'App\Models\\' . $request->questionableType;
        $q = $questionable_type::find($questionable_id);
        // dd($q->questions()->count());

        $question = $q->questions()->create(
            $request->only(['question_bank_organization_id', 'is_required', 'is_visible']) + [
                'arrangement' => $q->questions()->count() + 1,
            ]
        );
        if ($request->has('options')) {
            foreach ($request->options as $i => $option) {
                Option::create([
                    'content' => $option,
                    'question_id' => $question->id,
                ]);
            }
        }
        return back()->with(['message' => trans('translation.Added successfully'), 'alert-type' => 'success']);
    }
    //??=========================================================================================================
    public function update(Request $request, Question $question)
    {
        $question = Question::findOrFail(request()->question_id);
        // $question->update(request()->all());
        // return;
        // dd($request->old_options);
        if ($request->has('old_options')) {
            $old_options_ids = collect($request->old_options)->pluck(0)->toArray();
            foreach($question->options as $option){
                if(in_array($option->id, $old_options_ids)){
                    $index = array_search($option->id, $old_options_ids);
                    $option->update(['content' => $request->old_options[$index][1]]);
                }
                else{
                    $option->delete();
                }
            }
        }

        if($request->has('new_options')){
            foreach($request->new_options as $option){
                Option::create(['content'=>$option[1], 'question_id'=> $request->question_id]);
            }
        }

        $question->update(['is_required' => $request->is_required, 'is_visible' => $request->is_visible]);

        return response()->json(['question' => $question->load('options'), 'message' => 'Updated successfully'], 200);
    }
    //??=========================================================================================================
    public function destroy(Question $question)
    {
        if ($question->answers->isNotEmpty()) {
            return response(['message' => 'Question has answers, it cannot be deleted!'], 400);
        }

        $question->delete();
        return response()->json(['message' => "Deleted successfully"], 200);
    }
    //??=========================================================================================================
    public function setOrder($question, $count)
    {
        $question->update(['arrangement' => $count]);
    }
    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $query = $this->model::with(
            'question_bank_organization:id,description,question_bank_id',
            'question_bank_organization.question_bank:id,content,question_type_id',
            'question_bank_organization.question_bank.question_type:id,name',
            'options',
        );
        if (\request('question_id')) {
            $query->where(['questionable_id'=> \request('question_id'), 'questionable_type'=> 'App\\Models\\'.\request('question_type')]);
        }

        return datatables($query->orderBy('arrangement')->get())
            ->addColumn('description', function ($row) {
                return $row->question_bank_organization->description ?? trans('translation.no-data');
            })
            ->addColumn('content', function ($row) {
                return $row->question_bank_organization->question_bank->content ?? '-';
            })
            ->addColumn('question_type_name', function ($row) {
                return $row->question_bank_organization->question_bank->question_type->QuestionTypeName ?? '-';
            })
            ->addColumn('icon_is_visible', function ($row) {
                return '<i class="' . ($row->is_visible == 1 ? 'ri-check-fill text-success icon-bigger' : ($row->is_visible == 0 ? 'ri-close-fill text-danger icon-bigger': 'ri-record-circle-fill text-muted icon-bigger')) . '"></i>';
            })
            ->addColumn('icon_is_required', function ($row) {
                return '<i class="' . ($row->is_required == 1 ? 'ri-check-fill text-success icon-bigger' : ($row->is_required == 0 ? 'ri-close-fill text-danger icon-bigger' : 'ri-record-circle-fill text-muted icon-bigger')) . ' "></i>';
            })
            ->addColumn('actions', function ($row) {
                // <a href="{{url('services/edit')}}" class="btn btn-secondary mx-1 " data-service-name="{{$service->name}}" data-service-price="{{$service->price}}" data-service-id="{{$service->id}}" data-bs-target="#editService" data-bs-toggle="modal">{{trans('translation.edit')}}</a>
                return '<div class="d-flex justify-content-center">
                <a href="'.route(($this->table_name).'.edit',$row->id).'" class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 " data-question-id="' . $row->id .'" data-bs-target="#editQuestion" data-bs-toggle="modal">
                <i class="mdi mdi-square-edit-outline"></i>
            </a>
             <button
          class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete-question-btn" question-id="' . $row->id . '">
              <i class="mdi mdi-delete"></i>
          </button>
      </div>';
            })
            ->rawColumns(['actions', 'icon_is_visible', 'icon_is_required'])
            ->toJson();
    }
    //??=========================================================================================================
    public function checkRelatives($delete_model)
    {
        if (
            $delete_model->options->isNotEmpty()
        ) {
            return trans('translation.delete-relative-first');
        }
        return '';
    }
    public function sort(Request $request)
    {
        $items = explode(',',$request->items);
        foreach ($items as $key => $item) {
            $question = $this->model::find($item);
            $question->update([
                'arrangement' => $key+1
            ]);
        }

        return back()->with(['message'=> trans('translation.rearrangment successfully'),'alert-type'=>'success']);
    }
}
