<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cart - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div style="padding-top: 100px; text-align: center; min-height: 60vh;">
        <h2>Your Cart</h2>

        <table class="cart-table" style="margin: 0 auto;">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                
                // Check if the cart exists and has items
                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    // Loop through each item in the session
                    foreach ($_SESSION['cart'] as $id => $item) {
                        $total += $item['price']; // Add to the total cost
                        
                        echo '<tr>';
                        echo '<td>';
                        echo '<div class="product-info">';
                        echo '<span style="font-size: 24px;">' . htmlspecialchars($item['icon']) . '</span>';
                        echo '<span>' . htmlspecialchars($item['title']) . '</span>';
                        echo '</div>';
                        echo '</td>';
                        echo '<td>LKR ' . number_format($item['price'], 2) . '</td>';
                        // A link to remove the item
                        echo '<td><a href="cart_remove.php?id=' . $id . '" style="color: #e74c3c;">Remove</a></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="3" style="text-align: center; padding: 30px;">Your cart is empty.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <div class="cart-summary" style="margin: 20px auto; text-align: right; width: 80%; max-width: 800px;">
            <p style="font-size: 1.2rem; margin-bottom: 15px;">Total: LKR <strong><?php echo number_format($total, 2); ?></strong></p>
            
            <?php if ($total > 0): ?>
                <form action="checkout.php" method="POST">
                    <button type="submit" class="btn-primary" style="background-color: #27ae60;">Proceed to Checkout</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
