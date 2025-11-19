<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Iban extends Base
{
    protected $table = 'ibans';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at','created_at','updated_at'];
	protected $fillable = array('account_name', 'owner_national_id', 'iban','bank_id','ibanable_id','ibanable_type');
    // protected $appends =  ['bank_name'];

    public function bank(){
        return $this->belongsTo(Bank::class);
    }
    public function ibanable(){
        return $this->morphTo();
    }
    //! commented because of morph
    // public function users() {
    //     return $this->hasMany(User::class);
    // }
    // public function facilities() {
    //     return $this->hasMany(Facility::class);
    // }
    //!

    public function getBankNameAttribute(){
        return $this->bank->name?? '';
    }
    public static function columnNames()
    {
        return array(
            'id' => 'id',
            'account_name' => 'account_name',
            'owner_national_id' => 'owner_national_id',
            'ibanable_type' => 'account type',
            'ibanable_id' => 'account id',
            'iban' => 'iban',
            'bank_name' => 'bank_name',
            'action' => 'action',
        );
    }

    public static function columnInputs()
    {
        return array(
            'ibanable_type' => 'select',
            'ibanable_id' => 'select',
            'owner_national_id' => 'text',
            'account_name' => 'text',
            'bank_id' => 'select',
            'iban' => 'text',
        );
    }

    public static function columnOptions($organization = null)
    {
        return array(
            'bank_id' => Bank::get()->pluck('name', 'id')->toArray(),
            'ibanable_type' => ['Facility' => trans('translation.facilities'), 'User' => trans('translation.users')],
            'ibanable_id' => [],
            'Facility' => Facility::whereDoesntHave('iban')->pluck('name', 'id')->toArray(),
            'User' => User::whereDoesntHave('iban')->pluck('name', 'id')->toArray(),
        );
    }
}
