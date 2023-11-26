<?php
// process_update_bill.php

session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bill'])) {
    // Validate and sanitize input
    $spaceId = mysqli_real_escape_string($con, $_POST['space_id']);
    $updatedElectric = mysqli_real_escape_string($con, $_POST['updated_electric']);
    $updatedWater = mysqli_real_escape_string($con, $_POST['updated_water']);

    // Validate numeric values
    if (!is_numeric($updatedElectric) || !is_numeric($updatedWater)) {
        echo json_encode(['error' => 'Invalid numeric values for updated electric or water.']);
        exit();
    }

    // Assuming you have previously fetched the current bill details in get_current_bill.php
    if (isset($_SESSION['current_bill'])) {
        $currentBill = $_SESSION['current_bill'];

        // Update the bill
        $updateBillQuery = "UPDATE bill SET electric = '$updatedElectric', water = '$updatedWater' WHERE space_id = '$spaceId' AND tenant_name = '" . $currentBill['tenant_name'] . "'";
        $updateResult = $con->query($updateBillQuery);

        if ($updateResult === true) {
            echo json_encode(['success' => 'Bill updated successfully']);
            // Optionally, you can unset the session variable after updating
            unset($_SESSION['current_bill']);
            exit();
        } else {
            echo json_encode(['error' => 'Error updating bill: ' . $con->error]);
            exit();
        }
    } else {
        echo json_encode(['error' => 'No current bill data found.']);
        exit();
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
    exit();
}
?>
