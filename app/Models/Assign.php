<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assign extends Base
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'assigner_id', 
        'assignee_id', 
        'assignable_id', 
        'assignable_type'
    ];

    //*  Relations  *//
    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigner_id');
    }
    
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }
    
    public function assignable()
    {
        return $this->morphTo();
    }

    //*=====================================
    //*  Scopes  *//

    public function scopeForAssignee($query, $userId, $type = null)
    {
        $query->where('assignee_id', $userId);
        if ($type) {
            $query->where('assignable_type', $type);
        }
    }

    public function scopeForAssigner($query, $userId, $type = null)
    {
        $query->where('assigner_id', $userId);
        if ($type) {
            $query->where('assignable_type', $type);
        }
    }

    //*=====================================
    //*  Mutetor  *//
    public function getAssignerNameAttribute(){ // assigner_name
        return $this->assigner()->value('name');
    }

    public function getAssigneeNameAttribute(){ // assigner_name
        return $this->assignee()->value('name');
    }


    //*=====================================
    //*  accessitor  *//



    //*=====================================
    //*  General  *//


}
