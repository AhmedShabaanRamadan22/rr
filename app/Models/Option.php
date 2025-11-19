<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Base {

	protected $table = 'options';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('content', 'question_id');

	public function question()
	{
		return $this->belongsTo(Question::class);
	}

}
