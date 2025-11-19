<?php

namespace App\Observers;

use App\Models\Sector;
use App\Models\User;

class SectorObserver
{
    /**
     * Handle the Sector "created" event.
     */
    public function created(Sector $sector): void
    {
        //
        $this->assignRoles($sector, $sector->boss_id, 'boss_id', ['8']);
        $this->assignRoles($sector, $sector->supervisor_id, 'supervisor_id', ['7']);
    }

    /**
     * Handle the Sector "updated" event.
     */
    public function updated(Sector $sector): void
    {
        //
        $this->assignRoles($sector, $sector->boss_id, 'boss_id', ['8']);
        $this->assignRoles($sector, $sector->supervisor_id, 'supervisor_id', ['7']);
    }

    /**
     * Handle the Sector "deleted" event.
     */
    public function deleted(Sector $sector): void
    {
        //
    }

    /**
     * Handle the Sector "restored" event.
     */
    public function restored(Sector $sector): void
    {
        //
    }

    /**
     * Handle the Sector "force deleted" event.
     */
    public function forceDeleted(Sector $sector): void
    {
        //
    }
    
    protected function assignRoles($sector, $user_id, $column, $role)
    {
        if (isset($sector->$column)) {
            $user = User::findOrFail($user_id);
            $user->assignRole($role);
        }
    }
}