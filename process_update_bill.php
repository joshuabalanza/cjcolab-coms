<?php
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

        // Update the bill using prepared statement
        $updateBillQuery = "UPDATE bill SET electric = ?, water = ? WHERE space_id = ? AND tenant_name = ?";
        $stmt = $con->prepare($updateBillQuery);
        $stmt->bind_param("diis", $updatedElectric, $updatedWater, $spaceId, $currentBill['tenant_name']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Update the space_bill in the space table
            $updateSpaceBillQuery = "UPDATE space SET space_bill = ? WHERE space_id = ?";
            $totalBill = $updatedElectric + $updatedWater; // Assuming total_bill is the sum of electric and water bills
            $stmtSpace = $con->prepare($updateSpaceBillQuery);
            $stmtSpace->bind_param("di", $totalBill, $spaceId);
            $stmtSpace->execute();

            if ($stmtSpace->affected_rows > 0) {
                echo json_encode(['success' => 'Bill and space updated successfully']);
                // Optionally, you can unset the session variable after updating
                unset($_SESSION['current_bill']);
                exit();
            } else {
                echo json_encode(['error' => 'Error updating space bill: ' . $stmtSpace->error]);
                exit();
            }
        } else {
            echo json_encode(['error' => 'Error updating bill: ' . $stmt->error]);
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
