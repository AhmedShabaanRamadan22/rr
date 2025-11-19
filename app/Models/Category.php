<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Base
{
    use SoftDeletes;

    protected $table = 'categories';
	public $timestamps = true;

	protected $dates = ['created_at','deleted_at'];
    protected $fillable = ['name','code'];

    public function organization_categories(){
        return $this->hasMany(OrganizationCategory::class);
    }

	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'name',
			'code' => 'code',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
			'code' => 'text',
		);
	}
}
