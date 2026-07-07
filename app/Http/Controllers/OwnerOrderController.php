<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OwnerOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return view('owner.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('owner.orders.show', compact('order'));
    }

    public function destroy(Order $order)
    {
        if (! in_array($order->status, [Order::STATUS_COMPLETED, Order::STATUS_REFUNDED, Order::STATUS_CANCELLED], true)) {
            return Redirect::back()->with('error', 'Hanya pesanan dengan status Completed, Refunded, atau Cancelled dapat dihapus.');
        }

        $order->delete();

        return Redirect::route('owner.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}
