<?php
session_start();
include '../includes/db_connect.php';

// 1. Security Check: The user MUST be logged in to checkout
if (!isset($_SESSION['user_id'])) {
    // If they are not logged in, redirect them to login page
    header("Location: login.php");
    exit();
}

// 2. Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$total = 0;

// 3. Calculate the absolute total price securely on the server
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'];
}

// 4. Create the main Order record
// Note: Since these are digital goods, we will set the status to 'completed' automatically
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, order_status) VALUES (?, ?, 'pending')");
$stmt->bind_param("id", $user_id, $total);

if ($stmt->execute()) {
    // 5. Get the unique ID of the order we just created
    $order_id = $stmt->insert_id;
    $stmt->close();

    // 6. Loop through the cart and save each individual item to the order_items table
    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, product_id, price) VALUES (?, ?, ?)");
    
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $price = $item['price'];
        $stmt_items->bind_param("iid", $order_id, $product_id, $price);
        $stmt_items->execute();
    }
    $stmt_items->close();

    // 7. Success! Empty the cart
    unset($_SESSION['cart']);
    
    $message = "Thank you! Your order has been placed successfully.";
} else {
    $message = "Sorry, there was an error processing your order. Please try again.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <style>
        .checkout-box {
            max-width: 600px;
            margin: 150px auto;
            text-align: center;
            background: var(--bg-surface);
            padding: 50px;
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .checkout-box h2 {
            color: var(--success-color);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="checkout-box">
        <h2>Order Confirmed! 🛍️</h2>
        <p style="font-size: 1.2rem; margin-bottom: 30px;"><?php echo $message; ?></p>
        <a href="profile.php" class="btn-primary">View My Orders</a>
        <a href="homepage.php" class="btn-primary" style="background-color: transparent; border: 1px solid var(--accent-color); margin-left: 10px;">Keep Shopping</a>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
