# Cart System - Integration Guide

## 📌 Quick Start

### 1. Add "Add to Cart" Button to Product Page

Already implemented in `resources/views/public/product-detail.blade.php`:

```blade
<button 
    type="button" 
    onclick="addProductToCart({{ $product->id }})"
    class="btn btn-primary rounded-pill px-4 py-2"
>
    <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
</button>

<script>
    async function addProductToCart(productId) {
        const quantity = parseInt(document.getElementById('quantity').value);
        await cart.addToCart(productId, quantity);
    }
</script>
```

### 2. Cart Icon with Badge in Navigation

Add to your `resources/views/layouts/app.blade.php` (or your navbar component):

```blade
<nav class="navbar navbar-expand-lg navbar-light">
    <!-- ... navbar content ... -->
    
    @auth
    <div class="nav-item ms-3">
        <a href="{{ route('cart') }}" class="position-relative">
            <i class="bi bi-cart3" style="font-size: 1.5rem;"></i>
            <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
                0
            </span>
        </a>
    </div>
    @endauth
</nav>

<script>
    // Initialize cart badge
    document.addEventListener('DOMContentLoaded', function() {
        cart.onChange(function(cartData) {
            const badge = document.getElementById('cart-badge');
            const count = cartData.total_items || 0;
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        });
        cart.getCart().catch(() => {});
    });
</script>
```

### 3. Quick Add to Cart in Product Listing

Add to `resources/views/public/products.blade.php`:

```blade
@foreach($products as $product)
    <div class="card product-card">
        <img src="{{ $product->foto_url }}" class="card-img-top">
        <div class="card-body">
            <h5 class="card-title">{{ $product->nama_produk }}</h5>
            <p class="card-text text-muted">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
            
            <div class="d-flex gap-2">
                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary flex-grow-1">
                    View
                </a>
                <button type="button" class="btn btn-sm btn-primary" onclick="quickAddToCart({{ $product->id }})">
                    <i class="bi bi-cart-plus"></i>
                </button>
            </div>
        </div>
    </div>
@endforeach

<script>
    function quickAddToCart(productId) {
        cart.addToCart(productId, 1);
    }
</script>
```

### 4. View Cart Page

Already implemented at: `resources/views/public/cart.blade.php`

Access via: `/cart` route

## 🔌 API Usage Examples

### JavaScript Fetch Examples

```javascript
// Get current cart
const cartData = await cart.getCart();
console.log(cartData.items);
console.log(cartData.total_price);

// Add item
await cart.addToCart(productId, quantity);

// Update quantity
await cart.updateItem(itemId, newQuantity);

// Remove item
await cart.removeItem(itemId);

// Clear all
await cart.clearCart();

// Get formatted price
const formatted = cart.formatCurrency(100000); // "Rp 100.000"

// Listen to changes
cart.onChange(function(cartData) {
    console.log('Cart updated!', cartData);
});
```

### cURL Examples (for testing)

```bash
# Get CSRF token first
CSRF=$(curl -s http://localhost:8000/login | grep csrf-token | sed -r 's/.*content="([^"]*).*/\1/')

# Get cart
curl -X GET http://localhost:8000/api/cart \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Add to cart
curl -X POST http://localhost:8000/api/cart/add \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: $CSRF" \
  -d '{"product_id": 5, "quantity": 2}'

# Update item
curl -X PUT http://localhost:8000/api/cart/item/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: $CSRF" \
  -d '{"quantity": 3}'

# Remove item
curl -X DELETE http://localhost:8000/api/cart/item/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "X-CSRF-TOKEN: $CSRF"

# Clear cart
curl -X DELETE http://localhost:8000/api/cart/clear \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "X-CSRF-TOKEN: $CSRF"
```

### PHP Blade Usage (on Backend)

```blade
@auth
    @php
        $cart = auth()->user()->cart;
        $items = $cart ? $cart->items : [];
        $totalPrice = $cart ? $cart->total_price : 0;
    @endphp

    <div class="cart-summary">
        @if($items->count() > 0)
            <p>You have {{ $items->count() }} items in cart</p>
            <p>Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
        @else
            <p>Your cart is empty</p>
        @endif
    </div>
@endauth
```

## 📊 Admin Dashboard Integration

### Add Routes

Add to `routes/web.php`:

```php
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // ... existing routes ...
    
    Route::get('/carts/abandoned', [CartAnalyticsController::class, 'abandonedCarts'])
        ->name('carts.abandoned');
    Route::get('/carts/insights', [CartAnalyticsController::class, 'cartInsights'])
        ->name('carts.insights');
    Route::get('/carts/{cart}', [CartAnalyticsController::class, 'showAbandonedCart'])
        ->name('carts.show');
});
```

### Add Navigation Menu Item

Add to your admin layout navigation:

```blade
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.carts.abandoned') }}">
        <i class="bi bi-cart-x"></i> Abandoned Carts
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.carts.insights') }}">
        <i class="bi bi-graph-up"></i> Cart Analytics
    </a>
</li>
```

## 🎯 Advanced Usage

### Custom Cart Manager Instance

```javascript
// Create a custom instance if needed
class CustomCart extends CartManager {
    constructor() {
        super();
        this.apiBaseUrl = '/api/v1'; // Custom API base
    }
    
    // Override methods as needed
    showNotification(message, type = 'info') {
        // Use your custom notification system
        console.log(`[${type}] ${message}`);
    }
}

// Use it
const myCart = new CustomCart();
await myCart.addToCart(5, 2);
```

### Track Cart Events

