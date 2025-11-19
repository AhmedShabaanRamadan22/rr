<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OperationType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'operation_types';
    public $timestamps = true;
    protected $fillable = [/*'quantities',*/ 'model', 'name_en', 'name_ar', 'description_en', 'description_ar'];
    protected $dates = ['created_at', 'deleted_at'];
    // protected $appends =  ['name'];

    // protected $casts = [
    //     'quantities' => 'array'
    // ];
    
    const RAISE_TICKET = 1;
    const FOOD_SUPPORT = 2;
    const WATER_SUPPORT = 3;
    const ISSUE_FINE = 4;
    const MEAL_STAGES = 5;

    public function attachments_labels(){
        return AttachmentLabel::where('type', $this->model)->get();
    }
    public function reason_dangers()
    {
        return $this->hasMany(ReasonDanger::class);
    }


    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    public function getNameAttribute()
    {
        return app()->getLocale() == 'en' ? $this->name_en : $this->name_ar;
    }

    public function getHasQuantitiesAttribute()
    {
        if($this->model == 'supports'){
            return true;
        }
        return false;
    }


    public static function columnNames()
    {
        return array(
            'id' => 'id',
            'name_en' => 'name-en',
            'name_ar' => 'name-ar',
            'description_en' => 'description-en',
            'description_ar' => 'description-ar',
            // 'quantities' => 'quantities',
            'model' => 'model',
            'action' => 'action',
        );
    }
    
    public static function columnInputs()
    {
        return array(
            'name_en' => 'text',
            'name_ar' => 'text',
            'description_en' => 'text',
            'description_ar' => 'text',
            // 'quantities' => 'number',
            'model' => 'select',
        );
    }

	public static function columnOptions()
	{
		return array(
			'model' => [
				'meals' => 'meals',
				'tickets' => 'tickets',
				'supports' => 'supports',
			],
		);
	}
}
