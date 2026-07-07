# Sistem Keranjang Belanja - Dokumentasi

## 📋 Daftar Isi

1. [Fitur Utama](#fitur-utama)
2. [Arsitektur](#arsitektur)
3. [Struktur Database](#struktur-database)
4. [API Endpoints](#api-endpoints)
5. [Penggunaan Frontend](#penggunaan-frontend)
6. [Admin - Abandoned Cart Analytics](#admin---abandoned-cart-analytics)
7. [Contoh Implementasi](#contoh-implementasi)

## ✨ Fitur Utama

- ✅ Tambah produk ke keranjang tanpa reload halaman (AJAX)
- ✅ Update jumlah (quantity) produk
- ✅ Hapus item dari keranjang
- ✅ Simpan data keranjang di database MySQL
- ✅ Tracking abandoned carts untuk analisis
- ✅ Notifikasi real-time
- ✅ Format mata uang Indonesia (IDR)

## 🏗️ Arsitektur

### Backend (Laravel)

```
app/Models/
├── Cart.php           # Model keranjang
├── CartItem.php       # Model item keranjang
├── Product.php        # Model produk (updated dengan relasi)
└── User.php          # Model user (updated dengan relasi)

app/Http/Controllers/
└── CartController.php # Controller untuk semua operasi keranjang

database/migrations/
├── 2026_05_19_000001_create_carts_table.php
└── 2026_05_19_000002_create_cart_items_table.php

routes/
├── api.php           # API routes untuk cart
└── web.php           # Web routes
```

### Frontend (JavaScript)

```
resources/
├── js/
│   ├── app.js        # Main app (imports cart.js)
│   └── cart.js       # Cart manager class
└── views/
    └── public/
        ├── cart.blade.php              # Halaman keranjang
        └── product-detail.blade.php    # Detail produk (updated)
```

## 💾 Struktur Database

### Tabel: `carts`
```sql
- id (PRIMARY KEY)
- user_id (FOREIGN KEY → users.id)
- total_items (INTEGER) - Total jumlah item
- total_price (BIGINT) - Total harga dalam Rupiah
- created_at
- updated_at
```

### Tabel: `cart_items`
```sql
- id (PRIMARY KEY)
- cart_id (FOREIGN KEY → carts.id)
- product_id (FOREIGN KEY → products.id)
- quantity (INTEGER) - Jumlah produk
- price (BIGINT) - Harga saat ditambahkan
- created_at
- updated_at
```

## 🔌 API Endpoints

Semua endpoints memerlukan autentikasi via Sanctum (`auth:sanctum`)

### 1. Dapatkan Keranjang Saat Ini
```
GET /api/cart
```

**Response:**
```json
{
  "success": true,
  "cart": { ... },
  "items": [
    {
      "id": 1,
      "product_id": 5,
      "quantity": 2,
      "price": 50000,
      "subtotal": 100000,
      "product": {
        "id": 5,
        "nama_produk": "Laptop",
        "slug": "laptop-abc123",
        "harga": 50000,
        "foto_produk": "products/..."
      }
    }
  ],
  "total_items": 2,
  "total_price": 100000
}
```

### 2. Tambah ke Keranjang
```
POST /api/cart/add
```

**Request Body:**
```json
{
  "product_id": 5,
  "quantity": 2
}
```

**Response:**
```json
{
  "success": true,
  "message": "Produk berhasil ditambahkan ke keranjang",
  "cart": {
    "total_items": 2,
    "total_price": 100000
  }
}
```

### 3. Update Jumlah Item
```
PUT /api/cart/item/{cartItemId}
```

**Request Body:**
```json
{
  "quantity": 5
}
```

**Response:**
```json
{
  "success": true,
  "message": "Jumlah produk berhasil diperbarui",
  "item": {
    "id": 1,
    "quantity": 5,
    "subtotal": 250000
  },
  "cart": {
    "total_items": 5,
    "total_price": 250000
  }
}
```

### 4. Hapus Item dari Keranjang
```
DELETE /api/cart/item/{cartItemId}
```

**Response:**
```json
{
  "success": true,
  "message": "Produk berhasil dihapus dari keranjang",
  "cart": {
    "total_items": 0,
    "total_price": 0
  }
}
```

### 5. Kosongkan Seluruh Keranjang
```
DELETE /api/cart/clear
```

**Response:**
```json
{
  "success": true,
  "message": "Keranjang berhasil dikosongkan"
}
```

### 6. Dapatkan Abandoned Carts (Admin Only)
```
GET /api/cart/abandoned
```

**Response:**
```json
{
  "success": true,
  "carts": [
    {
      "id": 1,
      "user_id": 3,
      "total_items": 5,
      "total_price": 500000,
      "updated_at": "2026-05-12T10:30:00",
      "user": {
        "id": 3,
        "name": "John Doe",
        "email": "john@example.com"
      },
      "items": [...]
    }
  ],
  "total_abandoned": 1
}
```

## 🎨 Penggunaan Frontend

### Inisialisasi Cart Manager

Cart manager sudah tersedia secara global di `window.cart` karena diimport di `app.js`.

```javascript
// Cart manager sudah siap digunakan
const cartData = await cart.getCart();
```

### Method-method Utama

#### 1. Tambah ke Keranjang
```javascript
async addToCart(productId, quantity = 1)
```

**Contoh:**
```javascript
await cart.addToCart(5, 2); // Tambah produk ID 5, qty 2
```

#### 2. Update Quantity
```javascript
async updateItem(cartItemId, quantity)
```

**Contoh:**
```javascript
await cart.updateItem(1, 5); // Update item ID 1, qty 5
```

#### 3. Hapus Item
```javascript
async removeItem(cartItemId)
```

**Contoh:**
```javascript
await cart.removeItem(1); // Hapus item ID 1
```

#### 4. Kosongkan Keranjang
```javascript
async clearCart()
```

**Contoh:**
```javascript
await cart.clearCart(); // Kosongkan seluruh keranjang
```

#### 5. Dapatkan Keranjang
```javascript
async getCart()
```

**Contoh:**
```javascript
const cartData = await cart.getCart();
console.log(cartData.total_items);  // Jumlah item
console.log(cartData.total_price);  // Total harga
console.log(cartData.items);        // Array item
```

#### 6. Dapatkan Total Items
```javascript
getTotalItems()
```

**Contoh:**
```javascript
const totalItems = cart.getTotalItems(); // Mengembalikan integer
```

#### 7. Dapatkan Total Price
```javascript
getTotalPrice()
```

**Contoh:**
```javascript
const totalPrice = cart.getTotalPrice(); // Mengembalikan integer
```

#### 8. Format Mata Uang
```javascript
formatCurrency(amount)
```

**Contoh:**
```javascript
cart.formatCurrency(100000); // "Rp 100.000"
```

#### 9. Listen untuk Perubahan Keranjang
```javascript
onChange(callback)
```

**Contoh:**
```javascript
cart.onChange(function(cartData) {
  console.log('Keranjang diperbarui:', cartData);
  console.log('Total items:', cartData.total_items);
});
```

## 👨‍💼 Admin - Abandoned Cart Analytics

### Dapatkan Abandoned Carts

```javascript
async getAbandonedCarts() // 7 hari tidak diupdate
```

### Implementasi di Admin Dashboard

Buat controller baru untuk admin dashboard:

```php
// app/Http/Controllers/Admin/AbandonedCartController.php
public function index()
{
    $abandonedCarts = \App\Models\Cart::with(['user', 'items.product'])
        ->where('total_items', '>', 0)
        ->whereDate('updated_at', '<', now()->subDays(7))
        ->orderBy('updated_at', 'asc')
        ->get();

    return view('admin.abandoned-carts', [
        'carts' => $abandonedCarts,
        'total' => $abandonedCarts->count(),
        'totalValue' => $abandonedCarts->sum('total_price'),
    ]);
}
```

### Blade Template untuk Admin

```blade
@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Abandoned Carts Analysis</h2>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6>Total Abandoned</h6>
                    <h3>{{ $total }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6>Total Value</h6>
                    <h3>Rp {{ number_format($totalValue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Items</th>
                        <th>Total Value</th>
                        <th>Last Updated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carts as $cart)
                    <tr>
                        <td>{{ $cart->user->name }}</td>
                        <td>{{ $cart->user->email }}</td>
                        <td>{{ $cart->total_items }}</td>
                        <td>Rp {{ number_format($cart->total_price, 0, ',', '.') }}</td>
                        <td>{{ $cart->updated_at->diffForHumans() }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
```

## 💡 Contoh Implementasi

### 1. Tombol Add to Cart di Halaman Produk

**Sudah ada di:** `resources/views/public/product-detail.blade.php`

```html
<button 
    type="button" 
    onclick="addProductToCart({{ $product->id }})"
    class="btn btn-primary rounded-pill px-4 py-2 w-100 mb-2"
>
    <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
</button>
```

### 2. Halaman Keranjang Lengkap

**Sudah ada di:** `resources/views/public/cart.blade.php`

Menampilkan:
- Daftar item di keranjang
- Control quantity (−, input, +)
- Tombol hapus per item
- Ringkasan total
- Tombol checkout dan kosongkan keranjang

### 3. Integrasi dengan Navigation Bar

Tambahkan di header/navbar untuk menampilkan total items:

```blade
@auth
<div class="cart-icon">
    <a href="{{ route('cart') }}" class="btn btn-outline-primary">
        <i class="bi bi-cart"></i>
        <span id="cart-count" class="badge bg-danger">0</span>
    </a>
</div>
<script>
    // Update cart count setiap kali ada perubahan
    cart.onChange(function(cartData) {
        document.getElementById('cart-count').textContent = cartData.total_items;
    });
    
    // Load cart saat page load
    cart.getCart().catch(() => {});
</script>
@endauth
```

### 4. Quick Add to Cart di Product Listing

```blade
<div class="product-card">
    <h5>{{ $product->nama_produk }}</h5>
    <p>Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
    
    <button 
        type="button"
        onclick="quickAddToCart({{ $product->id }})"
        class="btn btn-sm btn-primary"
    >
        <i class="bi bi-cart-plus"></i> Add to Cart
    </button>
</div>

<script>
    function quickAddToCart(productId) {
        cart.addToCart(productId, 1);
    }
</script>
```

## 🚀 Setup Langkah-langkah

1. **Jalankan Migrations**
   ```bash
   php artisan migrate
   ```

2. **Pastikan CSRF Token di Layout**
   ```blade
   <meta name="csrf-token" content="{{ csrf_token() }}">
   ```

3. **Build Frontend Assets**
   ```bash
   npm run build
   ```

4. **Clear Cache**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

5. **Test API dengan Postman/Insomnia**
   - Get Cart: `GET /api/cart`
   - Add to Cart: `POST /api/cart/add`
   - Update Item: `PUT /api/cart/item/1`
   - Remove Item: `DELETE /api/cart/item/1`

## 🔒 Security Considerations

- ✅ Auth middleware pada semua endpoints
- ✅ User verification pada operasi cart
- ✅ Price capture saat add to cart (mencegah price manipulation)
- ✅ Unique constraint cart per user
- ✅ Cascade delete untuk integritas data

## 📊 Database Queries

### Query untuk Abandoned Carts dengan Total Value

```php
$abandonedCarts = Cart::with(['user', 'items.product'])
    ->where('total_items', '>', 0)
    ->whereDate('updated_at', '<', now()->subDays(7))
    ->orderBy('total_price', 'desc')
    ->get();

foreach ($abandonedCarts as $cart) {
    echo "{$cart->user->name}: Rp " . number_format($cart->total_price) . "\n";
}
```

### Query untuk Total Abandoned Value

```php
$totalValue = Cart::where('total_items', '>', 0)
    ->whereDate('updated_at', '<', now()->subDays(7))
    ->sum('total_price');
```

### Query untuk Top Abandoned Products

```php
$topProducts = \App\Models\CartItem::whereHas('cart', function ($query) {
    $query->where('total_items', '>', 0)
        ->whereDate('updated_at', '<', now()->subDays(7));
})
->selectRaw('product_id, COUNT(*) as abandoned_count, SUM(quantity) as total_qty')
->groupBy('product_id')
->orderByDesc('abandoned_count')
->with('product')
->get();
```

## ✅ Testing Checklist

- [ ] Add item to cart dari halaman produk
- [ ] Update quantity dari halaman keranjang
- [ ] Remove item dari keranjang
- [ ] Clear seluruh keranjang
- [ ] Perubahan otomatis di cart count (navbar)
- [ ] Data tersimpan di database
- [ ] Page tidak reload saat operasi cart
- [ ] Admin bisa melihat abandoned carts
- [ ] Format mata uang benar (Rp)

## 📝 Catatan

- Harga disimpan saat item ditambahkan untuk historical accuracy
- Total items dan total price selalu ter-update otomatis
- Abandoned cart ditentukan: total_items > 0 AND updated_at < 7 hari
- Cart dibuat otomatis saat user pertama kali add to cart
- Semua komunikasi via AJAX (no page reload)
