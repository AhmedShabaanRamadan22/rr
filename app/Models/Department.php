<?php

namespace App\Models;

use App\Traits\LocalizationTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory, LocalizationTrait;

    protected $dates = ['created_at', 'deleted_at'];
    protected $fillable = ['name_en', 'name_ar', 'head_id', 'slug'];
    // protected $appends =  ['name'];

    public function user()
    {
        return $this->belongsTo(User::class, 'head_id', 'id');
    }

    public function candidates(){
        return $this->hasMany(Candidate::class);
    }

    public function getNameAttribute()
    {
        return $this->localizeName();
    }

    public static function columnNames()
    {
        return [
            'id' => 'id',
            'name_ar' => 'name ar',
            'name_en' => 'name en',
            'slug' => 'slug',
            'head_id' => 'head',
            'action' => 'action',
        ];
    }

    public static function columnInputs()
    {
        return [
            'name_ar' => 'text',
            'name_en' => 'text',
            'slug' => 'text',
            'head_id' => 'select',
        ];
    }

    public static function columnOptions($organization = null)
    {
        // $users = user::all()->pluck('name', 'id')->toArray();
        return array(
            'head_id' => User::all()->pluck('name', 'id')->toArray(),
        );
    }

}