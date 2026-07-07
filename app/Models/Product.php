<?php

namespace App\Models;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'nama_produk',
        'slug',
        'deskripsi',
        'harga',
        'foto_produk',
        'ketersediaan_stok',
        'nomor_whatsapp',
    ];

    protected $casts = [
        'ketersediaan_stok' => 'string',
        'harga'             => 'integer',
    ];

    // Auto-generate slug
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->nama_produk) . '-' . Str::random(5);
        });
    }

    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute(): ?float
    {
        if ($this->relationLoaded('reviews')) {
            return $this->reviews->avg('rating') ? round($this->reviews->avg('rating'), 1) : null;
        }

        return null;
    }

    public function getReviewCountAttribute(): int
    {
        if ($this->relationLoaded('reviews')) {
            return $this->reviews->count();
        }

        return $this->reviews()->count();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getFotoUrlAttribute(): string
    {
        if (! $this->foto_produk) {
            return 'https://via.placeholder.com/900x600?text=No+Image';
        }

        return Storage::disk('public')->exists($this->foto_produk)
            ? Storage::disk('public')->url($this->foto_produk)
            : 'https://via.placeholder.com/900x600?text=No+Image';
    }
}