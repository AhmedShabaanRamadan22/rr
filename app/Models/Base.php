<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Base extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

}