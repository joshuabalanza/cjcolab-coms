<?php
include('includes/dbconnection.php');

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $checkUsernameQuery = "SELECT username FROM user WHERE username = '$username'";
    $result = $con->query($checkUsernameQuery);

    $response = array('available' => ($result->num_rows === 0));
    echo json_encode($response);
} if (isset($_GET['acusername'])) {
    $acusername = $_GET['acusername'];
    $checkUsernameQuery = "SELECT acusername FROM accountant WHERE acusername = '$acusername'";
    $result = $con->query($checkUsernameQuery);

    $response = array('available' => ($result->num_rows === 0));
    echo json_encode($response);
}else {
    // Handle the case where 'username' parameter is not provided
    $response = array('available' => false);
    echo json_encode($response);
}
?>