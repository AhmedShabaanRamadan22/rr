<?php

namespace App\Models;

use App\Traits\LocalizationTrait;
use Carbon\Traits\Localization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityEmployeePosition extends Base
{
    use SoftDeletes,HasFactory,LocalizationTrait;
    protected $table = 'facility_employee_positions';
	public $timestamps = true;
    protected $dates = ['deleted_at'];
	protected $fillable = array('name_ar', 'name_en');
    // protected $appends =  ['name'];

    public function facilityEmployees(){
        return $this->hasMany(FacilityEmployee::class);
    }
    public function getNameAttribute()
    {
        return $this->localizeName();
        // return app()->getLocale() == 'en' ? $this->name_en : $this->name_ar;
    }

    public static function columnNames()
	{
		return array(
			'id' => 'id',
            'name_ar' => 'Position Name in arabic',
            'name_en' => 'Position Name in english',
            'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name_ar' => 'text',
			'name_en' => 'text',
		);
	}

}
