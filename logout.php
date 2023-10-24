<?php

// Start the user session with the same session name used throughout your application
session_name("user_session");
session_start();

if (isset($_SESSION['uid'])) {
    // Unset all of the session variables
    session_unset();  // Unset the variables
    session_destroy();  // Destroy the session

    // Redirect to the index page when logging out
    header('Location: index.php');
    exit();
}
