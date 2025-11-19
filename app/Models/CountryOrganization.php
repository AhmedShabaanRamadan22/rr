<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CountryOrganization extends Base {

	protected $table = 'country_organization';
	public $timestamps = true;

	
	use SoftDeletes;
	
	protected $dates = ['deleted_at'];
	protected $fillable = array('country_id', 'organization_id');
	// protected $appends =  ['country_name','orders'];

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}

	public function country()
	{
		return $this->belongsTo(Country::class, 'country_id', 'id');
	}

	public function getOrdersAttribute(){
		return Order::whereJsonContains('country_ids', $this->id)->get();
	} 

	public function getHasOrdersAttribute(){
		return $this->orders->isNotEmpty();
	} 

	public function getCountryNameAttribute(){
		return $this->country->name??'';
	}

	public static function columnInputs()
	{
		return array(
            'country_id' => 'multiple-select',
            'organization_id' => 'select',
		);
	}

	public static function columnOptions($organization = null)
	{
		$countries = Country::query();
		if($organization != null){
			$countries->whereNotIn('id',$organization->country_organization->pluck('country_id')->toArray());
		}
		return array(
			'country_id' => $countries->get()->pluck('name','id')->toArray(),
            'organization_id' => null,
		);
	}

}