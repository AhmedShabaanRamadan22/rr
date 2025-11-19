<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationService extends Base
{
    use SoftDeletes;

	protected $table = 'organization_services';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('organization_id', 'service_id');

	// protected $appends =  ['service_name','name', 'has_contract_template'];

	public function service()
	{
		return $this->belongsTo(Service::class);
	}

	// public function sections()
	// {
	// 	return $this->hasMany(Section::class)->where('is_visible',1);
	// }

    public function forms()
	{
		return $this->hasMany(Form::class)->where('is_visible',1);
	}

	public function first_section(){
		return $this->sections->sortBy('order')->first();
	}

	public function orders()
	{
		return $this->hasMany(Order::class);
	}

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}
    public function questions(){
        return $this->morphMany(Question::class,'questionable')->where('is_visible',"1");
    }

	public function getServiceNameAttribute(){
		return $this->service->name?? '-';
	}

	public function getNameAttribute(){
		return $this->organization->name . ' - ' . $this->service->name;
	}

	public function getHasContractTemplateAttribute(){
		return ContractTemplate::where('type','service_'.$this->id)->first() ? true : false ;
	}

	public function contract_template(){
		return ContractTemplate::where('type', 'service_' . $this->id)->first();
	}

	public function contracts(){
		return Contract::where('contract_template_id', $this->contract_template()->id)->get();
	}

}