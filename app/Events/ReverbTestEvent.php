<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReverbTestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $message;
    private $userId;
    /**
     * Create a new event instance.
     */
    public function __construct($message, $userId = null)
    {
        $this->message = $message;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        $chats = [new Channel('chat')];
        if ($this->userId) $chats[] = new PrivateChannel('chat.' . $this->userId);
        return $chats;
    }

    public function broadcastAs()
    {
        return 'newMessage';
    }

    public function broadcastWith(): array
    {
        return ['message' => $this->message];
    }
}
