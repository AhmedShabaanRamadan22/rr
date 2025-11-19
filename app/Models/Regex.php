<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regex extends Base {

	protected $table = 'regexes';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('name', 'description', 'value', 'question_type_id');

	public function questions()
	{
		return $this->hasMany(Question::class);
	}
    // protected function questionTypeId(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn ($value) => json_decode($value, true),
    //         set: fn ($value) => json_encode($value),
    //     );
    // }
    public function question_bank()
	{
		return $this->hasMany(QuestionBank::class);
	}
	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'name',
			'description' => 'description',
			'value' => 'value',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
			'description' => 'text',
			'value' => 'text',
		);
	}

}
