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

class NouComentari implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    private int $ruta_id;
    private string $comentari;
    private User $user;
    public function __construct(int $ruta_id, string $comentari, User $user)
    {
        $this->ruta_id = $ruta_id;
        $this->comentari = $comentari;
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
            new Channel('ruta.' . $this->ruta_id)
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'comentari' => $this->comentari,
            'user' => $this->user->only(['username', 'avatar', 'id']),
        ];
    }

    public function broadcastAs()
    {
        return 'NouComentari';
    }
}
