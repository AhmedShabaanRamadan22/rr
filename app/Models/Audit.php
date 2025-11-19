<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{

    // protected $appends = [
    //     'value_changes',
    // ];
    // protected $casts = [
    //     'old_values' => 'array',
    //     'new_values' => 'array',
    // ];
    /**
     * Get the parent auditable model.
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    /**
     * get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getValueChangesAttribute(){
        return audit_value_changes($this);
    }
}