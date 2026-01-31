<?php
include 'config.php';

// Password: admin123
$admin_pass = password_hash('admin123', PASSWORD_DEFAULT);
// Password: user123
$user_pass = password_hash('user123', PASSWORD_DEFAULT);

$users_sql = "INSERT INTO users (name, email, password, role) VALUES 
('Admin User', 'admin@watchshop.com', '$admin_pass', 'admin'),
('John Doe', 'user@watchshop.com', '$user_pass', 'user')
ON DUPLICATE KEY UPDATE password = VALUES(password)";

$products = [
    ["Rolex Submariner", "The quintessential diving watch.", 9100.00, "https://images.unsplash.com/photo-1523170335258-f5ed11844a49?auto=format&fit=crop&w=400&q=80"],
    ["Omega Speedmaster", "The first watch worn on the moon.", 6300.00, "https://images.unsplash.com/photo-1622434641406-a158105c9168?auto=format&fit=crop&w=400&q=80"],
    ["Tag Heuer Carrera", "Born on the race tracks.", 4200.00, "https://images.unsplash.com/photo-1594576722512-582bcd46fba3?auto=format&fit=crop&w=400&q=80"],
    ["Seiko Prospex", "Built for the challenge.", 1200.00, "https://images.unsplash.com/photo-1620625515032-6ed0c1790c75?auto=format&fit=crop&w=400&q=80"],
    ["Casio G-Shock", "Absolute toughness.", 150.00, "https://images.unsplash.com/photo-1591535287612-5b94f387z123?auto=format&fit=crop&w=400&q=80"], // Fixed URL in next steps if broken, simplified for now
    ["Patek Philippe Nautilus", "The epitome of luxury sports watches.", 35000.00, "https://images.unsplash.com/photo-1619134778706-c7310a3a7893?auto=format&fit=crop&w=400&q=80"],
    ["Audemars Piguet Royal Oak", "A design icon since 1972.", 45000.00, "https://images.unsplash.com/photo-1548171915-e79a380a2a4b?auto=format&fit=crop&w=400&q=80"],
    ["Tissot PRX", "Throwback to the 1970s.", 650.00, "https://images.unsplash.com/photo-1639037746194-e34e56598c16?auto=format&fit=crop&w=400&q=80"],
    ["Breitling Navitimer", "The legendary pilot's watch.", 8500.00, "https://images.unsplash.com/photo-1614164185128-e4ec99c436d7?auto=format&fit=crop&w=400&q=80"],
    ["IWC Portugieser", "A masterpiece of engineering.", 7200.00, "https://images.unsplash.com/photo-1524805444758-089113d48a6d?auto=format&fit=crop&w=400&q=80"],
    ["Cartier Santos", "The first modern wristwatch.", 7800.00, "https://images.unsplash.com/photo-1623998021446-45cd96e318b2?auto=format&fit=crop&w=400&q=80"],
    ["Tudor Black Bay", "Vintage inspired diving watch.", 3800.00, "https://images.unsplash.com/photo-1612817288484-9691c951919d?auto=format&fit=crop&w=400&q=80"],
    ["Panerai Luminor", "Italian design, Swiss technology.", 6900.00, "https://images.unsplash.com/photo-1614246067727-2b737151061d?auto=format&fit=crop&w=400&q=80"],
    ["Longines Master", "Classic elegance.", 2400.00, "https://images.unsplash.com/photo-1507682346514-62985392de63?auto=format&fit=crop&w=400&q=80"],
    ["Hamilton Khaki Field", "Military heritage.", 550.00, "https://images.unsplash.com/photo-1509048191080-d2984bad6ae5?auto=format&fit=crop&w=400&q=80"]
];


try {
    // Insert Users
    $conn->exec($users_sql);
    echo "Users inserted/updated successfully.<br>";

    // Insert Products
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url) VALUES (:name, :desc, :price, :img)");
    
    // Clear existing to avoid duplicates if run twice (Optional: remove this if you want to keep old data)
    // $conn->exec("TRUNCATE TABLE products"); 

    foreach ($products as $p) {
        $stmt->execute([
            'name' => $p[0],
            'desc' => $p[1],
            'price' => $p[2],
            'img' => $p[3]
        ]);
    }
    echo "15 Products inserted successfully.<br>";
    
    echo "<h3>Setup Complete!</h3>";
    echo "Admin Login: <b>admin@watchshop.com</b> / <b>admin123</b><br>";
    echo "User Login: <b>user@watchshop.com</b> / <b>user123</b><br>";
    echo "<a href='index.php'>Go to Shop</a>";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
