<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use LaravelArchivable\Archivable;

class MonitorOrderSector extends Base
{
    protected $table = 'monitor_order_sectors';
	public $timestamps = true;

	use SoftDeletes, Archivable;

	protected $dates = ['created_at','deleted_at'];
    protected $fillable = ['user_id','order_sector_id','monitor_id'];//,'is_active'];
    
	// public function sector()
	// {
	// 	return $this->belongsTo(Sector::class);
	// }
    public function monitor()
	{
		return $this->belongsTo(Monitor::class);
	}

	public function order_sector(){
		return $this->belongsTo(OrderSector::class)->withArchived();
	}

	public function getMonitorNameAttribute(){
		return $this->monitor->name;
	}
}