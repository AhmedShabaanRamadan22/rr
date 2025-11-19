<?php

namespace App\Models;

use App\Traits\AttachmentTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assist extends Base
{
    protected $table = 'assists';
	public $timestamps = true;

	use SoftDeletes, AttachmentTrait;

    protected $dates = ['created_at', 'deleted_at'];
    protected $fillable = ['quantity', 'support_id', 'assigner_id', 'assistant_id', 'assist_sector_id', 'status_id'];
    // protected $appends =  ['attachment_url', 'assistant_info', 'assigner_info']; //,'signature'];

   public function assist_sent() {
    return $this->belongsTo(Sector::class, 'assist_sector_id', 'id');
   }

    public function attachments()
    {
        return $this->morphMany(Attachment::class,'attachmentable');
    }
    public function signature_attachment()
    {
        return $this->morphOne(Attachment::class,'attachmentable')->where('attachment_label_id', AttachmentLabel::ASSIST_SIGNATURE_LABEL)->latest('created_at');
    }

    public function assist_attachments()
    {
        return $this->morphMany(Attachment::class,'attachmentable')->where('attachment_label_id', AttachmentLabel::ASSIST_MEDIA_LABEL);
    }
    public function assistant()
	{
		return $this->belongsTo(User::class, 'assistant_id');
	}
    public function assigner()
	{
		return $this->belongsTo(User::class, 'assigner_id');
	}
    public function support()
    {
        return $this->belongsTo(Support::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class)->where('type', 'assists');
    }
    public function answers()
    {
        return $this->morphMany(Answer::class,'answerable');
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment_url_response_shape($this->attachments, $this);
    }

    public function getAssistantInfoAttribute()
    {
        // $this->relationLoaded('assistant');
        // return $this->assistant->last();
        return $this->assistant->load('bravo');
    }

    public function getAssignerInfoAttribute()
    {
        return $this->assigner;
    }

    public function getAssistFromAttribute()
    {
        if( $this->assist_sector_id == 0 ){
            return trans('translation.assist company');
        }else{
            return Sector::find($this->assist_sector_id)?->label;
        }
    }
    public static function columnOption($support, $organization = null)
    {
        $assists = [0 => trans('translation.assist company')];
        $sectors = Sector::with('monitor_order_sectors.monitor.user')->whereNot('id', $support->order_sector->sector_id)->whereHas('classification.organization', function ($q) use ($organization) {
            $q->where('id', $organization->id);
        });
        $assistant_representer = User::whereHas('roles', function ($q) use ($organization) {
            $q->where('name', ['assistant representer']);
        })->pluck('name', 'id')->toArray();
        // $monitors = $sectors->get()->pluck('monitor_order_sectors')->flatten()->unique('monitor_id')->pluck('monitor.user.name', 'monitor.user.id')->toArray();
        $monitors = Monitor::with('user')->get()->unique('user_id')->pluck('user.name', 'user.id')->toArray();

        return array(
            'assist_from' => $assists + $sectors->pluck('label', 'id')->toArray(),
            'assistant_id' => $assistant_representer,
            'monitors' => $monitors
        );
    }
    public static function columnSubtextOption( $support,$organization = null)
    {
        $sectors = Sector::with('monitor_order_sectors', 'monitor_order_sectors.monitor.user', 'monitor_order_sectors.order_sector.sector')->whereNot('id', $support->order_sector->sector_id)->whereHas('classification.organization', function ($q) use ($organization){
            $q->where('id', $organization->id);
        });
        $monitors = $sectors->get()->pluck('monitor_order_sectors')->flatten()->unique('monitor_id')->pluck('order_sector.sector_name', 'monitor.user.id')->toArray();
//        $monitors = Monitor::all()->unique('user_id')->pluck('user.name', 'user.id')->toArray();

        return array(
            'monitors' => $monitors,
        );
    }

    public function track_location(){
        return $this->morphOne(TrackLocation::class, 'track_locationable')->latestOfMany('created_at') ?? null;
    }

    public function track_locations(){
        return $this->morphMany(TrackLocation::class, 'track_locationable') ?? [];
    }

    public function getOrganizationIdAttribute(){
        return $this->support->order_sector->sector->classification->organization_id;
    }

    public function order_sector_obj(){
        return $this->support->order_sector;
    }

    public function getSupportTypeAttribute(){
        return $this->support->type;
    }

    public static function columnNames(){
        return array(
            'id' => 'id',
            'code' => 'code',
            'type_name' => 'type',
            'periods' => 'periods',
            'label' => 'label',
            'reason' => 'reason',
            'providor' => 'providor',
            'reporter-name' => 'reporter-name',
            'monitor-name' => 'monitor-name',
            'supervisor-name' => 'supervisor',
            'boss-name' => 'boss',
            'support.quantity' => 'required-quantity',
            'delivered-quantity' => 'delivered-quantity',
            'support.has_enough_quantity' => 'has-enough',
            'all-notes' => 'all-notes',
            'support_status' => 'support_status',
            'support-create-time' => 'support-create-time',
            'support-update-time' => 'support-update-time',
            'support_id' => 'support_id',
            'assigner' => 'assigner_name',
            'assistant' => 'assistant_name',
            'assist_sector' => 'assist_sector',
            'quantity' => 'assist_quantity',
            'assist_status' => 'assist_status',
            'create-time' => 'assist-create-time',
            'update-time' => 'assist-update-time',
            'action' => 'action'
        );
    }

    public static function filterColumns(){
        return array(
            'order_sector_id' => Support::with('order_sector')->get()->pluck('order_sector.order_sector_name', 'order_sector.id')->toArray(),
        );
    }

    public static function columnInputs(){
        return array();
    }
}
