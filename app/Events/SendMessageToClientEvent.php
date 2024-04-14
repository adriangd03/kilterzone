<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendMessageToClientEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $message = 'Hello World';
    private User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(string $message = null, User $user)
    {
        $this->message = $message ?? 'Hello World';
        $this->user = $user;
    }
    /**
     * Get the data to broadcast.
     */
    public function broadcastAs() {
        return 'SendMessageToClientEvent';
      }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('presence.SendMessageToClientEvent.1'),
        ];
    }

    

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'user' => $this->user->only(['username', 'avatar']),
        ];
    }

}
