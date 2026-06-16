<?php
session_start();
include '../includes/db_connect.php';

// Strict Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../pages/homepage.php");
    exit();
}

$message = "";

// Handle Deleting an FAQ
if (isset($_GET['delete'])) {
    $id_to_delete = $_GET['delete'];
    $del_stmt = $conn->prepare("DELETE FROM faqs WHERE id = ?");
    $del_stmt->bind_param("i", $id_to_delete);
    
    if ($del_stmt->execute()) {
        $message = "<p style='color: var(--success-color); padding: 15px; background: rgba(39, 174, 96, 0.1); border-radius: 8px; margin-bottom: 20px;'>FAQ deleted successfully!</p>";
    } else {
        $message = "<p style='color: #e74c3c; padding: 15px; background: rgba(231, 76, 60, 0.1); border-radius: 8px; margin-bottom: 20px;'>Error deleting FAQ.</p>";
    }
    $del_stmt->close();
}

// Handle Adding a New FAQ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_faq'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];

    $add_stmt = $conn->prepare("INSERT INTO faqs (question, answer) VALUES (?, ?)");
    $add_stmt->bind_param("ss", $question, $answer);
    
    if ($add_stmt->execute()) {
         $message = "<p style='color: var(--success-color); padding: 15px; background: rgba(39, 174, 96, 0.1); border-radius: 8px; margin-bottom: 20px;'>FAQ added successfully!</p>";
    } else {
         $message = "<p style='color: #e74c3c; padding: 15px; background: rgba(231, 76, 60, 0.1); border-radius: 8px; margin-bottom: 20px;'>Error adding FAQ.</p>";
    }
    $add_stmt->close();
}

// Fetch all FAQs
$result = $conn->query("SELECT * FROM faqs ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage FAQs - Lanka DigiMart</title>
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
        
        .admin-form input, .admin-form textarea {
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

    <?php include '../includes/header.php'; ?>

    <div class="admin-container">
        <h2>❓ Manage FAQs</h2>
        <a href="admin_dashboard.php" style="color: var(--text-secondary); display: inline-block; margin-bottom: 20px;">&larr; Back to Dashboard</a>

        <?php echo $message; ?>

        <!-- Form to Add a New FAQ -->
        <div class="admin-form">
            <h3 style="margin-bottom: 15px; color: var(--accent-color);">Add New FAQ</h3>
            <form action="admin_faqs.php" method="POST">
                <input type="text" name="question" placeholder="Enter the Question" required>
                <textarea name="answer" rows="4" placeholder="Enter the Answer" required></textarea>
                <button type="submit" name="add_faq" class="btn-primary">Add FAQ</button>
            </form>
        </div>

        <!-- Table Displaying All FAQs -->
        <h3 style="margin-bottom: 15px; color: var(--accent-color);">Current FAQs</h3>
        <?php if ($result->num_rows > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td style="font-weight: bold; width: 30%;"><?php echo htmlspecialchars($row['question']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($row['answer'])); ?></td>
                            <td style="width: 10%;">
                                <a href="admin_faqs.php?delete=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Delete this FAQ?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No FAQs found in the database.</p>
        <?php endif; ?>

    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
