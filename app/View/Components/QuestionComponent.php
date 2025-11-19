<?php

namespace App\View\Components;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Models\QuestionType;
use App\Models\Regex;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class QuestionComponent extends Component
{
    /**
     * Create a new component instance.
     */

    protected $title, $questionableId, $questionableType, $questionTypes, $regexes, $question_has_options_ids, $columns;

    public function __construct($title, $questionableId, $questionableType)
    {
        $this->title = $title;
        $this->questionableId = $questionableId;
        $this->questionableType = $questionableType;
        $this->questionTypes = QuestionType::all();
        $this->regexes = Regex::all();
        $this->question_has_options_ids = $this->questionTypes->where('has_option', 1)->pluck('id')->toArray();
        $this->columns = Question::columnNames();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // dd($this);
        return view('components.question-component')->with([
            'title' => $this->title,
            'questionableId' => $this->questionableId,
            'questionableType' => $this->questionableType,
            'questionTypes' => $this->questionTypes,
            // 'questionBankOrganizationId' => $this->questionBankOrganizationId,
            'regexes' => $this->regexes,
            'question_has_options_ids' => $this->question_has_options_ids,
            'columns' => $this->columns,
        ]);
    }
}