<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stage extends Base
{
    protected $table = 'stages';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['created_at','deleted_at'];
protected $fillable = array('name','period_id','status_id','start_at','end_at','user_id','notes','is_pass');

    public function questions(){
        return $this->morphMany(Question::class,'questionable');
    }
    public function period(){
        return $this->belongsTo(Period::class);

    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}