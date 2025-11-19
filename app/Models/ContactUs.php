<?php

namespace App\Models;

use App\Traits\AttachmentTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Base
{
    use SoftDeletes, AttachmentTrait;

    protected $table = 'contact_us';
    public $timestamps = true;

    protected $dates = [ 'created_at', 'deleted_at' ];
    protected $fillable = array( 'name', 'email', 'phone', 'phone_code', 'message', 'subject_id' );

    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public static function columnNames()
	{
		return array(
			'id' => 'id',
            'name' => 'name', 
            'email' => 'email', 
            'phone' => 'phone', 
            'phone_code' => 'phone_code', 
            'subject.name' => 'subject',
            'action' => 'action',
		);
	}
	
	public static function columnInputs()
	{
		return array(
			'name' => 'text',
            'email' => 'email',
            'phone' => 'number',
            'phone_code' => 'number',
            'message' =>'text', 
            'subject_id' => 'select',
		);
	}

    public static function columnOptions($organization = null)
	{
		// $users = user::all()->pluck('name', 'id')->toArray();
		return array(
			'subject_id' => Subject::all()->pluck('name', 'id')->toArray(),
		);
	}
}
