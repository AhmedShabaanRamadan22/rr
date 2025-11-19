<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Dictionary extends Base
{
    use SoftDeletes;

    protected $table = 'dictionaries';
    public $timestamps = true;
    protected $dates = ['created_at', 'deleted_at'];
    protected $fillable = ['value', 'key_en','key_ar'];
    protected $append = ['name', 'wrapped_value'];

    public function getNameAttribute()
    {
        return app()->getLocale() == 'en' ? $this->key_en : $this->key_ar;
    }
    public function getWrappedValueAttribute()
    {
        $start = '{{ ';
        $end = ' }}';
        return $start . $this->value . $end;
    }
    
}