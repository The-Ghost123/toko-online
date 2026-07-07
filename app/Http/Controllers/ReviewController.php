<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order, OrderItem $orderItem)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($orderItem->order_id !== $order->id) {
            abort(404);
        }

        if (! in_array($order->status, [Order::STATUS_COMPLETED, Order::STATUS_REFUNDED], true)) {
            return back()->with('error', 'Review hanya dapat ditambahkan setelah pesanan selesai atau refund.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $photoPath = null;
        $existingReview = Review::where('order_item_id', $orderItem->id)->first();

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('review_photos', 'public');
            if ($existingReview && $existingReview->photo) {
                Storage::disk('public')->delete($existingReview->photo);
            }
        }

        $reviewData = [
            'user_id' => Auth::id(),
            'product_id' => $orderItem->product_id,
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ];

        if ($photoPath) {
            $reviewData['photo'] = $photoPath;
        }

        Review::updateOrCreate(
            ['order_item_id' => $orderItem->id],
            $reviewData
        );

        return Redirect::back()->with('success', 'Review produk berhasil disimpan.');
    }
}
