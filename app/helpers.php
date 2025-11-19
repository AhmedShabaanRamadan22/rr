<?php

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Support\Str;

if (!function_exists('is_found')) {
    function is_found($text)
    {
        if (trim($text) == '' || $text == null) {
            return trans('translation.not_found');
        }
        return $text;
    }
}

//??================================================================

if (!function_exists('audit_value_changes')) {
    function audit_value_changes($audit){
        $keys = [
            'updated'=>array_keys($audit->old_values),
            'created'=>array_keys($audit->new_values),
            'deleted'=>array_keys($audit->old_values),
        ];
        $value_changes = '';
        $keys_event = $keys[$audit->event];
        // dd($audit->auditable);
        if($audit->auditable_type == "App\Models\Attachment"){
            $atachemnt = Attachment::withTrashed()->find($audit->auditable_id);
            $a_tag = '<a target="_blank" href="'.($atachemnt->url??'').'">' . ($atachemnt->placeholder??'-') . '</a>';
            $value_changes .= trans('translation.value-changed-from-to',['model'=>str_replace('App\\Models\\','',$audit->auditable_type),'old'=>$a_tag,'new'=>'','key'=>'file','event'=>trans('translation.'.$audit->event)]);


        }else {
            foreach( $keys_event as $key ){
                $value_changes .= trans('translation.value-changed-from-to',['model'=>str_replace('App\\Models\\','',$audit->auditable_type),'old'=>isset($audit->old_values[$key])?'من ('.$audit->old_values[$key].')':'','new'=>isset($audit->new_values[$key])?"إلى (" . $audit->new_values[$key].')':'','key'=>$key,'event'=>trans('translation.'.$audit->event)]);
            }

        }

        return $value_changes;
    }
}

//??================================================================

if (!function_exists('auditer_name')) {
    function auditer_name($audit)
    {
        if(isset($audit->user->name)){
            return $audit->user->name;
        }
        if($audit->auditable_type == "App\Models\User"){
            return $audit->auditable->name;
        }
        if(isset($audit->new_values['user_id'])){
            if($user = User::select('name')->find($audit->new_values['user_id'])){
                return $user->name;
            }
        }
        return "not found";
    }
}

//??================================================================

if (!function_exists('auditer_profile')) {
    function auditer_profile($audit)
    {
        if(isset($audit->user->profile_photo)){
            return $audit->user->profile_photo;
        }
        if($audit->auditable_type == "App\Models\User"){
            return $audit->auditable->profile_photo;
        }
        if(isset($audit->new_values['user_id'])){
            if($user = User::find($audit->new_values['user_id'])){
                return $user->profile_photo;
            }
        }
        return asset('build/images/users/32/person.png');
    }
}

//??================================================================

if (!function_exists('is_production')) {
    function is_production()
    {
        return App::environment() == 'production';
    }
}

//??================================================================

if (!function_exists('dates_range')) {
    function dates_range(int $previous_days, int $next_days, $format = "Y-m-d", $start_from = 'now' )
    {
        $dates = [];
        $start_date = \Carbon\Carbon::parse($start_from)->subDays($previous_days);
        $next_days = $next_days + $previous_days;
        for ($i = 0; $i < $next_days; $i++) {
            $dates[] = $start_date->addDay(1)->format($format);
        }

        return $dates;
    }
}

//??================================================================

