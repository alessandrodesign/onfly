<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\TravelOrder;

class OrderStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(TravelOrder $order)
    {
        $this->order = $order;
    }

    public function build(): OrderStatusNotification
    {
        return $this->subject('Atualização de Status do Pedido')
            ->markdown('emails.order_status');
    }
}
