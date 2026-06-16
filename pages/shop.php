<?php
session_start();
include '../includes/db_connect.php';

// 1. Check if a specific category was requested in the URL
$current_category = isset($_GET['category']) ? $_GET['category'] : 'all';

// 2. Fetch products based on the requested category
if ($current_category === 'all') {
    // Fetch everything
    $stmt = $conn->prepare("SELECT id, title, price, image_icon, category FROM products ORDER BY created_at DESC");
} else {
    // Fetch only the matching category
    $stmt = $conn->prepare("SELECT id, title, price, image_icon, category FROM products WHERE category = ? ORDER BY created_at DESC");
    $stmt->bind_param("s", $current_category);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <style>
        .shop-container {
            max-width: 1200px;
            margin: 100px auto 50px auto;
            padding: 0 20px;
        }
        
        .shop-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .shop-header h2 {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        /* The Filter Buttons */
        .filter-nav {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .filter-btn {
            padding: 10px 25px;
            border-radius: 30px;
            border: 2px solid var(--accent-color);
            color: var(--text-primary);
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            background: rgba(0, 0, 0, 0.3);
        }

        .filter-btn:hover {
            background: rgba(var(--accent-color-rgb), 0.2);
        }

        .filter-btn.active {
            background: var(--accent-color);
            color: white;
        }

        /* The Product Grid */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: var(--bg-surface);
            padding: 25px;
            border-radius: var(--border-radius);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-10px);
            border-color: var(--accent-color);
        }

        .product-icon {
            width: 120px;
            height: 120px;
            background-color: var(--bg-surface-hover);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            margin: 0 auto 20px auto;
        }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="shop-container">
        <div class="shop-header">
            <h2>Digital Catalog</h2>
            
            <!-- Category Filter Links -->
            <div class="filter-nav">
                <a href="shop.php?category=all" class="filter-btn <?php if($current_category == 'all') echo 'active'; ?>">All Products</a>
                <a href="shop.php?category=software" class="filter-btn <?php if($current_category == 'software') echo 'active'; ?>">Software</a>
                <a href="shop.php?category=templates" class="filter-btn <?php if($current_category == 'templates') echo 'active'; ?>">Templates</a>
                <a href="shop.php?category=music" class="filter-btn <?php if($current_category == 'music') echo 'active'; ?>">Music</a>
                <a href="shop.php?category=ebooks" class="filter-btn <?php if($current_category == 'ebooks') echo 'active'; ?>">eBooks</a>
                <a href="shop.php?category=courses" class="filter-btn <?php if($current_category == 'courses') echo 'active'; ?>">Courses</a>
            </div>
        </div>

        <!-- Display Products in a Grid -->
        <div class="product-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <a href="product.php?id=<?php echo $row['id']; ?>" style="text-decoration: none;">
                            <div class="product-icon">
                                <?php echo htmlspecialchars($row['image_icon']); ?>
                            </div>
                        </a>
                        
                        <h3 style="margin-bottom: 10px;">
                            <a href="product.php?id=<?php echo $row['id']; ?>" style="color: var(--text-primary); text-decoration: none;">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </a>
                        </h3>
                        
                        <p style="font-size: 1.5rem; color: var(--success-color); font-weight: bold; margin-bottom: 20px;">
                            LKR <?php echo number_format($row['price'], 2); ?>
                        </p>
                        
                        <form action="cart_add.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn-primary" style="width: 100%;">Add to Cart</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <h3 style="color: var(--text-secondary);">No products found in this category.</h3>
                </div>
            <?php endif; ?>
        </div>

    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
<?php $stmt->close(); ?>
