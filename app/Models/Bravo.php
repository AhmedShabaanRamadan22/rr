<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bravo extends Base
{
    use SoftDeletes;
    protected $table = 'bravos';
	public $timestamps = true;

	protected $dates = ['created_at','deleted_at'];
    protected $fillable = ['number','code','organization_id','given_id','channel'];
	// protected $appends =  ['is_used','has_return','name','giver_name','user_name','user_name_badge'];

    public function given_by(){
        return $this->belongsTo(User::class,'given_id','id');
    }
    public function user(){
        return $this->hasOne(User::class);
    }
    public function organization(){
        return $this->belongsTo(Organization::class);
    }

    public function used(){

    }

    public static function columnNames()
	{
		return array(
			'id' => 'id',
			'number' => 'bravo_number',
            'code' => 'code',
            'organization_name' => 'organization_name',
            'channel' => 'channel',
            'giver_name' => 'given_by',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
            'number' => 'number',
			'code' => 'text',
            'organization_id' => 'select',
			'given_id' => 'select',
			'channel' => 'text',
		);
	}

    public static function columnOptions()
	{
		return array(
			'organization_id' => Organization::all()->pluck('name','id')->toArray(),
            'given_id' => User::all()->pluck('name','id')->toArray(),
		);
	}
    // public static function columnSubtextOptions()
    // {
    // $bravosWithUser = Bravo::has('user')->with('user')->get();
    // $options = [];
    // foreach ($bravosWithUser as $bravo) {
    //     $options[$bravo->id] = $bravo->user->name ?? '';
    // }
    // return array(
    //     'bravo_id' => $options,
    // );
    // }

    public function getIsUsedAttribute()
    {
        return $this->user()->exists();
    }
    public function getHasReturnAttribute()
    {
        return !$this->given_by()->exists();
    }
    public function getNameAttribute()
    {
        return "(".$this->code.")".$this->number;
    }
    public function getGiverNameAttribute()
    {
        return $this->given_by->name ?? '';
    }
    public function getUserNameAttribute()
    {
        return $this->user->name ?? '';
    }
    public function getUserNameBadgeAttribute()
    {
        if($this->user_name == ''){
            return trans('translation.not-used') ;
        }
        return "<span class='badge bg-primary my-1'>" . $this->user_name . "</span>" ;
    }

}