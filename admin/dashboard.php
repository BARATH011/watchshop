<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Watch Shop</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <div class="admin-layout">
        <div class="sidebar">
            <h2 style="color: var(--primary-color);">Admin Panel</h2>
            <a href="#" onclick="showSection('products')">Manage Products</a>
            <a href="#" onclick="showSection('users')">Manage Users</a>
            <a href="../index.php">Back to Shop</a>
            <a href="#" onclick="logout()">Logout</a>
        </div>

        <div class="main-content">
            <div id="products-section">
                <h1>Manage Products</h1>
                
                <div class="form-container" style="margin: 0 0 2rem 0; max-width: 100%;">
                    <h3>Add New Product</h3>
                    <form id="addProductForm">
                        <input type="hidden" name="action" value="add_product">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="name" required>
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" step="0.01" name="price" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Image URL</label>
                            <input type="text" name="image_url" placeholder="https://example.com/watch.jpg" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn">Add Product</button>
                    </form>
                </div>

                <h3>Existing Products</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body">
                        <!-- Products loaded via JS -->
                    </tbody>
                </table>
            </div>

            <div id="users-section" style="display: none;">
                <h1>Manage Users</h1>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                         <!-- Users loaded via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            document.getElementById('products-section').style.display = 'none';
            document.getElementById('users-section').style.display = 'none';
            document.getElementById(sectionId + '-section').style.display = 'block';
        }

        function loadProducts() {
            fetch('../get_products.php')
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('product-table-body');
                    tbody.innerHTML = '';
                    data.forEach(p => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${p.id}</td>
                                <td>${p.name}</td>
                                <td>$${p.price}</td>
                                <td>
                                    <button onclick="deleteProduct(${p.id})" class="btn" style="background-color: #ff4444; padding: 5px 10px;">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                });
        }

        function loadUsers() {
            fetch('../get_users.php')
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('user-table-body');
                    tbody.innerHTML = '';
                    data.forEach(u => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${u.id}</td>
                                <td>${u.name}</td>
                                <td>${u.email}</td>
                                <td>${u.role}</td>
                                <td>
                                    ${u.role !== 'admin' ? `<button onclick="deleteUser(${u.id})" class="btn" style="background-color: #ff4444; padding: 5px 10px;">Delete</button>` : ''}
                                </td>
                            </tr>
                        `;
                    });
                });
        }

        function deleteProduct(id) {
            if(!confirm('Are you sure?')) return;
            const formData = new FormData();
            formData.append('action', 'delete_product');
            formData.append('id', id);
            fetch('../admin_actions.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    loadProducts();
                });
        }

        function deleteUser(id) {
            if(!confirm('Are you sure?')) return;
            const formData = new FormData();
            formData.append('action', 'delete_user');
            formData.append('id', id);
            fetch('../admin_actions.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    loadUsers();
                });
        }

        document.getElementById('addProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('../admin_actions.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    if(data.status === 'success') {
                        this.reset();
                        loadProducts();
                    }
                });
        });

        function logout() {
            const formData = new FormData();
            formData.append('action', 'logout');
            fetch('../auth.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') window.location.href = '../login.php';
                });
        }

        // Init
        loadProducts();
        loadUsers();
    </script>
</body>
</html>
