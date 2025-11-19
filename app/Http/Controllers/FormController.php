<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Form;
use App\Models\Section;
use App\Models\Question;
use App\Models\OrderSector;
use App\Models\QuestionBank;
use Illuminate\Http\Request;
use App\Models\SubmittedForm;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\AttachmentLabel;
use App\Traits\AttachmentTrait;
use App\Models\SubmittedSection;
use App\Traits\OrganizationTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SectorRequest;
use App\Http\Resources\FormResource;
use App\Models\OrganizationCategory;
use App\Traits\LocationTrackerTrait;
use App\Models\QuestionBankOrganization;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\Resources\SubmittedFormResource;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\SubmittedFormCollection;
use App\Http\Resources\OrganizationCategoryResource;

class FormController extends Controller
{
    use OrganizationTrait, AttachmentTrait, LocationTrackerTrait;
    public function forms($type) //change to index
    {
        $forms = Form::formsType($type)
            ->with([
                'sections.questions',
                'submitted_forms' => [
                    'user',
                    'form.sections_has_question',
                    'submitted_sections',
                ],
                'sections_has_question' => [
                    'form.submitted_forms.form.sections_has_question',
                    'visible_questions.question_bank_organization.question_bank.question_type'
                ]
            ])
            ->get()
            ->where('null_section', '0');
        //this is because im paginating after getting the collection(bc i want to filter based on appends) whereas paginates works only on the builder not collection
        $forms = new \Illuminate\Pagination\LengthAwarePaginator(
            $forms->forPage(\Illuminate\Pagination\Paginator::resolveCurrentPage(), \request('per_page') ?? 5),
            $forms->count(),
            \request('per_page') ?? 5
        );
        if(request()->has('order_sector_id')){
            $order_sector = OrderSector::find(request()->order_sector_id);
            $types = OrganizationCategory::where('organization_id', $order_sector->sector->organization->id)
            ->with('category')
            ->get();
            return response()->json([
                'types' => OrganizationCategoryResource::collection($types),
                'forms' => FormResource::collection($forms),
                'pages' => $forms->lastPage()
            ], 200);
        }
        return response()->json(['forms' => FormResource::collection($forms), 'pages' => $forms->lastPage()], 200);
    }


    public function submitSection(SectorRequest $request)
    {
        $validationRules = $this->validateSection($request);
        if (!empty($validationRules)) {
            return response()->json($validationRules, 400);
        }

        //! case should handle: submit section in 23:55 the after 12:00 the section has not disabled, when try submit the section again its "error: section has submitted before"
        
        $user = auth()->user();
        $section = Section::find($request->section_id);
        $form = Form::find($section->form->id);
        if ($form->submissions_by == 'USERS') {
            $submittedForms = SubmittedForm::where(['user_id' => $user->id, 'form_id' => $section->form->id, 'order_sector_id' => $request->order_sector_id])->get();
        } else { //single user
            $submittedForms = SubmittedForm::where(['form_id' => $section->form->id, 'order_sector_id' => $request->order_sector_id])->get();
        }
        if ($submittedForms->isNotEmpty()) { //if there is submitted form by this user in this sector 
            if ($submittedForms->where('is_completed', false)->isNotEmpty()) {
                //none of these submitted forms are completed//if there are multiple submitted forms//get latest first 
                $submittedForm = $submittedForms->where('is_completed', false)->sortByDesc('created_at')->first();
                //store answers in the section //then update this submitted forms sections 
                $this->updateSubmittedForm($request, $submittedForm);
                return response()->json(["message" => trans("translation.Section submitted successfully")], 200);
            } else { //all the submitted forms are completed
                if ($form->submissions_times == 'SINGLE') {
                    return ['message' => trans('translation.This form already has been submitted')];
                } elseif ($form->submissions_times == 'SINGLE_PER_DAY') {
                    $filteredForms = $submittedForms->filter(function ($form) {
                        return $form->created_at->startOfDay()->eq(today()->startOfDay());
                    });
                    if ($filteredForms->whereNull('deleted_at')->isNotEmpty()) {
                        return ['message' => trans('translation.This form already has been submitted')];
                    }
                }
            }
        }
        $this->createSubmittedForm($request, $section); //create this form 
        return response()->json(["message" => trans("translation.Section submitted successfully")], 200);
    }

