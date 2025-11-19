<?php

namespace App\Models;

use App\Traits\LocalizationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Base
{
    protected $table = 'banks';
    public $timestamps = true;

    use SoftDeletes, LocalizationTrait;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name_ar', 'name_en');
    // protected $appends =  ['name'];

    public function ibans()
    {
        return $this->hasMany(Iban::class);
    }
    // public function users(){
    //     return $this->hasMany(User::class);
    // }
    // public function facilities(){
    //     return $this->hasMany(Facility::class);
    // }
    public function getNameAttribute()
    {
        return $this->localizeName();
    }

    public static function columnNames()
    {
        return array(
            'id' => 'id',
            'name_ar' => 'name-ar',
            'name_en' => 'name-en',
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