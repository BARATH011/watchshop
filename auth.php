<?php
session_start();
include 'config.php';

$action = $_POST['action'] ?? '';

if ($action == 'register') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
    try {
        $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
        echo json_encode(['status' => 'success', 'message' => 'Registration successful!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists or error.']);
    }
} elseif ($action == 'login') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        echo json_encode(['status' => 'success', 'role' => $user['role'], 'message' => 'Login successful!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid credentials.']);
    }
} elseif ($action == 'logout') {
    session_destroy();
    echo json_encode(['status' => 'success', 'message' => 'Logged out.']);
}
?>
