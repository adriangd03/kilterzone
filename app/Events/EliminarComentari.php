<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EliminarComentari implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    private int $ruta_id;
    private int $comentari_id;
    private bool $isCreador;
    public function __construct(int $ruta_id, int $comentari_id, bool $isCreador)
    {
        $this->ruta_id = $ruta_id;
        $this->comentari_id = $comentari_id;
        $this->isCreador = $isCreador;
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
            'comentari_id' => $this->comentari_id,
            'isCreador' => $this->isCreador
        ];
    }

    public function broadcastAs()
    {
        return 'EliminarComentari';
    }
}
