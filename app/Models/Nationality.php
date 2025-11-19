<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class Nationality extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name','flag'];
    // protected $appends =  ['flag_icon'];

    
	public function nationality_organizations()
	{
		return $this->hasMany(NationalityOrganization::class);
	}

	public function organization()
	{
		return $this->belongsToMany(Organization::class, NationalityOrganization::class)->wherePivotNull('deleted_at');
	}

    public function getFlagIconAttribute()  {
        return '<img src="'.URL::asset('build/images/flags/'.(strtolower($this->flag)).'.svg').'" class="m-1" alt="Header Language" height="15">';
    }
    public function getIconAttribute()  {
        return URL::asset('build/images/flags/'.(strtolower($this->flag)).'.svg');
    }

	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'nationality-name',
			'flag_icon' => 'flag',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
			'flag' => 'select',
		);
	}

	public static function columnOptions()
	{
		return array(
			'flag' => Country::all()->pluck('flag_image','code')->toArray(),
		);
	}
}