<?php
session_start();
include '../includes/db_connect.php';

// Check if a product ID was sent via the button
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Create the cart session array if it does not exist yet
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // If the product is not already in the cart, fetch it and add it
    if (!isset($_SESSION['cart'][$product_id])) {
        
        $stmt = $conn->prepare("SELECT title, price, image_icon FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            // Save the product details into the session cart
            $_SESSION['cart'][$product_id] = array(
                'title' => $row['title'],
                'price' => $row['price'],
                'icon' => $row['image_icon']
            );
        }
        $stmt->close();
    }
    
    // Send the user directly to the cart page to see their item
    header("Location: cart.php");
    exit();
} else {
    // If someone tries to access this file directly, send them home
    header("Location: homepage.php");
    exit();
}
?>
