<?php

namespace App\Models;

use App\Traits\LocalizationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Mail\Mailables\Content;

class Subject extends Base
{
    use SoftDeletes;
    use LocalizationTrait;

    protected $table = 'subjects';
    protected $fillable = array('name_ar', 'name_en');
    // protected $appends =  ['name'];

    public function contact_uses(){
        return $this->hasMany(ContactUs::class);
    }
    // public function getNameAttribute()
    // {
    //     return $this->localizeName();
    //     // return app()->getLocale() == 'en' ? $this->name_en : $this->name_ar;
    // }

    public static function columnNames()
	{
		return array(
			'id' => 'id',
            'name_ar' => 'Subject Name in arabic',
            'name_en' => 'Subject Name in english',
            'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name_ar' => 'text',
			'name_en' => 'text',
		);
	}

    
}