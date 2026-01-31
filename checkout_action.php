<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login to checkout.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['cart']) && !empty($data['cart'])) {
    $user_id = $_SESSION['user_id'];
    $total_amount = 0;
    
    // Calculate total
    foreach ($data['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount) VALUES (:user_id, :total_amount)");
        $stmt->execute(['user_id' => $user_id, 'total_amount' => $total_amount]);
        $order_id = $conn->lastInsertId();

        $stmtItem = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)");
        
        foreach ($data['cart'] as $item) {
            $stmtItem->execute([
                'order_id' => $order_id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Order placed successfully!']);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Order failed: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Cart is empty.']);
}
?>
