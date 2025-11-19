<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuestionResource;
use App\Models\Meal;
use App\Models\MealOrganizationStage;
use App\Models\Question;
use App\Models\Status;
use App\Traits\AttachmentTrait;
use App\Traits\LocationTrackerTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MealOrganizationStageController extends Controller
{
    use AttachmentTrait, LocationTrackerTrait;

    public function questions($meal_organization_stage_id)
    {
        $organization_stage = MealOrganizationStage::find($meal_organization_stage_id)->organization_stage;
        $questions = $organization_stage->visible_questions;
        return QuestionResource::collection($questions);
    }

    public function answers(Request $request, $meal_organization_stage_id)
    {
        $meal_organization_stage = MealOrganizationStage::find($meal_organization_stage_id);
        
        if (count($meal_organization_stage->answers) > 0) {
            return response()->json(['message' => trans('translation.Already Answered')], 400);
        }

        if (request()->answers) {
            $extraKeys = array_diff(collect(request()->answers)->keys()->toArray(), $meal_organization_stage->organization_stage->questions->pluck('id')->toArray());
            foreach (request()->answers as $questionId => $answer) {
                if (!in_array($questionId, $extraKeys)) {
                    $answer_record = $meal_organization_stage->answers()->create([
                        'user_id' => auth()->user()->id,
                        'question_id' => $questionId,
                        'value' => is_array($answer) ? json_encode($answer) : $answer,
                    ]);
                    if ($request->hasFile("answers.{$questionId}")) {
                        $answer_record->update(['value' => 'not-answered']);
                        //to store file answer
                        $fileRequest = $request->file("answers.{$questionId}");
                        $attachments = $this->storeAnswerFile($fileRequest, $answer, $answer_record);
                        // If it's a single file, use the attachment_id; if multiple files, store an array of attachment_ids
                        $answer_record->update(['value' => $attachments]);
                    }
                }

            }
        }


        $meal_organization_stage->update([
            'status_id' => Status::DONE_MEAL_STAGE,
            'done_at' => Carbon::now(),
            'done_by' => auth()->user()->id,
        ]);

        $this->update_next_stage_status($meal_organization_stage->meal);



        $action = trans('translation.meal stage is done');
        $this->tracker($request, $meal_organization_stage, $action);
        return response()->json(['message' => trans('translation.Answer submitted successfully')], 200);
    }

    public function update_next_stage_status(Meal $meal){

        $next = MealOrganizationStage::where('meal_id',$meal->id)->whereNull('done_at')->first();
        if ($next) {
            $next->update([
                'status_id' => Status::OPENED_MEAL_STAGE
            ]);
        } else {
            $meal->update([
                'status_id' => Status::DONE_MEAL
            ]);
        }

    }
}
