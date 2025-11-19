<?php

namespace App\Observers;

use App\Events\ModelCRUDEvent;
use App\Models\Assist;

class AssistObserver
{
    /**
     * Handle the Support "created" event.
     */
    public function created(Assist $assist): void
    {
        $support = $assist->support;
        event(new ModelCRUDEvent(class_basename($support), $support->id, 'created'));
    }
    
    /**
     * Handle the Support "updated" event.
     */
    public function updated(Assist $assist): void
    {
        $support = $assist->support;
        event(new ModelCRUDEvent(class_basename($support), $support->id, 'updated'));
    }
    
    /**
     * Handle the Support "deleted" event.
     */
    public function deleted(Assist $assist): void
    {
        $support = $assist->support;
        event(new ModelCRUDEvent(class_basename($support), $support->id, 'deleted'));
    }

    /**
     * Handle the Support "restored" event.
     */
    public function restored(Assist $assist): void
    {
        //
    }

    /**
     * Handle the Support "force deleted" event.
     */
    public function forceDeleted(Assist $assist): void
    {
        //
    }
}
