<?php
include 'config.php';

$stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($products);
?>
