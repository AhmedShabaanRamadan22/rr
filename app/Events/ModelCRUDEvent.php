<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelCRUDEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $model_name;
    public $model_id;
    public $change_type; // 'created', 'updated', 'deleted'
    public $extra_data;

    /**
     * Create a new event instance.
     *
     * @param string $model_name The class name of the model.
     * @param mixed $model_id The ID of the model.
     * @param string $change_type The type of change ('created', 'updated', 'deleted').
     */
    public function __construct(string $model_name, $model_id, string $change_type, $extra_data = [])
    {
        $this->model_name = $model_name;
        $this->model_id = $model_id;
        $this->change_type = $change_type;
        $this->extra_data = $extra_data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    public function broadcastOn()
    {
        return new Channel( 'ModelCRUD-changes');
    }

    public function broadcastAs() {
        return $this->model_name . '-changes';
    }

    public function broadcastWith(){
        [
            //'data'=> $this->data,
        ];
    }

}
