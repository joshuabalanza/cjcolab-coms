<?php

session_name("user_session");
session_start();
require('includes/dbconnection.php'); // Ensure this line is included at the top

if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    $map_name = $_POST['map_name'];
    $spaces = $_POST['spaces'];

    // Handle file upload
    if (isset($_FILES['fp'])) {
        $file_name = $_FILES['fp']['name'];
        $file_tmp = $_FILES['fp']['tmp_name'];

        // You may want to add additional validation and security checks for the uploaded file here

        // Move the uploaded file to a directory
        $upload_dir = 'uploads/';
        move_uploaded_file($file_tmp, $upload_dir . $file_name);

        // Insert data into the database
        $sql = "INSERT INTO map (owner_id, map_name, map_image, spaces) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$uid, $map_name, $file_name, $spaces]);

        // Redirect to a success page or any other appropriate action
        header('Location: success.php');
        exit();
    } else {
        // Handle the case where the file was not uploaded properly
        echo "File upload failed!";
    }
} else {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit();
}
