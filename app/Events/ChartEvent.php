<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChartEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ordersData;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($ordersData)
    {
        //dd($ordersData);
        $this->ordersData = $ordersData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['charts'];
    }
    public function broadcastAs()
    {
        return 'chart-event';
    }
    public function broadcastWith(){
        [
            'ordersData'=> $this->ordersData,
        ];
    }
}
