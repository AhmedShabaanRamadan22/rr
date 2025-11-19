<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBankOrganization extends Base
{
    use SoftDeletes;
	protected $table = 'question_bank_organizations';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = ['organization_id', 'question_bank_id', 'is_visible', 'is_required','description'];

    public function question_bank(){
		return $this->belongsTo(QuestionBank::class);
	}
    public function organization()
	{
		return $this->belongsTo(Organization::class);
	}
    // public function options()
	// {
	// 	return $this->hasMany(Option::class);
	// }
    public function questions(){
        // return $this->morphMany(Question::class,'questionable');
        return $this->hasMany(Question::class);
    }

	public static function columnNames()
    {
        return array(
            'id' => 'id',
            // 'question_bank_id' => 'Question bank id',
            'question_bank.content' => 'Question',
            'question_bank.question_type.name' => 'question_type',
            'is_visible' => 'Visible',
            'is_required' => 'Required',
            'description' => 'Description',
            'action' => 'action',
        );
    }

    public static function columnInputs()
    {
        return array(
            'question_bank_id' => 'select',
            'description' => 'text',
            'is_visible' => 'switch',
            'is_required' => 'switch',
        );
    }

	public static function columnOptions()
	{
		return array(
			'question_bank_id' => QuestionBank::get()->pluck('content_with_type','id')->toArray(),
		);
	}

}