<?php

session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $concourse_id = $_GET['concourse_id']; // You should validate and sanitize this input.

    // Retrieve form data
    $total_area = $_POST['total_area'];
    $total_spaces = $_POST['total_spaces'];
    $location = $_POST['location'];
    $contract_of_lease = $_FILES['contract_of_lease']['name']; // Handle file upload.
    $feedback = $_POST['feedback'];

    // Update the database with the new data
    $updateQuery = "UPDATE concourse_verification SET
        concourse_total_area = '$total_area',
        total_spaces = '$total_spaces',
        location = '$location',
        contract_of_lease = '$contract_of_lease',
        feedback = '$feedback'
        WHERE concourse_id = $concourse_id";

    // Execute the query
    if (mysqli_query($con, $updateQuery)) {
        echo "Data updated successfully.";
    } else {
        echo "Error updating data: " . mysqli_error($con);
    }
}

// Redirect back to the concourse details page
header("Location: concourse_configuration.php?concourse_id=$concourse_id");
