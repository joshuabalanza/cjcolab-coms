<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
require 'vendor/autoload.php'; // Include PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicationId = $_POST['applicationId'];
    $action = $_POST['action'];

    // Update the space_application table using MySQLi
    $updateApplicationQuery = "UPDATE space_application SET status = ? WHERE app_id = ?";
    $updateApplicationStmt = $con->prepare($updateApplicationQuery);

    if ($action === 'approve') {
        $status = 'approved';

        // Get tenant_name, tenant_email, and space_name from space_application
        $getTenantInfoQuery = "SELECT tenant_name, ap_email, spacename, tenantid FROM space_application WHERE app_id = ?";
        $getTenantInfoStmt = $con->prepare($getTenantInfoQuery);
        $getTenantInfoStmt->bind_param('i', $applicationId);
        $getTenantInfoStmt->execute();
        $tenantInfoResult = $getTenantInfoStmt->get_result();

        if ($tenantInfoResult->num_rows > 0) {
            $tenantInfoRow = $tenantInfoResult->fetch_assoc();
            $tenantName = $tenantInfoRow['tenant_name'];
            $tenantEmail = $tenantInfoRow['ap_email'];
            $spaceName = $tenantInfoRow['spacename'];

            // Get the current date for contract start
            $currentdate = date('y-m-d');
            $contractStart = date('y-m-d', strtotime($currentdate . '+2 days'));
            
            // Calculate contract end (1 year from start)
            $contractEnd =  date("Y-m-d", strtotime($contractStart . '+1 year'));
            $sendingdate =  date("Y-m-d", strtotime($contractEnd . '-1 month'));

            // Update the contract table with start and end dates
            $updateContractQuery = "INSERT INTO contract (tenant_name, c_start, c_end, eoc_sendingdate, space_id) VALUES ('$tenantName', '$contractStart', '$contractEnd', '$sendingdate','$applicationId') ON DUPLICATE KEY UPDATE c_start = VALUES(c_start), c_end = VALUES(c_end)";
            $con->query($updateContractQuery);

            // Send email notification to the tenant
            sendEmailToTenant($tenantEmail, $tenantName, $status, $spaceName);

            // Schedule notification 1 month before the end of the contract
            // $notificationDate = date('Y-m-d', strtotime($contractEnd . ' - 1 month'));
            // scheduleNotification($tenantName, $tenantEmail, $spaceName, $notificationDate);
        }

        // Update the space_application table
        $updateApplicationStmt->bind_param('si', $status, $applicationId);
        $updateApplicationStmt->execute();

        // Update the space table if approved
        $updateSpaceQuery = "UPDATE space SET status = 'occupied', space_tenant = ? WHERE space_name = ?";
        $updateSpaceStmt = $con->prepare($updateSpaceQuery);
        $updateSpaceStmt->bind_param('ss', $tenantName, $spaceName);
        $updateSpaceStmt->execute();
    } elseif ($action === 'reject') {
        $status = 'rejected';

        // Update the space_application table
        $updateApplicationStmt->bind_param('si', $status, $applicationId);
        $updateApplicationStmt->execute();

        // Update the space table if rejected
        $updateSpaceQuery = "UPDATE space SET status = 'available' WHERE space_name IN (SELECT spacename FROM space_application WHERE app_id = ?)";
        $updateSpaceStmt = $con->prepare($updateSpaceQuery);
        $updateSpaceStmt->bind_param('i', $applicationId);
        $updateSpaceStmt->execute();
    }

    // Check if the update in the space_application table was successful
    if ($updateApplicationStmt->affected_rows > 0) {
        // Your other update and success/error handling code here
        echo 'Application status updated successfully';
    } else {
        echo 'Error updating application status. Please try again.';
    }
}

// Function to send an email to the tenant
function sendEmailToTenant($email, $tenantName, $status, $spaceName)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'coms.system.adm@gmail.com'; // Your Gmail email address
        $mail->Password = 'wdcbquevxahkehla'; // Your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('coms.system.adm@gmail.com', 'Concessionaire Monitoring Operation System');
        $mail->addAddress($email, $tenantName); // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Application Status Update';
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
                    <h1>Application Status Update</h1>
                    <p>Dear $tenantName,</p>
                    <p>Your space application for space $spaceName is $status.</p>
                    <p>Regards,<br>Your Landlord</p>
                </div>
            </body>
            </html>
        ";

        $mail->send();

        return true; // Email sent successfully
    } catch (Exception $e) {
        return false; // Email sending failed
    }
}

// Function to schedule a notification
function scheduleNotification($tenantName, $tenantEmail, $spaceName, $notificationDate)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'coms.system.adm@gmail.com'; // Your Gmail email address
        $mail->Password = 'wdcbquevxahkehla'; // Your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('coms.system.adm@gmail.com', 'Concessionaire Monitoring Operation System');
        $mail->addAddress($tenantEmail, $tenantName);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Upcoming Contract End';
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
                    <h1>Contract End Notification</h1>
                    <p>Dear $tenantName,</p>
                    <p>Your contract for space $spaceName is ending soon. We wanted to remind you that the contract is scheduled to end on $notificationDate.</p>
                    <p>Please make necessary arrangements or contact us if you have any questions.</p>
                    <p>Thank you,<br>Your Landlord</p>
                </div>
            </body>
            </html>
        ";

        $mail->send();

        return true; // Email sent successfully
    } catch (Exception $e) {
        return false; // Email sending failed
    }
}
?>
