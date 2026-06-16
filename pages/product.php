<?php
session_start();
include '../includes/db_connect.php';

// Check if a product ID is present in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: homepage.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch the specific product from the database
$stmt = $conn->prepare("SELECT title, description, price, category, image_icon FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// If the product doesn't exist, send them back home
if ($result->num_rows === 0) {
    header("Location: homepage.php");
    exit();
}

$product = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['title']); ?> - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <style>
        .product-page-container {
            max-width: 1000px;
            margin: 100px auto;
            display: flex;
            gap: 50px;
            background: var(--bg-surface);
            padding: 50px;
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .product-image-large {
            flex: 1;
            background-color: var(--bg-surface-hover);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 120px;
            min-height: 300px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .product-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-details h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-align: left;
            color: var(--text-primary);
        }

        .category-badge {
            display: inline-block;
            background-color: var(--accent-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 20px;
            width: fit-content;
        }

        .product-price {
            font-size: 2rem;
            color: var(--success-color);
            font-weight: bold;
            margin-bottom: 25px;
        }

        .product-description {
            font-size: 1.1rem;
            color: var(--text-secondary);
            line-height: 1.8;
            margin-bottom: 35px;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .product-page-container {
                flex-direction: column;
                margin: 50px 20px;
                padding: 30px;
            }
        }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="product-page-container">
        <div class="product-image-large">
            <?php echo htmlspecialchars($product['image_icon']); ?>
        </div>
        
        <div class="product-details">
            <h2><?php echo htmlspecialchars($product['title']); ?></h2>
            <span class="category-badge"><?php echo htmlspecialchars($product['category']); ?></span>
            
            <div class="product-price">
                LKR <?php echo number_format($product['price'], 2); ?>
            </div>
            
            <div class="product-description">
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </div>

            <form action="cart_add.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                <button type="submit" class="btn-primary" style="font-size: 1.2rem; padding: 15px 30px; width: 100%;">Add to Cart 🛒</button>
            </form>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
