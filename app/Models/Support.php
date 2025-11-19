<?php

namespace App\Models;

use App\Traits\AttachmentTrait;
use App\Traits\bySupervisorTrait;
use App\Traits\CodeTrait;
use App\Traits\HasCreatorLabelTrait;
use App\Traits\UuidableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Support extends Base
{
    protected $table = 'supports';
	public $timestamps = true;

	use SoftDeletes ,AttachmentTrait, CodeTrait, UuidableTrait, HasCreatorLabelTrait;

    //! use scope in trait that check order_sector>sector>supervisor_id
    use bySupervisorTrait;

	protected $dates = ['created_at','deleted_at'];
    protected $fillable = ['created_at', 'quantity', 'type','order_sector_id','period_id','has_enough','has_enough_quantity','status_id','user_id', 'reason_danger_id', 'meal_id'];//user_id = monitor_id

    const FOOD_TYPE = 2;
    const WATER_TYPE = 3;

	// protected $appends =  array();//, 'support_assist'];

	//AttachmentLabel::where('type', $this->getTable())->first()->id?? null;

    public function attachments()
    {
        return $this->morphMany(Attachment::class,'attachmentable');
    }
    public function support_attachments()
    {
        return $this->morphMany(Attachment::class,'attachmentable')->where('attachment_label_id', AttachmentLabel::SUPPORT_LABEL);
    }

    public function assists()
	{
		return $this->hasMany(Assist::class);
	}
	public function order_sector()
	{
		return $this->belongsTo(OrderSector::class)->withArchived();
	}

    public function user()
	{
		return $this->belongsTo(User::class);
	}
    public function period()
	{
		return $this->belongsTo(Period::class);
	}
    public function status()
	{
		return $this->belongsTo(Status::class)->where('type','supports');
	}

	public function reason_danger()
    {
        return $this->belongsTo(ReasonDanger::class);
    }
	public function notes()
	{
		return $this->morphMany(Note::class, 'notable')->orderBy('created_at', 'desc') ?? [];
	}

	public function note()
	{
		return $this->morphOne(Note::class, 'notable')->latestOfMany('created_at') ?? null;
	}

	public function track_location(){
        return $this->morphOne(TrackLocation::class, 'track_locationable')->latestOfMany('created_at') ?? null;
    }

    public function track_locations(){
        return $this->morphMany(TrackLocation::class, 'track_locationable') ?? [];
    }

	public static function types(){
		return $type_array = ['','',trans('translation.Food'),trans('translation.Water')];
	}

	public function getTypeNameAttribute(){
		return $this::types()[$this->type];
	}
	public static function columnNames(){
		$data = array(
			'id' => 'id',
			'code' => 'code',
			'type_name' => 'type',
			'periods' => 'periods',
			'label' => 'label',
			'reason' => 'reason',
			'sight' => 'sight',
			'providor' => 'providor',
			'reporter-name' => 'reporter-name',
			'monitor-name' => 'monitor-name',
			'supervisor-name' => 'supervisor',
			'boss-name' => 'boss',
			'quantity' => 'required-quantity',
			'delivered-quantity' => 'delivered-quantity',
			'has_enough_quantity' => 'has-enough',
			'all-notes' => 'all-notes',
			'status' => 'status',
			'create-time' => 'create-time',
			'update-time' => 'update-time',
		);
		// $is_chairman = auth()->check() && auth()->user()->hasRole('organization chairman');
        $can_view_action_column =  auth()->check() && auth()?->user()?->can('view_support_action_column');
        if($can_view_action_column){ // || !$is_chairman ){
            $data = array_merge( $data, ['action' => 'action']);
        }

        return $data;
	}
	public function getAttachmentUrlAttribute()
    {
        return $this->attachment_url_response_shape($this->attachments, $this);
    }

    public function getCodeAttribute()
    {
        return $this->generateCode($this);
    }

	public function getSupportAssistAttribute()
	{
		return $this->assists()->get() ?? [];
		// return $this->assists()->with('assistant', 'assigner')->get()??[];
	}

	public function getAssignedQuantityAttribute()
	{
		return $this->assists->sum('quantity') - $this->assists->where('status_id', Status::CANCELED_ASSIST)->sum('quantity');
	}

	public function getRemainingQuantityAttribute()
	{
		return $this->quantity - $this->assigned_quantity;
	}

	public function getDeliveredQuantityAttribute()
	{
		return $this->assists->where('status_id', Status::DELIVERED_ASSIST)->sum('quantity');
	}

	public static function progress_statuses()
	{
		return Status::admin_support_statuses()->get();
	}

	public static function canceled_statuses()
	{
		return Status::monitor_support_statuses()->get();
	}

	public function cancelable(){
		if(in_array($this->status_id, [Status::CANCELED_SUPPORT, Status::HAS_ENOUGH_SUPPORT, Status::CLOSED_SUPPORT])){
			return 0;
		}
		$assists = $this->assists()->get();
		if(isset($assists)){
			foreach($assists as $assist){
				if(in_array($assist->status_id, [Status::DELIVERED_ASSIST, Status::IN_PROGRESS_ASSIST])){
					return 0;
				}
			}
			return 1;
		}
		return 1;
	}

	public function getOrganizationIdAttribute(){
        return $this->order_sector->sector->classification->organization_id;
    }

	public function order_sector_obj(){
		return $this->order_sector;
	}

    public static function columnInputs(){
        return array(
            'order_sector' => 'select',
            'monitor' => 'select',
            'reason_danger' => 'select',
            'quantity' => 'number',
            'period' => 'select',
            'notes' => 'text',
            'attachments' => 'file',
        );
    }

    public static function columnOptions($organization = null, $operation_type = null)
    {
        $order_sectors = OrderSector::with('sector:id,label,classification_id', 'sector.classification:id,organization_id');
        if ($organization != null) {
            $order_sectors->whereHas('sector', function ($q) use ($organization) {
                $q->whereHas('classification', function ($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->whereDoesntHave('parent')->get();
        }

        $reason_danger = ReasonDanger::query()
            ->where('operation_type_id', $operation_type ); // operation_type_id = 2 For food support / 3 For Water support
        if ($organization != null) {
            $reason_danger = $reason_danger->where('organization_id', $organization->id);
        }

        $period = Period::query()
            ->where('operation_type_id', $operation_type ); // operation_type_id = 2 For food support / 3 For Water support
        if ($organization != null) {
            $reason_danger = $reason_danger->where('organization_id', $organization->id);
        }
        return array(
            'order_sector' => $order_sectors->get()->pluck('order_sector_name', 'id')->toArray(),
            'reason_danger' => $reason_danger->get()->pluck('name', 'id')->toArray(),
            'period' => $period->get()->pluck('name', 'id')->toArray(),
            'monitor' => [],//User::role('monitor')->get()->pluck('name', 'id')->toArray(),
        );
    }

    public static function columnSubtextOptions($organization = null)
    {
        $order_sectors = OrderSector::with([
			'sector:id,label',
            'order:id,facility_id,organization_service_id',
            'order.facility:id,name',
            'order.organization_service:id,service_id',
            'order.organization_service.service:id,name_ar,name_en',
            'monitor_order_sectors.monitor.user:id,name',
		]);
        if ($organization != null) {
            $order_sectors->whereHas('sector', function ($q) use ($organization) {
                $q->whereHas('classification', function ($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            });
        }
		$data = $order_sectors->get()->map(function ($orderSector) {
            return [
                'order_sector_id' => $orderSector->id,
                'users' => $orderSector->monitor_order_sectors->map(function ($monitor_order_sectors) {
                    return [
                        'id' => $monitor_order_sectors->monitor->user_id,
                        'name' => $monitor_order_sectors->monitor->user->name,
                    ];
                })->unique()->values(),
            ];
        });

        return array(
            'order_sectors' => $data,
        );
    }

	public function meal(){
		return $this->belongsTo(Meal::class);
	}

	public function link_meal_period($support_period){
		if($support_period == Period::BREAKFAST_FOOD_SUPPORT){
			return Period::BREAKFAST_MEAL;
		}
		elseif($support_period == Period::LUNCH_FOOD_SUPPORT){
			return Period::LUNCH_MEAL;
		}
		elseif($support_period == Period::DINNER_FOOD_SUPPORT){
			return Period::DINNER_MEAL;
		}
		else{
			return null;
		}
	}



    //* Scopes *//

    public function scopeToday($query){
        return $query->whereDate('created_at', now()->toDateString());
    }

    public function scopeClosed($query){
        return $query->whereIn('status_id',[Status::CLOSED_SUPPORT,Status::HAS_ENOUGH_SUPPORT]);
    }

    public function scopeNotClosed($query){
        return $query->whereIn('status_id',[Status::NEW_SUPPORT,Status::IN_PROGRESS_SUPPORT,Status::PROCESSING_SUPPORT]);
    }

    public function scopeNew($query){
        return $query->where('status_id',Status::NEW_SUPPORT);
    }
}
