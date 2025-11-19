<?php

namespace App\Events;

use App\Http\Resources\MealDashboard\MealResource;
use App\Models\Meal;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MealStageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $meal;

    /**
     * Create a new event instance.
     *
     * @param string $model_name The class name of the model.
     * @param mixed $model_id The ID of the model.
     * @param string $change_type The type of change ('created', 'updated', 'deleted').
     */
    public function __construct(Meal $meal)
    {
        $this->meal = $meal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    public function broadcastOn()
    {
        return new Channel( 'MealStage-changes-channal');
    }

    public function broadcastAs() {
        return 'MealStage-changes';
    }

    public function broadcastWith(){
        return (new MealResource($this->meal))->toArray();
    }

}
