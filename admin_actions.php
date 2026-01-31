<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action == 'add_product') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image_url = $_POST['image_url'];

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url) VALUES (:name, :description, :price, :image_url)");
    if ($stmt->execute(['name' => $name, 'description' => $description, 'price' => $price, 'image_url' => $image_url])) {
        echo json_encode(['status' => 'success', 'message' => 'Product added.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product.']);
    }

} elseif ($action == 'delete_product') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
    if ($stmt->execute(['id' => $id])) {
        echo json_encode(['status' => 'success', 'message' => 'Product deleted.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete product.']);
    }

} elseif ($action == 'delete_user') {
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
    if ($stmt->execute(['id' => $id])) {
        echo json_encode(['status' => 'success', 'message' => 'User deleted.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete user.']);
    }
}
?>
