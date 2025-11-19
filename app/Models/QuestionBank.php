<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Base
{
    use SoftDeletes;
	protected $table = 'question_banks';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = ['content', 'question_type_id', 'regex_id', 'placeholder'];

    public function question_type(){
		return $this->belongsTo(QuestionType::class);
	}
    public function regex()
	{
		return $this->belongsTo(Regex::class);
	}
    public function question_bank_oraganization()
	{
		return $this->hasMany(QuestionBankOrganization::class);
	}

    public static function columnNames()
	{
		return array(
			'id' => 'id',
			'content' => 'content',
            'placeholder' => 'placeholder',
			'question_type' => 'question_type',
			'regex.name' => 'regex_name',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'content' => 'text',
			'placeholder' => 'text',
			'question_type_id' => 'select',
            'regex_id' => 'select',
			'organization_id' => 'hidden'
		);
	}
    public static function columnOptions()
	{
		return array(
			'question_type_id' => QuestionType::all()->pluck('question_type_name','id')->toArray(),
            'regex_id' => Regex::all()->pluck('name','id')->toArray(),
		);
	}
    public function getRegexNameAttribute()
	{
		return $this->regex->name;
	}
    public function getQuestionTypeNameAttribute()
	{
		return $this->question_type->name;
	}
    public function getContentWithTypeAttribute()
	{
		return $this->content . ' (' . $this->question_type_name .')';
	}
}