<?php

namespace App\Traits;

use App\Models\Notification;
use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\RoutesNotifications;

trait BaseNotifiable
{
    use HasDatabaseNotifications, RoutesNotifications;

	public function base_notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }

    public function unreadNotificationsLimit()
    {
        return $this->base_notifications()->unread()->lastsWithLimit();
    }

}