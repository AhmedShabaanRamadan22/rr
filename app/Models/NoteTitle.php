<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NoteTitle extends Base
{
    use HasFactory,SoftDeletes;

    const HAS_ENOUGH_TITLE = 1; 

    public function notes(){
        return $this->hasMany(Note::class);
    }
}
