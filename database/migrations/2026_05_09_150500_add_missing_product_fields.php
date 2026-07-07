<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'nama_produk')) {
                $table->string('nama_produk', 150)->after('category_id');
            }

            if (!Schema::hasColumn('products', 'slug')) {
                $table->string('slug', 170)->unique()->after('nama_produk');
            }

            if (!Schema::hasColumn('products', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('slug');
            }

            if (!Schema::hasColumn('products', 'harga')) {
                $table->decimal('harga', 12, 0)->after('deskripsi');
            }

            if (!Schema::hasColumn('products', 'foto_produk')) {
                $table->string('foto_produk')->nullable()->after('harga');
            }

            if (!Schema::hasColumn('products', 'ketersediaan_stok')) {
                $table->enum('ketersediaan_stok', ['tersedia', 'habis'])
                      ->default('tersedia')
                      ->after('foto_produk');
            }

            if (!Schema::hasColumn('products', 'nomor_whatsapp')) {
                $table->string('nomor_whatsapp', 20)->after('ketersediaan_stok');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'nomor_whatsapp')) {
                $table->dropColumn('nomor_whatsapp');
            }

            if (Schema::hasColumn('products', 'ketersediaan_stok')) {
                $table->dropColumn('ketersediaan_stok');
            }

            if (Schema::hasColumn('products', 'foto_produk')) {
                $table->dropColumn('foto_produk');
            }

            if (Schema::hasColumn('products', 'harga')) {
                $table->dropColumn('harga');
            }

            if (Schema::hasColumn('products', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }

            if (Schema::hasColumn('products', 'slug')) {
                $table->dropUnique('products_slug_unique');
                $table->dropColumn('slug');
            }

            if (Schema::hasColumn('products', 'nama_produk')) {
                $table->dropColumn('nama_produk');
            }
        });
    }
};
