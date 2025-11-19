<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class Attachment extends Base {

	protected $table = 'attachments';
	public $timestamps = true;

	const VIDEO = ['mp4', 'mov'];
	const IMAGE = ['jpg', 'png', 'jpeg'];
	const DOC = ['pdf'];

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('name', 'path', 'attachmentable_id', 'attachmentable_type','user_id', 'attachment_label_id');

	// protected $appends =  ['label', 'type','placeholder'];

	public function order()
	{
		return $this->answer->order();
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function answer()
	{
		return $this->belongsTo(Answer::class);
	}

    public function facility()
	{
		return $this->belongsTo(Facility::class);
	}
    public function facility_employees()
	{
		return $this->belongsTo(FacilityEmployee::class);
	}

    public function attachmentable(){
        return $this->morphTo();
    }

	public function getBinaryDataAttribute(){
		return Storage::get( ($this->path??'') . '/' . ($this->name??''));
	}

	public function getRealPathAttribute(){
		return \Storage::disk()->url( ($this->path??'') . '/' . ($this->name??''));
	}

	public function getUrlAttribute(){
		if( is_production() ){
			return \Storage::disk('s3')->temporaryUrl(($this->path??'') . '/' . ($this->name??''), now()->addMinutes(7*24*60)); // 60 to convert to hours, 24 to convert to day,30 to convert to month,>
			// return \Storage::disk('s3')->temporaryUrl(($this->path??'') . '/' . ($this->name??''), now()->addMinutes(48*60));
		}
		return url('/') . $this->real_path;
	}

	public function getLabelAttribute(){
		return $this->attachment_label->label?? '';
	}

	public function getPlaceholderAttribute(){
		return $this->attachment_label->placeholder?? '';
	}

	public function getTypeAttribute(){
		$extension = \File::extension($this->name);
		switch ($extension) {
			case in_array($extension, $this::VIDEO):
				return 'VIDEO';
			case in_array($extension, $this::IMAGE):
				return 'IMAGE';
			case in_array($extension, $this::DOC):
				return 'DOC';
			default:
				return '';
		}
	}


	public function scopeImages($query)
	{
		return $query->whereRaw("name REGEXP '\\.(jpg|jpeg|png|gif|webp)$'");
	}
	
	public function scopeAnswer($query)
    {
        return $query->where('attachmentable_type', 'like', '%Answer');
    }

	public function attachment_label(){
		return $this->belongsTo(AttachmentLabel::class);
	}

}
