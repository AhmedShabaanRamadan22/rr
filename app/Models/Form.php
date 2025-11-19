<?php

namespace App\Models;

use App\Traits\OrganizationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Base
{
	use OrganizationTrait;
	protected $table = 'forms';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['created_at', 'deleted_at'];
	protected $fillable = array('name', 'display', 'code', 'description', 'organization_id', 'organization_service_id', 'is_visible', 'organization_category_id', 'submissions_times', 'submissions_by', 'submissions_limit');
	// protected $appends =  ['null_section', 'display_flag'];
	static $form_type = ['web' => ['WEB', 'ALL'], 'app' => ['APP', 'ALL']];
	public function organization_service()
	{
		return $this->belongsTo(OrganizationService::class);
	}
	public function organization()
	{
		return $this->organization_service->organization;
	}

	public function sections()
	{
		return $this->hasMany(Section::class)->orderBy('arrangement');
	}

	public function questions()
	{
		return $this->hasManyThrough(Question::class, Section::class);
	}

	public function sections_has_question()
	{
		return $this->hasMany(Section::class)
			->where('is_visible', '1')
			->whereHas(
				'questions',
				function ($q) {
					$q->where('is_visible', '1')
						->orWhere(
							function ($q) {
								$q->where('is_visible', 'default')
									->whereHas('question_bank_organization', function ($q) {
										$q->where('is_visible', '1');
									});
							}
						);
				}
			)->orderBy('arrangement');
	}

	public static function formsType($type)
	{
		if (!array_key_exists($type, self::$form_type)) {
			throw ValidationException::withMessages(['message' => trans("translation.key-does'nt-exists")]);
		}
		$forms = self::with('sections_has_question.visible_questions')
			->where('is_visible', '1')
			->whereIn('display', self::$form_type[$type])
			->whereHas('sections_has_question');
		if ($type == 'app') {
			return self::formApp($forms);
		} elseif ($type == 'web') {
			return self::formWeb($forms);
		}
	}

	public function organization_category()
	{
		return $this->belongsTo(OrganizationCategory::class);
	}


	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'name',
			'display' => 'display-for-who',
			'code' => 'code', 
			'description' => 'description', 
			'organization_service.service.name' => 'service-name', 
			'organization_category.category.name' => 'category-name',
			'action' => 'action',
		);
	}

	public static function columnOptions()
	{
		return array(
			'organization_service' => OrganizationService::with('organization:id,name_ar,name_en')->get()->pluck('organization.name')->toArray(),
			'display'=> ['ALL'=> 'All', 'WEB'=>'Web', 'APP'=>'App'],
			'submissions_times'=> ['SINGLE'=> 'Single', 'SINGLE_PER_DAY'=>'Single Per Day', 'MULTIPLE'=>'Multiple'],
			'submissions_by'=> ['USER'=> 'User', 'USERS'=>'Users'],
			'display_edit'=> ['ALL'=> 'All', 'WEB'=>'Web', 'APP'=>'App'],
			'submissions_times_edit'=> ['SINGLE'=> 'Single', 'SINGLE_PER_DAY'=>'Single Per Day', 'MULTIPLE'=>'Multiple'],
			'submissions_by_edit'=> ['USER'=> 'User', 'USERS'=>'Users'],
		);
	}
	public function submitted_forms()
	{
		return $this->hasMany(SubmittedForm::class,);
	}

	public static function formWeb($form_query)
	{
		$form_instance = new Form();
		$form_instance->validateOrganization();

		return $form_query->whereHas('organization_service', function ($query) {
			$query->where('organization_id', request()->organization_id);
		}); //->get();
	}

	public static function formApp($form_query)
	{
		if (!request()->has('order_sector_id')) {
			throw ValidationException::withMessages([trans('translation.No order sector provided')]);
			// return response()->json(['message' => trans('translation.No order sector provided')], 401);
		}
		$organization_service_id = OrderSector::findOrFail(request()->order_sector_id)->order->organization_service->id;
		return $form_query->whereHas('organization_service', function ($query) use ($organization_service_id) {
			$query->where('id', $organization_service_id);
		}); //->get();
	}

	public function getNullSectionAttribute()
	{
		// $sections = $this->sections()->with('questions')->get();
		$sections = $this->sections;
		if ($sections->isEmpty() || $sections->some(fn ($section) => $section->questions->isEmpty())) { // The form or at least one section doesn't have questions
			return 1;
		}
		return 0; // All sections have at least one question
	}

	public function getFormFullNameAttribute(){
		$organization_name = '';
		if(isset($this->organization_service->organization->name)){
			$organization_name = ' (' . $this->organization_service->organization->name .') ';
		}
		$service_name = '';
		if(isset($this->organization_service->name)){
			$service_name = ' (' . $this->organization_service->service->name .') ';
		}
		$category_name = '';
		if(isset($this->organization_category->category->name)){
			$category_name = ' (' . $this->organization_category->category->name .') ';
		}
		return $this->name . $organization_name . $service_name . $category_name;
	}

	public function getNameAndOrganizationAttribute(){
		return ($this->name ?? '-') . ' - ' . ($this->organization_service->organization->name ?? '-');
	}

	public static function formQuestions($request, $type = 'app')
	{
		$form = self::formsType($type);
		$qustions = $form->where('id', $request->form_id)
			->get()
			->where('null_section', '0')
			->flatMap(function ($item) {
				return [
					'id' => $item->sections_has_question->flatMap(function ($section) {
						return $section['visible_questions']->pluck('id');
					}),
				];
			})->flatten()->toArray();

		return $qustions;
	}

	public static function validSection($request, $type = 'app')
	{
		$form = self::formsType($type);
		$section = Section::find($request->section_id);
		if (!$section) {
			return 0;
		}
		$sections = $form->where('id', $section->form->id)
			->get()
			->where('null_section', '0')
			->flatMap(function ($item) {
				return [
					'id' => $item->sections_has_question->pluck('id')
				];
			})->flatten(); //->toArray();
		return $sections->contains($section->id);
	}
	
	public function getDisplayFlagAttribute()
	{
		$submitted_form = $this->submitted_forms;
		if(isset(request()->order_sector_id)){
			$submitted_form = $submitted_form->where('order_sector_id',request()->order_sector_id);
		}
		// $submitted_form_query = SubmittedForm::where(['form_id' => $this->id]);
		// if(isset(request()->order_sector_id)){
		// 	$submitted_form_query->where('order_sector_id',request()->order_sector_id);
		// }
		// $submitted_form = $submitted_form_query->get();
		
		if ($submitted_form->where('is_completed', true)->isNotEmpty()) {

			$completed_forms = $submitted_form->where('is_completed', true);
			$completed_forms_by_user = $completed_forms->filter(function ($form) {
				return $form->user_id == auth()->id();
			});
			// $completed_forms_by_user = $completed_forms->where('user_id', auth()->user())->latest()->first();

			if ($this->submissions_times == 'SINGLE') {
				if ($completed_forms_by_user->isEmpty() && $this->submissions_by == 'USERS') {
					return true;
				}
				return false;
			}
			if ($this->submissions_times == 'SINGLE_PER_DAY') { //otherwise it would be single or multiple & no need for checking the day 
				if ($this->submissions_by == 'USERS') {
					$submitted_today =  $completed_forms_by_user;
				} else {
					$submitted_today = $completed_forms;
				}
				$submitted_today = $submitted_today->filter(function ($sForm) {
					return $sForm->created_at->startOfDay()->eq(today()->startOfDay());
				})->whereNull('deleted_at');
				if ($submitted_today->isNotEmpty()) {
					return false;
				}
			}
		}
		return true;
	}
}
