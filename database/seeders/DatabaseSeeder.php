<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);

        // Seed kategori
        \App\Models\Category::create([
            'nama_kategori' => 'Baju Perempuan',
            'slug' => 'baju-perempuan',
            'deskripsi' => 'Koleksi baju perempuan terkini dengan desain modern dan elegan',
        ]);

        \App\Models\Category::create([
            'nama_kategori' => 'Baju Laki-laki',
            'slug' => 'baju-laki-laki',
            'deskripsi' => 'Koleksi baju laki-laki berkualitas dengan berbagai pilihan warna',
        ]);

        \App\Models\Category::create([
            'nama_kategori' => 'Aksesori',
            'slug' => 'aksesori',
            'deskripsi' => 'Aksesori fashion lengkap untuk melengkapi penampilan Anda',
        ]);

        // Seed produk
        $kategori1 = \App\Models\Category::where('slug', 'baju-perempuan')->first();
        $kategori2 = \App\Models\Category::where('slug', 'baju-laki-laki')->first();

        \App\Models\Product::create([
            'category_id' => $kategori1->id,
            'nama_produk' => 'Kemeja Kain Premium',
            'slug' => 'kemeja-kain-premium-' . uniqid(),
            'deskripsi' => 'Kemeja berkualitas tinggi dari bahan kain premium yang nyaman dipakai',
            'harga' => 150000,
            'foto_produk' => null,
            'ketersediaan_stok' => 'tersedia',
            'nomor_whatsapp' => '6283801403812',
        ]);

        \App\Models\Product::create([
            'category_id' => $kategori1->id,
            'nama_produk' => 'Dress Kasual Cantik',
            'slug' => 'dress-kasual-cantik-' . uniqid(),
            'deskripsi' => 'Dress kasual dengan desain yang elegan cocok untuk acara santai atau resmi',
            'harga' => 200000,
            'foto_produk' => null,
            'ketersediaan_stok' => 'tersedia',
            'nomor_whatsapp' => '6283801403812',
        ]);

        \App\Models\Product::create([
            'category_id' => $kategori1->id,
            'nama_produk' => 'Rok Panjang Motif',
            'slug' => 'rok-panjang-motif-' . uniqid(),
            'deskripsi' => 'Rok panjang dengan motif menarik yang membuat penampilan lebih stylish',
            'harga' => 120000,
            'foto_produk' => null,
            'ketersediaan_stok' => 'tersedia',
            'nomor_whatsapp' => '6283801403812',
        ]);

        \App\Models\Product::create([
            'category_id' => $kategori2->id,
            'nama_produk' => 'Kaos Polos Hitam',
            'slug' => 'kaos-polos-hitam-' . uniqid(),
            'deskripsi' => 'Kaos polos dengan bahan katun berkualitas yang nyaman dan tahan lama',
            'harga' => 75000,
            'foto_produk' => null,
            'ketersediaan_stok' => 'tersedia',
            'nomor_whatsapp' => '6283801403812',
        ]);

        \App\Models\Product::create([
            'category_id' => $kategori2->id,
            'nama_produk' => 'Celana Chino Abu-abu',
            'slug' => 'celana-chino-abu-abu-' . uniqid(),
            'deskripsi' => 'Celana chino dengan warna abu-abu netral cocok untuk berbagai kesempatan',
            'harga' => 180000,
            'foto_produk' => null,
            'ketersediaan_stok' => 'tersedia',
            'nomor_whatsapp' => '6283801403812',
        ]);

        \App\Models\Product::create([
            'category_id' => $kategori2->id,
            'nama_produk' => 'Kemeja Flanel Biru',
            'slug' => 'kemeja-flanel-biru-' . uniqid(),
            'deskripsi' => 'Kemeja flanel dengan warna biru yang cocok untuk gaya casual atau semi-formal',
            'harga' => 140000,
            'foto_produk' => null,
            'ketersediaan_stok' => 'tersedia',
            'nomor_whatsapp' => '6283801403812',
        ]);
    }
}
