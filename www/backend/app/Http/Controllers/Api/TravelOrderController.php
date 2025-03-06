<?php

namespace App\Http\Controllers\Api;

use App\Events\NewOrderEvent;
use App\Events\OrderStatusChangedEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\TravelOrder;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusNotification;

class TravelOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = TravelOrder::query();

        if ($request->has('status') && $request->get('status') !== null) {
            $query->where('status', $request->status);
        }
        if ($request->has('destination') && $request->get('destination') !== null) {
            $query->where('destination', 'like', '%' . $request->destination . '%');
        }
        if ($request->has('start_date') && $request->get('start_date') !== null && $request->has('end_date') && $request->get('end_date') !== null) {
            $query->whereBetween('departure_date', [$request->start_date, $request->end_date]);
        }

        // Se for cliente, só permite ver seus pedidos
        $user = auth()->user();
        if ($user->role == 'client') {
            $query->where('user_id', $user->id);
        } else {
            $query->with('user');
        }

        $query->latest();

        $orders = $query->paginate(10);

        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'destination'     => 'required|string',
            'departure_date'  => 'required|date',
            'return_date'     => 'required|date|after_or_equal:departure_date',
        ]);

        $order = TravelOrder::create([
            'user_id'        => auth()->user()->id,
            'destination'    => $request->destination,
            'departure_date' => $request->departure_date,
            'return_date'    => $request->return_date,
            'status'         => 'solicitado'
        ]);

        // Notifica os administradores
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new OrderStatusNotification($order));
        }

        // Dispara evento para notificação via componente (por exemplo, via broadcast)
        event(new NewOrderEvent($order));

        return response()->json($order, 201);
    }

    public function show($id): JsonResponse
    {
        $order = TravelOrder::with('user')->findOrFail($id);

        if (auth()->user()->role == 'client' && $order->user_id != auth()->user()->id) {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        return response()->json($order);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $order = TravelOrder::findOrFail($id);

        $user  = auth()->user();

        if ($user->role != 'admin') {
            return response()->json(['error' => 'Acesso negado'], 403);
        }

        $request->validate([
            'status' => 'required|in:aprovado,cancelado'
        ]);

        // Caso o pedido já esteja aprovado, você pode inserir lógica para permitir ou não o cancelamento.
        $order->status = $request->status;
        $order->save();

        // Notifica o cliente sobre a mudança
        $client = $order->user;

        Mail::to($client->email)->send(new OrderStatusNotification($order));

        event(new OrderStatusChangedEvent($order));

        return response()->json($order);
    }
}
