<?php

namespace App\Models;

use App\Traits\LocalizationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Base
{
    use SoftDeletes, LocalizationTrait;
    protected $table = 'saudi_cities';
    protected $fillable = ['name_ar', 'name_en' ];
    // protected $appends =  ['name'];

    public function districts(){
        return $this->hasMany(District::class);
    }

    public function getNameAttribute(){
        return $this->localizeName();
	}

    public function organizations()
{
    return $this->hasMany(Organization::class);
}
}
