<?php

namespace App\Traits;

use App\Models\Country;

trait bySupervisorTrait
{


    public function scopeBySupervisor($query, $supervisorId)
    {
        return $query->whereHas('order_sector', function ($orderSectorQuery) use ($supervisorId) {
            $orderSectorQuery->whereHas('sector', function ($sectorQuery) use ($supervisorId) {
                $sectorQuery->where('supervisor_id', $supervisorId);
            });
        });
    }
}
