<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_space_modal'])) {
    $space_name = $_POST['space_name'];
    $space_width = $_POST['space_width'];
    $space_length = $_POST['space_length'];
    $space_price = $_POST['space_price'];
    $space_status = $_POST['status'];
    $concourse_id = $_POST['concourse_id'];

    // Get owner information from the user table
    $ownerId = $_SESSION['uid'];
    $getUserQuery = "SELECT uname, uemail FROM user WHERE uid = $ownerId";
    $userResult = mysqli_query($con, $getUserQuery);

    if ($userResult && mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_assoc($userResult);
        $ownerName = $userData['uname'];
        $ownerEmail = $userData['uemail'];

        $space_image = '';  // Initialize an empty string for space image

        // Handle file upload
        if (isset($_FILES['space_image_modal'])) {
            $uploadDir = __DIR__ . '/uploads/';  // Specify the upload directory
            $uploadFile = $uploadDir . basename($_FILES['space_image_modal']['name']);
    
            if (move_uploaded_file($_FILES['space_image_modal']['tmp_name'], $uploadFile)) {
                $space_image = $_FILES['space_image_modal']['name'];
            } else {
                echo "File upload failed.";
                exit;
            }
        }
        $space_dimension = $space_width . ' x ' . $space_length;
        // Insert space with owner information, coordinates, and space image
        $insertQuery = "INSERT INTO space (concourse_id, space_name, space_width, space_length, space_price, status, space_owner, space_oemail, space_img, space_dimension) 
                        VALUES ('$concourse_id', '$space_name', $space_width, $space_length, $space_price, '$space_status', '$ownerName', '$ownerEmail', '$space_image', '$space_dimension')";
    
        if (mysqli_query($con, $insertQuery)) {
            // Successfully inserted space, redirect to map_display_mayk.php with the necessary information
            header("Location: map_display.php?concourse_id=$concourse_id&inserted_space=$space_name&spacedim=$space_dimension");
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Error retrieving owner information.";
    }
}
?>
