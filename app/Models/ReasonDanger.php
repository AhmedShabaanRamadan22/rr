<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonDanger extends Base
{
    use HasFactory;

    protected $table = 'reason_dangers';
protected $fillable = ['danger_id', 'reason_id', 'organization_id', 'operation_type_id'];
    // protected $appends =  ['name'];

    public function danger()
    {
        return $this->belongsTo(Danger::class);
    }

    public function operation_type(){
        return $this->belongsTo(OperationType::class);
    }

    public function organization()
	{
		return $this->belongsTo(Organization::class);
	}

    public function tickets(){
        return $this->hasMany(Ticket::class);

    } public function supports(){
        return $this->hasMany(Support::class);
    }

    public function reason(){
        return $this->belongsTo(Reason::class);
    }

    public function getNameAttribute(){
        $name = $this->reason->name;
        // $this->unsetRelation('reason');
        return $name;
    }
    public static function columnInputs()
	{
		return array(
            'operation_type_id' => 'select',
			'reason_id' => 'select',
			'danger_id' => 'select',
		);
	}

	public static function columnOptions($organization = null)
	{
		// $sectors = Sector::query();
		// if($organization != null){
		// 	$sectors->whereHas('classification',function($q) use($organization) {
		// 		$q->where('organization_id',$organization->id);
		// 	});
		// }

		return array(
            'operation_type_id'=>OperationType::with('reason_dangers:id,danger_id,reason_id,operation_type_id,organization_id')->whereNotIn('model', ['meals', 'fines'])->get()->pluck('name','id')->toArray(),
			'reason_id'=>Reason::pluck('name','id')->toArray(),
			'danger_id'=>Danger::pluck('level','id')->toArray(),
		);

	}
}