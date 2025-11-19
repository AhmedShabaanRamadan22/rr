<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Regex;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function store(Request $request)
    {
        $section = Section::where([
            'name' => $request->name,
            'arrangement' => $request->arrangement,
            'form_id' => $request->form_id,
            'is_visible' => $request->is_visible
        ])->first();
        if ($section) {
            return back()->with(array('message' => trans('translation.Section name already exist in the same form!'), 'alert-type' => 'error'));
        }
        $new_section = Section::create($request->only(['name', 'arrangement', 'is_visible', 'form_id']));
        // $section->update(['arrangement'=>$section->form->sections->count()]);

        return back()->with(array('message' => trans('translation.Added successfully'), 'alert-type' => 'success')); //,'form_id_updated'=>$section->form_id));
    }
    //??=========================================================================================================
    public function show(Form $form, Section $section)
    {
        $question_types = QuestionType::all();
        $regexes = Regex::all();
        $question_has_options_ids = $question_types->where('has_option', 1)->pluck('id')->toArray();
        $questions = $section->questions;
        
        return view('admin.sections.show', compact('section','questions'));
    }
    //??=========================================================================================================
    public function update(Request $request)
    {
        $section = Section::findOrFail($request->section_id);
        // dd($request->all());
        $section->update([
            'is_visible' => $request->is_visible,
            'name' => $request->section_name,
            'arrangement' => $request->section_arrangement,
        ]);

        return back();
    }
    //??=========================================================================================================
    public function destroy(Form $form, Section $section)
    {
        if ($section->questions->isNotEmpty()) {
            return response(['message' => trans('translation.Section has questions, please delete them first!')], 400);
        }

        $section->delete();

        return response(['message' => trans('translation.delete-successfully')], 200);
    }
    //??=========================================================================================================
    // public function datatable(Request $request, $section_id)
    // {
    //     $query = Question::with('options', 'regex');
    //     // dd($query);
    //     $query->whereHas('section', function ($q) use ($section_id) {
    //         $q->where('id', $section_id);
    //     });

    //     return datatables($query->orderByDesc('created_at')->get())

    //         ->editColumn('regex', function (Question $question) {
    //             return $question->regex->name ?? "-";
    //         })
    //         ->editColumn('question_type_id', function (Question $question) {
    //             return $question->question_type->name ?? "-";
    //         })
    //         ->editColumn('actions', function (Question $question) {
    //             return
    //                 '<div class="d-flex justify-content-center">
    //                     <button
    //                     class="btn btn-outline-secondary btn-sm m-1  on-default m-r-5 edit-button"
    //                     data-bs-target="#editQuestion"
    //                     data-bs-toggle="modal"
    //                     data-original-title="Edit"
    //                     data-question-id="' . $question->id . '">
    //                         <i class="mdi mdi-clipboard-edit-outline"></i>
    //                     </button>

    //                     <button
    //                     class="btn btn-outline-danger btn-sm m-1  on-default m-r-5 delete-question-btn" data-question-id="' . $question->id . '">
    //                         <i class="mdi mdi-delete"></i>
    //                     </button>
    //                 </div>';
    //         })
    //         ->rawColumns(['actions'])
    //         ->toJson();
    // }
}
