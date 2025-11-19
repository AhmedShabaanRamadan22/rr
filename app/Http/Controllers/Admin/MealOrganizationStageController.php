<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\MOSQuestionAnswerResource;
use App\Services\AnswerService;
use App\Services\MealOrganizationStageService;
use App\Models\Answer;
use App\Models\Status;
use Illuminate\Http\Request;
use App\Traits\CrudOperationTrait;
use App\Http\Controllers\Controller;
use App\Models\MealOrganizationStage;

class MealOrganizationStageController extends Controller
{
    use CrudOperationTrait;

    public function __construct()
    {
        $this->set_model($this::class);
    }
    //??=========================================================================================================
    public function questions($meal_organization_stage_id)
    {
        $mosService = new MealOrganizationStageService($meal_organization_stage_id);

        $meal_organization_stage = $mosService->get_model();

        // Extract the questions from the answers
        $questions = $mosService->get_mos_questions();

        return view('admin.meals.questions', compact('meal_organization_stage', 'questions'));
    }

    public function questions_and_answers($meal_organization_stage_id)
    {
        $mosService = new MealOrganizationStageService($meal_organization_stage_id);
        $answerService = new AnswerService();

        $answers = $mosService->get_mos_answers()->map(function ($answer) use ($answerService) {
            return [
                'answer' => $answer ? $answerService->generateAnswerValue($answer, $answer->question) : '—',
                'question' => $answer->question->question_bank_organization->question_bank->content,
            ];
        });

        return response()->json(['html' => view('components.dashboard.stage-answers', [
            'answers' => $answers,
            'stage' => $mosService->get_model()->organization_stage->stage_bank->name,
        ])->render()]);
    }

    //??=========================================================================================================
    public function dataTable(Request $request)
    {
        $organization_id = $request->input('organization_id');
        $meal_id = $request->input('meal_id');
        $done_meal_stage = Status::DONE_MEAL_STAGE;
        $query = $this->model::with(
            'organization_stage.stage_bank:id,name,duration',
            'organization_stage:id,stage_bank_id,arrangement',
            'status:id,name_ar,name_en,color',
            'user:id,name',
            'meal.food_weight_meals:id,food_weight_id,meal_id',
            'meal.food_weight_meals.food_weight.food:id,name',
        )->where('meal_id', $meal_id)->whereHas('organization_stage', function ($q) use ($organization_id) {
            $q->where('organization_id', $organization_id);
        });
        return datatables($query->orderBy('arrangement')->get())

            ->editColumn('arrangement', function ($row) {
                return $row->organization_stage->arrangement ?? '-';
            })

            ->editColumn('stage', function ($row) {
                return $row->organization_stage->stage_bank->name ?? '-';
            })
            ->addColumn('duration', function ($row) {
                return ($row->duration ?? $row->organization_stage->stage_bank->duration) . ' ' . trans('translation.minutes');
            })

            ->editColumn('actual_duration', function ($row) {
                if ($row->status_id != Status::CLOSED_MEAL_STAGE && !is_null($row->calculate_duration())) {

                    if ($row->passed_actual_duration){
                        //red badge
                        $html = '<span class="badge bg-danger mx-1 mb-2">' . $row->actual_duration->forHumans() . '</span>';
                    } else {
                        //green badge
                        $html = '<span class="badge bg-success mx-1 mb-2">' . $row->actual_duration->forHumans() . '</span>';
                    }
                    return $html;
                } else {
                    return '-';
                }
            })

            ->editColumn('done_at', function ($row) {
                return $row->done_at ?? '-';
            })

            ->editColumn('done_by', function ($row) {
                return $row->user->name ?? '-';
            })

            ->editColumn('food-name', function ($row) {

                if ($row->meal->food_weight_meals->count() < 1) {
                    return trans('translation.no-food');
                }
                $html = '';
                $i = 1;
                foreach ($meals = $row->meal->food_weight_meals as $food_weight_meal) {
                    if(!isset($food_weight_meal->food_weight->food->name)){
                        continue;
                    }
                    $html .= '<span class="badge bg-primary mx-1 mb-2">' . $food_weight_meal->food_weight->food->name . '</span>';
                    if($i < $meals->count()){
                        $html .= ' | ';
                    }
                    if ($i++ % 3 == 0) {
                        $html .= '<br>';
                    }
                }
                return $html;
            })

            ->editColumn('status', function ($row) {
                $html = "<span class='badge ' style='background:" . $row->status->color . "' >" . $row->status->name . "</span>";
                return $html;
            })

            ->editColumn('done_by', function ($row) {
                return $row->user->name ?? '-';
            })
            ->addColumn('action', function ($row) use ($done_meal_stage) {
                // TODO Meal Org Stage Questions and Answers
                return $row->status_id == $done_meal_stage
                    ? '<div class="d-flex justify-content-center">
                           <a href="' . route('meal-organization-stages.questions', $row->id) . '" class="btn btn-outline-secondary btn-sm m-1 on-default m-r-5">
                               <i class="mdi mdi-eye"></i>
                           </a>
                       </div>'
                    : '-';
            })
            ->rawColumns(['food-name', 'status', 'action', 'actual_duration'])
            ->toJson();
    }
}
