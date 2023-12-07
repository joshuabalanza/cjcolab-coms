<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');

// if (isset($_POST['update_bill'])) {
    // Validate and sanitize input
    $spaceId = mysqli_real_escape_string($con, $_POST['space_id']);
    $updatedElectric = mysqli_real_escape_string($con, $_POST['updated_electric']);
    $updatedWater = mysqli_real_escape_string($con, $_POST['updated_water']);
    $paymentstatus = mysqli_real_escape_string($con, $_POST['updated_paymentstatus']);

    // Validate numeric values
    if (!is_numeric($updatedElectric) || !is_numeric($updatedWater)) {
        echo json_encode(['error' => 'Invalid numeric values for updated electric or water.']);
        exit();
    }

    // Assuming you have previously fetched the current bill details in get_current_bill.php
    if (isset($updatedElectric)) {

        // Update the bill using prepared statement
        $updateBillQuery = "UPDATE bill SET electric = '$updatedElectric', water = '$updatedWater', paymentstatus='$paymentstatus' WHERE space_id = '$spaceId' and bill_id=(select max(bill_id) from bill)";
        $updateResult = $con->query($updateBillQuery);

        if ($updateResult) {
            $totalBill = $updatedElectric + $updatedWater; // Assuming total_bill is the sum of electric and water bills

            // Update the space_bill in the space table
            $updateSpaceBillQuery = "UPDATE space SET space_bill = '$totalBill' WHERE space_id = '$spaceId'";
            $update2Result = $con->query($updateSpaceBillQuery);

            if ($update2Result) {
                echo json_encode(['success' => 'Bill and space updated successfully']);
                // Optionally, you can unset the session variable after updating
                exit();
            } else {
                echo json_encode(['error' => 'Error updating space bill: ' . $update2Result->error]);
                exit();
            }
        } else {
            echo json_encode(['error' => 'Error updating bill: ' . $updateResult->error]);
            exit();
        }
    } else {
        echo json_encode(['error' => 'No current bill data found.']);
        exit();
    }
// } 
// else {
//     echo json_encode(['error' => 'Invalid request.']);
//     exit();
// }
?>
