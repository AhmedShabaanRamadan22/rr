<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Base
{

	protected $table = 'statuses';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('name_ar','name_en', 'type', 'color', 'description');

	// protected $appends =  ['name'];

	const NEW_ORDER = 1;
	const PROCESSING_ORDER = 2;
	const CONFIRMED_ORDER = 3; //
	const APPROVED_ORDER = 4; //first acceptance
	const ACCEPTED_ORDER = 5; //finale acceptance
	const REJECTED_ORDER = 6; //
	const CANCELED_ORDER = 7;
	const NEW_TICKET = 8;
	const PROCESSING_TICKET = 9;
	const IN_PROGRESS_TICKET = 10;
	const CLOSED_TICKET = 11;
	const NEW_SUPPORT = 12;
	const PROCESSING_SUPPORT = 13;
	const IN_PROGRESS_SUPPORT = 14;
	const CLOSED_SUPPORT = 15;
    const CANCELED_SUPPORT = 16;
    const HAS_ENOUGH_SUPPORT = 17;
    const IN_PROGRESS_ASSIST = 18;
    const DELIVERED_ASSIST = 19;
    const CANCELED_ASSIST = 20;
    const NEW_CANDIDATE = 21;
    const PROCESSING_CANDIDATE = 22;
    const ACCEPTED_CANDIDATE = 23;
    const REJECTED_CANDIDATE = 24;
    const CLOSED_MEAL = 25;
    const OPENED_MEAL = 26;
    const DONE_MEAL = 27;
    const CLOSED_MEAL_STAGE = 28;
    const OPENED_MEAL_STAGE = 29;
    const DONE_MEAL_STAGE = 30;
    const NEW_FINE = 31;
    const ACCEPTED_FINE = 32;
    const REJECTED_FINE = 33;
    const NEW_INTERVIEW = 34;
    const ACCEPTED_INTERVIEW = 35;
    const REJECTED_INTERVIEW = 36;
    const HALF_ACCEPTED_INTERVIEW = 37;
    const APPROVED_CANDIDATE = 38;
    const AWAITING_DATA_COMPLETION_CANDIDATE = 39;
    const COMPLETED_DATA_CANDIDATE = 40;
    const FALSE_TICKET = 41;
    const CLOSED_MEAL_FOR_SUPPORT = 42;


	public function orders()
	{
		return $this->hasMany(Order::class);
	}
	public function supports()
	{
		return $this->hasMany(Support::class);
	}

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

	public static function order_statuses()
	{
		return Status::where('type', 'orders');
	}

	public static function order_interview_statuses()
	{
		return Status::where('type', 'order_interviews');
	}

	public static function support_statuses()
	{
		return Status::where('type', 'supports');
	}

	public static function admin_support_statuses()
	{
		return Status::where('type', 'supports')->whereNotIn('id', [Status::HAS_ENOUGH_SUPPORT, Status::CANCELED_SUPPORT]);
	}

	public static function all_support_statuses_without_cancel()
	{
		return Status::where('type', 'supports')->whereNotIn('id', [Status::CANCELED_SUPPORT]);
	}

	public static function monitor_support_statuses()
	{
		return Status::whereIn('id', [Status::HAS_ENOUGH_SUPPORT, Status::CANCELED_SUPPORT]);
	}

	public static function meal_statuses()
	{
		return Status::where('type', 'meals');
	}
	public static function meal_stages_statuses()
	{
		return Status::where('type', 'meal_stages');
	}
    public static function candidate_statuses()
    {
        return Status::where('type', 'candidates');
    }
    public static function ticket_statuses()
    {
        return Status::where('type', 'tickets');
    }
    public static function fine_statuses()
    {
        return Status::where('type', 'fines');
    }
    public static function assist_statuses()
    {
        return Status::where('type', 'assists');
    }

	public function tickets()
	{
		return $this->hasMany(Ticket::class);
	}

	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'status-name',
			'description' => 'description',
			'type' => 'type',
			'color' => 'color',
			'action' => 'action',
		);
	}

	public static function columnInputs()
	{
		return array(
			'name_ar' => 'text',
			'name_en' => 'text',
			'description' => 'text',
			'type' => 'select',
			'color' => 'color',
		);
	}

	public static function columnOptions()
	{
		$types = Status::get()->unique('type')->pluck('type', 'type')->toArray();
		return array(
			'type' => $types,
		);
	}

	public static function filterColumns()
	{
		return array(
			'type' => Status::get()->pluck('type', 'type')->unique()->toArray(),
		);
	}

	public function getNameAttribute()
	{
		return app()->getLocale() == 'en' ? $this->name_en : $this->name_ar;
	}

	public function getIsNoteRequiredAttribute() {
		return in_array($this->id,[$this::REJECTED_ORDER]);//,$this::ACCEPTED_ORDER]);
	}

}