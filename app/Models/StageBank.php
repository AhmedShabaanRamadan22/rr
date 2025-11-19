<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class StageBank extends Base
{
    protected $table = 'stage_banks';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['created_at','deleted_at'];
    protected $fillable = array('name','duration','description','arrangement');
    protected $appends =  ['sortable_name'];

    public function organization_stages()
    {
        return $this->hasMany(OrganizationStage::class);
    }

    public static function columnNames()
	{
		return array(
			'id' => 'id',
			'arrangement' => 'arrangement',
			'name' => 'name',
			'description' => 'description',
			'duration' => 'duration',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
			'duration' => 'number',
            'description' => 'text',
		);
	}

	// public static function columnOptions()
	// {
	// 	return array(
	// 		'previous_stage_id' => StageBank::whereNull('next_stage_id')->get()->pluck('name', 'id')->toArray(),
	// 	);
	// }

    public function getSortableNameAttribute()
    {
        return $this->name??'-';
    }
}
