<?php
session_start();
include '../includes/db_connect.php';

// Strict Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../pages/homepage.php");
    exit();
}

$message = "";

// Handle Deleting a User
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    
    // Prevent the admin from deleting themselves
    if ($id_to_delete == $_SESSION['user_id']) {
        $message = "<p style='color: #e74c3c; padding: 15px; background: rgba(231, 76, 60, 0.1); border-radius: 8px; margin-bottom: 20px;'>You cannot delete your own account.</p>";
    } else {
        $del_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $del_stmt->bind_param("i", $id_to_delete);
        
        if ($del_stmt->execute()) {
            $message = "<p style='color: var(--success-color); padding: 15px; background: rgba(39, 174, 96, 0.1); border-radius: 8px; margin-bottom: 20px;'>User deleted successfully!</p>";
        } else {
            $message = "<p style='color: #e74c3c; padding: 15px; background: rgba(231, 76, 60, 0.1); border-radius: 8px; margin-bottom: 20px;'>Error deleting user.</p>";
        }
        $del_stmt->close();
    }
}

// Handle Updating User Role
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    // Prevent the admin from demoting themselves
    if ($user_id == $_SESSION['user_id'] && $new_role !== 'admin') {
        $message = "<p style='color: #e74c3c; padding: 15px; background: rgba(231, 76, 60, 0.1); border-radius: 8px; margin-bottom: 20px;'>You cannot demote your own account.</p>";
    } else {
        $update_stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_role, $user_id);
        
        if ($update_stmt->execute()) {
            $message = "<p style='color: var(--success-color); padding: 15px; background: rgba(39, 174, 96, 0.1); border-radius: 8px; margin-bottom: 20px;'>User role updated successfully.</p>";
        } else {
            $message = "<p style='color: #e74c3c; padding: 15px; background: rgba(231, 76, 60, 0.1); border-radius: 8px; margin-bottom: 20px;'>Error updating role.</p>";
        }
        $update_stmt->close();
    }
}

// Fetch all users
$result = $conn->query("SELECT id, full_name, email, phone_number, role, created_at FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Lanka DigiMart</title>
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
        
        .role-select {
            padding: 8px;
            border-radius: 6px;
            border: none;
            background: rgba(255,255,255,0.9);
            color: #000;
            font-family: inherit;
        }

        .action-btn {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9em;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }
        .update-btn { background-color: var(--accent-color); }
        .update-btn:hover { background-color: var(--accent-hover); }
        .delete-btn { background-color: #e74c3c; margin-left: 10px; }
        .delete-btn:hover { background-color: #c0392b; }
        
        .admin-badge {
            background-color: var(--accent-color);
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            margin-left: 10px;
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="admin-container">
        <h2>👥 Manage Users</h2>
        <a href="admin_dashboard.php" style="color: var(--text-secondary); display: inline-block; margin-bottom: 20px;">&larr; Back to Dashboard</a>

        <?php echo $message; ?>

        <?php if ($result->num_rows > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Joined Date</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td>
                                <?php echo htmlspecialchars($row['full_name']); ?>
                                <?php if ($row['role'] == 'admin') echo '<span class="admin-badge">Admin</span>'; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                            
                            <!-- Form to update the user role -->
                            <form action="admin_users.php" method="POST" style="display: inline;">
                                <td>
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <select name="role" class="role-select">
                                        <option value="customer" <?php if($row['role'] == 'customer') echo 'selected'; ?>>Customer</option>
                                        <option value="admin" <?php if($row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="submit" name="update_role" class="action-btn update-btn">Update</button>
                            </form>
                                    
                                    <!-- Delete Button -->
                                    <a href="admin_users.php?delete=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you absolutely sure you want to delete this user? This cannot be undone.');">Delete</a>
                                </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found in the database.</p>
        <?php endif; ?>

    </div>

    <?php include 'footer.php'; ?>

</body>
</html>