if (!function_exists('crud_routes')) {
    function crud_routes()
    {
        return [
            'user'=>[
                ['route' => 'users', 'controller' => 'UserController', 'icon' => 'mdi-account-outline'],
                ['route' => 'organizations', 'controller' => 'OrganizationController', 'icon' => 'mdi-account-group-outline'],
                ['route' => 'facilities', 'controller' => 'FacilityController', 'icon' => 'mdi-office-building-outline'],
            ],
            'operation type before'=>[
                ['route' => 'orders', 'controller' => 'OrderController', 'icon' => 'mdi-reorder-horizontal'],
            ],
            'operation type during'=>[
                ['route' => 'tickets', 'controller' => 'TicketController', 'icon' => 'mdi-ticket-confirmation-outline'],
                ['route' => 'supports', 'controller' => 'SupportController', 'icon' => 'mdi-truck-delivery-outline'],

            ],
            // 'operation type after'=>[
                //     //['route' => 'reports', 'controller' => 'ReportController', 'icon' => 'mdi-flag-outline'],
                // ],
                'questions'=>[
                    ['route' => 'forms', 'controller' => 'FormController', 'icon' => 'mdi-note-edit-outline'],
                    ['route' => 'question-banks', 'controller' => 'QuestionBankController', 'icon' => 'mdi-bank'],
                    ['route' => 'question-types', 'controller' => 'QuestionTypeController', 'icon' => 'mdi-transition-masked'],
                    ['route' => 'regexes', 'controller' => 'RegexController', 'icon' => 'mdi-quadcopter'],
                    // ['route' => 'question-bank-organizations', 'controller' => 'QuestionBankOrganizationController', 'icon' => 'mdi-quadcopter'],
                ],
                'sectors'=>[
                    ['route' => 'sectors', 'controller' => 'SectorController', 'icon' => 'mdi-chart-timeline-variant'],
                    ['route' => 'classifications', 'controller' => 'ClassificationController', 'icon' => 'mdi-tag-outline'],
                    ['route' => 'nationalities', 'controller' => 'NationalityController', 'icon' => 'mdi-flag-outline'],
                    ['route' => 'monitors', 'controller' => 'MonitorController', 'icon' => 'mdi-flag-outline'],
                ],
                'food'=>[
                    ['route' => 'food', 'controller' => 'FoodController', 'icon' => 'mdi-food-apple-outline'],
                    ['route' => 'meals', 'controller' => 'MealController', 'icon' => 'mdi-food-fork-drink'],
                    ['route' => 'food-types', 'controller' => 'FoodTypeController', 'icon' => 'mdi-food-outline'],
                    ['route' => 'periods', 'controller' => 'PeriodController', 'icon' => 'mdi-timetable'],
                ],
                'settings'=>[
                    ['route' => 'services', 'controller' => 'ServiceController', 'icon' => 'mdi-cog-outline'],
                    ['route' => 'roles', 'controller' => 'RoleController', 'icon' => 'mdi-account-outline'],
                    ['route' => 'categories', 'controller' => 'CategoryController', 'icon' => 'mdi-book-outline'],
                    ['route' => 'facility-employee-positions', 'controller' => 'FacilityEmployeePositionController', 'icon' => 'mdi-account-box-outline'],
                    ['route' => 'statuses', 'controller' => 'StatusController', 'icon' => 'mdi-state-machine'],
                    ['route' => 'attachment-labels', 'controller' => 'AttachmentLabelController', 'icon' => 'mdi-pin-outline'],
                    ['route' => 'operation-types', 'controller' => 'OperationTypeController', 'icon' => 'mdi-account-cog-outline'],
                    ['route' => 'reasons', 'controller' => 'ReasonController', 'icon' => 'mdi-lightbulb-outline'],
                    ['route' => 'dangers', 'controller' => 'DangerController', 'icon' => 'mdi-alert-decagram-outline'],
                    ['route' => 'fines', 'controller' => 'FineController', 'icon' => 'mdi-cash-multiple'],
                    ['route' => 'bravos', 'controller' => 'BravoController', 'icon' => 'mdi-radio-tower'],
                    ],
                'message'=>[
                    ['route' => 'senders', 'controller' => 'SenderController', 'icon' => 'mdi-email-outline'],
                    ['route' => 'messages', 'controller' => 'MessageController', 'icon' => 'mdi-message-reply-text-outline']
                ],


        ];
    }
}

//??================================================================

if (!function_exists('fakeUuid')) {
    function fakeUuid(){
        $uuid = (string) Str::Uuid();
        $modifiedUuid = substr_replace($uuid, 'kaf241', -2, 0);
        return $modifiedUuid; 
    }
}

//??================================================================
