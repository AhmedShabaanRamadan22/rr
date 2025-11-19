<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Base
{
    use HasFactory;
    protected $table = 'messages';
    public $timestamps = true;

    protected $fillable = array('organization_id', 'whatsapp_instance_id', 'receiver_id', 'receiver_phone', 'message');

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}