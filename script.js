// Utility to get base URL
const BASE_URL = window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/');

// Cart functionality
let cart = JSON.parse(localStorage.getItem('watchShopCart')) || [];

function updateCartCount() {
    const countSpan = document.getElementById('cart-count');
    if (countSpan) {
        const count = cart.reduce((acc, item) => acc + item.quantity, 0);
        countSpan.textContent = count;
    }
}

function addToCart(product) {
    const existing = cart.find(item => item.id === product.id);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({...product, quantity: 1});
    }
    localStorage.setItem('watchShopCart', JSON.stringify(cart));
    updateCartCount();
    alert('Added to cart!');
}

function renderCart() {
    const cartContainer = document.getElementById('cart-items');
    const totalSpan = document.getElementById('cart-total');
    if (!cartContainer) return;

    cartContainer.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
        cartContainer.innerHTML = '<p>Your cart is empty.</p>';
        totalSpan.textContent = '0.00';
        return;
    }

    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        const div = document.createElement('div');
        div.className = 'cart-item';
        div.innerHTML = `
            <div>
                <strong>${item.name}</strong><br>
                $${item.price} x ${item.quantity}
            </div>
            <div>
                $${itemTotal.toFixed(2)}
                <button onclick="removeFromCart(${item.id})" class="btn" style="background-color: #ff4444; margin-left:10px; padding: 5px 10px;">X</button>
            </div>
        `;
        cartContainer.appendChild(div);
    });

    totalSpan.textContent = total.toFixed(2);
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    localStorage.setItem('watchShopCart', JSON.stringify(cart));
    renderCart();
    updateCartCount();
}

// Global init
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    renderCart();
});
