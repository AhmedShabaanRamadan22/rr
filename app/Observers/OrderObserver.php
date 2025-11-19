<?php

namespace App\Observers;

use App\Events\ModelCRUDEvent;
use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        event(new ModelCRUDEvent(class_basename($order), $order->id, 'created'));
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        event(new ModelCRUDEvent(class_basename($order), $order->id, 'updated'));
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        event(new ModelCRUDEvent(class_basename($order), $order->id, 'deleted'));
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
