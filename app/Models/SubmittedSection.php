<?php

namespace App\Models;

use App\Traits\HasCreatorLabelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmittedSection extends Model
{
    use HasFactory, SoftDeletes, HasCreatorLabelTrait;
    protected $fillable = array('section_id', 'user_id', 'submitted_form_id');

    public function user(){
        return $this->belongsTo(User::class, );
    }

    public function section(){
        return $this->belongsTo(Section::class, );
    }

    public function submitted_form(){
        return $this->belongsTo(SubmittedForm::class, );
    }
    public function track_location()
    {
        return $this->morphOne(TrackLocation::class, 'track_locationable')->latestOfMany('created_at') ?? null;
    }

    public function track_locations()
    {
        return $this->morphMany(TrackLocation::class, 'track_locationable') ?? [];
    }

    public function getOrganizationIdAttribute(){
        return $this->submitted_form->order_sector->sector->classification->organization_id;
    }

    public function order_sector_obj(){
        return $this->submitted_form->order_sector;
    }
}