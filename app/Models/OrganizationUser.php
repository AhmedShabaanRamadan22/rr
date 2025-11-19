<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationUser extends Base
{
    use SoftDeletes;
	protected $table = 'organization_user';
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
