<?php

namespace App\Models;

use App\Traits\AssignableTrait;
use App\Traits\UuidableTrait;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Base
{

	protected $table = 'orders';
	public $timestamps = true;

	use SoftDeletes, UuidableTrait, AssignableTrait;

	protected $dates = ['created_at', 'deleted_at'];
	protected $fillable = array('user_id', 'organization_service_id', 'status_id', 'facility_id', 'is_sign', 'pass_interview', 'country_ids', 'interview_note', 'interview_status_id','bonus');
	protected $casts = ['country_ids' => 'array'];

	// protected $appends =  ['code', 'country_organization'];//, 'status_name','interview_fulfill_standard','interview_total_score_before_bonus','interview_total_score_after_bonus'];

	//AttachmentLabel::where('type', $this->getTable())->first()->id?? null;
	const PROGRESS_STATUSES = ['New', 'Processing', 'Confirmed', 'Approved', 'Accepted'];
	const CANCELED_STATUSES = ['Canceled', 'Rejected'];
	public function user()
	{
		return $this->belongsTo(User::class);
	}
	public function order_sectors()
	{
		return $this->hasMany(OrderSector::class)->withArchived();
	}

	public function status()
	{
		return $this->belongsTo(Status::class)->where('type', 'orders');
	}

	public function interview_status()
	{
		return $this->belongsTo(Status::class, 'interview_status_id', 'id')->where('type', 'order_interviews');
	}

	public static function progress_statuses()
	{
		return Status::all()->where('type', 'orders')->whereIn('name_en', Order::PROGRESS_STATUSES);
	}

	public static function canceled_statuses()
	{
		return Status::all()->where('type', 'orders')->whereIn('name_en', Order::CANCELED_STATUSES);
	}

	public function getStatusNameAttribute()
	{
		return ($this->status_id == 6 ? __('طلب') : '') . ' ' . $this->status->name;
	}

	public function service()
	{
		return $this->organization_service->service();
	}

	public function organization()
	{
		return $this->organization_service->organization();
	}

	public function organization_service()
	{
		return $this->belongsTo(OrganizationService::class);
	}

	public function facility()
	{
		return $this->belongsTo(Facility::class);
	}

	public function answers()
	{
		return $this->morphMany(Answer::class, 'answerable');
	}
	public function questions()
	{
		return $this->belongsToMany(Question::class, Answer::class)->wherePivotNull('deleted_at');
	}

	public function attachments()
	{
		return $this->morphMany(Attachment::class, 'attachmentable');
	}

	// public function order_attachments()
	// {
	//     return $this->morphOne(Attachment::class,'attachmentable')->where('attachment_label_id', AttachmentLabel::ORDER_LABEL);
	// }
	public function contracts()
	{
		return $this->morphMany(Contract::class, 'contractable');
	}
	public function contract()
	{
		return $this->morphOne(Contract::class, 'contractable');
	}

	public function notes()
	{
		return $this->morphMany(Note::class, 'notable')->orderByDesc('created_at');
	}
	public function note()
	{
		return $this->morphOne(Note::class, 'notable')->latestOfMany('created_at');
	}

	public static function columnNames()
	{
		return array(
			'id' => 'table_id',
			'code' => 'order-code',
			'organization_name' => 'organization-name',
			'service_name' => 'service-name',
			'user-name' => 'facility-user-name',
			'facility-name' => 'facility-name',
			'user-phone' => 'facility-user-phone',
			'user-email' => 'facility-user-email',
			'user-birthday' => 'facility-user-birthday',
			'user-nationality_name' => 'facility-user-nationality',
			'user-national_id' => 'facility-user-national_id',
			'user-national_source_name' => 'facility-user-national_source',
			'user-national_id_expired' => 'facility-user-national_id_expired',
			'user-created_at' => 'user-created_at',
			'user-updated_at' => 'user-updated_at',
			'user-is_updated' => 'user-is_updated',
			'facility-registration_number' => 'facility-registration_number',
			'version_date' => 'facility-version_date',
			'end_date' => 'facility-end_date',
			'facility-registration_source_name' => 'facility-registration_source',
			'facility-license' => 'facility-license',
			'license_expired' => 'facility-license_expired',
			'facility-capacity' => 'facility-capacity',
			'facility-tax_certificate' => 'facility-tax_certificate',
			'facility-national_address' => 'facility-national_address',
			'facility-bank_information-bank_name' => 'facility-bank_name',
			'facility-bank_information-account_name' => 'facility-account_name',
			'facility-bank_information-iban' => 'facility-iban',
			'facility-chefs_number' => 'facility-chefs_number',
			'facility-kitchen_space' => 'facility-kitchen_space',
			'facility-employee_number' => 'facility-employee_number',
			'facility-created_at' => 'facility-created_at',
			'facility-updated_at' => 'facility-updated_at',
			'facility-is_updated' => 'facility-is_updated',
			'status' => 'status',
			'status_name' => 'status-name',
			'created_at' => 'order-created_at',
			'updated_at' => 'order-updated_at',
			'action' => 'action',
		);
	}

	public static function orderReportColumns()
	{
		return array(
			'id' => 'table_id',
			'code' => 'order-code',
			'facility-name' => 'facility-name',
			'user-name' => 'facility-user-name',
			'organization_name' => 'organization_name',
			'status' => 'status',
			'facility-reports' => 'facility-reports',
			'order-reports' => 'order-reports',
			'operation-summary-reports' => 'operation-summary-report',
			'meal-reports' => 'meal-reports',
			'support-reports' => 'support-reports',
			'ticket-reports' => 'ticket-reports',
			// 'fine-reports' => 'fine-reports',
			'submitted-form-reports' => 'submitted-form-reports',
		);
	}

	public static function columnInput()
	{
		return array();
	}


	public function getOrder(Order $order)
	{

		$service = $order->organization_service->service;
		$order = $order->with('status', 'facility', 'answers.question.options', 'notes')->where('id', $order->id)->first();
		$order->service = $service;

		return ($order);
	}

	public function getCodeAttribute()
	{
		$code = 'ORD' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
		return $code;
	}

	public function getCountryOrganizationAttribute()
	{
		return $this->country_ids ? CountryOrganization::whereIn('id', $this->country_ids)->get()->pluck('country_name') : [];
	}

	public function interview_standard_orders()
	{
		return $this->hasMany(InterviewStandardOrder::class);
	}

	public function interview_standard_order($id)
	{
		return $this->hasOne(InterviewStandardOrder::class)->where('interview_standard_id',$id);
	}

	public function interview_standards()
	{
		return $this->belongsToMany(InterviewStandard::class, 'interview_standard_orders');
	}

	public function getHasInterviewStandardOrdersAttribute()
	{
		return $this->interview_standard_orders->isEmpty();
	}

	public function calculate_interview_total_score(){
		return round(($this->interview_standard_orders->sum('score') / $this->interview_standard_orders->sum('max_score')) * 100 ,1);
	}

	public function getInterviewTotalScoreAttribute()
	{
	}

	public function getInterviewTotalScoreBeforeBonusAttribute()
	{
		if (!$this->has_interview_standard_orders) {
			return $this->calculate_interview_total_score() . '%';
		}
		return trans('translation.not-have-interview-yet');
	}

	public function getInterviewTotalScoreAfterBonusAttribute()
	{
		if (!$this->has_interview_standard_orders) {
			return ($this->calculate_interview_total_score() + $this->bonus) . '%';
		}
		return trans('translation.not-have-interview-yet');
	}

	public function getInterviewFulfillStandardAttribute()
	{
		$standard_max_scores = InterviewStandard::all()->pluck('max_score');
		$max_score = $this->interview_standard_orders->pluck('max_score');

		return $standard_max_scores->diff($max_score)->isEmpty();
	}
	public function active_order_sectors()
	{
		return $this->order_sectors->whereNull('parent_id');
	}


	//* Assignable
	public function assigns()
    {
        return $this->morphMany(Assign::class, 'assignable');
    }

    public function scopeFacility($query, $facility)
    {
        return $query->where('facility_id', $facility);
    }
}
