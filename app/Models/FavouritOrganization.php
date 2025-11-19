<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FavouritOrganization extends Base
{
    use SoftDeletes;
	protected $table = 'favourit_organization';
	public $timestamps = true;


	protected $dates = ['deleted_at'];
	protected $fillable = array('user_id','organization_id');

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function organization(){
        return $this->belongsTo(Organization::class);
    }
}
