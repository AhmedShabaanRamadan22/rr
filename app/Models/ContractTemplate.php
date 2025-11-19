<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractTemplate extends Base
{
    use SoftDeletes;

    protected $table = 'contract_templates';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('type', 'organization_id','content');

    public function contracts(){
        return $this->hasMany(Contract::class);
    }
    public function organization(){
        return $this->belongsTo(Organization::class);
    }
}