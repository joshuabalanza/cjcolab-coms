<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
error_log('process_create_bill.php accessed.');
error_log('POST data: ' . print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_bill'])) {
    // Validate and sanitize input
    $spaceId = mysqli_real_escape_string($con, $_POST['space_id']);
    $electric = mysqli_real_escape_string($con, $_POST['electric']);
    $water = mysqli_real_escape_string($con, $_POST['water']);
    $concourseId = mysqli_real_escape_string($con, $_POST['concourse_id']);

    // Validate numeric values
    if (!is_numeric($electric) || !is_numeric($water)) {
        echo json_encode(['error' => 'Invalid numeric values for electric or water.']);
        exit();
    }

    // Get tenant_name and space_bill from space table
    $getTenantQuery = "SELECT space_tenant, space_bill FROM space WHERE space_id = '$spaceId'";
    $tenantResult = $con->query($getTenantQuery);

    if ($tenantResult === false) {
        echo json_encode(['error' => 'Error retrieving tenant data: ' . $con->error]);
        exit();
    }

    if ($tenantResult->num_rows > 0) {
        $tenantRow = $tenantResult->fetch_assoc();
        $tenantName = $tenantRow['space_tenant'];
        $spaceBill = $tenantRow['space_bill'];

        // Calculate total bill
        $total = $electric + $water + $spaceBill;

        // Insert into the bill table
        $insertBillQuery = "INSERT INTO bill (space_id, tenant_name, electric, water, space_bill, total, due_date, created_at) 
                            VALUES ('$spaceId', '$tenantName', '$electric', '$water', '$spaceBill', '$total', DATE_ADD(NOW(), INTERVAL 7 DAY), NOW())";

        $insertResult = $con->query($insertBillQuery);

        if ($insertResult === true) {
            // Assuming $concourseId is set in view_concourse.php
            // Redirect back to the view_concourse.php page or another appropriate page
            header('Location: view_concourse.php?concourse_id=' . $concourseId);
            exit();
        } else {
            echo json_encode(['error' => 'Error creating bill: ' . $con->error]);
            exit();
        }
    } else {
        echo json_encode(['error' => 'Space not found for the provided space_id.']);
        exit();
    }
} else {
    echo json_encode(['error' => 'Invalid request.']);
    exit();
}
?>
