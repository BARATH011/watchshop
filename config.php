<?php
$host = 'localhost';
$db_name = 'watch_shop';
$username = 'root'; // Change if your MySQL user is different
$password = ''; // Change if your MySQL password is different

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
