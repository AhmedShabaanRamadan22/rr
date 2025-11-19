<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssistQuestion extends Base
{
    use HasFactory, SoftDeletes;

    protected $dates = ['created_at','deleted_at'];
    protected $fillable = array('organization_id');

    public function questions()
	{
		return $this->morphMany(Question::class, 'questionable');
	}

    public function organization(){
        return $this->belongsTo(Organization::class);
    }

    public function visible_questions()
	{
		return $this->morphMany(Question::class, 'questionable')
        ->where([
            ['is_visible', '1'],  
            ['questionable_id',$this->id],
            ['questionable_type','App\Models\AssistQuestion']
        ])
        ->orWhere(
            function ($q) {
                $q->where([
                    ['is_visible', 'default'],
                    ['questionable_id',$this->id],
                    ['questionable_type','App\Models\AssistQuestion']
                    ])
                    ->whereHas('question_bank_organization', function ($q) {
                        $q->where('is_visible', '1');
                    });
            }
        )->orderBy('arrangement');
	}

}
