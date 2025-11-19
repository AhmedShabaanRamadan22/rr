<?php

namespace App\Models;

use App\Models\Stage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Base
{
    use SoftDeletes;

	const DAY_WATER_SUPPORT = 1;
	const NIGHT_WATER_SUPPORT = 2;
	const BREAKFAST_FOOD_SUPPORT = 3;
	const LUNCH_FOOD_SUPPORT = 4;
	const DINNER_FOOD_SUPPORT = 5;
	const BREAKFAST_MEAL = 6;
	const LUNCH_MEAL = 7;
	const DINNER_MEAL = 8;

    protected $table = 'periods';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['created_at','deleted_at'];
    protected $fillable = ['name','duration', 'operation_type_id'];

    public function stages(){
        return $this->hasMany(Stage::class);
    }

    public function support(){
        return $this->hasOne(Support::class);

    }

	public function operation_type(){
		return $this->belongsTo(OperationType::class);
	}

	public function meals(){
		return $this->hasMany(Meal::class);
	}

	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'name',
			'operation_type.name' => 'operation-type-id',
			'duration' => 'duration',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
			'duration' => 'number',
			'operation_type_id' => 'select',
		);
	}

	public static function columnOptions()
	{
		return array(
			'operation_type_id' => OperationType::all()->pluck('name', 'id')->toArray(),
		);
	}
}