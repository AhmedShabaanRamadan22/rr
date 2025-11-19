<?php

namespace App\Models;

use App\Traits\AttachmentTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityEmployee extends Base
{
    use HasFactory, AttachmentTrait, SoftDeletes;
    protected $table = 'facility_employees';
	public $timestamps = true;

    protected $dates = ['deleted_at'];
	protected $fillable = array('national_id','name','facility_employee_position_id','facility_id');

    // protected $appends = ['work_card_photo','health_photo', 'work_card_photo_url', 'health_card_photo_url'];
    protected $appends = ['facility_name','attachmentUrl', 'position_name', 'health_photo', 'health_card_photo_url', 'work_card_photo', 'work_card_photo_url', 'national_id_attachment', 'national_id_attachment_url', 'personal_photo', 'personal_photo_url','employee_cv_attachment','employee_cv_attachment_url'];


    public function facility(){
        return $this->belongsTo(Facility::class);
    }

    public function facility_employee_position(){
        return $this->belongsTo(FacilityEmployeePosition::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class,'attachmentable');
    }

    public function attachments_work_card_photo()
    {
        return $this->morphMany(Attachment::class,'attachmentable')->where('label','work_card_photo');
    }

    public function attachment_work_card_photo()
    {
    return $this->morphOne(Attachment::class,'attachmentable')->where('attachment_label_id',AttachmentLabel::WORK_CARD_LABEL)->latest('created_at');
    }

    public function attachments_health_photo()
    {
        return $this->morphMany(Attachment::class,'attachmentable')->where('label','health_photo');
    }

    public function attachment_health_photo()
    {
        return $this->morphOne(Attachment::class,'attachmentable')->where('attachment_label_id',AttachmentLabel::HEALTH_CARD_LABEL)->latest('created_at');
    }
    public function attachments_national_id()
    {
        return $this->morphMany(Attachment::class,'attachmentable')->where('label','national_id');
    }

    public function attachment_national_id()
    {
        return $this->morphOne(Attachment::class,'attachmentable')->where('attachment_label_id',AttachmentLabel::EMPLOYEE_NATIONAL_ID_LABEL)->latest('created_at');
    }
    public function attachments_personal_photo()
    {
        return $this->morphMany(Attachment::class,'attachmentable')->where('label', 'personal_photo');
    }

    public function attachment_personal_photo()
    {
        return $this->morphOne(Attachment::class,'attachmentable')->where('attachment_label_id',AttachmentLabel::PERSONAL_PHOTO_LABEL)->latest('created_at');
    }
    public function attachments_employee_cv()
    {
        return $this->morphMany(Attachment::class,'attachmentable')->where('label', 'employee_cv');
    }

    public function attachment_employee_cv()
    {
        return $this->morphOne(Attachment::class,'attachmentable')->where('attachment_label_id',AttachmentLabel::EMPLOYEE_CV)->latest('created_at');
    }

    public function getWorkCardPhotoUrlAttribute(){
        return $this->attachment_work_card_photo->url??'-';
    }

    public function getHealthCardPhotoUrlAttribute(){
        return $this->attachment_health_photo->url?? '-';
    }

    public function getNationalIdAttachmentUrlAttribute(){
        return $this->attachment_national_id->url??'-';
    }

    public function getEmployeeCvAttachmentUrlAttribute(){
        return $this->attachment_employee_cv->url??'-';
    }

    public function getPersonalPhotoUrlAttribute(){
        return $this->attachment_personal_photo->url?? asset('build/images/users/32/person.png');
    }

    public function getWorkCardPhotoAttribute(){
        return $this->attachment_work_card_photo->real_path??'-';
    }

    public function getHealthPhotoAttribute(){
        return $this->attachment_health_photo->real_path??'-';
    }

    public function getNationalIdAttachmentAttribute(){
        return $this->attachment_national_id->real_path??'-';
    }

    public function getPersonalPhotoAttribute(){
        return $this->attachment_personal_photo->real_path??'-';
    }

    public function getEmployeeCvAttachmentAttribute(){
        return $this->attachment_employee_cv->real_path??'-';
    }

    public function getAttachmentUrlAttribute(){
        return $this->attachment_url_response_shape($this->attachments, $this);
	}

    public function getFacilityNameAttribute(){
        $name = $this->facility->name ?? '' ;
        $this->unsetRelations(['facility']);
        return $name;
    }

    public function getPositionNameAttribute(){
        $name = $this->facility_employee_position->name ?? '' ;
        $this->unsetRelations(['facility_employee_position']);
        return $name;
    }
    public static function columnNames()
    {
        return array('id', 'name', 'national-id', 'facility_employee_position', 'employee_attachments');
        // return array('id', 'name', 'national-id', 'facility_employee_position', 'national-id-attachment', 'work-card-photo', 'health-card-photo', 'personal_photo','employee-cv-attachment');
    }

    public function scopeFacility($query, $facility)
    {
        return $query->where('facility_id', $facility);
    }

}
