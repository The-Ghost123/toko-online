<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderOwnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return view('owner.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items']);
        return view('owner.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        if (in_array($order->status, [Order::STATUS_COMPLETED, Order::STATUS_REFUNDED, Order::STATUS_CANCELLED], true)) {
            $order->delete();
            return redirect()->route('owner.orders.index')->with('success', 'Pesanan berhasil dihapus.');
        }

        return back()->with('error', 'Pesanan hanya bisa dihapus jika statusnya sudah selesai, direfund, atau dibatalkan.');
    }
}
