<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use LaravelArchivable\Archivable;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderSector extends Base
{
	protected $table = 'order_sectors';
	public $timestamps = true;

	use SoftDeletes, Archivable;

	protected $dates = ['created_at', 'deleted_at'];
	protected $fillable = ['order_id', 'sector_id', 'parent_id'];
	// protected $appends = ['name', 'is_active', 'child_names', 'order_sector_name','sector_name', 'order_name' , 'sector_monitors'];


	public function order()
	{
		return $this->belongsTo(Order::class);
	}

	public function service(){
		return $this->order->organization_service->service;
	}

	public function children()
	{
		return $this->hasMany(OrderSector::class, 'parent_id', 'id');
	}

	public function parent()
	{
		return $this->belongsTo(OrderSector::class, 'parent_id', 'id');
	}
	public function sectors()
	{
		return $this->belongsTo(Sector::class);
	}
	public function sector()
	{
		return $this->belongsTo(Sector::class);
	}
	public function meals()
	{
		return $this->hasMany(Meal::class);
	}
	public function submitted_forms()
	{
		return $this->hasMany(SubmittedForm::class,);
	}
	public function submitted_form()
	{
		return $this->hasOne(SubmittedForm::class,)->latest();//->first();
	}

	public function monitor_order_sectors(){
        return $this->hasMany(MonitorOrderSector::class,)->withArchived();
    }

	public function monitors(){
		return $this->belongsToMany(Monitor::class, 'monitor_order_sectors');
	}

    public function monitor_order_sector(){
        return $this->monitor_order_sectors()->latest('id')->first();
        // return $this->hasOne(MonitorOrderSector::class, 'order_sector_id', 'id')->first();
    }

	public function contract(){
		return $this->morphOne(Contract::class, 'contractable')->latest();
	}

	public function contracts(){
		return $this->morphMany(Contract::class, 'contractable');
	}

	public function service_contract($service){
		return $this->contracts()->whereHas('contract_template', function($query) use ($service){
			$query->where('type', $service);
		})->get();
	}

	public function has_parent()
	{
		// return $this->parent()->first() ? true : false; //this is the old expression NOT OPTIMIZED
		return $this->parent ? true : false;
	}

	public function getIsActiveAttribute()
	{
		return !$this->has_parent();
	}

	public function getChildNamesAttribute()
	{
		return $this->children->isEmpty() ? trans('translation.order-sector-has-no-children') : $this->children->pluck('order.facility.name')->implode(',');
		// return (is_null($this->children)) ?  trans('translation.order-sector-has-no-children') :  ($this->children->pluck('order.facility.name')->implode(','));
		// $children = $this->children()->with('order.facility')->get();
		// return $children->isEmpty() ? trans('translation.order-sector-has-no-children') :  ($children->pluck('order.facility.name'));
	}

	public function getNameAttribute()
	{
		return $this->order->facility->name??'-';
	}

	public function getOrderSectorNameAttribute(){
		return ($this->sector->label??'-') . ' - ' . ($this->order->organization_service->service->name??'=') . ' - ' . ($this->order->facility->name??'-') . ' - ' . ($this->order->organization_service->organization->name??'-');
	}

	public function getOrderSectorNameWithoutServiceAttribute(){
		return ($this->sector->label??'-') . ' - ' . ($this->order->facility->name??'-') . ' - ' . ($this->order->organization_service->organization->name??'-');
	}

    public function getOrderNameAttribute(){
		return ($this->order->facility->name??'-') . ' - ' . ($this->service()->name??'-');
	}

    public function getSectorNameAttribute(){
		return $this->sector->label;
	}

	public static function columnInputs()
	{
		return array();
	}

	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'facility-name',
			'child_names' => 'child facilities',
			'sector_label' => 'sector-label',
			'sight' => 'sight',
			'service' => 'service',
			'organization' => 'organization',
			// 'order_sector_name' => 'order info',
			'boss' => 'boss',
			'supervisor' => 'supervisor',
			'monitors' => 'monitors',
		);
	}

	public function getSectorMonitorsAttribute(){
		$monitors = collect();
		$this->monitor_order_sectors->each(function ($q) use($monitors){
			$monitors->push($q->monitor);
		});
		return $monitors;
		// return $this->monitors;
	}
	
	public function getMonitorsNameAttribute()
	{
		return $this->monitor_order_sectors->pluck('monitor.user.name')->unique()->toArray();
	}
	
	public function getMonitorsLabelAttribute()
	{
		$monitors = $this->monitor_order_sectors;

		if (config('app.use_monitor_code')) {
			return $monitors->pluck('monitor.code')->unique()->toArray();
		}

		return $monitors->pluck('monitor.user.name')->unique()->toArray();
	}

	public function fine(){
		return $this->hasOne(Fine::class,)->latest();//->first();
	}
	public function fines(){
		return $this->hasMany(Fine::class,);
	}

	public function support(){
		return $this->hasOne(Support::class,)->latest();//->first();
	}
	public function supports(){
		return $this->hasMany(Support::class,);
	}
	public function ticket(){
		return $this->hasOne(Ticket::class,)->latest();//->first();
	}
	public function tickets(){
		return $this->hasMany(Ticket::class,);
	}

	public function getHasOperationsAttribute(){
		if($this->is_active){
			// $mos= $this->monitor_order_sector;
			$contract = $this->contract;
			$support = $this->support;
			$ticket = $this->ticket;
			$submitted_form = $this->submitted_form;
			$fine = $this->fine;

			if (!is_null($contract) || !is_null($support) || !is_null($ticket) || !is_null($submitted_form) || !is_null($fine)) {
				// At least one of the variables is not null
				return true;
			}
			//else its a parent but doesnt has any operation 
			//return false
		}
		return false; //not parent ->> no operations 
	}

	public function getOrganizationIdAttribute(){
        return $this->sector->classification->organization_id;
    }

	public function scopeActive(Builder $query): void
    {
        $query->whereNull('parent_id');
    }
}