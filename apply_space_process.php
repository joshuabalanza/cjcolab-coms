<?php
session_name("user_session");
session_start();
require('includes/dbconnection.php');

// Check if the user is not logged in
if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    // Assuming you have validation checks for the form fields
    require('includes/dbconnection.php');

    // Get user information from the session
    $tenantName = $_SESSION['uname'];
    $tenantEmail = $_SESSION['uemail'];

    // Get space information from the form
    $spaceName = $_POST['spacename'];

    // Insert application into space_application table
    $insertApplicationQuery = "INSERT INTO space_application (spacename, tenant_name, ap_email, status) VALUES ('$spaceName', '$tenantName', '$tenantEmail', 'pending')";
    $insertResult = $con->query($insertApplicationQuery);

    // Update status in space table to 'reserved'
    $updateSpaceStatusQuery = "UPDATE space SET status = 'reserved', space_tenant = '$tenantName' WHERE space_name = '$spaceName'";
    $updateResult = $con->query($updateSpaceStatusQuery);

    // Check if both queries were successful
    if ($insertResult && $updateResult) {
        $_SESSION['successMessage'] = 'Application submitted successfully!';
        header('Location: dashboard.php'); // Redirect back to dashboard.php
        exit();
    } else {
        $_SESSION['errorMessage'] = 'Error submitting application. Please try again.';
        header('Location: dashboard.php'); // Redirect back to dashboard.php
        exit();
    }
}
?>
