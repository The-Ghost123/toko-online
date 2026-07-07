<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['nama_kategori', 'slug', 'deskripsi', 'foto_kategori'];

    // Auto-generate slug 
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->nama_kategori);
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getFotoUrlAttribute(): string
    {
        return $this->foto_kategori
            ? asset('storage/' . $this->foto_kategori)
            : 'https://via.placeholder.com/900x600?text=No+Image';
    }
}