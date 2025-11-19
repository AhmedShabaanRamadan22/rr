<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sending extends Base
{
    protected $table = 'sendings';
    public $timestamps = true;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('VIA', 'whatsapp_token', 'instance_id', 'phone_app_sid',
    'phone_sender_id', 'sender_name','receiver_phone','message','has_sent','actioner_id');
}
