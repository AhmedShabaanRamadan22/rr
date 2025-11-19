<?php

namespace App\Models;

use App\Traits\HasCreatorLabelTrait;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MealOrganizationStage extends Base
{
    protected $table = 'meal_organization_stages';
    public $timestamps = true;

	use SoftDeletes, HasCreatorLabelTrait;

	protected $dates = ['created_at','deleted_at'];
    protected $fillable = array('meal_id','organization_stage_id','status_id','duration','done_by','done_at', 'arrangement');

    public function organization_stage(){
        return $this->belongsTo(OrganizationStage::class);
    }

    public function meal(){
        return $this->belongsTo(Meal::class);
    }

    public function status(){
        return $this->belongsTo(Status::class)->where('type','meal_stages');
    }

    public function user(){
        return $this->belongsTo(User::class, 'done_by', 'id');
    }

    public function questions()
	{
		return $this->morphMany(Question::class, 'questionable');
	}

    public function answers()
	{
		return $this->morphMany(Answer::class, 'answerable');
	}

    public function track_location(){
        return $this->morphOne(TrackLocation::class, 'track_locationable')->latestOfMany('created_at') ?? null;
    }

    public function track_locations(){
        return $this->morphMany(TrackLocation::class, 'track_locationable') ?? [];
    }

    public static function columnNames()
	{
		return array(
			'id' => 'arrangement',
			// 'arrangement' => 'arrangement',
			'stage' => 'stage',
			// 'food_name'=>'food-name',
			'status' => 'status',
			'done_by' => 'done_by',
			'done_at' => 'done_at',
			'duration' => 'expected-duration',
			'actual_duration' => 'actual-duration',
			'action' => 'action',
		);
	}

    public function isOpen()
    {
        $meal = $this->meal;
        if (in_array($meal->status_id , [Status::CLOSED_MEAL, Status::CLOSED_MEAL_FOR_SUPPORT])) {
            return false;
        } else {
            $opened_id = $meal->meal_organization_stages->whereNull('done_at')->first()?->id ;
            if ($this->id == $opened_id) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function previous_stage()
    {
        if (!$this->relationLoaded('meal')) {
            $this->load('meal');
        }

        if (!$this->meal->relationLoaded('meal_organization_stages')) {
            $this->meal->load('meal_organization_stages');
        }

        return $this->meal->meal_organization_stages->firstWhere('arrangement', $this->arrangement - 1);
    }

    public function calculate_duration()
    {
        $previous_stage = $this->previous_stage();
        if ($previous_stage) {
            return Carbon::parse($this->done_at)->diffInSeconds($previous_stage->done_at);
        } elseif (!$previous_stage) {
            if ($this->status_id != Status::DONE_MEAL_STAGE) {
                return null;//first stage but not done
            }
            return null;//first stage & done
            // return Carbon::parse($this->done_at)->diffInMinutes($meal->day_date.$meal->start_time);
        }
        return null;
    }

    public function getOrganizationIdAttribute(){
        return $this->order_sector_obj()?->sector->classification->organization_id;
    }

    public function order_sector_obj(){
        $service_id = Service::where('name_en', 'Catering')->first()->id;
        $organization_service = OrganizationService::where(['service_id' => $service_id, 'organization_id' => $this->organization_stage->organization_id])->first()->id;
        return $this->meal->sector->active_order_sector_service($organization_service)->first();
    }

    public function answered_questions()
    {
        $answers = Answer::where([
            ['answerable_id', $this->id],
            ['answerable_type', 'App\Models\MealOrganizationStage']
        ])->with('question')->get();

        $questions = $answers->map(function ($answer) {
            return $answer->question;
        });

        return $questions;
    }

    public function getPassedActualDurationAttribute(){
        $actual_duration = $this->actual_duration; //->forHumans();
        if(is_null($actual_duration)){
            return false;
        }
        $expected_duration = CarbonInterval::seconds($this->duration * 60)->cascade(); //->forHumans();
        return $actual_duration->greaterThan($expected_duration);
    }

    public function getActualDurationAttribute(){
        if(is_null($this->calculate_duration())){
            return null;
        }
        return CarbonInterval::seconds($this->calculate_duration())->cascade();
    }

    private function stageColorArray(){
        return [
            ['bg-class'=>'bg-light text-dark'], // for after current
            ['bg-class'=>'bg-info text-light'], // current
            ['bg-class'=>'bg-success text-light'], // after current and on time
            ['bg-class'=>'bg-danger text-light'], // after current and late
        ];
    }

    public function getTimeStatusAttribute()
    {
        // in-progress (on-time / late)
        // done (on-time / late)
        // not-started

        // ----------------- not started stages -----------------
        if($this->status_id == Status::CLOSED_MEAL_STAGE) return 'not-started';

        $previousStage = $this->previous_stage();
        $currentDone = $this->done_at ? Carbon::parse($this->done_at) : null;
        $previousDone = $previousStage?->done_at ? Carbon::parse($previousStage->done_at) : null;

        // ----------------- done stages -----------------
        if($this->status_id == Status::DONE_MEAL_STAGE)
        {
            // First stage
            if(!$previousStage) return $currentDone->copy()->gt($this->meal->scheduled_start_timestamp) ? 'done late' : 'done on-time';

            // Last stage
            if($this->isLastStage()) return $currentDone->copy()->gt($this->meal->scheduled_end_timestamp) ? 'done late' : 'done on-time';

            // middle stages
            return $currentDone->gt($previousDone->copy()->addMinutes($this->duration)) ? 'done late' : 'done on-time';
        }

        // ----------------- in progress stage -----------------
        // First stage
        if(!$previousStage) return now()->gt($this->meal->scheduled_start_timestamp) ? 'in-progress late' : 'in-progress on-time';

        // Last stage
        if($this->isLastStage()) return now()->gt($this->meal->scheduled_end_timestamp) ? 'in-progress late' : 'in-progress on-time';

        // middle stages
        return now()->gt($previousDone->copy()->addMinutes($this->duration)) ? 'in-progress late' : 'in-progress on-time';
    }

    public function getExpectedEndTimeAttribute(){
        if($this->previous_stage()){
            return Carbon::parse($this->previous_stage()->done_at)->addMinutes($this->duration)->format('H:i:s');
        }
        return $this->meal->start_time;
    }

    public function getStageBgClassAttribute(){
        return $this->stageColorBy('bg-class');
    }
    public function stageColorBy($by){
        // Todo: make validation of $by
        $current_meal_organization_stage = $this->meal->meal_organization_stage;
        $total_meal_organization_stage = $this->meal->meal_organization_stages->count();

        if(
            $current_meal_organization_stage->id == $this->id &&
            $total_meal_organization_stage != $this->arrangement // mean it last stage in the meal,so go to nex condition to check if it late or on time
            ){
            return $this->stageColorArray()[1][$by];
        }
        if($this->arrangement <= $current_meal_organization_stage->arrangement ){
            if($this->getPassedActualDurationAttribute()){
                return $this->stageColorArray()[3][$by];
            }else{
                return $this->stageColorArray()[2][$by];
            }
        }
        return $this->stageColorArray()[0][$by];

    }

    public function isLastStage(): bool
    {
        $currentId = $this->meal->meal_organization_stage->id;
        $totalStagesCount = $this->meal->meal_organization_stages->count();

        return $currentId == $this->id && $totalStagesCount == $this->arrangement;
    }
}