    public function validateSection($request)
    {
        $section = Form::validSection($request); //return the forms that exist in this sector then check if this section is visible in this form 
        if (!$section) {
            return ['message' => trans("translation.Section doesn't exist in this form")];
        }
        $section_questions = Section::sectionQuestions($request->section_id); //return the section questions

        // $missing_questions = array_diff($section_questions, collect(request()->answers)->keys()->toArray());
        foreach (request()->answers as $questionId => $answer) {
            if($answer == 'not-answered'){
                $question = Question::findOrFail($questionId);
                if(($question->is_required == 'default' && $question->question_bank_organization->is_required == '1') || $question->is_required  == '1'){
                    return ["message" => trans("translation.Must provide answers for all required questions")];
                }
            }
        }
        // foreach ($missing_questions as $missing_question) { //to see if this question is required
        //     $question = Question::findOrFail($missing_question);
        //     if(($question->is_required == 'default' && $question->question_bank_organization->is_required == '1') || $question->is_required  == '1'){
        //         return ["message" => trans("translation.Must provide answers for all required questions")];
        //     }
        // }
        return [];
    }

    public function storeAnswers($request, $submittedForm)
    {
        // try {
        //     DB::beginTransaction();

            $extraKeys = array_diff(collect(request()->answers)->keys()->toArray(), Section::sectionQuestions($request->section_id));
            //store the answers
            foreach (request()->answers as $questionId => $answer) {
                if (!in_array($questionId, $extraKeys)) {
                    $answer_record = $submittedForm->answers()->create([
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

        //     DB::commit();
        //     return;
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return response()->json(['message' => trans('translation.submitting-section-failed')], 500);
        // }
    }
    public function createSubmittedForm($request, $section)
    {
        // $section = Section::find($request->section_id);
        $newSubmittedForm = SubmittedForm::create([
            'form_id' => $section->form->id,
            'order_sector_id' => $request->order_sector_id,
            'user_id' => auth()->user()->id
        ]);
        $this->storeAnswers($request, $newSubmittedForm);
        // $submitted_sections [] = $request->section_id;
        $newSubmittedForm->update([
            // 'submitted_sections' => json_encode([$request->section_id], true), //$submitted_sections,
            'submitted_sections' => [$request->section_id], //$submitted_sections,
        ]);
        $submitted_section = SubmittedSection::create([
            'user_id' => auth()->user()->id,
            'section_id' => $request->section_id,
            'submitted_form_id' => $newSubmittedForm->id,
        ]);
        $action = trans('translation.Submit section in form ') . $section->form->name;
        $this->tracker($request, $submitted_section, $action);
    }
    public function updateSubmittedForm($request, $submittedForm)
    {
        $submitted_sections = $submittedForm->submitted_sections;
        $section = Section::find($request->section_id);
        if (!in_array($request->section_id, $submitted_sections)) {
            $this->storeAnswers($request, $submittedForm);
            array_push($submitted_sections, $request->section_id);
            $submittedForm->update([
                'submitted_sections' => $submitted_sections,
            ]);
            $submitted_section = SubmittedSection::create([
                'user_id' => auth()->user()->id,
                'section_id' => $request->section_id,
                'submitted_form_id' => $submittedForm->id,
            ]);
            $action = trans('translation.Submit section in form ') . $section->form->name;
            $this->tracker($request, $submitted_section, $action);
            $submittedForm->update(['user_id' => auth()->user()->id]);
        } else {
            throw ValidationException::withMessages(['message' => trans('translation.Section already submitted!')]);
        }
    }

    //?=================================OLD SUBMITTED FORMS CODE==================================================

    // public function submit_form(SectorRequest $request)
    // {
    //     $question_ids = Form::formQuestions($request); //return the forms that exist in this sector then filter only the question ids
    //     $validationRules = $this->validateSubmittedForm($request, $question_ids);

    //     if (!empty($validationRules)) {
    //         return response()->json($validationRules, 400);
    //     }
    //     return $this->store($request, $question_ids);
    // }
    // public function store($request, $question_ids)
    // {
    //     $user = auth()->user();
    //     $newSubmittedForm = SubmittedForm::create([
    //         'form_id' => $request->form_id,
    //         'order_sector_id' => $request->order_sector_id,
    //         'user_id' => $user->id
    //     ]);
    //     $extraKeys = array_diff(collect(request()->answers)->keys()->toArray(), $question_ids);
    //     //store the answers
    //     foreach (request()->answers as $questionId => $answer) {
    //         if (!in_array($questionId, $extraKeys)) {
    //             $answer_record = $newSubmittedForm->answers()->create([
    //                 'user_id' => $user->id,
    //                 'question_id' => $questionId,
    //                 'value' => is_array($answer) ? json_encode($answer) : $answer,
    //             ]);
    //             if ($request->hasFile("answers.{$questionId}")) {
    //                 //to store file answer 
    //                 $fileRequest = $request->file("answers.{$questionId}");
    //                 $attachments = $this->storeAnswerFile($fileRequest, $answer, $answer_record);
    //                 // If it's a single file, use the attachment_id; if multiple files, store an array of attachment_ids
    //                 $answer_record->update(['value' => $attachments]);
    //             }
    //         }
    //     }
    //     return response()->json(["message" => trans("translation.Form submitted successfully")], 200);
    // }

    // public function validateSubmittedForm(SectorRequest $request, $question_ids)
    // {
    //     // if (!$question_ids) {
    //     //     return ['message' => trans("translation.Form doesn't belong to this order sector")];
    //     // }
    //     // $missingKeys = array_diff($question_ids, collect(request()->answers)->keys()->toArray());
    //     // foreach ($missingKeys as $missingKey) { //to see if this question is required
    //     //     $question = Question::findOrFail($missingKey);
    //     //     if ($question->question_bank_organization->is_required) {
    //     //         return ["message" => trans("translation.Must provide answers for all required questions")];
    //     //     }
    //     // }
    //     //check the form submissions_times type 
    //     // $user = auth()->user();
    //     // $submittedForms = SubmittedForm::where(['user_id' => $user->id, 'form_id' => $request->form_id, 'order_sector_id' => $request->order_sector_id])->get(); //->get()->where('is_completed','0');

    //     // if ($submittedForms) { //if there is submitted form by this user in this sector 
    //     //     if($submittedForms->where('is_completed', '0')->get()){
    //     //         //none of these submitted forms are completed
    //     //         //if it multiple 
    //     //         //get latest first 
    //     //         //store answers for this section 
    //     //         //then update this submitted forms sections 

    //     //     }else{//all the submitted forms is completed
    //     //         $form = Form::findOrFail($request->form_id);
    //     //         if ($form->submissions_times == 'SINGLE') {
    //     //             return ['message' => trans('translation.This form already has been submitted')];
    //     //         }elseif($form->submissions_times == 'SINGLE_PER_DAY'){
    //     //             if($submittedForms->whereNull('deleted_at')->whereDate('created_at', today())->get()){
    //     //                 return ['message' => trans('translation.This form already has been submitted')];
    //     //             }else{
    //     //                 //there is a submitted forms and its completed ***** but not today *****
    //     //                 //create new submitted form
    //     //             }
    //     //         }else{
    //     //             //multiple submits 
    //     //             //create new submitted form
    //     //         }
    //     //     }
    //     // } else {
    //     //     //create this form     
    //     // }
    //     // return [];
    // }

}
