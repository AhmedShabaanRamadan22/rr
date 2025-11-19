<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrganizationNew extends Base
{
    use HasFactory, SoftDeletes;

    protected $table = 'organization_news';

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    protected $fillable = array('new','organization_id');

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
