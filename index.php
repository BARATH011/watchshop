<?php
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Shop - Premium Timepieces</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <a href="index.php" class="logo">TIMELESS</a>
        <nav>
            <a href="index.php">Home</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin/dashboard.php">Admin Panel</a>
                <?php endif; ?>
                <a href="#" onclick="logout()">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
            <a href="checkout.php">Cart (<span id="cart-count">0</span>)</a>
        </nav>
    </header>

    <div class="hero">
        <h1>Precision & Elegance</h1>
        <p>Discover our collection of premium watches.</p>
        <a href="#products" class="btn">Shop Now</a>
    </div>

    <div class="container" id="products">
        <h2 style="border-bottom: 2px solid var(--primary-color); display: inline-block; padding-bottom: 10px;">Featured Collection</h2>
        <div class="products-grid" id="product-list">
            <!-- Products will be loaded here -->
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        // Fetch products
        fetch('get_products.php')
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('product-list');
                container.innerHTML = '';
                data.forEach(product => {
                    const card = document.createElement('div');
                    card.className = 'product-card';
                    card.innerHTML = `
                        <img src="${product.image_url}" alt="${product.name}" class="product-image">
                        <div class="product-info">
                            <div class="product-title">${product.name}</div>
                            <div class="product-price">$${product.price}</div>
                            <div class="product-desc">${product.description}</div>
                            <button class="btn" onclick='addToCart(${JSON.stringify(product)})'>Add to Cart</button>
                        </div>
                    `;
                    container.appendChild(card);
                });
            });

        function logout() {
            const formData = new FormData();
            formData.append('action', 'logout');
            fetch('auth.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') location.reload();
                });
        }
    </script>
</body>
</html>
