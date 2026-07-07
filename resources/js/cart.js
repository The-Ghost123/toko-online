/**
 * Shopping Cart Manager
 * Handles all cart operations via API calls
 */

class CartManager {
    constructor() {
        this.apiBaseUrl = '/api';
        this.cartData = null;
        this.listeners = [];
        this.csrfInitialized = false;
    }

    /**
     * Ensure CSRF cookie is set for Sanctum (for session auth)
     */
    async ensureCsrf() {
        if (this.csrfInitialized) return;
        try {
            // try to get CSRF cookie (Sanctum)
            await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
            this.csrfInitialized = true;
        } catch (e) {
            console.warn('Failed to initialize CSRF cookie', e);
        }
    }

    /**
     * Register a listener for cart changes
     */
    onChange(callback) {
        this.listeners.push(callback);
    }

    /**
     * Notify all listeners of cart changes
     */
    notifyListeners() {
        this.listeners.forEach(callback => callback(this.cartData));
    }

    /**
     * Get CSRF token from meta tag
     */
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    /**
     * Make API request with error handling
     */
    async request(endpoint, options = {}) {
        const url = `${this.apiBaseUrl}${endpoint}`;
        const headers = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.getCsrfToken(),
            'Accept': 'application/json',
        };

        // Ensure CSRF cookie for Sanctum (so session cookie is accepted)
        await this.ensureCsrf();

        // Diagnostic: log if XSRF token meta present and cookies visible
        try {
            console.debug('CSRF meta token:', this.getCsrfToken());
            console.debug('document.cookie (partial, HttpOnly cookies hidden):', document.cookie);
        } catch (e) {
            console.debug('Cookie inspection failed', e);
        }

        const response = await fetch(url, {
            ...options,
            headers: { ...headers, ...options.headers },
            credentials: 'include', // include cookies across origins
        });

        if (response.status === 401) {
            // Helpful warning for debugging in browser
            console.warn('API returned 401 Unauthenticated for', url, 'response:', response);
        }

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || 'API request failed');
        }

        return await response.json();
    }

    /**
     * Fetch current user's cart
     */
    async getCart() {
        try {
            const response = await this.request('/cart');
            this.cartData = response;
            this.notifyListeners();
            return response;
        } catch (error) {
            console.error('Error fetching cart:', error);
            throw error;
        }
    }

    /**
     * Add product to cart
     */
    async addToCart(productId, quantity = 1) {
        try {
            const response = await this.request('/cart/add', {
                method: 'POST',
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                }),
            });

            if (response.success) {
                await this.getCart();
                this.showNotification(response.message, 'success');
            }

            return response;
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showNotification(error.message, 'error');
            throw error;
        }
    }

    /**
     * Update cart item quantity
     */
    async updateItem(cartItemId, quantity) {
        try {
            if (quantity < 1) {
                return this.removeItem(cartItemId);
            }

            const response = await this.request(`/cart/item/${cartItemId}`, {
                method: 'PUT',
                body: JSON.stringify({
                    quantity: quantity,
                }),
            });

            if (response.success) {
                await this.getCart();
                this.showNotification(response.message, 'success');
            }

            return response;
        } catch (error) {
            console.error('Error updating cart item:', error);
            this.showNotification(error.message, 'error');
            throw error;
        }
    }

    /**
     * Remove item from cart
     */
    async removeItem(cartItemId) {
        try {
            const response = await this.request(`/cart/item/${cartItemId}`, {
                method: 'DELETE',
            });

            if (response.success) {
                await this.getCart();
                this.showNotification(response.message, 'success');
            }

            return response;
        } catch (error) {
            console.error('Error removing cart item:', error);
            this.showNotification(error.message, 'error');
            throw error;
        }
    }

    /**
     * Clear entire cart
     */
    async clearCart() {
        try {
            const response = await this.request('/cart/clear', {
                method: 'DELETE',
            });

            if (response.success) {
                await this.getCart();
                this.showNotification(response.message, 'success');
            }

            return response;
        } catch (error) {
            console.error('Error clearing cart:', error);
            this.showNotification(error.message, 'error');
            throw error;
        }
    }

    /**
     * Get abandoned carts (admin only)
     */
    async getAbandonedCarts() {
        try {
            const response = await this.request('/cart/abandoned');
            return response;
        } catch (error) {
            console.error('Error fetching abandoned carts:', error);
            throw error;
        }
    }

    /**
     * Get total items in cart
     */
    getTotalItems() {
        return this.cartData?.total_items || 0;
    }

    /**
     * Get total price of cart
     */
    getTotalPrice() {
        return this.cartData?.total_price || 0;
    }

    /**
     * Format currency (Indonesian Rupiah)
     */
    formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
        }).format(amount);
    }

    /**
     * Show notification (you can integrate with your notification library)
     */
    showNotification(message, type = 'info') {
        // Create a simple toast notification
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            background-color: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
            color: white;
            border-radius: 4px;
            z-index: 9999;
            animation: slideIn 0.3s ease-in-out;
            max-width: 300px;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-in-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
}

// Create global cart manager instance
window.cart = new CartManager();

// Add CSS animations for notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Export for use in modules
export default CartManager;
