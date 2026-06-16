<?php
session_start();
include '../includes/db_connect.php';

// 1. Strict Security Check: Must be logged in AND have the 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../pages/homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Master Dashboard - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <style>
        .admin-container {
            max-width: 1000px;
            margin: 100px auto;
            padding: 40px;
            background: var(--bg-surface);
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .admin-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .admin-card {
            background: rgba(0, 0, 0, 0.2);
            padding: 30px;
            border-radius: var(--border-radius);
            text-align: center;
            border: 1px solid rgba(255,255,255,0.05);
            transition: transform 0.3s;
        }

        .admin-card:hover {
            transform: translateY(-5px);
            background: rgba(0, 0, 0, 0.4);
        }

        .admin-card h3 {
            color: var(--accent-color);
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="admin-container">
        <div class="admin-header">
            <h2>⚙️ Web Master Dashboard</h2>
            <p>Welcome, Administrator.</p>
        </div>

        <div class="admin-grid">
            <div class="admin-card">
                <h3>Products</h3>
                <p>Manage digital items</p>
                <a href="admin_products.php" class="btn-primary" style="display: block; margin-top: 15px;">Manage</a>
            </div>

            <div class="admin-card">
                <h3>Orders</h3>
                <p>View customer purchases</p>
                <a href="admin_orders.php" class="btn-primary" style="display: block; margin-top: 15px;">Manage</a>
            </div>

            <div class="admin-card">
                <h3>Users</h3>
                <p>Manage accounts & roles</p>
                <a href="admin_users.php" class="btn-primary" style="display: block; margin-top: 15px;">Manage</a>
            </div>
            
            <div class="admin-card">
                <h3>FAQs</h3>
                <p>Update help section</p>
                <a href="admin_faqs.php" class="btn-primary" style="display: block; margin-top: 15px;">Manage</a>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
