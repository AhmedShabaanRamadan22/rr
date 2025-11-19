<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Monitor extends Base
{
	use SoftDeletes;

	protected $table = 'monitors';
	public $timestamps = true;


	protected $dates = ['created_at', 'deleted_at'];
	protected $fillable = ['user_id', 'code',/*'bravo_number','has_receive','has_return'*/];
	// protected $appends =  ['name'];
	public function user()
	{
		return $this->belongsTo(User::class,);
	}
	public function monitor_order_sectors()
	{
		return $this->hasMany(MonitorOrderSector::class);
	}
	public function supports()
	{
		return $this->hasMany(Support::class);
	}
	public function assists()
	{
		return $this->hasMany(Assist::class);
	}
	public function monitors_in_same_sector(){
		return MonitorOrderSector::get()->whereIn('id', $this->monitor_order_sectors->pluck('order_sector_id'));
	}
	public function assigned_sectors()
	{
		return OrderSector::get()->whereIn('id', $this->monitor_order_sectors->pluck('order_sector_id'))->pluck('order_sector_name','id');
	}
	public function unassigned_sectors()
	{
		return OrderSector::get()->whereNull('parent')->whereNotIn('id', $this->monitor_order_sectors->pluck('order_sector_id'))->pluck('order_sector_name','id');
	}
	public function swap_unassigned(){
		return MonitorOrderSector::whereNot('monitor_id', $this->id)->whereNotIn('order_sector_id', $this->assigned_sectors())->get()->pluck('order_sector.order_sector_name', 'id');
	}
	public function swap_monitors()
	{
		// dd(MonitorOrderSector::whereNot('monitor_id', $this->id)->get()->pluck('monitor.name', 'id'));
		return MonitorOrderSector::whereNot('monitor_id', $this->id)->get();
	}
	// public function order_sectors(){
	// 	return $this->hasMany(OrderSector::class, MonitorOrderSector::class,);//doenst work
	// }

	public function submitted_forms()
	{
		//this monitor has many submitted forms where SubmittedForm monitor_id == this id
		return $this->hasMany(SubmittedForm::class, 'monitor_id', 'id');
	}

	// public function all_order_sectors(){
	// 	$other_facilities = $this->monitor_order_sectors()->get()->load('order_sector.children.order.facility:id');
	// 	return $other_facilities;
	// }
	function getNameAttribute(){
		$this->setHidden(['user']);
		return $this->user->name;
	}
	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'monitor' => 'monitor-name',
			'role-name' => 'role-name',
			'phone' => 'user-phone-num',
			'code' => 'code',
			'bravo_number' => 'bravo-number',
            'bravo_code' => 'bravo-code',
			'order_sectors' => 'sector - service',
			'reports' => 'reports',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'monitor_name' => 'select',
			// 'roles' => 'select',
			// 'code' => 'text',
		);
	}

	public static function columnOptions($organization = null)
	{
		return array(
			'monitor_name' => User::whereNotIn('id', Monitor::get()->pluck('user_id')->toArray())->pluck('name', 'id')->toArray(),
			'roles' => Role::whereIn('id', [Role::BOSS, Role::SUPERVISOR])->pluck('name','id')->toArray(),
		);
	}

	public function getSectorsAttribute()
	{
		return OrderSector::get()->whereIn('id', $this->monitor_order_sectors->pluck('order_sector_id'))->pluck('sector.label');
	}

	public function getSectorsForOrganizations($organizationIds = [1, 2])
	{
		return $this->monitor_order_sectors
			->filter(function ($mos) use ($organizationIds) {
				return in_array($mos->order_sector->sector->classification->organization_id, $organizationIds);
			})
			->pluck('order_sector.sector.label')
			->sort()
			->values();
	}	
	
	public function getSectorsSpecificOrganizationAttribute()
	{
		return $this->getSectorsForOrganizations([1, 2]);
	}
}