<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Continent extends Base
{
	use SoftDeletes;

    protected $table = 'continents';
	public $timestamps = true;


	protected $dates = ['deleted_at'];

    protected $fillable = array('name','code');

    public function countries()
    {
        return $this->hasMany(Country::class);
    }

}

