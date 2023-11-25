<?php
session_name("user_session");
session_start();
require('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicationId = $_POST['applicationId'];
    $action = $_POST['action'];

    // Update the space_application table using MySQLi
    $updateApplicationQuery = "UPDATE space_application SET status = ? WHERE app_id = ?";
    $updateApplicationStmt = $con->prepare($updateApplicationQuery);

    if ($action === 'approve') {
        $status = 'approved';

        // Update the space table if approved
        $updateSpaceQuery = "UPDATE space SET status = 'occupied' WHERE space_name IN (SELECT spacename FROM space_application WHERE app_id = ?)";
        $updateSpaceStmt = $con->prepare($updateSpaceQuery);
        $updateSpaceStmt->bind_param('i', $applicationId);
    } elseif ($action === 'reject') {
        $status = 'rejected';

        // Update the space table if rejected
        $updateSpaceQuery = "UPDATE space SET status = 'available' WHERE space_name IN (SELECT spacename FROM space_application WHERE app_id = ?)";
        $updateSpaceStmt = $con->prepare($updateSpaceQuery);
        $updateSpaceStmt->bind_param('i', $applicationId);
    }

    $updateApplicationStmt->bind_param('si', $status, $applicationId);

    // Check if the update in the space_application table was successful
    if ($updateApplicationStmt->execute()) {
        // Check if the space table update was successful
        if ($updateSpaceStmt->execute()) {
            echo 'Application status updated successfully';
        } else {
            echo 'Error updating space table. Please try again.';
        }
    } else {
        echo 'Error updating application status. Please try again.';
    }
}
?>
