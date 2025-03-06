<?php

namespace App\Events;

use App\Models\TravelOrder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class OrderStatusChangedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TravelOrder $order;

    /**
     * Cria uma nova instância do evento.
     *
     * @param TravelOrder $order
     * @return void
     */
    public function __construct(TravelOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Define o canal para transmissão do evento.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        // Pode usar o mesmo canal ou definir outro, conforme sua necessidade
        return new PrivateChannel('orders');
    }

    /**
     * Nome personalizado para o evento transmitido.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'order.status.changed';
    }
}
