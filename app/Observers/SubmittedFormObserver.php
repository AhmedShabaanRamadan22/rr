<?php

namespace App\Observers;

use App\Events\ModelCRUDEvent;
use App\Models\SubmittedForm;

class SubmittedFormObserver
{
    /**
     * Handle the SubmittedForm "created" event.
     */
    public function created(SubmittedForm $submittedForm): void
    {
        event(new ModelCRUDEvent(class_basename($submittedForm), $submittedForm->id, 'created'));
    }

    /**
     * Handle the SubmittedForm "updated" event.
     */
    public function updated(SubmittedForm $submittedForm): void
    {
        event(new ModelCRUDEvent(class_basename($submittedForm), $submittedForm->id, 'updated'));
    }

    /**
     * Handle the SubmittedForm "deleted" event.
     */
    public function deleted(SubmittedForm $submittedForm): void
    {
        event(new ModelCRUDEvent(class_basename($submittedForm), $submittedForm->id, 'deleted'));
    }

    /**
     * Handle the SubmittedForm "restored" event.
     */
    public function restored(SubmittedForm $submittedForm): void
    {
        //
    }

    /**
     * Handle the SubmittedForm "force deleted" event.
     */
    public function forceDeleted(SubmittedForm $submittedForm): void
    {
        //
    }
}
