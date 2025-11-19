<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrackLocation extends Base
{
    use SoftDeletes;
    protected $table = 'track_locations';
    public $timestamps = true;
    
    protected $dates = ['deleted_at'];
    protected $fillable = array('device', 'user_id', 'longitude', 'latitude', 'details', 'action', 'device_info', 'track_locationable_id', 'track_locationable_type');
    protected $casts = ['device_info' => 'json'];

    public function track_locationable(){
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getOrganizationIdAttribute(){
        return $this->track_locationable->organization_id;
    }

    public function getOrderSectorIdAttribute(){
        return $this->track_locationable->order_sector_obj()->id;
    }
}