```javascript
// Listen for cart changes
cart.onChange(function(cartData) {
    // Send analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'cart_update', {
            value: cartData.total_price,
            currency: 'IDR',
            items: cartData.total_items,
        });
    }
});

// On add to cart
const addButton = document.querySelector('[onclick*="addProductToCart"]');
if (addButton) {
    const originalOnclick = addButton.onclick;
    addButton.onclick = async function() {
        console.log('User adding item to cart');
        await originalOnclick.call(this);
    };
}
```

### Persistent Cart State

```javascript
// Save cart state to localStorage
cart.onChange(function(cartData) {
    localStorage.setItem('cart-state', JSON.stringify(cartData));
});

// Restore cart state on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedState = localStorage.getItem('cart-state');
    if (savedState) {
        const state = JSON.parse(savedState);
        console.log('Restoring saved cart:', state);
    }
    cart.getCart();
});
```

## 🛒 Checkout Page Integration (Future)

```blade
@extends('layouts.app')

@section('content')
<div class="checkout-container">
    <div class="row">
        <div class="col-md-8">
            <!-- Shipping & Payment Form -->
        </div>
        <div class="col-md-4">
            <div class="order-summary">
                <h3>Order Summary</h3>
                <div id="checkout-items"></div>
                <div class="total">
                    <p>Total: <strong id="checkout-total">Rp 0</strong></p>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Complete Payment
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    cart.getCart().then(function(cartData) {
        const container = document.getElementById('checkout-items');
        container.innerHTML = cartData.items.map(item => `
            <div class="item">
                <span>${item.product.nama_produk}</span>
                <span>${item.quantity}x</span>
                <span>${cart.formatCurrency(item.subtotal)}</span>
            </div>
        `).join('');
        
        document.getElementById('checkout-total').textContent = 
            cart.formatCurrency(cartData.total_price);
    });
</script>
@endsection
```

## 🔐 Security Best Practices

### 1. Validate Quantity on Backend
```php
// In CartController
$request->validate([
    'quantity' => 'required|integer|min:1|max:100', // Add max
]);
```

### 2. Prevent Price Tampering
```php
// Price is captured at add time, not from request
$cartItem->update([
    'quantity' => $request->quantity,
    // price is NOT updated from request
]);
```

### 3. Rate Limiting
```php
// In routes/api.php
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::post('/cart/add', [CartController::class, 'addToCart']);
});
```

### 4. CSRF Protection
```blade
<!-- Already handled by framework, but ensure in form -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## 🧪 Testing the System

### Manual Testing Checklist

- [ ] Add product to cart from detail page
- [ ] Cart count updates in navbar
- [ ] Navigate to /cart to view items
- [ ] Update quantity with input field
- [ ] Update quantity with +/- buttons
- [ ] Remove single item
- [ ] Clear entire cart
- [ ] Add same product twice (should update quantity)
- [ ] Refresh page - cart persists
- [ ] Logout and login - cart persists
- [ ] Admin can view abandoned carts

### Automated Testing Example

```php
// tests/Feature/CartTest.php
class CartTest extends TestCase
{
    public function test_add_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['harga' => 50000]);
        
        $response = $this->actingAs($user)
            ->postJson('/api/cart/add', [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);
        
        $response->assertStatus(200)
            ->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }
}
```

## 📈 Performance Optimization

### 1. Caching Cart Data
```php
// Cache cart for 5 minutes
$cart = cache()->remember(
    'cart_' . auth()->id(),
    300,
    function () {
        return auth()->user()->cart()->with('items.product')->first();
    }
);
```

### 2. Database Query Optimization
```php
// Use eager loading
$cart = Cart::with([
    'items' => function ($query) {
        $query->with('product:id,nama_produk,harga,foto_produk');
    }
])->find($cartId);
```

### 3. Frontend Debouncing
```javascript
// Debounce quantity updates
let updateTimeout;
function updateQuantityDebounced(itemId, quantity) {
    clearTimeout(updateTimeout);
    updateTimeout = setTimeout(() => {
        cart.updateItem(itemId, quantity);
    }, 500);
}
```

## 🐛 Troubleshooting

### Cart not persisting after refresh
- Check CSRF token is in meta tag
- Verify authentication is working
- Check browser DevTools Network tab for API errors

### API returns 401 Unauthorized
- Ensure user is authenticated
- Check Sanctum token is valid
- Verify auth middleware in routes

### Quantity not updating
- Check JavaScript console for errors
- Verify API response format
- Check database transaction issues

### Abandoned carts not showing
- Run migrations: `php artisan migrate`
- Check carts have `total_items > 0`
- Check `updated_at` is older than 7 days

## 📝 Database Queries Reference

### Get all items in user's cart
```sql
SELECT * FROM cart_items 
WHERE cart_id = (SELECT id FROM carts WHERE user_id = ?)
```

### Get abandoned carts value by category
```sql
SELECT p.category_id, SUM(ci.price * ci.quantity) as total
FROM cart_items ci
JOIN carts c ON ci.cart_id = c.id
JOIN products p ON ci.product_id = p.id
WHERE c.total_items > 0 AND c.updated_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY p.category_id
```

### Top customers who abandon carts
```sql
SELECT c.user_id, u.name, COUNT(*) as count, SUM(c.total_price) as value
FROM carts c
JOIN users u ON c.user_id = u.id
WHERE c.total_items > 0 AND c.updated_at < DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY c.user_id
ORDER BY value DESC
LIMIT 10
```

## 🚀 Future Enhancements

- [ ] Abandoned cart email reminders
- [ ] Cart recovery campaigns
- [ ] One-click reorder from cart
- [ ] Gift card support
- [ ] Coupon/discount codes
- [ ] Wishlist to cart transfer
- [ ] Cart sharing via link
- [ ] Save cart for later
- [ ] Integration with payment gateway
- [ ] Order history
