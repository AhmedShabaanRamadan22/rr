<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Str;

trait AssignableTrait{

    public function assignees()
    {
        return $this->morphToMany(User::class, 'assignable', 'assigns', 'assignable_id', 'assignee_id')->whereNull('assigns.deleted_at');
    }

	//?=========================================================

    public function assigners()
    {
        return $this->morphToMany(User::class, 'assignable', 'assigns', 'assignable_id', 'assigner_id')
					->whereNull('assigns.deleted_at')
					->distinct();
    }

	//?=========================================================

	public function scopeAssignee($query, User $user)
	{
		return $query->whereHas('assignees', function ($q) use ($user) {
			$q->where('users.id', $user->id);
		});
	}
	
	//?=========================================================
	
	public function is_assignee($user){

		return $user ? $this->assignees()->where('users.id',$user->id)->exists() : null;
	}
	
	//?=========================================================
	
	public function assignToUser($assignee){
		$assigner = auth()->user();

		return $this->is_assignee($assignee) ? null : $this->assigns()->create([
			'assigner_id' => $assigner->id ?? 1,
			'assignee_id' => $assignee->id,
		]);
	}

	//?=========================================================
	
	public function assignToUserWithMessage($assignee)
	{
		if($this->assignToUser($assignee)){
			return trans('translation.success-assign');
		} else {
			return trans('translation.failed-assign');
		}
	}

	//?=========================================================
	
	public function unassignToUser($assignee)
	{
		return !$this->is_assignee($assignee) ? null : $this->assigns()
			->where('assignee_id', $assignee->id)
			->delete();
	}

	//?=========================================================
	
	public function unassignToUserWithMessage($assignee)
	{
		if($this->unassignToUser($assignee)){
			return trans('translation.success-unassign');
		} else {
			return trans('translation.failed-unassign');
		}
	}

	//?=========================================================
	

}