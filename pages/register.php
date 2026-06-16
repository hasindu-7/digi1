<?php session_start();
// 1. Connect to the database
include '../includes/db_connect.php';

// Variable to hold success or error messages for the user
$message = "";

// 2. Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 3. Grab the data from the form inputs
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // 4. Validate the passwords match
    if ($password !== $confirmPassword) {
        $message = "<p style='color: #e74c3c; margin-bottom: 15px;'>Passwords do not match!</p>";
    } else {
        // 5. Encrypt the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 6. Insert the data into the database securely
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, phone_number, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullName, $email, $phone, $hashedPassword);

        if ($stmt->execute()) {
            $message = "<p style='color: #27ae60; margin-bottom: 15px;'>Registration successful! You can now log in.</p>";
        } else {
            // Error 1062 means the email already exists in the database
            if ($conn->errno == 1062) { 
                $message = "<p style='color: #e74c3c; margin-bottom: 15px;'>This email is already registered.</p>";
            } else {
                $message = "<p style='color: #e74c3c; margin-bottom: 15px;'>Error: " . $stmt->error . "</p>";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>

    <?php include '../includes/header.php'; ?>

    <div class="login-box" style="margin: 100px auto;">
        <h2>Register</h2>
        
        <?php echo $message; ?>

        <form action="register.php" method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone_number" placeholder="Phone Number">
            <input type="password" name="password" placeholder="Password" required minlength="6">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required minlength="6">

            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 15px;">Register</button>
        </form>

        <p style="margin-top: 20px;">Already have an account? <a href="login.php">Log in</a></p>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
        // Client-Side Validation for Registration Form
        document.querySelector('form').addEventListener('submit', function(event) {
            const password = document.querySelector('input[name="password"]').value;
            const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
            const phone = document.querySelector('input[name="phone_number"]').value;

            let errorMessage = "";

            // 1. Validate Passwords Match
            if (password !== confirmPassword) {
                errorMessage += "Passwords do not match.\n";
            }

            // 2. Validate Password Length
            if (password.length < 6) {
                errorMessage += "Password must be at least 6 characters long.\n";
            }

            // 3. Validate Phone Number (Optional, but if entered, must be numbers/plus sign)
            const phoneRegex = /^[0-9+ ]+$/;
            if (phone !== "" && !phoneRegex.test(phone)) {
                errorMessage += "Please enter a valid phone number (numbers only).\n";
            }

            // If there are errors, stop the form from submitting and alert the user
            if (errorMessage !== "") {
                event.preventDefault(); // Stops the form submission
                alert("Please fix the following errors:\n\n" + errorMessage);
            }
        });
    </script>
</body>
</html>
