<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Base
{
    protected $table = 'menus';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('food_weight_id', 'nationality_organization_id');


	public function nationality_organization(){
		return $this->belongsTo(NationalityOrganization::class);
	}

	public function food_weight(){
		return $this->belongsTo(FoodWeight::class);
	}

}