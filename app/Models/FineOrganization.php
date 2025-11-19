<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FineOrganization extends Base
{
    use SoftDeletes;
	protected $table = 'fine_organizations';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = ['organization_id', 'fine_bank_id', 'price','description'];
    // protected $appends =  ['name'];

    public function fine_bank()
    {
		return $this->belongsTo(FineBank::class);
	}
    public function organization()
	{
		return $this->belongsTo(Organization::class);
    }
    public function fines()
	{
		return $this->hasMany(Fine::class);
    }

    public static function columnNames()
    {
        return array(
            'id' => 'id',
            'fine_bank_name' => 'fine id',
            'price' => 'price',
            'description' => 'Description',
            'action' => 'action',
        );
    }

    public static function columnInputs()
    {
        return array(
            'fine_bank_id' => 'select',
            'price' => 'number',
            'description' => 'text',
        );
    }

	public static function columnOptions($organization = null )
	{
        $fines = FineBank::query();
		if($organization != null){
			$fines->whereHas('fine_organizations',function($q) use($organization) {
				$q->where('organization_id',$organization->id);
			});
        }
		return array(
			'fine_bank_id' => FineBank::whereNotIn("id",$fines->pluck('id')->toArray())->pluck('name','id')->toArray(),
		);
	}


}
