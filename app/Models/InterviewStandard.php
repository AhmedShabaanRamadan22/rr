<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewStandard extends Base
{
    use SoftDeletes;
    protected $table = 'interview_standards';
	public $timestamps = true;

	protected $dates = ['deleted_at'];
	protected $fillable = array('max_score','name','description');

    public function interview_standard_orders()
	{
		return $this->hasMany(InterviewStandardOrder::class);
	}
	public static function columnNames()
	{
		return array(
			'id' => 'id',
			'name' => 'standard',
			'max_score' => 'max_score',
			'description' => 'description',
			'action' => 'action'
		);
	}
	public static function columnInputs()
	{
		return array(
			'name' => 'text',
			'max_score' => 'number',
			'description' => 'text',
		);
	}



	public function get_score_suggestion($order){
		$facility = $order->facility;
		$score_suggestion = null;

		if($facility){
			switch($this->id){ 
				case 1: // standard: عدد العمالة
					$employee_number = $facility->employee_number;
					$score_suggestion = 0;
					if($employee_number != 0 ){
						if( $employee_number < 5){
							$score_suggestion = 9;
						}
						else if($employee_number >= 5 && $employee_number < 10){
							$score_suggestion = 12;
						}
						else if($employee_number >= 10 ){
							$score_suggestion = 15;
						}
					}
					break;
				case 2: // standard: مساحة المطبخ
					$kitchen_space = $facility->kitchen_space;
					$score_suggestion = 0;
					if($kitchen_space > 300 ){
						if( $kitchen_space <= 400){
							$score_suggestion = 6;
						}
						else if($kitchen_space > 400 && $kitchen_space <= 600 ){
							$score_suggestion = 8;
						}
						else if($kitchen_space > 600 ){
							$score_suggestion = 10;
						}
					}
					break;

				case 3: // standard: عدد سنوات السجل التجاري
					$diff_in_years = \Carbon\Carbon::now()->diffInYears($facility->version_date);
					$score_suggestion = 1;
					if($diff_in_years >= 1 && $diff_in_years <= 3 ){
						$score_suggestion = 2;
					}
					else if($diff_in_years > 3){
						$score_suggestion = 5;
					}
					break;
			}
		}
		

		return $score_suggestion;
	}
}