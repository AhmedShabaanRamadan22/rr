<?php

namespace App\Providers;

use App\Models\Assist;
use App\Models\Meal;
use App\Models\MealOrganizationStage;
use App\Models\Sector;
use App\Observers\MealObserver;
use App\Observers\SectorObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\Support;
use App\Models\Ticket;
use App\Models\Order;
use App\Models\SubmittedForm;
use App\Observers\AssistObserver;
use App\Observers\MealOrganizationStageObserver;
use App\Observers\TicketObserver;
use App\Observers\OrderObserver;
use App\Observers\SubmittedFormObserver;
use App\Observers\SupportObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
        Sector::observe(SectorObserver::class);
        Support::observe(SupportObserver::class);
        Assist::observe(AssistObserver::class);
        Ticket::observe(TicketObserver::class);
        Order::observe(OrderObserver::class);
        Meal::observe(MealObserver::class);
        MealOrganizationStage::observe(MealOrganizationStageObserver::class);
        SubmittedForm::observe(SubmittedFormObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
