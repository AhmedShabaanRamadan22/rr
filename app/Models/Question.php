<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\QuestionTypesEnum;

class Question extends Base
{

	use SoftDeletes;
	protected $table = 'questions';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = [
		'is_required', 'arrangement', 'is_visible', 'questionable_id',
		'questionable_type', 'question_bank_organization_id'
	];
    protected $appends = ["sortable_name"];

	// protected $appends = [
	// 	"required", "visible", "required_star", "question_type_name", "question_type_id", "content",
    //     "description", "options","sortable_name"
	// ];

	//! those all comments becoz trans to question bank  {
	// public function organization_service(){
	// 	return $this->belongsTo(OrganizationService::class,'questionable_id')->where('questions.questionable_type',OrganizationService::class);
	// }

	// public function question_type(){
	// 	return $this->belongsTo(QuestionType::class);
	// }

	// public function section()
	// {
	//     return $this->belongsTo(Section::class, 'questionable_id')->where('questions.questionable_type', Section::class);
	// }

	// public function regex()
	// {
	// 	return $this->belongsTo(Regex::class);
	// }
	//! }

	public function question_bank_organization()
	{
		return $this->belongsTo(QuestionBankOrganization::class, 'question_bank_organization_id', 'id');
	}

	public function options()
	{
		return $this->hasMany(Option::class, 'question_id', 'id');
	}

	public function answers()
	{
		return $this->hasMany(Answer::class);
	}
	public function answer($submitted_form_id)
	{
		return $this->hasOne(Answer::class)->where([
			['answerable_id', $submitted_form_id], 
			['answerable_type', 'App\Models\SubmittedForm'],
			['question_id', $this->id],
		]);
	}
	public function meal_stage_answer($meal_organization_stage_id)
	{
		return $this->hasOne(Answer::class)->where([
			['answerable_id', $meal_organization_stage_id], 
			['answerable_type', 'App\Models\MealOrganizationStage'],
			['question_id', $this->id],
		]);
	}

	public function has_options(){
		return $this->question_type->has_option || in_array($this->question_type->name, ['Yes_No', "Apply_Doesn't apply", "Agree_Doesn't agree", "Match_Doesn't match", 'nationalities', 'cities']);
	}

	public function get_answers($model)
	{
		if ($this->question_type->has_option) {
			return $this->user_answer_option($model);
		} else {
			if (in_array($this->question_type, ['file'])) {
				return $this->user_answer_file($model);
			} else {
				return $this->user_answer($model);
			}
		}
	}
	public function user_answer($model)
	{
		// dd(['user_id' => $model->user_id, 'answerable_id' => $model->id, 'answerable_type' => get_class($model) ,'question_id' => $this->id]);
		return Answer::where(['user_id' => $model->user_id, 'answerable_id' => $model->id, 'answerable_type' => get_class($model) ,'question_id' => $this->id])->first();
	}

	public function user_answer_option($model)
	{
		$answer = $this->user_answer($model);
		// dd(json_decode($answer->value,true));
		if (is_null($answer)) {
			return false;
		}
		return Option::withTrashed()->whereIn('id', json_decode($answer->value, true))->get();
	}
	public function user_answer_file($model)
	{
		//! check the logic of store file answers first
		$answer = $this->user_answer($model);
		// dd(json_decode($answer->value,true));
		$attachment =  Attachment::where([
			'question_id' => $this->id,
			'order_id' => $model
		])->orderByDesc('created_at')->get()->first();
		if (is_null($answer) || is_null($attachment)) {
			return false;
		}
		// return  \Storage::get( $attachment->name);


		return \Storage::disk()->url($attachment->path . '/' . $attachment->name);
		// return storage_path(  $attachment->path . '/' . $attachment->name);
	}
	public function attachments()
	{
		return $this->hasMany(Attachment::class);
	}

	public function getContentAttribute()
	{
		return $this->question_bank_organization->question_bank->content ?? '';
	}

	public function getPlaceholderAttribute()
	{
		return $this->question_bank_organization->question_bank->placeholder ?? '';
	}

	public function getDescriptionAttribute()
	{
		return $this->question_bank_organization->description ?? '';
	}

	public function getQuestionTypeNameAttribute()
	{
		return $this->question_bank_organization->question_bank->question_type->name ?? '-';
	}

	public function getQuestionTypeIdAttribute()
	{
		return $this->question_bank_organization->question_bank->question_type->id ?? 0;
	}

	public function getRequiredAttribute()
	{
		return $this->is_required == 1 || ($this->is_required == 'default' && $this->question_bank_organization->is_required == 1);
	}

	public function getVisibleAttribute()
	{
		return $this->is_visible == 1 || ($this->is_visible == 'default' && $this->question_bank_organization->is_visible == 1);
	}

	public function getRequiredStarAttribute()
	{
		return $this->required ? '*' : '';
	}

    public function getSortableNameAttribute()
    {
        return $this->content??'-';
    }
	public function stages()
	{
		return $this->hasMany(Stage::class);
	}
	public function questionable()
	{
		return $this->morphTo();
	}

	public static function columnNames()
	{
		return array(
			'id',
			// 'questionable_type',
			// 'question_bank_organization_id',
			'content',
			'description',
			'question-type',
			'arrangement',
			'is-required',
			'visible',
			'options',
			'action'
		);
	}

	// public static function columnInputs()
	// {
	//     return array(
	// 		'question_bank_organization_id',
	// 		'arrangement',
	// 		'is_visible',
	// 		'is_required',
	// 		'question_bank_organization_id',
	// 		'question_bank_organization.question_bank.content',
	// 		'action'
	// 	);
	// }

	public function getOptionsAttribute()
	{
		$question_type = $this->question_bank_organization->question_bank->question_type->name;
		// dd(Nationality::pluck('name', 'name'));
		if ($question_type) {
			switch ($question_type) {
				case "Yes_No":
					return [['id' => 'yes', 'content' => trans('translation.yes')], ['id' => 'no', 'content' => trans('translation.no')]];
				case "Apply_Doesn't apply":
					return [['id' => 'apply', 'content' => trans('translation.apply')], ['id' => "doesn't apply", 'content' => trans("translation.doesn't apply")]];
				case "Agree_Doesn't agree":
					return [['id' => 'agree', 'content' => trans('translation.agree')], ['id' => "doesn't agree", 'content' => trans("translation.doesn't agree")]];
				case "Match_Doesn't match":
					return [['id' => 'match', 'content' => trans('translation.match')], ['id' => "doesn't match", 'content' => trans("translation.doesn't match")]];
				case "nationalities":
					return Country::get()->transform(function ($country) {
						return ['id' => $country->name, 'content' => $country->name];
					});
				case "cities":
					return City::get()->transform(function ($city) {
						return ['id' => $city->name, 'content' => $city->name];
					});
				default:
					return $this->options()->select('id', 'content')->get(); //->toArray();
			}
		}
	}
}