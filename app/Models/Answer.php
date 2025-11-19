<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Base
{

	protected $table = 'answers';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('question_id', 'value', 'user_id', 'answerable_id', 'answerable_type');
	// protected $appends =  ['actual_value'];

	public function question()
	{
		return $this->belongsTo(Question::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function option()
	{
		return $this->belongsTo(Option::class,'value','id');
	}

	public function attachment()
	{
		return $this->morphOne(Attachment::class, 'attachmentable');
	}

	public function attachments()
	{
		return $this->morphMany(Attachment::class, 'attachmentable');
	}

	public function answerable()
	{
		return $this->morphTo();
	}

	// public function attachment()
	// {
	// 	return Attachment::where([
	// 		'question_id' => $this->question_id,
	// 		'user_id' => $this->user_id,
	// 		'order_id' => $this->order_id
	// 	])->get()->first();
	// }

	public function answer_values()
	{
		if($this->value == 'not-answered'){
			return 'not-answered';
		}
		$type = $this->question->question_bank_organization->question_bank->question_type;
		if ($type->has_option) {
			if ($type->name == 'checkbox') {
				if(json_decode($this->value) == null ){
					return null;
				}
				return Option::withTrashed()->whereIn('id', json_decode($this->value, true))->get();
			}
			return Option::withTrashed()->where('id', $this->value)->first()?->id;
		// } elseif (in_array( $type->id,Answer::specialQuestions() ) ) {
		// 	return trans('translation.'.$this->value);
		} else {
			if ($type->name == 'file' || $type->name == 'files'|| $type->name == 'signature') {
				if(json_decode($this->value) == null ){
					return null;
				}
				$attachment_url = Attachment::whereIn('id', json_decode($this->value));
				// if ($type->name == 'file') {
				// 	return $attachment_url->first()?->url;
				// 	// return $attachment_url->url;
				// }
				// if ($type->name == 'files') {
					return $attachment_url->get()?->transform(function ($attachment) {
						return [
							'id' => $attachment->id,
							'url' => $attachment->url,
						];
					});
				// }
			}else {
				return $this->value;
			}
		}
	}

	public static function specialQuestions(){
		return array(QuestionType::YES_NO, QuestionType::APPLY_DOES_NOT_APPLY, QuestionType::AGREE_DOES_NOT_AGREE, QuestionType::MATCH_DOES_NOT_MATCH);
	}
	public function getActualValueAttribute()
	{
		return $this->answer_values();
	}
}