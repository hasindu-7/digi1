<?php
session_start();

// Check if an ID was passed in the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    // If the item exists in the cart, remove it
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
}

// Redirect back to the cart
header("Location: cart.php");
exit();
?>
