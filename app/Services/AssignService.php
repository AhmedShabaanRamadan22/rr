<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Notifications\AssignNotify;

class AssignService
{

    //*====================
    //* Generel
    public static function all_assignees_by_model($assignable , $more_columns = []){
        $select = ['id','name'] + $more_columns;
        $assignees = User::whereHas('assigns_to', function ($query) use($assignable,$select) {
            $query->where('assignable_type', $assignable);
        })->select($select)->get();
        
        return $assignees;
    }
    
    public static function assign_multiple_users_to_model($assignable,$user_ids)
    {
        $assigner_id = auth()->user()->id ;
        $new_assigns_count = 0;

        foreach ($user_ids ?? [] as $assignee_id) {
            $assign = $assignable->assigns()->firstOrCreate([
                'assignee_id' => $assignee_id,
                // 'assignable_id' => $assignable->id,
                // 'assignable_type' => get_class($assignable),
            ],
            [
                'assigner_id' => $assigner_id,
            ]);

            if($assign->wasRecentlyCreated){
                $assign->assignee->notify(new AssignNotify($assignable,$assign->assigner()->value('name') . " [$assigner_id]"));
                $new_assigns_count++;
            }

        }

        return $new_assigns_count;
    }

    public static function assign_multiple_record_of_model_to_user($assignee,$assignable_ids,$assignable_type)
    {
        $assigner_id = auth()->user()->id ;
        $new_assignables_count = 0;

        foreach($assignable_ids ?? [] as $assignable_id){
            $assign = $assignee->assigns_to()->firstOrCreate([
                'assignable_id'   => $assignable_id,
                'assignable_type' => $assignable_type,
            ],[
                'assigner_id'     => $assigner_id,
            ]);
            if($assign->wasRecentlyCreated){
                $assignee->notify(new AssignNotify($assign->assignable,$assign->assigner()->value('name') . " [$assigner_id]"));
                $new_assignables_count++;
            }
        }

        return $new_assignables_count;
        
    }

    public function unassign_multiple_record_of_model_to_user($assignee,$assignable_ids,$assignable_type)
    {
        $assignables_deleted_count = 0;

        foreach($assignable_ids ?? [] as $assignable_id){
            $assignables = $assignee->assigns_to()->where([
                'assignable_id'   => $assignable_id,
                'assignable_type' => $assignable_type,
            ])->delete();
            $assignables_deleted_count += $assignables;
        }

        return $assignables_deleted_count;
    }
   

}