<?php

namespace App\Services;

use Illuminate\Support\Facades\App;

class WhiteListService
{
    public static function check_white_list_phone($request){
        return config('app.white_list_wall_flag') && 
        App::environment() === 'production' && ! in_array($request->phone,config('app.white_list_wall_flag_numbers'));
    }
    public static function check_white_list_monitor_phone($request,$user){

        $supervisor_pass_white_list_wall = (config('app.supervisor_pass_white_list_wall_flag') && $user->hasRole(['boss','supervisor']));
        if($supervisor_pass_white_list_wall){
            return !$supervisor_pass_white_list_wall; // add ! to make pass the condition in login function
        }
        return ($user->hasRole('monitor') && self::check_white_list_phone($request));
    }

}