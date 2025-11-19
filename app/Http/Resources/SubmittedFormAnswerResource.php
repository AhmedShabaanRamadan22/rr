<?php

namespace App\Http\Resources;

use App\Services\AnswerService;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmittedFormAnswerResource extends JsonResource
{
    protected $submittedForm;

    public function __construct($resource, $submittedForm)
    {
        parent::__construct($resource);
        $this->submittedForm = $submittedForm;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'section' => [
                'id' => $this->id,
                'name' => $this->name,
            ],
            'submissions' => [
                [
                    'user_name' => $this->submittedForm->user ? $this->submittedForm->user->name : null,
                    'user_phone' => $this->submittedForm->user ? ($this->submittedForm->user->phone_code . $this->submittedForm->user->phone) : null,
                    'submitted_at' => $this->submittedForm->created_at,
                    'answers' => $this->getFormattedQuestions($this->submittedForm->id),
                    'user' => $this->submittedForm->user ? new UserResource($this->submittedForm->user) : null,
                ]
            ],
        ];
    }

    /**
     * Get formatted questions with answers.
     *
     * @param int $submittedFormId
     * @return mixed
     */
    protected function getFormattedQuestions($submittedFormId)
    {
        $questions = $this->answered_questions($submittedFormId)->get();

        if ($questions->isEmpty()) {
            return __('section.no_answers_yet');
        }

        $questionIds = $questions->pluck('id');
        $answers = \App\Models\Answer::where([
            ['answerable_id', $submittedFormId],
            ['answerable_type', 'App\Models\SubmittedForm'],
        ])->whereIn('question_id', $questionIds)
            ->get()
            ->keyBy('question_id');

        return $questions->map(function ($question) use ($submittedFormId, $answers) {
            $question->setRelation('answer', $question->answer($submittedFormId));
            return $this->formatQuestionWithAnswer($question, $submittedFormId, $answers);
        })->all();
    }

    /**
     * Format a question with its answer.
     *
     * @param \App\Models\Question $question
     * @param int $submittedFormId
     * @param \Illuminate\Support\Collection $answers
     * @return array
     */
    protected function formatQuestionWithAnswer($question, $submittedFormId, $answers)
    {
        $answer = $answers->get($question->id) ?? $question->answer($submittedFormId)->first();

        $answerService = new AnswerService;

        return [
            'question' => $question->content,
            'answer' => $answer ? $answerService->generateAnswerValue($answer, $question) : null,
        ];
    }
}
