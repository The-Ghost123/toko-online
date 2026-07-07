<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'total_items',
        'total_price',
    ];

    protected $casts = [
        'total_items' => 'integer',
        'total_price' => 'integer',
    ];

    /**
     * Get the user that owns the cart
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items in the cart
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Calculate total items in cart
     */
    public function calculateTotalItems(): int
    {
        return $this->items()->sum('quantity');
    }

    /**
     * Calculate total price in cart
     */
    public function calculateTotalPrice(): int
    {
        return $this->items()
            ->with('product')
            ->get()
            ->sum(function ($item) {
                return $item->product->harga * $item->quantity;
            });
    }

    /**
     * Update cart totals
     */
    public function updateTotals(): void
    {
        $this->update([
            'total_items' => $this->calculateTotalItems(),
            'total_price' => $this->calculateTotalPrice(),
        ]);
    }
}
