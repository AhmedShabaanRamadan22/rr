<?php

namespace App\Models;

use App\Traits\CodeTrait;
use App\Traits\AttachmentTrait;
use App\Traits\HasCreatorLabelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fine extends Base
{
    use SoftDeletes, AttachmentTrait, CodeTrait, HasCreatorLabelTrait;
    protected $table = 'fines';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = ['fine_organization_id', 'user_id', 'order_sector_id', 'status_id'];
	// protected $appends =  ['code', 'attachment_url', 'notes'];

    public function fine_organization()
    {
		return $this->belongsTo(FineOrganization::class,);
	}

	public function user(){
		return $this->belongsTo(User::class,);
	}

	public function status(){
		return $this->belongsTo(Status::class,);
	}

	public function order_sector(){
		return $this->belongsTo(OrderSector::class)->withArchived();
	}

	public function monitor(){
		return $this->user->monitor();
	}

	public function attachments()
    {
        return $this->morphMany(Attachment::class,'attachmentable');
    }

	public function notes()
    {
        return $this->morphMany(Note::class, 'notable')->orderByDesc('created_at');
    }

	public function note()
    {
        return $this->morphOne(Note::class, 'notable')->latestOfMany('created_at');
    }

	public function track_location(){
        return $this->morphOne(TrackLocation::class, 'track_locationable')->latestOfMany('created_at') ?? null;
    }

    public function track_locations(){
        return $this->morphMany(TrackLocation::class, 'track_locationable') ?? []; 
    }

    public static function columnNames()
	{
		return array(
			'id' => 'id',
			'fine_name' => 'name',
			'user_name' => 'fine issuer',
			'status_id' => 'status',
            'order_sector' => 'order_sector',
            'organization_name' => 'organization',
            'code' => 'code',
			'more_details' => 'more_details',
		);
	}

    public static function filterColumns()
	{
		// return array(
		// 	'organization_id' => Organization::get(),
		// 	'sector_id' => Sector::get(),
		// 	'fine_id' => FineBank::get(),
		// 	'user_id' => User::get(),
		// );
		return array(
			'organization_id' => Organization::get()->pluck('name', 'id')->toArray(),
			'sector_id' => Sector::get()->pluck('label', 'id')->toArray(),
			'fine_id' => FineBank::get()->pluck('name', 'id')->toArray(),
			'user_id' => User::get()->pluck('name', 'id')->toArray(),
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
			'code' => 'text',
			// 'order_sector.name' => 'select',
            // 'fine_organization.organization.name' => 'select',
		);
	}

	public function getCodeAttribute(){
		return $this->generateCode($this);
		// $code = 'FIN'. str_pad($this->id, 5 ,'0', STR_PAD_LEFT);
		// return $code;	
	}

	public function getAttachmentUrlAttribute()
    {
        return $this->attachment_url_response_shape($this->attachments, $this);
    }

	public function getNotesAttribute()
    {
        return $this->notes()->orderByDesc('created_at')->get();
    }

	public function getOrganizationIdAttribute(){
        return $this->order_sector->sector->classification->organization_id;
    }

	public function order_sector_obj(){
		return $this->order_sector;
	}

}