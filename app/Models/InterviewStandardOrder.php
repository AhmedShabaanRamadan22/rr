<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewStandardOrder extends Base
{
    use SoftDeletes;
    protected $table = 'interview_standard_orders';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('interview_standard_id','order_id','score','max_score');

    public function order()
	{
		return $this->belongsTo(Order::class);
	}
    public function interview_standard()
	{
		return $this->belongsTo(InterviewStandard::class);
	}

	public function getNameAttribute(){
		return $this->interview_standard->name??'-';
	}
}
