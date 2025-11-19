<?php

namespace App\Models;

use App\Traits\UuidableTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meal extends Base
{
	use SoftDeletes, UuidableTrait;
	protected $table = 'meals';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('sector_id', 'period_id', 'day_date', 'start_time', 'end_time', 'status_id', 'order_sector_id');

	public function food_weights()
	{
		return $this->belongsToMany(FoodWeight::class, 'food_weight_meals');
	}

	public function food_weight_meals()
	{
		return $this->hasMany(FoodWeightMeal::class);
	}

	public function sector()
	{
		return $this->belongsTo(Sector::class);
	}

	public function order_sector()
	{
		return $this->belongsTo(OrderSector::class)->withArchived();
	}

	public function period()
	{
		return $this->belongsTo(Period::class);
	}

	public function meal_organization_stages()
	{
		return $this->hasMany(MealOrganizationStage::class);
	}

	public function meal_organization_stages_arranged()
	{
		return $this->hasMany(MealOrganizationStage::class)->orderBy('arrangement');
	}

	public function meal_organization_stage()
	{
		return $this->hasOne(MealOrganizationStage::class)->whereNull('done_at')->orderBy('arrangement')->exists() ? $this->hasOne(MealOrganizationStage::class)->whereNull('done_at')->orderBy('arrangement') : $this->hasOne(MealOrganizationStage::class)->latest('arrangement');
	}

	public function getCurrentMealOrganizationStageAttribute()
	{
		$stages = $this->relationLoaded('meal_organization_stages')
			? $this->meal_organization_stages
			: $this->meal_organization_stages()->get();

		return $stages
			->whereNull('done_at')
			->sortBy('arrangement')
			->first()
			?? $stages->sortByDesc('arrangement')->first();
	}

	public function status()
	{
		return $this->belongsTo(Status::class)->where('type', 'meals');
	}

	public static function meal_period()
	{
		return Period::where('operation_type_id', OperationType::MEAL_STAGES)->get();
	}

	public static function columnNames($all_columns = false)
	{
		$columns_array = array(
			'sector_label' => 'sector',
			'sector' => 'sector-providor',
			'guest_quantity' => 'meals-count',
			'period.name' => 'period',
			'day_date' => 'day',
			'sector_nationality' => 'nationality',
			'current-stage' => 'current-stage',
			'progress' => 'progress',
			'start_time' => 'expected-start-time',
			'end_time' => 'expected-end-time',
			'status' => 'meal-status',
			'updated_at' => 'update-date',
		);
		// $is_chairman = auth()->user() != null ? auth()->user()->hasRole('organization chairman') : false;
        $can_view_action_column =  auth()->check() && auth()?->user()?->can('view_meal_action_column');
		if ($can_view_action_column) {
			$columns_array = array_merge($columns_array, ['action' => 'action']);
		}
		if ($all_columns) {
			$columns_array = array_merge($columns_array, array(
				'actual_start_time' => 'actual-start-time',
				'actual_end_time' => 'actual-end-time',
				'start-status' => 'start-status',
				'stage-status' => 'stage-status',
				'deliver-status' => 'deliver-status',
				'duration' => 'duration',
			));
		}
		return $columns_array;
	}

	public static function columnInputs()
	{
		// !! add meal by sector
		// return array(
		// 	'sector_id' => 'select',
		// 	'day_date' => 'date',
		// 	'period_id' => 'select',
		// 	'food_weights' => 'group-select',
		// 	'start_time' => 'time',
		// 	'end_time' => 'time',
		// );
		// !! add meal by nationality
		return array(
			'natioanlity_organization_id' => 'select',
			'sector_id' => 'multiple-select',
			'day_date' => 'date',
			'period_id' => 'select',
			'food_weights' => 'group-select',
			'start_time' => 'time',
			'end_time' => 'time',
		);
	}

	public static function columnOptions($organization = null)
	{
		// !! add meal by sector
		// $sectors = Sector::query();
		// $food_weights = FoodWeight::with('food:id,name,food_type_id', 'food.food_type:id,name');
		// if ($organization != null) {
		// 	$sectors->whereHas('classification', function ($q) use ($organization) {
		// 		$q->where('organization_id', $organization->id);
		// 	});
		// }
		// if ($organization != null) {
		// 	$food_weights = $food_weights->where('organization_id', $organization->id);
		// }
		// return array(
		// 	'food_weights' => $food_weights->orderBy('food_id')->get()
		// 		->map(function ($items, $key) {
		// 			$items->option_group_label = $items->food?->name;
		// 			$items->name = $items->food_name;
		// 			return $items;
		// 		})
		// 		->values()
		// 		->toArray(),
		// 	// 'food_weights' => FoodWeight::all()->pluck('food_name', 'id')->toArray(),
		// 	'sector_id' => $sectors->get()->pluck('label', 'id')->toArray(),
		// 	'period_id' => Meal::meal_period()->pluck('name', 'id')->toArray(),
		// );
		// !! add meal by nationality
		$food_weights = FoodWeight::with('food:id,name,food_type_id', 'food.food_type:id,name');
		$nationality_organizations = NationalityOrganization::with('nationality:id,name');
		if ($organization) {
			$food_weights = $food_weights->where('organization_id', $organization->id);
			$nationality_organizations = $nationality_organizations->where('organization_id', $organization->id);
		}
		return array(
			'natioanlity_organization_id' => $nationality_organizations->get()->pluck('nationality.name', 'id')->toArray(),
			'sector_id' => [],
			'period_id' => Meal::meal_period()->pluck('name', 'id')->toArray(),
			'food_weights' => $food_weights->orderBy('food_id')->get()
				->map(function ($items, $key) {
					$items->option_group_label = $items->food?->name;
					$items->name = $items->food_name;
					return $items;
				})
				->values()
				->toArray(),
		);
	}

	public static function columnSubtextOptions($organization = null, $value = 'organization_name')
	{
		// !! add meal by sector
		// $sectors = Sector::with('nationality_organization:id,nationality_id', 'nationality_organization.nationality:id,name');
		// if ($organization != null) {
		// 	$sectors->whereHas('classification', function ($q) use ($organization) {
		// 		$q->where('organization_id', $organization->id);
		// 	});
		// }
		// return array(
		// 	'sector_id' => $sectors->get()->pluck($value, 'id')->toArray(),
		// );
		// !! add meal by nationality
		$sectors_classifications = Sector::with('classification:id,code,organization_id', 'classification.organization:id,name_ar,name_en');
		if ($organization != null) {
			$sectors_classifications->whereHas('classification', function ($q) use ($organization) {
				$q->where('organization_id', $organization->id);
			});
		}
		return array(
			'sector_id' => $sectors_classifications->get()->pluck('classification.code', 'id')->toArray(),
		);
	}

	public function linkRelative($request)
	{
		return $this->food_weights()->syncWithoutDetaching($request->food_weights);
	}

	public static function filterColumns()
	{
		$meals = Meal::with(
			'order_sector.sector.nationality_organization.nationality:id,name'
		)->get();
		return array(
			'organization_ids' => Organization::get()->pluck('name', 'id')->unique()->toArray(),
			'period' => Period::get()->where('operation_type_id', OperationType::MEAL_STAGES)->pluck('name', 'id')->unique()->toArray(),
			'day' => $meals->sortBy('day_date')->pluck('day_date', 'day_date')->unique()->toArray(),
			'meal_status' => Status::get()->where('type', 'meals')->pluck('name', 'id')->toArray(),
			//            'current_stage' => StageBank::get()->pluck('name', 'id')->toArray(),
			//            'sector' => Sector::get()->pluck('label', 'id')->toArray(),
			'order_sector' => OrderSector::with(
				'sector:id,label,nationality_organization_id',
				'order.organization_service.service:id,name_ar,name_en',
				'order.facility:id,name',
				'order.organization_service.organization:id,name_ar,name_en',
				'sector.nationality_organization.nationality:id,name',
			)->get()->pluck('order_sector_name', 'sector_id')->toArray(),
			'nationality' => $meals->pluck('order_sector.sector.nationality_organization.nationality')
				->filter(function ($item) {
					return !is_null($item);
				})
				->pluck('name', 'id')
				->unique()
				->toArray(),
		);
	}

	public function supports()
	{
		return $this->hasMany(Support::class);
	}

	public function getHasSupportAttribute()
	{
		return $this->supports->isNotEmpty();
	}

	public function getScheduledStartTimestampAttribute(): Carbon
	{
		return Carbon::parse("$this->start_time $this->day_date")->addMinutes(10); // 10 minutes buffer for the first stage
	}

	public function getScheduledEndTimestampAttribute(): Carbon
	{
		return Carbon::parse("$this->end_time $this->day_date");
	}

    public function getCurrentStageAttribute()
    {
        if ($this->relationLoaded('meal_organization_stages')) {
            $stages = $this->meal_organization_stages;
        } else {
            $stages = $this->meal_organization_stages()->get();
        }

        return $stages->sortBy('arrangement')
            ->first(fn($s) => is_null($s->done_at))
            ?? $stages->sortBy('arrangement')->last();
    }
}
