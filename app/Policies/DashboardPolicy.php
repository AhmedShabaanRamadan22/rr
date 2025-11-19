<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;

class DashboardPolicy
{

    public function view_meals_dashboard(User $user, Organization $organization): bool
    {
        return  $user->hasPermissionTo('view_meals_dashboard') || ($user->hasRole('organization chairman') && $user->organization_id == $organization->id);
    }

    public function view_all_meals_dashboard(User $user): bool
    {
        return  $user->hasPermissionTo('view_all_meals_dashboard');
    }
}
