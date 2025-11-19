<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classification extends Base
{
    use SoftDeletes;
    protected $table = 'classifications';
	public $timestamps = true;

	protected $dates = ['created_at','deleted_at'];
    protected $fillable = ['guest_value','code','organization_id','description'];

	// protected $appends =  ['guest_value_sar'];
    public function sectors(){
        return $this->hasMany(Sector::class);
    }

    public function organization(){
        return $this->belongsTo(Organization::class);
    }

    public function getOrganizationNameAttribute(){
        return $this->organization->name??trans('translation.no-organization');
    }

    public function getOrganizationNameAndCodeAttribute(){
        return $this->code . ' - ' . $this->organization_name  ;
    }

    public function getOrganizationNameWithPriceAttribute(){
        return isset($this->organization->name)? $this->guest_value_sar . ' - ' . $this->organization->name:trans('translation.no-organization');
    }

	public function getGuestValueSarAttribute(){
		return $this->guest_value . (app()->getLocale() == 'en' ? ' SAR' : ' ريال');
	}

	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'code' => 'code',
			'description' => 'description',
			'guest_value_sar' => 'guest-value',
			'organization_name' => 'organization',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'code' => 'text',
			'description' => 'text',
			'guest_value' => 'number',
			'description' => 'text',
			'organization_id' => 'select',
		);
	}

	public static function columnOptions()
	{
		return array(
			'organization_id' => Organization::all()->pluck('name_ar','id')->toArray(),
		);
	}
}
