<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Events\ModelCRUDEvent;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        event(new ModelCRUDEvent(class_basename($ticket), $ticket->id, 'created', ['order_sector_id' => $ticket->order_sector_id]));
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        event(new ModelCRUDEvent(class_basename($ticket), $ticket->id, 'updated'));
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        event(new ModelCRUDEvent(class_basename($ticket), $ticket->id, 'deleted'));
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
