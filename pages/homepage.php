<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/homepage.css">
</head>

<body>

<?php include '../includes/header.php'; ?>

<!--  HERO SECTION -->
<section class="hero fade-in show">

    <h1>Discover Digital Products</h1>
    <p>All in one place</p>

    <a href="#featured-products" class="cta-btn">Explore Now</a>

</section>

<!--  FEATURED PRODUCTS -->
<section id="featured-products"><h2 class="fade-in">Featured Products</h2><br>

<div class="slider-container fade-in">

<button class="prev">❮</button>

<div class="products" id="slider">
    <?php
    // Connect to the database
    include '../includes/db_connect.php';

    // Fetch products from the database
    $sql = "SELECT id, title, price, image_icon FROM products ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Loop through each product and generate the HTML card
        while($row = $result->fetch_assoc()) {
            $icon = htmlspecialchars($row['image_icon']);
            $title = htmlspecialchars($row['title']);
            $price = number_format($row['price'], 2);
            $productId = $row['id'];
            
            echo '<div class="product">';
            
            // Wrap the icon in a link to the product page
            echo '<a href="product.php?id=' . $productId . '" style="text-decoration: none;">';
            echo '<div style="width: 150px; height: 150px; background-color: var(--bg-surface-hover); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 50px; margin: 0 auto 15px auto;">';
            echo $icon;
            echo '</div>';
            echo '</a>';
            
            // Wrap the title in a link as well
            echo '<h3><a href="product.php?id=' . $productId . '" style="color: var(--text-primary);">' . $title . '</a></h3>';
            echo '<p class="product-price">LKR ' . $price . '</p>';
            
            // The Add to Cart button
            echo '<form action="cart_add.php" method="POST">';
            echo '<input type="hidden" name="product_id" value="' . $productId . '">';
            echo '<button type="submit" class="btn-primary" style="margin-top: 10px;">Add to Cart</button>';
            echo '</form>';
            
            echo '</div>';
        }
    } else {
        echo '<p>No products found.</p>';
    }
    ?>
</div>
</div>

<button class="next">❯</button>

</div>
</section> 

<br><br><br><br><br>

<!--  FEATURES -->
<section>
<div class="features fade-in show">
<div>⚡ Instant Delivery</div>
<div>🔒 Secure Platform</div>
<div>❤️ Support Local</div>
</div>
</section>

<?php include '../includes/footer.php'; ?>

<script src="../js/homepage.js"></script>

</body>
</html>
