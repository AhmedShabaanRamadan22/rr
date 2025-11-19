<?php

namespace App\Observers;

use App\Models\Support;
use App\Events\ModelCRUDEvent;

class SupportObserver
{
    /**
     * Handle the Support "created" event.
     */
    public function created(Support $support): void
    {
        event(new ModelCRUDEvent(class_basename($support), $support->id, 'created'));
    }

    /**
     * Handle the Support "updated" event.
     */
    public function updated(Support $support): void
    {
        event(new ModelCRUDEvent(class_basename($support), $support->id, 'updated',  [
            'meal_id' => $support->meal_id,
            'status' => $support->status_id,
            'type' => $support->type,
            'order_sector_id' => $support->order_sector_id,
        ]));
    }

    /**
     * Handle the Support "deleted" event.
     */
    public function deleted(Support $support): void
    {
        event(new ModelCRUDEvent(class_basename($support), $support->id, 'deleted'));
    }

    /**
     * Handle the Support "restored" event.
     */
    public function restored(Support $support): void
    {
        //
    }

    /**
     * Handle the Support "force deleted" event.
     */
    public function forceDeleted(Support $support): void
    {
        //
    }
}
