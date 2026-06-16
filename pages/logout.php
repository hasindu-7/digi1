<?php
// Start the session so PHP knows which session to destroy
session_start();

// Remove all session variables
session_unset();

// Destroy the session completely
session_destroy();

// Redirect the user back to the homepage
header("Location: homepage.php");
exit();
?>
