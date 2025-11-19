<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Facility;
use App\Models\Order;
use App\Models\Organization;
use App\Models\User;
use App\Policies\DashboardPolicy;
use App\Policies\FacilityPolicy;
use App\Policies\OrderPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Facility::class => FacilityPolicy::class,
        Order::class => OrderPolicy::class,
        Organization::class => DashboardPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });
    }
}
