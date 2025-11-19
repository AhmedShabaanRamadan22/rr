<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationStage extends Base
{
    protected $table = 'organization_stages';
    public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['created_at','deleted_at'];
    protected $fillable = array('stage_bank_id','organization_id','duration','arrangement');
    protected $appends =  ['sortable_name'];

    public function organization(){
        return $this->belongsTo(Organization::class);
    }

    public function stage_bank()
    {
        return $this->belongsTo(StageBank::class);
    }

    public function meal_organization_stages()
    {
        return $this->hasMany(MealOrganizationStage::class);
    }

    public function questions()
	{
		return $this->morphMany(Question::class, 'questionable');
	}

    public function visible_questions()
	{
		return $this->morphMany(Question::class, 'questionable')
        ->where([
            ['is_visible', '1'],  
            ['questionable_id',$this->id],
            ['questionable_type','App\Models\OrganizationStage']
        ])
        ->orWhere(
            function ($q) {
                $q->where([
                    ['is_visible', 'default'],
                    ['questionable_id',$this->id],
                    ['questionable_type','App\Models\OrganizationStage']
                    ])
                    ->whereHas('question_bank_organization', function ($q) {
                        $q->where('is_visible', '1');
                    });
            }
        )->orderBy('arrangement');
	}

    public static function columnNames()
	{
		return array(
			'id' => 'id',
			'stage-bank-name' => 'stage-bank-name',
			'arrangement' => 'arrangement',
			'duration' => 'duration',
			'questions' => 'questions',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			// 'organization_id' => 'number',
			// 'stage_bank_id' => 'select',
			'duration' => 'number',
		);
	}

	// public static function columnOptions()
	// {
    //     if ($id) {
    //         $org_id = OrganizationStage::find($id)->organization_id;
    //         // dd(OrganizationStage::find($id)->organization_id);
    //         dd($org_id);
    //     }
	// 	return array(
	// 		'stage_bank_id' => StageBank::whereDoesntHave('organization_stages',function($q) use ($org_id){
    //             $q->where('organization_id',3);
    //         })->get()->pluck('name', 'id')->toArray(),
	// 	);
    // }

    public function getSortableNameAttribute()
    {
        return $this->stage_bank->name??'-';
    }
}
