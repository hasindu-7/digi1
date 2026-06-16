<?php
// 1. Start the session BEFORE any HTML is printed
session_start();

// 2. Connect to the database
include '../includes/db_connect.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 3. Look for the user in the database
    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // 4. Verify the password
        if (password_verify($password, $user['password'])) {
            // 5. Password is correct! Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            
            // 6. Redirect to the homepage
            header("Location: homepage.php");
            exit();
        } else {
            $message = "<p style='color: #e74c3c; margin-bottom: 15px;'>Incorrect password.</p>";
        }
    } else {
        $message = "<p style='color: #e74c3c; margin-bottom: 15px;'>No account found with that email.</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="login-box" style="margin: 100px auto;">
        <h2>Login</h2>

        <?php echo $message; ?>

        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 15px;">Login</button>
        </form>

        <p style="margin-top: 20px;">Don't have an account? <a href="register.php">Sign up</a></p>
    </div>

    <?php include '../includes/footer.php'; ?>

</body>
</html>
