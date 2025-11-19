<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Otp extends Base
{


    protected $table = 'otps';
	public $timestamps = true;


	protected $dates = ['deleted_at'];
	protected $fillable = array('user_id', 'expired_at','value');


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(){
        return Carbon::now()->isAfter($this->expired_at);
    }
}
