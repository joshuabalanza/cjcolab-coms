<?php

// Add this part to verification_concourse_process.php
session_name("user_session");
session_start();
require('includes/dbconnection.php');

if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit();
}

$uid = $_SESSION['uid'];
$utype = $_SESSION['utype'];
$verificationStatus = "Not approved"; // Default status

// $verificationQuery = "SELECT status FROM user_verification WHERE user_id = $uid";
$verificationQuery = "SELECT status, first_name, last_name FROM user_verification WHERE user_id = $uid";

$verificationResult = mysqli_query($con, $verificationQuery);

if ($verificationResult && mysqli_num_rows($verificationResult) > 0) {
    $verificationData = mysqli_fetch_assoc($verificationResult);
    $verificationStatus = $verificationData['status'];

    // Retrieve the owner's first name and last name from the query result
    $owner_first_name = $verificationData['first_name'];
    $owner_last_name = $verificationData['last_name'];
}

if ($utype === 'Owner' && $verificationStatus === 'approved') {
    if (isset($_POST['submit_concourse'])) {
        // Process the concourse submission
        $owner_id = $uid; // The owner's user ID
        $owner_name = $_SESSION['uname']; // The owner's name
        $concourse_name = mysqli_real_escape_string($con, $_POST['concourseName']);
        $concourse_address = mysqli_real_escape_string($con, $_POST['concourseAddress']);
        $concourse_map = ''; // You can set this based on your requirements
        $spaces = mysqli_real_escape_string($con, $_POST['concourseSpaces']);

        // Check if the "uploads" directory exists, create it if not
        $upload_directory = 'uploads/';
        if (!file_exists($upload_directory)) {
            mkdir($upload_directory, 0777, true);
        }

        // if ($_FILES['concourseImage']['error'] === UPLOAD_ERR_OK) {
        //     $image_name = $_FILES['concourseImage']['name'];
        //     $image_tmp = $_FILES['concourseImage']['tmp_name'];
        //     $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        //     $image_filename = uniqid() . '.' . $image_extension;

        //     $upload_path = $upload_directory . $image_filename;

        //     if (move_uploaded_file($image_tmp, $upload_path)) {
        //         // File upload was successful
        //     } else {
        //         // Handle file upload error
        //     }
        // } else {
        //     // Handle file upload error
        // }

        // // Insert a new concourse verification record
        // $insert_query = "INSERT INTO concourse_verification (owner_id, owner_name, concourse_name, concourse_map, spaces, created_at, status)
        //                 VALUES ('$owner_id', '$owner_name', '$concourse_name', '$image_filename', '$spaces', NOW(), 'pending')";

        // if (mysqli_query($con, $insert_query)) {
        //     // Concourse verification data inserted successfully
        //     header('Location: concourses.php?success=true');
        //     exit();
        // } else {
        //     // Handle database insertion error
        //     header('Location: concourses.php?error=database');
        //     exit();
        // }
        if ($_FILES['concourseMap']['error'] === UPLOAD_ERR_OK) {
            $image_name = $_FILES['concourseMap']['name'];
            $image_tmp = $_FILES['concourseMap']['tmp_name'];
            $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_filename = uniqid() . '.' . $image_extension;

            $upload_path = $upload_directory . $image_filename;

            if (move_uploaded_file($image_tmp, $upload_path)) {
                // File upload was successful
                // Update the database query to include the image filename
                $insert_query = "INSERT INTO concourse_verification (owner_id, owner_name, concourse_name,concourse_address, concourse_map, spaces, created_at, status)
                                VALUES ('$owner_id', '$owner_first_name $owner_last_name', '$concourse_name','$concourse_address', '$image_filename', '$spaces', NOW(), 'pending')";

                if (mysqli_query($con, $insert_query)) {
                    // Concourse verification data inserted successfully
                    header('Location: concourses.php?success=true');
                    exit();
                } else {
                    // Handle database insertion error
                    header('Location: concourses.php?error=database');
                    exit();
                }
            } else {
                // Handle file upload error
                header('Location: concourses.php?error=upload');
                exit();
            }
        } else {
            // Handle file upload error
            header('Location: concourses.php?error=upload');
            exit();
        }
    }
} else {
    // Redirect to the verification page or display a message based on the user's type and verification status
}
mysqli_close($con);
