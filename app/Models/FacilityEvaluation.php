<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityEvaluation extends Base
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['season','mark','facility_id','details'];

    const SEASONS = [
        '1444' => '1444', 
        '1445' => '1445', 
        '1446' => '1446', 
        '1447' => '1447'
    ];

    public function facility(){
        return $this->belongsTo(Facility::class);
    }

    public function getEvaluationSeasonAttribute(){
        return $this->season . 'h: ' . $this->mark_percentage;
    }

    public function getMarkPercentageAttribute(){
        if(!$this->mark) return "-";
        return $this->mark . "%";
    }

    public static function columnNames()
	{
		return array(
			'id' => 'id',
			'season' => 'season',
			'mark' => 'mark',
			'facility_id' => 'facility',
            'details' => 'details',
			'action' => 'action',
		);
	}

    public static function filterColumns()
	{

		return array(
			'facility_id' => Facility::whereHas('facility_evaluations')->pluck('name', 'id')->toArray(),
            'season' => self::SEASONS,
		);
	}

	public static function columnInputs()
	{
		return array(
			'season' => 'select',
			'mark' => 'number',
			'facility_id' => 'select',
            'details' => 'textarea',
		);
	}


	public static function columnOptions()
	{
		return array(
			'facility_id' => Facility::pluck('name', 'id')->toArray(),
            'season' => self::SEASONS,
		);
	}


    public static function columnSubtextOptions()
    {

        return array(
            'facility_id' => Facility::get()->pluck('registration_number_and_license', 'id')->toArray(),
        );
    }

}
