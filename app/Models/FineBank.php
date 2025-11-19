<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FineBank extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'fine_banks';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = ['name', 'price', 'code'];

    public function fine_organizations()
    {
		return $this->hasMany(FineOrganization::class);
	}

    public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'name',
            // 'price' => 'price',
            'code' => 'code',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name' => 'text',
            // 'price' => 'price',
			'code' => 'text',
		);
	}
}
