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

class Ticket extends Base
{
    use SoftDeletes, AttachmentTrait, CodeTrait, UuidableTrait, HasCreatorLabelTrait;

    //! use scope in trait that check order_sector>sector>supervisor_id
    use bySupervisorTrait;

    protected $table = 'tickets';
    public $timestamps = true;

    protected $fillable = ['reason_danger_id', 'user_id', 'status_id', 'order_sector_id', 'code', 'closed_at', 'updated_at'];
    protected $dates = ['created_at', 'deleted_at', 'closed_at'];
    // protected $appends = ['notes', 'attachment_url', 'code'];

    public function ticket_attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::TICKET_LABEL);
    }

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class)->where('type', 'tickets');
    }
    public function order_sector()
    {
        return $this->belongsTo(OrderSector::class)->withArchived();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reason_danger()
    {
        return $this->belongsTo(ReasonDanger::class);
    }
    public static function columnNames()
    {
        // return array('id', 'code', 'danger', 'reason', 'label', 'sight', 'providor', 'reporter-name', 'monitor-name', 'monitor-bravo', 'organization', 'status', 'create-time', 'update-time', 'closed-time', 'action');
        $data = array(
            'id' => 'id',
            'code' => 'code',
            'level' => 'danger',
            'ticket_reason_id' => 'reason',
            'label' => 'label',
            'sight' => 'sight',
            'provider_name' => 'providor',
            'reporter_name' => 'reporter-name',
            'monitor' => 'monitor-name',
            'bravo' => 'monitor-bravo',
            'organization_name' => 'organization',
            'status_id' => 'status',
            'created_at' => 'create-time',
            'updated_at' => 'update-time',
            'closed_at' => 'closed-time',
        );
        // $is_chairman = auth()->user() != null ? (auth()->user()->hasRole('organization chairman')) : false;
        $can_view_action_column = auth()->check() && auth()?->user()?->can('view_ticket_action_column');
        if($can_view_action_column){
            $data = array_merge( $data, ['action' => 'action']);
        }

        return $data;
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable'); //->where('label', 'ticket');
    }
    public function notes()
    {
        return $this->morphMany(Note::class, 'notable')->orderByDesc('created_at') ?? [];
    }

    public function note()
    {
        return $this->morphOne(Note::class, 'notable')->latestOfMany('created_at') ?? null;
    }

    public function track_location()
    {
        return $this->morphOne(TrackLocation::class, 'track_locationable')->latestOfMany('created_at') ?? null;
    }

    public function track_locations()
    {
        return $this->morphMany(TrackLocation::class, 'track_locationable') ?? [];
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment_url_response_shape($this->attachments, $this);
    }

    public function getCodeAttribute()
    {
        return $this->generateCode($this);
    }

    public static function columnInputs()
    {
        return array(
            'order_sector' => 'select',
            'monitor' => 'select',
            'reason_danger' => 'select',
            'notes' => 'text',
            'attachments' => 'file',
        );
    }

    public static function columnOptions($organization = null)
    {
        $order_sectors = OrderSector::with(
            'sector:id,label',
            'order:id,facility_id,organization_service_id',
            'order.facility:id,name',
//            'order.organization_service:id,service_id',
            'order.organization_service.service:id,name_ar,name_en'
        );
        if ($organization != null) {
            $order_sectors->whereHas('sector', function ($q) use ($organization) {
                $q->whereHas('classification', function ($q) use ($organization) {
                    $q->where('organization_id', $organization->id);
                });
            })->whereDoesntHave('parent')->get();
        }

        //$monitors = User::role('monitor');

        $reason_danger = ReasonDanger::with('reason:id,name')
            ->where('operation_type_id', OperationType::RAISE_TICKET); // operation_type_id = 1 For Raised TICKET
        if ($organization != null) {
            $reason_danger = $reason_danger->where('organization_id', $organization->id);
        }

        return array(
            'order_sector' => $order_sectors->get()->pluck('order_sector_name', 'id')->toArray(),
            'reason_danger' => $reason_danger->get()->pluck('name', 'id')->toArray(),
            'monitor' => [],
        );
    }
    public static function columnSubtextOptions($organization = null)
    {
        $order_sectors = OrderSector::with(
            'sector:id,label',
            'order:id,facility_id,organization_service_id',
            'order.facility:id,name',
            'order.organization_service:id,service_id',
            'order.organization_service.service:id,name_ar,name_en',
            'monitor_order_sectors.monitor.user:id,name',
        );
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
                'users' => $orderSector->monitor_order_sectors->map(function ($monitor) {
                    return [
                        'id' => $monitor->monitor->user_id,
                        'name' => $monitor->monitor->user->name,
                    ];
                })->unique()->values(),
            ];
        });

        return array(
            'order_sectors' => $data,
        );
    }

    public function getOrganizationIdAttribute(){
        return $this->order_sector->sector->classification->organization_id;
    }

	public function order_sector_obj(){
		return $this->order_sector;
	}

    //* Scopes *//

    public function scopeToday($query){
        return $query->whereDate('created_at', now()->toDateString());
    }

    public function scopeClosed($query){
        return $query->where('status_id',Status::CLOSED_TICKET);
    }

    public function scopeWrong($query){
        return $query->where('status_id',Status::FALSE_TICKET);
    }

    public function scopeNotClosed($query){
        return $query->whereIn('status_id',[Status::NEW_TICKET,Status::IN_PROGRESS_TICKET,Status::PROCESSING_TICKET]);
    }

    public function scopeNew($query){
        return $query->where('status_id',Status::NEW_TICKET);
    }
}
