@component('mail::message')
    # Atualização de Pedido de Viagem

    Seu pedido para viajar para **{{ $order->destination }}** foi atualizado para **{{ $order->status }}**.

    @component('mail::button', ['url' => url('/orders/'.$order->id)])
        Ver Pedido
    @endcomponent

    Obrigado,<br>
    {{ config('app.name') }}
@endcomponent
