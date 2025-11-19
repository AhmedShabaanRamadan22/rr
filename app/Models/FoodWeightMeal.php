<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodWeightMeal extends Base
{
    use SoftDeletes;
    protected $table = 'food_weight_meals';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('food_weight_id','meal_id');

    public function food_weight(){
        return $this->belongsTo(FoodWeight::class);
    }

    public function meal(){
        return $this->belongsTo(Meal::class);
    }
}