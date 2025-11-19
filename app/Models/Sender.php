<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sender extends Base
{
    protected $table = 'senders';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name', 'whatsapp_instance_id', 'whatsapp_token', 'email', 'phone_app_sid', 'phone_sender_id','send_sms','operation_support_phone');

    // protected $appends =  [
    //     'has_selected',
    //     'has_whatsapp',
    //     'has_email',
    //     'has_sms',
    //     'able_to_send_sms',
    // ];

    public function organization()
    {
        return $this->hasOne(Organization::class);
    }

    public function getHasSelectedAttribute()
    {
        return $this->organization != null;
    }

    public function getHasWhatsappAttribute() {
        return $this->whatsapp_instance_id != null && $this->whatsapp_token != null;
    }

    public function getHasEmailAttribute() {
        return $this->email != null;
    }

    public function getHasSmsAttribute() {
        return $this->phone_app_sid != null && $this->phone_sender_id != null;
    }

    public function getAbleToSendSmsAttribute() {
        return $this->send_sms == 1;
    }

    public function optionProperty($organization)
    {
     if(!$this->has_selected){
        return '';
     }elseif($this->has_selected){
        if(($this->organization->id??0) == $organization->id ){
            return 'selected';
        }
        return 'disabled';
     }
     return '-';
    }

    public static function columnNames()
    {
        return array(
            'id' => 'id',
            'name' => 'name',
            'whatsapp_instance_id' => 'whatsapp-instance-id',
            'whatsapp_token' => 'whatsapp-token',
            'email' => 'email',
            'phone_app_sid' => 'phone-app-sid',
            'phone_sender_id' => 'phone-sender-id',
            'organization_name' => 'organization',
            'send_sms_icon' => 'send_sms',
            'operation_support_phone' => 'operation_support_phone',
            'action' => 'action',
        );
    }

    public static function columnInputs()
    {
        return array(
            'name' => 'text',
            'whatsapp_instance_id' => 'text',
            'whatsapp_token' => 'text',
            'email' => 'email',
            'phone_app_sid' => 'text',
            'phone_sender_id' => 'text',
            'send_sms' => 'switch',
            'operation_support_phone' => 'text',

        );
    }
}
