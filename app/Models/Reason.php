<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reason extends Base
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'reasons';
    public $timestamps = true;
    protected $dates = ['created_at', 'deleted_at'];
    protected $fillable = ['name'];
    
    // public function tickets()
    // {
    //     return $this->hasMany(Ticket::class);
    // }

    public function reason_dangers(){
        return $this->hasMany(ReasonDanger::class);   
    }

	// public function operation_type(){
    //     return $this->belongsTo(OperationType::class);   
    // }

	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'reason-name',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
		);
	}
}