<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubmittedFormAnswerCollection;
use Exception;
use Illuminate\Http\Request;
use App\Models\SubmittedForm;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SectorRequest;
use App\Http\Requests\SubmittedFormAnswerRequest;
use App\Http\Resources\SubmittedFormAnswerResource;
use App\Http\Resources\SubmittedFormResource;

class SubmittedFormController extends Controller
{

    public function submitted_form() //show
    {
        $submitted_form = SubmittedForm::where('id', request()->id)
            ->with(['form.sections_has_question.visible_questions' => function ($q) {
                $q->submittedForm_id = request()->id;
                $q->with(['answers' => function ($q) {
                    $q->where('answerable_id', request()->id);
                }]);
            }])->first();
        return response()->json(['submitted_form' => new SubmittedFormResource($submitted_form)], 200);
    }

    public function all_submitted_forms(SectorRequest $request) //index
    {
        $submitted_forms = SubmittedForm::where('order_sector_id', $request->order_sector_id)
        ->orderBy('created_at', 'desc')
        ->get()
        ->where('is_completed', true);
        //this is because im paginating after getting the collection(bc i want to filter based on appends) whereas paginates works only on the builder not collection
        $paginated_forms = new \Illuminate\Pagination\LengthAwarePaginator(
            $submitted_forms->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), $request->per_page ?? 5),
            $submitted_forms->count(),
            $request->per_page ?? 5
        );

        foreach ($paginated_forms as $submitted_form) {
            $submitted_form->load([
                'user',
                'form' => [
                    'sections.questions',
                    'submitted_forms' => [
                        'user',
                        'form.sections_has_question',
                        'submitted_sections',
                    ],
                    'sections_has_question' => [
                        'form.submitted_forms.form.sections_has_question',
                        'visible_questions' => [
                            'question_bank_organization.question_bank.question_type',
                            'answers' => function ($q) use ($submitted_form) {
                                $q->where([['answerable_id', $submitted_form->id], ['answerable_type', 'App\Models\SubmittedForm']]);
                            },
                        ]
                    ]
                ],

            ]);
        }
        // $questions = $submitted_forms->load('form.sections_has_question.visible_questions')->flatten()->pluck('form.sections_has_question')->flatten()->pluck('visible_questions')->flatten()->pluck('content', 'id');
        // $questions =  $submitted_forms
        // ->load('form.sections_has_question.visible_questions')
        // ->flatMap(function ($form) {
        //     return $form->form->sections_has_question->flatMap(function ($sectionQuestion) {
        //         return $sectionQuestion->visible_questions->select('id', 'content');
        //     });
        // });
        // $submitted_forms_ids = $submitted_form->form->submitted_forms->pluck('id')->toArray();
        // $answers = $submitted_form->load(['form.sections_has_question.visible_questions.answers' => function ($q) use ($submitted_forms_ids) {
        //     $q->whereIn('answerable_id', $submitted_forms_ids)->where('answerable_type', 'App\Models\SubmittedForm');
        // }]);
        // $answers = $submitted_form->form->sections_has_question->flatMap(function ($sectionQuestion) {
        //     return $sectionQuestion->visible_questions->flatMap(function ($question) {
        //         return $question->answers->select('id', 'question_id', 'actual_value', 'answerable_id');
        //     });
        // });

        return response()->json([
            'all_submitted_forms' => SubmittedFormResource::collection($paginated_forms),
            'pages' => $paginated_forms->lastPage()
        ], 200);
    }


    public function cascadeDelete($submitted_form_id)
    {
        // dd('enterd');
        try {
            DB::beginTransaction();

            $submitted_form = SubmittedForm::find($submitted_form_id);
            if (is_null($submitted_form)) {
                return response()->json(['message' => trans('translation.something went wrong')], 400);
            }

            $submitted_form->submitted_sections()->each(function ($section) {
                $section->track_locations()->delete();
            });
            $submitted_form->submitted_sections()->delete();
            $submitted_form->answers()->each(function ($answer) {
                $answer->attachments()->delete();
            });
            $submitted_form->answers()->delete();
            $submitted_form->delete();

            DB::commit();

            return response()->json(['message' => trans('translation.deleted-successfully')], 400);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => trans('translation.delete-failed')], 500);
        }
    }
    public function hardDelete($submitted_form_id)
    {
        try {
            DB::beginTransaction();
            $submitted_form = SubmittedForm::withTrashed()->where('id', $submitted_form_id)->first();

            if (is_null($submitted_form)) {
                return response()->json(['message' => trans('translation.something went wrong')], 400);
            }

            $submitted_form->submitted_sections()->withTrashed()->each(function ($section) {
                $section->track_locations()->withTrashed()->forceDelete();
            });
            $submitted_form->submitted_sections()->withTrashed()->forceDelete();
            $submitted_form->answers()->withTrashed()->each(function ($answer) {
                $answer->attachments()->withTrashed()->forceDelete();
            });
            $submitted_form->answers()->withTrashed()->forceDelete();
            $submitted_form->forceDelete();

            DB::commit();

            return response()->json(['message' => trans('translation.deleted-successfully')], 400);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => trans('translation.delete-failed')], 500);
        }
    }
    public function submittedFormAnswers(SubmittedFormAnswerRequest $request)
    {
        $expectedKey = config('services.api_key');
        $providedKey = $request->header('X-API-KEY');

        if ($providedKey !== $expectedKey) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $submittedForm = SubmittedForm::with([
            'form.sections.questions',
            'user'
        ])->findOrFail($request->submitted_form_id);

        return [
            'data' => new SubmittedFormAnswerCollection($submittedForm->form->sections, $submittedForm)
        ];
    }
}
