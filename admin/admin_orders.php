<?php
session_start();
include '../includes/db_connect.php';

// Security Check: Must be admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../pages/homepage.php");
    exit();
}

$message = "";

// Handle Status Updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    $update_stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_status, $order_id);
    
    if ($update_stmt->execute()) {
        $message = "<p style='color: var(--success-color); padding: 15px; background: rgba(39, 174, 96, 0.1); border-radius: 8px; margin-bottom: 20px;'>Order #$order_id status updated to $new_status.</p>";
    } else {
        $message = "<p style='color: #e74c3c; padding: 15px; background: rgba(231, 76, 60, 0.1); border-radius: 8px; margin-bottom: 20px;'>Error updating order.</p>";
    }
    $update_stmt->close();
}

// Fetch all orders and join with the users table to get the customer's name
$sql = "SELECT orders.id, orders.total_amount, orders.order_date, orders.order_status, users.full_name, users.email 
        FROM orders 
        JOIN users ON orders.user_id = users.id 
        ORDER BY orders.order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Lanka DigiMart</title>
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
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .admin-table th, .admin-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .admin-table th { color: var(--accent-color); }
        .status-select {
            padding: 8px;
            border-radius: 6px;
            border: none;
            background: rgba(255,255,255,0.9);
            color: #000;
            font-family: inherit;
        }
        .update-btn {
            padding: 8px 12px;
            border-radius: 6px;
            border: none;
            background-color: var(--accent-color);
            color: white;
            cursor: pointer;
            font-weight: bold;
        }
        .update-btn:hover { background-color: var(--accent-hover); }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="admin-container">
        <h2>📦 Manage Orders</h2>
        <a href="admin_dashboard.php" style="color: var(--text-secondary); display: inline-block; margin-bottom: 20px;">&larr; Back to Dashboard</a>

        <?php echo $message; ?>

        <?php if ($result->num_rows > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total (LKR)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($row['full_name']); ?></strong><br>
                                <span style="font-size: 0.85em; color: var(--text-secondary);"><?php echo htmlspecialchars($row['email']); ?></span>
                            </td>
                            <td><?php echo date('M j, Y g:i A', strtotime($row['order_date'])); ?></td>
                            <td><?php echo number_format($row['total_amount'], 2); ?></td>
                            
                            <form action="admin_orders.php" method="POST">
                                <td>
                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                    <select name="order_status" class="status-select">
                                        <option value="pending" <?php if($row['order_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                        <option value="processing" <?php if($row['order_status'] == 'processing') echo 'selected'; ?>>Processing</option>
                                        <option value="completed" <?php if($row['order_status'] == 'completed') echo 'selected'; ?>>Completed</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No orders have been placed yet.</p>
        <?php endif; ?>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
