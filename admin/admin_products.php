<?php
session_start();
include '../includes/db_connect.php';

// 1. Strict Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../pages/homepage.php");
    exit();
}

$message = "";

// 2. Handle Deleting a Product
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    $del_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $del_stmt->bind_param("i", $id_to_delete);
    
    if ($del_stmt->execute()) {
        $message = "<p style='color: var(--success-color); padding: 15px; background: rgba(39, 174, 96, 0.1); border-radius: 8px; margin-bottom: 20px;'>Product deleted successfully!</p>";
    } else {
        $message = "<p style='color: #e74c3c; padding: 15px; background: rgba(231, 76, 60, 0.1); border-radius: 8px; margin-bottom: 20px;'>Error deleting product.</p>";
    }
    $del_stmt->close();
}

// 3. Handle Adding a New Product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $icon = $_POST['icon']; 

    $add_stmt = $conn->prepare("INSERT INTO products (title, description, price, category, image_icon) VALUES (?, ?, ?, ?, ?)");
    $add_stmt->bind_param("ssdss", $title, $desc, $price, $category, $icon);
    
    if ($add_stmt->execute()) {
         $message = "<p style='color: var(--success-color); padding: 15px; background: rgba(39, 174, 96, 0.1); border-radius: 8px; margin-bottom: 20px;'>Product added successfully!</p>";
    } else {
         $message = "<p style='color: #e74c3c; padding: 15px; background: rgba(231, 76, 60, 0.1); border-radius: 8px; margin-bottom: 20px;'>Error adding product.</p>";
    }
    $add_stmt->close();
}

// 4. Fetch all current products to display in the table
$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <style>
        .admin-container {
            max-width: 1100px;
            margin: 50px auto;
            padding: 40px;
            background: var(--bg-surface);
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .admin-form {
            background: rgba(0,0,0,0.2);
            padding: 30px;
            border-radius: var(--border-radius);
            margin-bottom: 40px;
        }
        
        .admin-form input, .admin-form select, .admin-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: none;
            background: rgba(255,255,255,0.9);
            color: #000;
            font-family: inherit;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admin-table th, .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .admin-table th { color: var(--accent-color); }
        
        .action-btn {
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            color: white;
        }
        .delete-btn { background-color: #e74c3c; }
        .delete-btn:hover { background-color: #c0392b; }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="admin-container">
        <h2>📦 Manage Products</h2>
        <a href="admin_dashboard.php" style="color: var(--text-secondary); display: inline-block; margin-bottom: 20px;">&larr; Back to Dashboard</a>

        <?php echo $message; ?>

        <div class="admin-form">
            <h3 style="margin-bottom: 15px; color: var(--accent-color);">Add New Product</h3>
            <form action="admin_products.php" method="POST">
                <input type="text" name="title" placeholder="Product Title" required>
                
                <select name="category" required>
                    <option value="" disabled selected>Select Category</option>
                    <option value="ebooks">eBooks</option>
                    <option value="software">Software</option>
                    <option value="templates">Templates</option>
                    <option value="music">Music</option>
                    <option value="courses">Courses</option>
                </select>

                <input type="number" name="price" placeholder="Price (LKR)" step="0.01" required>
                <input type="text" name="icon" placeholder="Emoji Icon (e.g., 🚀, 💻, 📘)" required>
                
                <textarea name="description" rows="4" placeholder="Product Description..." required></textarea>
                
                <button type="submit" name="add_product" class="btn-primary">Add Product</button>
            </form>
        </div>

        <h3 style="margin-bottom: 15px; color: var(--accent-color);">Current Products</h3>
        <?php if ($result->num_rows > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price (LKR)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td style="font-size: 24px;"><?php echo htmlspecialchars($row['image_icon']); ?></td>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td style="text-transform: capitalize;"><?php echo htmlspecialchars($row['category']); ?></td>
                            <td><?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <a href="admin_products.php?delete=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No products found in the database.</p>
        <?php endif; ?>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
