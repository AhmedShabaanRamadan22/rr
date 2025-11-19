<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MobileInfo extends Model
{
    use SoftDeletes;

    protected $casts = [
        'current_version' => 'integer',
    ];

    // protected $guarded = [];
    protected $fillable = array('current_version', 'about_us', 'term_conditions');

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function androidBundleFile()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::ANDROID_APP_BUNDLE)->latest('created_at');
    }

    public function iosBundleFile()
    {
        return $this->morphOne(Attachment::class, 'attachmentable')->where('attachment_label_id', AttachmentLabel::IOS_APP_BUNDLE)->latest('created_at');
    }
    public static function columnNames()
	{
		return array(
                
            'current_version' => 'current-version',
            'about_us' => 'about-us',
            'term_conditions' =>  'term-conditions',
            'androidBundleFile' => 'androidBundleFile',
            'iosBundleFile' => 'iosBundleFile',
            'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
           'current_version' => 'number',
            'about_us' => 'text',
            'term_conditions' =>  'text',
            AttachmentLabel::ANDROID_APP_BUNDLE =>  'custom-file',
            AttachmentLabel::IOS_APP_BUNDLE =>  'custom-file',        
		);
	}
}
