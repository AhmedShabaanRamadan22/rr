<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Base
{
	use SoftDeletes;
	protected $table = 'food';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('name', 'food_type_id');

	public function food_type()
	{
		return $this->belongsTo(FoodType::class);
	}
	// public function menus()
	// {
	// 	return $this->hasMany(Menu::class);
	// }
	public function food_weight_meals()
	{
		return $this->hasMany(FoodWeightMeal::class);
	}
	// public function meals()
	// {
	// 	return $this->belongsToMany(FoodWeightMeal::class,'food_meals');
	// }

	public function nationality_organizations()
	{
		return $this->belongsToMany(NationalityOrganization::class, 'menus');
	}
	public function food_weights()
	{
		return $this->hasMany(FoodWeight::class);
	}

	public function getNameWithTypeAttribute(){
		return $this->name . ' (' . $this->food_type->name . ')';
	}

	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'name',
			'food_type_name' => 'food-types',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
			'food_type_id' => 'select',
		);
	}

	public static function columnOptions()
	{
		return array(
			'food_type_id' => FoodType::pluck('name', 'id')->toArray(),
		);
	}
}