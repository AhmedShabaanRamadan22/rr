<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NationalityOrganization extends Base
{
    use HasFactory, SoftDeletes;

    protected $table = 'nationality_organizations';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = array('nationality_id','organization_id');

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function nationality()
    {
        return $this->belongsTo(Nationality::class);
    }

    public function menu(){
        return $this->hasMany(Menu::class);
    }

    public function food_weights(){
		return $this->belongsToMany(FoodWeight::class,'menus');
	}

    public function sectors(){
        return $this->hasMany(Sector::class);
    }
    public static function columnNames()
    {
        return array('id', 'nationality-name', 'flag', 'menu', 'action');
    }
    
}