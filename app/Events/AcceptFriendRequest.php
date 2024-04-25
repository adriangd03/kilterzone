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

class AcceptFriendRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private User $friend;
    private User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(User $friend, User $user){
        $this->friend = $friend;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('presence.ChatMessage.'.$this->friend->id),
        ];
    }


    public function broadcastWith(): array
    {
        return [
            'user' => $this->user->only(['username', 'avatar', 'id']),
        ];
    }

    public function broadcastAs() {
        return 'AcceptFriendRequest';
      }
}
