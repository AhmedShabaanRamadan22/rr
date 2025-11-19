<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\MealOrganizationStage;

class MealOrganizationStageService
{
    private $mos_id;
    private $answers;

    public function __construct($mos_id)
    {
        $this->mos_id = $mos_id;
    }


    public function get_mos_questions()
    {
        return $this->get_mos_answers()->pluck('question');
    }

    public function get_mos_answers()
    {
        if (!$this->answers) {
            $this->answers = Answer::with('question')
                ->where('answerable_id', $this->mos_id)
                ->where('answerable_type', 'App\Models\MealOrganizationStage')
                ->get();
        }

        return $this->answers;
    }

    public function get_model()
    {
        return MealOrganizationStage::find($this->mos_id);
    }
}
