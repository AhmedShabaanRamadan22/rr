<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\QuestionTypesEnum;

class QuestionType extends Base
{

	use SoftDeletes;
	protected $table = 'question_types';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('name', 'has_option');
	// protected $appends =  [];

	const YES_NO = 10;
	const APPLY_DOES_NOT_APPLY = 11;
	const AGREE_DOES_NOT_AGREE = 12;
	const MATCH_DOES_NOT_MATCH = 13;

	public function questions()
	{
		return $this->hasMany(Question::class);
	}
    public function question_bank()
	{
		return $this->hasMany(QuestionBank::class);
	}


	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'question-type-name',
			'has_option' => 'has-option',
//			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
			'has_option' => 'switch',
		);
	}

	public function getQuestionTypeNameAttribute(){
		$questionTypeName = [
			'text' => trans('translation.text'),
    		'number' => trans('translation.number'),
    		'textarea' => trans('translation.textarea'),
    		'email' => trans('translation.email'),
    		'file' => trans('translation.file'),
    		'checkbox' => trans('translation.checkbox'),
    		'radio' => trans('translation.radio'),
    		'select' => trans('translation.select'),
    		'multiple_select' => trans('translation.multiple_select'),
    		'Yes_No' => trans('translation.Yes_No'),
    		"Apply_Doesn't apply" => trans("translation.Apply_Doesn't apply"),
    		"Agree_Doesn't agree" => trans("translation.Agree_Doesn't agree"),
    		"Match_Doesn't match" => trans("translation.Match_Doesn't match"),
    		'nationalities' => trans('translation.nationalities'),
    		'cities' => trans('translation.cities'),
    		'files' => trans('translation.files'),
            'rate' => trans('translation.rate'),
            'signature' => trans('translation.signature'),
        ];

		return $questionTypeName[$this->name]?? $this->name;
	}

	// public function regexes() {
	// 	return $this->hasMany(Regex::class);
	// }

	public function getQuestionTypeMobileAttribute(){
		if(in_array($this->id, [QuestionType::YES_NO, QuestionType::APPLY_DOES_NOT_APPLY, QuestionType::AGREE_DOES_NOT_AGREE, QuestionType::MATCH_DOES_NOT_MATCH])){
			return 'radio';
		}
		return $this->name;
	}
}
