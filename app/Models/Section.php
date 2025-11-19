<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Base
{

	protected $table = 'sections';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('form_id',  'name', 'arrangement', 'is_visible');
	// protected $appends =  ['display_flag'];


	// public function service()
	// {
	// 	return $this->organization_service->service();
	// }

	// public function organization()
	// {
	// 	return $this->organization_service->organization();
	// }
	// public function organization_service()
	// {
	// 	return $this->belongsTo(OrganizationService::class);
	// }

	public function visible_questions()
	{
		return $this->morphMany(Question::class, 'questionable')
			->where(function($q){
				$q->where('is_visible', '1')
					->orWhere(
						function ($q) {
							$q->where([
								['questionable_type', 'App\Models\Section']
							])->where('is_visible', 'default')
								->whereHas('question_bank_organization', function ($q) {
									$q->where('is_visible', '1');
								});
						});
			})->orderBy('arrangement');
	}

	public function answered_questions($submitted_form_id)
	{
		// dd($this->morphMany(Question::class, 'questionable')->where([
		// 	['questionable_id', $this->id],
		// 	['questionable_type', 'App\Models\Section']
		// ])->with('answers')->get());
		return $this->morphMany(Question::class, 'questionable')->withTrashed()->where([
			['questionable_id', $this->id],
			['questionable_type', 'App\Models\Section']
		])->with('answers')->whereHas('answers', function($q) use ($submitted_form_id){
			$q->where([['answerable_id', $submitted_form_id], ['answerable_type', 'App\Models\SubmittedForm']]);
		})->orderBy('arrangement');
	}

	public function questions()
	{
		return $this->morphMany(Question::class, 'questionable');
	}

	public function form()
	{
		return $this->belongsTo(Form::class); //->where('is_visible',1);
	}

	// public function next_section(){
	// 	$next = $this->organization_service->sections->where('arrangement','>',$this->order)->sortBy('arrangement')->first();
	// 	return $next;
	// }

	// public function previous_section(){
	// 	$previous = $this->group_order_type->sections->where('arrangement','<',$this->order)->sortByDesc('arrangement')->first();
	// 	return $previous;
	// }

	public static function sectionQuestions($section_id)
	{
		$section = Section::with('visible_questions')->findOrFail($section_id);
		return $section->visible_questions->pluck('id')->toArray();
	}

	public function getDisplayFlagAttribute()
	{

		$submitted_form = $this->form->submitted_forms;
		if(isset(request()->order_sector_id)){
			$submitted_form = $submitted_form->where('order_sector_id',request()->order_sector_id);
		}
		// $submitted_form_query = SubmittedForm::where(['form_id' => $this->form->id]);
		// if(isset(request()->order_sector_id)){
		// 	$submitted_form_query->where('order_sector_id',request()->order_sector_id);
		// }
		// $submitted_form = $submitted_form_query->get();

	    if($this->form->display_flag == false){
			return false;
		}else{
			if($this->form->submissions_by == 'USERS'){
				$submitted_form = $submitted_form->where('user_id', auth()->user()->id);//->latest()->first();
			}
			$completed_forms = $submitted_form->where('is_completed', false);
			// if ($this->form->submissions_times == 'SINGLE_PER_DAY') { //otherwise it would be single or multiple & no need for checking the day 
			// 	$completed_forms = $completed_forms->filter(function ($sForm) {
			// 		return $sForm->created_at->startOfDay()->eq(today()->startOfDay());
			// 	})->whereNull('deleted_at');
			// }
			$submittedForm = $completed_forms->sortByDesc('created_at')->first();
			if ($submittedForm) {
				if (in_array($this->id, $submittedForm->submitted_sections)) {
					return false;
				}else{
					return true;
				}
			}else{
				return true;
			}
		}
	}
}