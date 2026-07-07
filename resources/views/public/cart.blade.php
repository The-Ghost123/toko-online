@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Keranjang Belanja</h1>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-5 border-b border-gray-200 flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Pilih Produk untuk Checkout</h2>
                            <p class="text-sm text-gray-500">Centang item yang ingin Anda sertakan dalam pembayaran.</p>
                        </div>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                            <input id="cart-select-all-checkbox" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span>Pilih semua</span>
                        </label>
                    </div>
                    <div id="cart-items-container">
                        <div class="p-6 text-center text-gray-500">
                            <p>Memuat keranjang belanja...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                    <div class="space-y-3 mb-6 pb-6 border-b">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Item:</span>
                            <span id="cart-total-items" class="font-semibold">0</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total Harga:</span>
                            <span id="cart-total-price">Rp 0</span>
                        </div>
                    </div>

                    <div class="space-y-4 mb-4">
                        <p id="cart-selected-info" class="text-sm text-gray-600">Memilih produk untuk checkout...</p>
                    </div>
                    <div class="space-y-2">
                        <button
                            id="checkout-button"
                            type="button"
                            onclick="goToCheckout()"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition"
                        >
                            Checkout
                        </button>
                        <button
                            type="button"
                            onclick="clearCart()"
                            class="w-full bg-red-100 text-red-600 py-2 rounded-lg font-semibold hover:bg-red-200 transition"
                        >
                            Kosongkan Keranjang
                        </button>
                        <a
                            href="{{ route('products') }}"
                            class="w-full block text-center bg-gray-100 text-gray-600 py-2 rounded-lg font-semibold hover:bg-gray-200 transition"
                        >
                            Lanjut Belanja
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize cart on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();

        // Listen for cart changes
        cart.onChange(function(cartData) {
            updateCartUI(cartData);
        });
    });

    let currentCartData = null;
    let selectedItemIds = [];
    let selectedInitialized = false;

    async function loadCart() {
        try {
            const data = await cart.getCart();
            if (!selectedInitialized && selectedItemIds.length === 0 && data.items && data.items.length) {
                selectedItemIds = data.items.map(item => item.id);
                selectedInitialized = true;
            }
            updateCartUI(data);
        } catch (error) {
            console.error('Error loading cart:', error);
        }
    }

    function updateCartUI(data) {
        currentCartData = data;
        const container = document.getElementById('cart-items-container');
        const totalItems = document.getElementById('cart-total-items');
        const totalPrice = document.getElementById('cart-total-price');
        const checkoutButton = document.getElementById('checkout-button');
        const selectedInfo = document.getElementById('cart-selected-info');
        const selectAllCheckbox = document.getElementById('cart-select-all-checkbox');

        const items = data.items || [];

        selectedItemIds = selectedItemIds.filter(id => items.some(item => item.id === id));

        const selectedItems = items.filter(item => selectedItemIds.includes(item.id));
        const selectedTotals = selectedItems.reduce((totals, item) => {
            totals.quantity += item.quantity;
            totals.price += item.subtotal;
            return totals;
        }, { quantity: 0, price: 0 });

        totalItems.textContent = selectedTotals.quantity;
        totalPrice.textContent = cart.formatCurrency(selectedTotals.price || 0);

        if (!items.length) {
            container.innerHTML = `
                <div class="p-6 text-center text-gray-500">
                    <p class="text-lg mb-4">Keranjang belanja Anda kosong</p>
                    <a href="{{ route('products') }}" class="text-blue-600 hover:text-blue-700">
                        Lanjut Belanja →
                    </a>
                </div>
            `;
            if (selectedInfo) selectedInfo.textContent = '';
            if (selectAllCheckbox) selectAllCheckbox.checked = false;
            if (checkoutButton) checkoutButton.disabled = true;
            return;
        }

        container.innerHTML = items.map(item => {
            const checked = selectedItemIds.includes(item.id) ? 'checked' : '';
            return `
                <div class="flex items-center gap-4 p-6 border-b hover:bg-gray-50 transition">
                    <div class="flex items-center">
                        <input type="checkbox" id="cart-item-${item.id}-checkbox" data-item-id="${item.id}" class="cart-item-select-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" ${checked}>
                    </div>
                    <div class="flex-shrink-0 w-20 h-20">
                        <img
                            src="${item.product.foto_produk ? '/storage/' + item.product.foto_produk : 'https://via.placeholder.com/200x200?text=No+Image'}"
                            alt="${item.product.nama_produk}"
                            class="w-full h-full object-cover rounded"
                        />
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <a href="/products/${item.product.slug}" class="hover:text-blue-600">
                                ${item.product.nama_produk}
                            </a>
                        </h3>
                        <p class="text-gray-600">
                            ${cart.formatCurrency(item.product.harga)}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            onclick="updateQuantity(${item.id}, ${item.quantity - 1})"
                            class="px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition"
                        >
                            −
                        </button>
                        <input
                            type="number"
                            value="${item.quantity}"
                            onchange="updateQuantity(${item.id}, this.value)"
                            min="1"
                            class="w-12 text-center border rounded px-2 py-1"
                        />
                        <button
                            type="button"
                            onclick="updateQuantity(${item.id}, ${item.quantity + 1})"
                            class="px-2 py-1 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition"
                        >
                            +
                        </button>
                    </div>

                    <div class="text-right min-w-fit">
                        <p class="font-semibold text-lg">
                            ${cart.formatCurrency(item.subtotal)}
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="removeItem(${item.id})"
                        class="ml-4 text-red-600 hover:text-red-700 font-semibold transition"
                    >
                        Hapus
                    </button>
                </div>
            `;
        }).join('');

        if (selectedInfo) {
            selectedInfo.textContent = `${selectedItems.length} dari ${items.length} produk terpilih untuk checkout.`;
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = selectedItems.length === items.length;
            selectAllCheckbox.onchange = function() {
                toggleSelectAll(this.checked);
            };
        }

        bindSelectionCheckboxes();
        if (checkoutButton) checkoutButton.disabled = selectedItems.length === 0;
    }

    function bindSelectionCheckboxes() {
        document.querySelectorAll('.cart-item-select-checkbox').forEach(checkbox => {
            checkbox.onchange = function() {
                const itemId = Number(this.dataset.itemId);
                toggleItemSelection(itemId, this.checked);
            };
        });
    }

    function toggleItemSelection(itemId, checked) {
        if (checked) {
            if (!selectedItemIds.includes(itemId)) {
                selectedItemIds.push(itemId);
            }
        } else {
            selectedItemIds = selectedItemIds.filter(id => id !== itemId);
        }

        if (currentCartData) {
            updateCartUI(currentCartData);
        }
    }

    function toggleSelectAll(checked) {
        if (!currentCartData) return;
        const items = currentCartData.items || [];

        if (checked) {
            selectedItemIds = items.map(item => item.id);
        } else {
            selectedItemIds = [];
        }

        updateCartUI(currentCartData);
    }

    function goToCheckout() {
        if (!selectedItemIds.length) {
            alert('Pilih minimal satu barang untuk checkout.');
            return;
        }

        const params = new URLSearchParams();
        selectedItemIds.forEach(id => params.append('items[]', id));
        window.location.href = `/checkout?${params.toString()}`;
    }

    async function updateQuantity(itemId, quantity) {
        quantity = parseInt(quantity);
        if (quantity < 1) return;
        await cart.updateItem(itemId, quantity);
    }

    async function removeItem(itemId) {
        if (confirm('Hapus produk ini dari keranjang?')) {
            await cart.removeItem(itemId);
        }
    }

    async function clearCart() {
        if (confirm('Kosongkan seluruh keranjang?')) {
            await cart.clearCart();
        }
    }
</script>
@endsection
