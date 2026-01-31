<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Watch Shop</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="index.php" class="logo">TIMELESS</a>
        <nav>
            <a href="index.php">Home</a>
             <a href="login.php">Login</a>
        </nav>
    </header>

    <div class="container">
        <div class="form-container">
            <h2 style="text-align: center; color: var(--primary-color);">Register</h2>
            <div id="alert" class="alert"></div>
            <form id="registerForm">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn" style="width: 100%;">Register</button>
                 <p style="text-align: center; margin-top: 1rem;"><a href="login.php" style="color: var(--primary-color);">Already have an account?</a></p>
            </form>
        </div>
    </div>

    <script src="script.js"></script>
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('auth.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                const alertBox = document.getElementById('alert');
                alertBox.style.display = 'block';
                if (data.status === 'success') {
                    alertBox.className = 'alert alert-success';
                    alertBox.innerText = data.message;
                    setTimeout(() => window.location.href = 'login.php', 1500);
                } else {
                    alertBox.className = 'alert alert-error';
                    alertBox.innerText = data.message;
                }
            });
        });
    </script>
</body>
</html>
