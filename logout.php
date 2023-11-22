<?php

// Start the session with the appropriate session name
session_name("user_session");
session_start();

if (isset($_SESSION['uid'])) {
    // Unset all of the session variables
    session_unset();  
    // Destroy the session
    session_destroy();  

    // Redirect to the index page after logging out
    header('Location: index.php');
    exit();
} elseif (isset($_SESSION['act_id'])){
    // Unset all of the session variables
    session_unset();  
    // Destroy the session
    session_destroy(); 
    // If no accountant is logged in, you can redirect them to the login page
    header('Location: acc_login.php');
    exit();
}

