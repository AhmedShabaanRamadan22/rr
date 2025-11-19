<?php

namespace App\Traits;

trait HasCreatorLabelTrait
{
    public function getCreatorLabelAttribute()
    {
        if (config('app.use_monitor_code')) {
            return $this->user?->monitor?->code ?? '-';
        }

        return $this->user?->name ?? '-';
    }
}
