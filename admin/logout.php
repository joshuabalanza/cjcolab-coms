<?php

session_name("admin_session");

session_start();

// if (isset($_SESSION['aid'])) {
//     // Unset all of the session variables
//     // $_SESSION = array();

//     // Destroy the session
//     session_destroy();
// }

// // Redirect to the index page when logout
// header('Location: index.php');
// exit();


// Start the user session with the same session name used throughout your application
// session_name("admin_session");
// session_start();

if (isset($_SESSION['aid'])) {
    // Unset all of the session variables
    session_unset();  // Unset the variables
    session_destroy();  // Destroy the session

    // Redirect to the index page when logging out
    header('Location: index.php');
    exit();
}
