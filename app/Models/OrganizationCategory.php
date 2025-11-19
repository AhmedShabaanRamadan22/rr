<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationCategory extends Base
{
    use SoftDeletes;
    protected $table = 'organization_categories';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('organization_id', 'category_id');
    // protected $appends =  ['name'];

    public function question_banks(){
        return $this->hasMany(QuestionBank::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function organization(){
        return $this->belongsTo(Organization::class);
    }
    public function forms(){
        return $this->hasMany(Form::class);
    }

    public function getNameAttribute(){
        return $this->category->name??'';
    }

}
