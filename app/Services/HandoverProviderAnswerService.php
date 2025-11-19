<?php

namespace App\Services;


class HandoverProviderAnswerService
{
    public static function getValueAnswerById($answers,$question_bank_id,$wanted = 'actual_value',$default_value = "-"){
        return $answers->where("question.question_bank_organization.question_bank.id", $question_bank_id)->first()->{$wanted} ?? $default_value;
    }

    public static function getUrlAnswerValueById($answers,$question_bank_id){
        $value = self::getValueAnswerById($answers,$question_bank_id);
        if($value != "-"){
            return $value[0]['url'];
        }

        return "";
    }

    public static function getSignatureAnswerValueById($answers,$question_bank_id){
        $value = self::getValueAnswerById($answers,$question_bank_id);
        if($value != "-"){
            $url =  $value[0]['url'];
            return view('admin.export.components.signature-image',['src_url'=>$url]);
        }

        return "-";
    }

    public static function getDateAnswerValueById($answers,$question_bank_id,$wanted = 'actual_value'){
        $value = self::getValueAnswerById($answers,$question_bank_id,$wanted);
        if($value != "-"){
            return $value->format('Y/m/d H:i:s');
        }

        return "-";
    }
}