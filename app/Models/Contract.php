<?php

namespace App\Models;

use App\Http\Controllers\ContractTemplateController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Base
{
	use SoftDeletes;

    protected $table = 'contracts';
	public $timestamps = true;


	protected $dates = ['deleted_at'];
    protected $fillable = array('contractable_id', 'contractable_type', 'is_approved','start_at','end_at','user_id','sign_date', 'contract_template_id');
    // protected $appends =  ['has_signed_contract'];
    // public function order()
    // {
    //     return $this->belongsTo(Order::class);
    // }
    
    public function contractable(){
        return $this->morphTo();
    }
    //! commented because of switching to morph
    // public function order_sector(){
    //     return $this->belongsTo(OrderSector::class);
    // }
    //!
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function sector(){
        return $this->belongsTo(Sector::class);
    }
    public function contract_template(){
        return $this->belongsTo(ContractTemplate::class);
    }
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }
    public function attachment()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::CONTRACT_LABEL)->latest('created_at');
    }
    public function signedContract()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::SIGNED_CONTRACT_LABEL)->latest('created_at');
    }
    public function getHasSignedContractAttribute()
    {
        return !is_null($this->signedContract);
    }
}