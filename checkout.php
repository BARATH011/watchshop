<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Watch Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">TIMELESS</a>
        <nav>
            <a href="index.php">Home</a>
        </nav>
    </header>

    <div class="container">
        <h1>Your Cart</h1>
        <div id="cart-items">
            <!-- Items rendered here -->
        </div>
        
        <div style="text-align: right; margin-top: 2rem; border-top: 1px solid #333; padding-top: 1rem;">
             <h3>Total: $<span id="cart-total">0.00</span></h3>
             <?php if(isset($_SESSION['user_id'])): ?>
                <button onclick="placeOrder()" class="btn">Place Order</button>
             <?php else: ?>
                <a href="login.php" class="btn">Login to Checkout</a>
             <?php endif; ?>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        function placeOrder() {
            if (cart.length === 0) {
                alert('Cart is empty');
                return;
            }

            fetch('checkout_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cart: cart })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('Order placed successfully!');
                    cart = [];
                    localStorage.setItem('watchShopCart', JSON.stringify(cart));
                    window.location.href = 'index.php';
                } else {
                    alert(data.message);
                }
            });
        }
    </script>
</body>
</html>
