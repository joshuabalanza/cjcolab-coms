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

        // Get tenant_name and tenant_email from space_application
        $getTenantInfoQuery = "SELECT tenant_name, ap_email, spacename FROM space_application WHERE app_id = ?";
        $getTenantInfoStmt = $con->prepare($getTenantInfoQuery);
        $getTenantInfoStmt->bind_param('i', $applicationId);
        $getTenantInfoStmt->execute();
        $tenantInfoResult = $getTenantInfoStmt->get_result();

        if ($tenantInfoResult->num_rows > 0) {
            $tenantInfoRow = $tenantInfoResult->fetch_assoc();
            $tenantName = $tenantInfoRow['tenant_name'];
            $tenantEmail = $tenantInfoRow['ap_email'];
            $spaceName = $tenantInfoRow['spacename'];
        
            // Send email notification to the tenant
            sendEmailToTenant($tenantEmail, $tenantName, $status, $spaceName);
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
        $mail->Body = "Dear $tenantName, <br><br>Your space application for space '$spaceName' is $status. <br><br>Regards, <br>Your Landlord";

        $mail->send();

        return true; // Email sent successfully
    } catch (Exception $e) {
        return false; // Email sending failed
    }
}
?>
