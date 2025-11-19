<?php
namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can view any users.
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view_users'); // Using Spatie Permissions
    }

    /**
     * Determine if the user can view a specific user.
     */
    public function view(User $user, User $model)
    {
        return $user->id === $model->id || $user->hasPermissionTo('view_users');
    }

    /**
     * Determine if the user can create users.
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_users');
    }

    /**
     * Determine if the user can update a user.
     */
    public function edit(User $user, User $model)
    {
        return $user->id === $model->id || $user->hasPermissionTo('view_edit_user') || $model->orders()->assignee($user)->exists();
    }

    /**
     * Determine if the user can update a user.
     */
    public function update(User $user, User $model)
    {
        return $user->id === $model->id || $user->hasPermissionTo('update_users');
    }

    /**
     * Determine if the user can delete a user.
     */
    public function delete(User $user, User $model)
    {
        return $user->hasPermissionTo('delete_users');
    }
}
