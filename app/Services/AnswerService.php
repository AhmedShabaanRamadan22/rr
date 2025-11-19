<?php

namespace App\Services;

use App\Models\Option;

class AnswerService
{
    public function generateAnswerValue( $answer, $question, $show_barcode = false, $max_width = null, $max_height = null, $show_real_path = false)
    {
        if($answer->value === 'not-answered'){
            return   trans('translation.not-answered');
        }
        if ($question->question_type_name === 'signature') {
            return $this->generateImageAnswerValue($answer, $max_width, $max_height);
        } elseif ($question->question_type_name === 'file' || $question->question_type_name === 'files') {
            return $this->generateFileAnswerValue($answer, $show_barcode, $show_real_path);
        } elseif ($question->question_type_name === 'checkbox') {
            return $this->generateCheckboxAnswerValue($answer);
        } elseif ($question->question_type_name === 'radio') {
            return $this->generateRadioAnswerValue($answer);
        }
        else {
            return in_array($question->question_type_id, $answer->specialQuestions()) ? trans('translation.' . $answer->actual_value) : $answer->actual_value;
        }
    }

    public function generateImageAnswerValue($answer , $max_width = null, $max_height = null)
    {
        $answer_value = '';

        foreach ($answer->actual_value as $key => $value) {
            $answer_value .= view('admin.export.components.signature-image',['src_url'=>$value['url'], 'max_width' => $max_width, 'max_height' => $max_height]);
            
        }
        return $answer_value;
    }

    public function generateFileAnswerValue($answer, $show_barcode, $show_real_path = false)
    {
        $answer_value = '<table class="barcode-table">
                            <tr>';
        $lastIndex = count($answer->actual_value) - 1;

        foreach ($answer->actual_value as $key => $value) {
            $answer_value .= 
                $show_barcode ? 
                    view('admin.export.components.barcode-td', ['url' => $value['url']]):
                    '<a href="' . $value['url'] . '" target="_blank">' . ($show_real_path ? $value['url'] : trans('translation.click-here')) . '</a>';
            if ($key !== $lastIndex) {
                $answer_value .= ' | ';
            }
        }
        $answer_value .= '</tr>
                    </table>';
        return $answer_value;
    }

    public function generateCheckboxAnswerValue($answer)
    {
        $answer_value = '';
        $count = count($answer->actual_value);
        foreach ($answer->actual_value as $key => $value) {
            $answer_value .= $value->content ?? '-';
            if ($key < $count - 1) {
                $answer_value .= ' - ';
            }
        }
        return $answer_value;
    }

    public function generateRadioAnswerValue($answer)
    {
        $answer_value = '-';

        if ($answer && !empty($answer->value)) {
            $option = Option::withTrashed()->find($answer->value);

            if ($option && !empty($option->content)) {
                $answer_value = $option->content;
            }
        }

        return $answer_value;
    }

}
