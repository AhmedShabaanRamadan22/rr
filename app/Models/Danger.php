<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Danger extends Base
{
    use SoftDeletes;

    protected $table = 'dangers';
    public $timestamps = true;
    protected $dates = ['created_at', 'deleted_at'];
    protected $fillable = ['level', 'color'];

    const HIGH = 1;
    const MEDIUM = 2;
    const LOW = 3;
    const NO_DANGER = 4;

    // public function ticket_reasons(){
    //     return $this->hasMany(Ticket::class);
    // }

    public function reason_dangers()
    {
        return $this->hasMany(ReasonDanger::class);
    }
    public function tickets()
    {
        return $this->hasManyThrough(Ticket::class, ReasonDanger::class);
    }

	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'level' => 'name',
			'color' => 'color',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'level' => 'text',
			'color' => 'color',
		);
	}

    public function getDangerDescriptionAttribute(){
        return 'مستوى الخطورة: ' . $this->level;
    }
}
