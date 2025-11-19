<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationAttachmentLabel extends Base
{
    use SoftDeletes;
    protected $table = 'organization_attachments_labels';
    public $timestamps = true;
    protected $dates = ['created_at', 'deleted_at'];
    protected $fillable = ['organization_id', 'attachment_label_id','notes'];

    // protected $appends =  ['label','placeholder'];

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class,);
    }
    public function attachment_labels()
    {
        return $this->belongsTo(AttachmentLabel::class,);
    }

    public function attachment_label()
    {
        return $this->belongsTo(AttachmentLabel::class, )->where('id', $this->attachment_label_id);
    }

    public function getLabelAttribute(){
        return $this->attachment_label->label?? '';
    }

    public function getPlaceholderAttribute(){
        return $this->attachment_label->placeholder?? '';
    }



}
