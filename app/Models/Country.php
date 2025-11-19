<?php

namespace App\Models;

use App\Traits\LocalizationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Country extends Base {

	protected $table = 'countries';
	public $timestamps = true;

	use SoftDeletes, LocalizationTrait;

	protected $dates = ['deleted_at'];
	protected $fillable = array('name_ar', 'name_en', 'continent', /*'flag_path'*/ 'phone_code','iso3','code');
	// protected $appends =  ['name'];

	public function organization()
	{
		return $this->belongsToMany(Organization::class)->wherePivotNull('deleted_at');
	}

	public function country_organization()
	{
		return $this->hasMany(CountryOrganization::class);
	}

    public function countinents()
    {
        return $this->belongsTo(Continent::class);
    }

	public function getNameAttribute(){
		return $this->localizeName();	
	}

	public function getFlagImageAttribute() {
		return '<img src="'.URL::asset('build/images/flags/'.(strtolower($this->code)).'.svg').'" class="mx-2" alt="Header Language" height="15"><span class="">' . $this->name . '</span';
	}

}