<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodType extends Base
{
    use SoftDeletes;

    protected $table = 'food_types';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('name');

    public function foods(){
        return $this->hasMany(Food::class);
    }


	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'food-type',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
		);
	}
}
