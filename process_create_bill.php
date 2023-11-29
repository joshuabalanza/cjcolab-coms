<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
require 'vendor/autoload.php'; // Include PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

    // Get tenant_name, space_bill, and tenant_email from space table
    $getTenantQuery = "SELECT space_tenant, space_bill FROM space WHERE space_id = ?";
    $stmt = $con->prepare($getTenantQuery);
    $stmt->bind_param("s", $spaceId);
    $stmt->execute();
    $tenantResult = $stmt->get_result();

    if ($tenantResult === false) {
        echo json_encode(['error' => 'Error retrieving tenant data: ' . $stmt->error]);
        exit();
    }

    if ($tenantResult->num_rows > 0) {
        $tenantRow = $tenantResult->fetch_assoc();
        $tenantName = $tenantRow['space_tenant'];
        $spaceBill = $tenantRow['space_bill'];

        // Get tenant_email from the user table
        $getTenantEmailQuery = "SELECT uemail FROM user WHERE uname = ? AND utype = 'tenant'";
        $stmt = $con->prepare($getTenantEmailQuery);
        $stmt->bind_param("s", $tenantName);
        $stmt->execute();
        $emailResult = $stmt->get_result();

        if ($emailResult->num_rows > 0) {
            $emailRow = $emailResult->fetch_assoc();
            $tenantEmail = $emailRow['uemail'];

            // Calculate total bill
            $total = $electric + $water + $spaceBill;

            // Insert into the bill table using prepared statements
            $insertBillQuery = "INSERT INTO bill (space_id, tenant_name, tenant_email, electric, water, space_bill, total, due_date, created_at, notified) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 7 DAY), NOW(), 'not notified')";

            $stmt = $con->prepare($insertBillQuery);
            $stmt->bind_param("sssssss", $spaceId, $tenantName, $tenantEmail, $electric, $water, $spaceBill, $total);

            if ($stmt->execute()) {
                // Calculate the due date for email notification
                $dueDate = date('Y-m-d', strtotime('+7 days'));

                // Notify the tenant via email
                if (sendEmailToTenant($tenantEmail, $tenantName, $electric, $water, $spaceBill, $total, $dueDate)) {
                    // Update notified status to 'notified'
                    $updateNotifiedQuery = "UPDATE bill SET notified = 'notified' WHERE space_id = ? AND tenant_name = ?";
                    $stmt = $con->prepare($updateNotifiedQuery);
                    $stmt->bind_param("ss", $spaceId, $tenantName);
                    $stmt->execute();
                }

                // Update space_bill in the space table
                $updateSpaceBillQuery = "UPDATE space SET space_bill = ? WHERE space_id = ?";
                $stmt = $con->prepare($updateSpaceBillQuery);
                $stmt->bind_param("ss", $total, $spaceId);
                $stmt->execute();

                // Redirect back to the view_concourse.php page or another appropriate page
                header('Location: view_concourse.php?concourse_id=' . $concourseId);
                exit();
            } else {
                echo json_encode(['error' => 'Error creating bill: ' . $stmt->error]);
                exit();
            }
        } else {
            echo json_encode(['error' => 'Tenant email not found.']);
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

// Function to send an email to the tenant
function sendEmailToTenant($email, $tenantName, $electric, $water, $spaceBill, $total, $dueDate)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'coms.system.adm@gmail.com'; // Your Gmail email address
        $mail->Password = 'wdcbquevxahkehla'; // Your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('coms.system.adm@gmail.com', 'Concessionaire Monitoring Operation System');
        $mail->addAddress($email, $tenantName);     // Add a recipient

        // Get the current month
        $currentMonth = date('F');

        // Content
        $mail->isHTML(true);
        $mail->Subject = "Billing information";
        $mail->Body = "
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    body {
                        font-family: 'Arial', sans-serif;
                        background-color: #f4f4f4;
                        color: #333;
                        padding: 20px;
                        margin: 0;
                    }

                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }

                    h1 {
                        color: #3498db;
                    }

                    p {
                        margin-bottom: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h1>Bill Information</h1>
                    <p>Dear $tenantName,</p>
                    <p>Your new bill details for the month of $currentMonth are as follows:</p>
                    <ul>
                        <li>Electric: $electric</li>
                        <li>Water: $water</li>
                        <li>Space Bill: $spaceBill</li>
                        <li>Total: $total</li>
                        <li>Due Date: $dueDate</li>
                    </ul>
                    <p>Regards,<br>Your Landlord</p>
                </div>
            </body>
            </html>
        ";

        $mail->send();
        
        return true; // Email sent successfully
    } catch (Exception $e) {
        echo json_encode(['error' => 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
        return false; // Email sending failed
    }
}
?>
