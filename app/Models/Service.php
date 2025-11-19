<?php

namespace App\Models;

use App\Traits\LocalizationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Base {
	use LocalizationTrait;

	protected $table = 'services';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('name_ar', 'name_en', 'price');
	protected $appends =  ['name'];

	public function orders()
	{
		return $this->hasManyThrough(Order::class,OrganizationService::class);
	}

	// public function sections()
	// {
	// 	return $this->hasManyThrough(Section::class,OrganizationService::class);
	// }

	public function organization_services()
	{
		return $this->hasMany(OrganizationService::class);
	}

	public function organizations(){
		return $this->belongsToMany(Organization::class,OrganizationService::class)->wherePivotNull('deleted_at');
	}

	public function facility(){
		return $this->belongsToMany(Facility::class,FacilityService::class);
	}
    public function facility_services()
	{
		return $this->hasMany(FacilityService::class);
	}
	public function getNameAttribute(){
		return $this->localizeName();		
	}
	public static function columnInputs(){
		return array(
			'name_ar' => 'text',
			// 'name_ar' => [
			// 	'type' => 'text',
			// 	'is_requirde' => false,
			// 	'show_in_edit' => false,
			// 	'options' => [],

			// ],
			'name_en' => 'text',
			'price' => 'number',
		);
	}

}