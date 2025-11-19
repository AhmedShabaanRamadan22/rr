<?php

namespace App\Models;

use App\Traits\HasCreatorLabelTrait;
use App\Traits\UuidableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubmittedForm extends Base
{
    use SoftDeletes, HasFactory, UuidableTrait, HasCreatorLabelTrait;

    protected $table = 'submitted_forms';
    public $timestamps = true;
    protected $dates = ['created_at', 'deleted_at', 'updated_at'];
    protected $fillable = ['user_id', 'order_sector_id', 'form_id', 'submitted_sections'];
    // protected $appends =  ['is_completed'];

    protected $casts = ['submitted_sections' => 'array'];

    public function monitor(){
        return $this->user->monitor();
    }

    public function user(){
        return $this->belongsTo(User::class,);
    }

    public function order_sector(){
        return $this->belongsTo(OrderSector::class,)->withArchived();
    }

    public function form(){
        return $this->belongsTo(Form::class,);
    }

	public function answers()
	{
		return $this->morphMany(Answer::class,'answerable');
	}

	public function yes_no_answers()
	{
		return $this->morphMany(Answer::class,'answerable')->whereHas('question.question_bank_organization.question_bank',function($q){
            $q->where('question_type_id',10);
        });
	}

	public function yes_answers()
	{
		return $this->yes_no_answers()->where('value','yes');
	}

    public function getYesAnswersPercentageAttribute(){
        return $this->yes_no_answers->count() == 0 ? 0 : round( ( $this->yes_answers->count() / $this->yes_no_answers->count() ) * 100 , 2);
    }

    public function getYesAnswersPercentageColorTextAttribute(){
        $value =$this->yes_answers_percentage;
        return $value < 50 ?
                    'danger' :
                    ($value > 75 ?
                        'success' :
                        'warning'
                    );
    }

    public function getIsCompletedAttribute(){
        //logic for checking if this submittedForm has all submittedSections
        // dd($this->form->sections_has_question()->pluck('id')->toArray(),  $this->submitted_sections);
        return empty(array_diff($this->form->sections_has_question->pluck('id')->toArray(), $this->submitted_sections));
        // return $this->form->sections_has_question()->count() == count($this->submitted_sections);
    }
    public static function columnNames()
    {
        $data = array(
            'id' => 'id',
            'order_sector_name' => 'order_sector',
            'sector_nationality' => 'nationality',
            'category' => 'category',
            'monitors' => 'monitor',
            'form' => 'form',
            'submitted_section' => 'filled_by',
//            'yes_answers_percentage' => 'yes_answers_percentage',
            'is_completed' => 'completed',
            'created_at' => 'creation-date',
            'updated_at' => 'update-date',
        );
        // $is_chairman = auth()->user() != null ? auth()->user()->hasRole('organization chairman') : false;
        $can_view_action_column = auth()->check() && auth()->user()?->can('view_submitted_form_action_column');
        if($can_view_action_column){
            $data = array_merge( $data, ['sector-reports' => 'sector-reports', 'monitor-reports' => 'monitor-reports', 'action' => 'action']);
        }

        return $data;
    }

    public static function columnInputs()
    {
        return array();
    }

    public static function filterColumns()
    {
        // return array(
        //     'order_sector_id',
        //     'user_id',
        //     'form_id',
        // );
        $submitted_form = SubmittedForm::with([
                'order_sector:id,order_id,sector_id',
                'order_sector.sector:id,label,nationality_organization_id',
                'order_sector.order.organization_service.service:id,name_ar,name_en',
                'order_sector.order.facility:id,name',
                'order_sector.order.organization_service.organization:id,name_ar,name_en',
                'order_sector.sector.nationality_organization.nationality:id,name',
        ])->get();
        return array(
            'order_sector_id' => $submitted_form->pluck('order_sector.order_sector_name', 'order_sector.id'),
            // 'order_sector_id' => OrderSector::get()->pluck('order_sector_name', 'id'),
            'user_id' => User::with('roles')->whereHas('roles', function($q){
                $q->where('name', 'monitor');
            })->pluck('name', 'id'),
            'organization_id' => Organization::get()->pluck('name', 'id'),
            'category_id' => Category::get()->pluck('name', 'id'),
            'sector_nationality_id' => $submitted_form->pluck('order_sector.sector.nationality_organization.nationality.name', 'order_sector.sector.nationality_organization.nationality.id'),
            'form_id' => Form::get()->pluck('name_and_organization', 'id'),
        );
    }
    public static function columnOptions()
    {
        // return array(
        //     'order_sector_id' => OrderSector::all()->pluck('order_sector_name', 'id')->toArray(),
        //     'user_id' => User::all()->pluck('name', 'id')->toArray(),
        //     'form_id' => Form::all()->pluck('name', 'id')->toArray(),
        // );
    }

    public function submitted_sections(){
        return $this->hasMany(SubmittedSection::class, );
    }
    public function submitted_section(){
        return $this->hasOne(SubmittedSection::class, )->latest();
    }
}
