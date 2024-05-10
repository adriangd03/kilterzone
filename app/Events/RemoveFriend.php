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

class RemoveFriend implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private User $user;
    private User $receiver;

    /**
     * Create a new event instance.
     * @param User $user Usuari que ha eliminat l'amic
     * @param User $receiver Usuari que ha estat eliminat com a amic
     */
    public function __construct(User $user, User $receiver)
    {
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
            new PresenceChannel('presence.ChatMessage.'.$this->receiver->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'user' => $this->user->only(['username', 'avatar', 'id']),
        ];
    }

    public function broadcastAs() {
        return 'RemoveFriend';
      }
}
