<?php

namespace App\View\Components;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserTrackingComponent extends Component
{
    /**
     * Create a new component instance.
     */
    protected $usersJson; 
    public function __construct()
    {
        // $this->usersJson = User::with('track_locations')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-tracking-component')->with([
            'usersJson' => $this->usersJson
        ]);
    }
}
