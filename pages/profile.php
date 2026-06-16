<?php
session_start();
include '../includes/db_connect.php';

// Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $conn->prepare("SELECT full_name, email, phone_number, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Fetch order history and tracking status
$order_stmt = $conn->prepare("SELECT id, total_amount, order_date, order_status FROM orders WHERE user_id = ? ORDER BY order_date DESC");
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$orders_result = $order_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <style>
        .profile-container {
            max-width: 900px;
            margin: 100px auto;
            background: var(--bg-surface);
            padding: 40px;
            border-radius: var(--border-radius);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .profile-container h2 {
            color: var(--accent-color);
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        .profile-details p {
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        .profile-details strong {
            color: var(--text-secondary);
            display: inline-block;
            width: 150px;
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .history-table th, .history-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .history-table th {
            color: var(--text-secondary);
            text-transform: uppercase;
            font-size: 0.9em;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
        }
        .status-completed { background: rgba(39, 174, 96, 0.2); color: #2ecc71; }
        .status-pending { background: rgba(243, 156, 18, 0.2); color: #f1c40f; }
        .status-processing { background: rgba(52, 152, 219, 0.2); color: #3498db; }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="profile-container">
        <h2>My Profile</h2>
        <div class="profile-details">
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
            <p><strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
        </div>

        <h2 style="margin-top: 50px;">Purchase History & Tracking</h2>
        
        <?php if ($orders_result->num_rows > 0): ?>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders_result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo date('M j, Y g:i A', strtotime($order['order_date'])); ?></td>
                            <td>LKR <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <?php 
                                    $statusClass = 'status-' . strtolower($order['order_status']);
                                    echo '<span class="status-badge ' . $statusClass . '">' . ucfirst($order['order_status']) . '</span>';
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p style="color: var(--text-secondary);">You have not placed any orders yet.</p>
        <?php endif; ?>
        
        <?php $order_stmt->close(); ?>

    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
