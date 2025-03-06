<?php

namespace App\Events;

use App\Models\TravelOrder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class NewOrderEvent implements ShouldBroadcast
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
     * Define em qual canal o evento será transmitido.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        // Canal privado 'orders'
        return new PrivateChannel('orders');
    }

    /**
     * Nome personalizado para o evento transmitido.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'new.order';
    }
}
