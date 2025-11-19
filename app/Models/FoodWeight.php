<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodWeight extends Base
{
    use HasFactory, SoftDeletes;

    protected $table = 'food_weights';
    public $timestamps = true;

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];
    protected $fillable = array('organization_id', 'food_id', 'unit', 'quantity');
    // protected $appends =  array('unit_name', 'food_name');

    // const UNITS = [
    //     'fl oz' => trans('translation.fluid-ounce') . ' (fl oz)',
    //     'Cup' => trans('translation.cup') . ' (Cup)',
    //     'ml' => trans('translation.milliliter') . ' (ml)',
    //     'l' => trans('translation.liter') . ' (l)',
    //     'oz' => trans('translation.ounce') . ' (oz)',
    //     'lb' => trans('translation.pound') . ' lb)',
    //     'g' => trans('translation.gram') . ' (g)',
    //     'gk' => trans('translation.kilogram') . ' (kg)',
    // ];

    public static function units()
    {
        return [
            'ml' => trans('translation.milliliter') . ' (ml)',
            'g' => trans('translation.gram') . ' (g)',
            'piece' => trans('translation.piece') . ' (piece)',
            'amount' => trans('translation.amount') . ' (amount)',
            'not-found' => trans('translation.not-found') . ' (not-found)',

        ];
    }

    public function getUnitTransAttribute()
    {
        $data = [
            'ml' => trans('translation.milliliter') ,
            'g' => trans('translation.gram'),
            'piece' => trans('translation.piece'),
            'amount' => trans('translation.amount'),
            'not-found' => trans('translation.not-found'),

        ];
        return $data[$this->unit] ?? $this->unit;
    }

    public function getUnitNameAttribute(){
        return FoodWeight::units()[$this->unit];
    }

    public function getFoodNameAttribute(){
        $quantity_with_unit = '';
        if($this->unit != 'not-found'){
            $quantity_with_unit = ' (' . $this->quantity . ' ' . $this->unit_trans . ')';
        }
        return $this->food->name . ' - ' . ($this->food->food_type->name?? '') . $quantity_with_unit ;
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    public function food()
    {
        return $this->belongsTo(Food::class);
    }
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'food_weight_meals');
    }

    public static function columnNames()
    {
        return array(
            'id' => 'id',
            'food.name' => 'food',
            'food_type_name' => 'food-type-name',
            'unit_name' => 'unit',
            'quantity' => 'quantity',
            'action' => 'action',
        );
    }

    public static function columnInputs()
    {
        return array(
            'organization_id' => 'hidden',
            'food_id' => 'select',
            'unit' => 'select',
            'quantity' => 'number',
        );
    }

    public static function columnOptions($organization = null)
    {
        $query = Food::with('food_type:id,name');

        // if ($organization !== null) {
        //     $query->whereHas('food_weights', function ($query) use ($organization) {
        //         $query->where('organization_id', $organization->id);
        //     });
        // }

        $foodItems = $query->get()->pluck('name_with_type', 'id')->toArray();

        return [
            'food_id' => $foodItems,
            'unit' => FoodWeight::units(),
        ];
    }
}