<?php
// Start the session
session_start();

// Destroy the session
session_destroy();

// Redirect the user to the home page (index.php)
header("Location: index.php");
exit();
?>