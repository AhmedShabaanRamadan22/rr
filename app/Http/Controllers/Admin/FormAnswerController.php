<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\FormAnswerDetails\FormResource;
use App\Models\Form;
use App\Models\Option;
use App\Models\SubmittedForm;
use App\Models\User;
use App\Services\AnswerService;
use AWS\CRT\HTTP\Request;

use function Aws\map;

class FormAnswerController extends Controller
{

    public function index($form_id)
    {
        $form = Form::with([
            'sections_has_question.visible_questions',
        ])
        //            ->whereDate('created_at', \Carbon\Carbon::today())
        ->where('id', $form_id)
        ->orderBy('created_at', 'desc')
        ->first();
        $form->append('form_full_name');
        $form_resource = new FormResource($form);
        $form_array = $form_resource->toArray();
 
        $submitted_forms = $form->submitted_forms;
        $unique_dates = $submitted_forms->pluck('created_at')->map(function ($date) {
            return $date->toDateString();
        })->unique()->values()->toArray();

        $filters = [
            'dates' => $unique_dates,
            'monitors' => $submitted_forms->pluck('submitted_section.user')->unique('id', 'name')->sortBy('id'),
            'sectors' => $submitted_forms->pluck('order_sector.sector')->unique('id', 'label')->sortBy('label'),
            'nationalities' => $submitted_forms->pluck('order_sector.sector.nationality_organization.nationality')->unique('id', 'name')->sortBy('id'),
        ];
        return view('admin.form_answers.index', [
            'form' => $form_array,
            'filters' => $filters,
        ]);


    }

    public function dataTable()
    {
        $answerService = new AnswerService();
    
        // Step 1: Fetch the paginated records
        $submitted_forms_query = SubmittedForm::with([
            'answers.question.question_bank_organization.question_bank.question_type',
            'submitted_sections',
            'form',
            'order_sector.sector.nationality_organization.nationality',
        ])
        ->where('form_id', request()->form_id);

        if(request()->has('monitors')){
            $submitted_forms_query->whereIn('user_id',request()->monitors);
        }

        if(request()->has('sectors')){
            $submitted_forms_query->whereHas('order_sector',function($q){
                $q->whereIn('sector_id',request()->sectors);
            });
        }

        if(request()->has('nationalities')){
            $submitted_forms_query->whereHas('order_sector.sector.nationality_organization',function($q){
                $q->whereIn('nationality_id',request()->nationalities);
            });
        }
    
        // Apply pagination
        $submitted_forms = $submitted_forms_query->orderByDesc('created_at')->paginate(request()->input('per_page', 50));
    
        // Step 2: Transform data for each submitted form
        $transformedData = $submitted_forms->getCollection()->map(function ($submitted_form) use ($answerService) {
            $is_completed = empty(array_diff($submitted_form->form->sections()->pluck('id')->toArray(), $submitted_form->submitted_sections));
            $answers = [];
            foreach ($submitted_form->answers as $answer) {
                $answers[$answer->question_id] = $answer ? $answerService->generateAnswerValue($answer, $answer->question, false, null, null, true) : '—';
            }
    
            return [
                'id' => $submitted_form->id,
                'created_at' => $submitted_form->created_at->format('Y-m-d H:i:s'),
                'label' => $submitted_form->order_sector->sector->label ?? '-',
                'nationality_name' => $submitted_form->order_sector->sector->nationality_organization->nationality->name ?? '-',
                'facility_name' => $submitted_form->order_sector->order->facility->name ?? '-',
                'monitor_names' => $submitted_form->order_sector->monitors_name ?? '-',
                'filled_by_name' => $submitted_form->submitted_section->user->name ?? '-',
                'completed' => $is_completed,
                'answers_value' => $answers, // Dynamic answers
            ];
        });
    
        $totals = $submitted_forms->total();
        // Step 3: Return a DataTables JSON response with proper metadata
        return response()->json([
            'data' => $transformedData,
            'draw' => intval(request()->input('draw')), // Required for DataTables
            'recordsTotal' => $totals, // Total records
            'recordsFiltered' => $totals, // Filtered records (adjust if you apply filters)
        ]);
    }
    

}
