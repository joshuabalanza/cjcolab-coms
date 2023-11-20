<?php
include('includes/dbconnection.php');

if (isset($_GET['username'])) {
    $adminUsername = $_GET['username'];
    $checkAdminUsernameQuery = "SELECT ausername FROM admin WHERE ausername = '$adminUsername'";
    $result = $con->query($checkAdminUsernameQuery);

    $response = array('available' => ($result->num_rows === 0));
    echo json_encode($response);
} else {
    // Handle the case where 'username' parameter is not provided
    $response = array('available' => false);
    echo json_encode($response);
}
?>
