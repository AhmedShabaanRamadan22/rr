<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectorRequest;
use App\Http\Resources\QuestionResource;
use App\Models\Assist;
use App\Models\AssistQuestion;
use App\Models\OrderSector;
use App\Models\Organization;
use App\Models\Status;
use App\Models\User;
use App\Notifications\CrudNotify;
use App\Traits\AttachmentTrait;
use App\Traits\LocationTrackerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class AssistQuestionController extends Controller
{
    use AttachmentTrait, LocationTrackerTrait;

    public function questions(SectorRequest $request, $organization_id = null)
    {
        $order_sector = OrderSector::findOrFail($request->order_sector_id);
        $organization_id = $organization_id ?? $order_sector?->sector?->classification?->organization_id;
        $organization = Organization::findOrFail($organization_id);
        $questions = $organization->assist_question->visible_questions;
        $questions->load('question_bank_organization.question_bank.question_type');
        return QuestionResource::collection($questions);
    }

    public function answers(SectorRequest $sectorRequest, $assist_id)
    {
        $assist = Assist::findOrFail($assist_id);
        // $assist = Assist::find(request()->assist_id);

        if ($assist->status_id == Status::DELIVERED_ASSIST) {
            return response()->json(['message' => trans('translation.Assist already submitted')], 400);
        }
        if ($assist->status_id == Status::CANCELED_ASSIST) {
            return response()->json(['message' => trans('translation.Assist has been canceled')], 400);
        }

        if (count($assist->answers) > 0) {
            return response()->json(['message' => trans('translation.Already Answered')], 400);
        }


        $assist_questions = $assist->support->order_sector->sector->classification->organization->assist_question ?? null;




        if (!(request()->answers && $assist_questions && $assist_questions->questions)) {
            return response()->json(['message' => trans('translation.no-answers-or-questions')], 400);
        }
        $extraKeys = array_diff(collect(request()->answers)->keys()->toArray(), $assist_questions->questions->pluck('id')->toArray());
        foreach (request()->answers as $questionId => $answer) {
            if (!in_array($questionId, $extraKeys)) {
                $answer_record = $assist->answers()->create([
                    'user_id' => auth()->user()->id,
                    'question_id' => $questionId,
                    'value' => is_array($answer) ? json_encode($answer) : $answer,
                ]);
                if (request()->hasFile("answers.{$questionId}")) {
                    $answer_record->update(['value' => 'not-answered']);
                    //to store file answer
                    $fileRequest = request()->file("answers.{$questionId}");
                    $attachments = $this->storeAnswerFile($fileRequest, $answer, $answer_record);
                    // If it's a single file, use the attachment_id; if multiple files, store an array of attachment_ids
                    $answer_record->update(['value' => $attachments]);
                }
            }
        }


        $assist->update([
            'status_id' => Status::DELIVERED_ASSIST,
        ]);
        $support = $assist->support;
        $this->notifySupportUser($support, $assist);

        if ($support->status_id == Status::HAS_ENOUGH_SUPPORT && $support->assigned_quantity == $support->deliverd_quantity) {
            $support->update([
                'status_id' => Status::CLOSED_SUPPORT,
            ]);
            $this->notifySupportUser($support, $support);
            // User::find($support->user->id)->notify(new CrudNotify($support, 'changeStatus'));
        } elseif ($support->delivered_quantity == $support->quantity) {
            $support->update([
                'status_id' => Status::CLOSED_SUPPORT,
            ]);
            $this->notifySupportUser($support, $support);
            // User::find($support->user->id)->notify(new CrudNotify($support, 'changeStatus'));
        }

        $action = trans('translation.Submit assist for support: ') . $support->code;
        $this->tracker($sectorRequest, $assist, $action);
        return response()->json(
            [
                'message' => trans('translation.Assist submitted successfully'),
                'assist' => $assist
            ],
            200
        );
    }

    public function notifySupportUser($support, $notifable)
    {
        $user = User::find($support->user->id ?? 0);
        if ($user) $user->notify(new CrudNotify($notifable, 'changeStatus'));
    }
}
