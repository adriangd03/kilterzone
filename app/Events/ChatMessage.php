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

class ChatMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $message = '';
    private User $user;
    private User $receiver;


    /**
     * Create a new event instance.
     */
    public function __construct( string $message, User $user, User $receiver)
    {
        $this->message = $message;
        $this->user = $user;
        $this->receiver = $receiver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('presence.ChatMessage.'.$this->receiver->id.''),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'user' => $this->user->only(['username', 'avatar']),
            'receiver' => $this->receiver->only(['username', 'avatar']),
        ];
    }

    public function broadcastAs() {
        return 'ChatMessage';
      }


}
