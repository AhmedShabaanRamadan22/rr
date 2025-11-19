<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityService extends Base
{
    use SoftDeletes;

    protected $table = 'facility_services';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('facility_id', 'service_id');

    public function service()
	{
		return $this->belongsTo(Service::class);
	}

    public function facility()
	{
		return $this->belongsTo(Facility::class);
	}
}

