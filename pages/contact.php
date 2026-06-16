<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Lanka DigiMart</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/contact.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="contact-box" style="margin-top: 100px;">
        <h2>Contact Us</h2>
        <form action="#" method="GET">
            <input type="text" placeholder="Name" required>
            <input type="email" placeholder="Email" required>
            <textarea placeholder="Your Message" required></textarea><br>
            <button type="submit" class="btn-primary" style="width: 300px;">Send Message</button>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
