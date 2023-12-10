<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
require 'vendor/autoload.php'; // Include PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send an email notification to the space owner
function notifySpaceOwner($spaceName, $tenantName)
{
    global $con;

    // Get space owner's email from the space table
    $getSpaceOwnerQuery = "SELECT space_oemail FROM space WHERE space_name = ?";
    $getSpaceOwnerStmt = $con->prepare($getSpaceOwnerQuery);
    $getSpaceOwnerStmt->bind_param('s', $spaceName);
    $getSpaceOwnerStmt->execute();
    $spaceOwnerResult = $getSpaceOwnerStmt->get_result();

    if ($spaceOwnerResult->num_rows > 0) {
        $spaceOwnerRow = $spaceOwnerResult->fetch_assoc();
        $spaceOwnerEmail = $spaceOwnerRow['space_oemail'];

        // Send email notification to the space owner
        sendEmailToSpaceOwner($spaceOwnerEmail, $spaceName, $tenantName);
    }
}

// Function to send an email to the space owner
function sendEmailToSpaceOwner($email, $spaceName, $tenantName)
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
        $mail->addAddress($email); // Add the space owner's email as a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Space Application';
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
                    <h1>New Space Application</h1>
                    <p>Dear Space Owner,</p>
                    <p>A new application has been submitted for space <strong>$spaceName</strong> by tenant <strong>$tenantName</strong>.</p>
                    <p>Regards,<br>Concessionaire Monitoring Operation System</p>
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    // Assuming you have validation checks for the form fields

    // Get user information from the session
    $tenantName = $_SESSION['uname'];
    $tenantEmail = $_SESSION['uemail'];
    $tenantid = $_SESSION['uid'];

    // Get space information from the form
    $spaceName = $_POST['spacename'];
    $SpaceID = $_POST['SpaceID'];
    $concourseid = isset($_GET['concourse_id']);

    $requirements_file = '';
 
    $concourseQuery3 = "SELECT * FROM concourse_verification WHERE concourse_id = '$concourseid'  ";
    if ($concourseQuery3Result && mysqli_num_rows($concourseQuery3) > 0) {
        $concourseData = mysqli_fetch_assoc($concourseQuery3Result);
        $ownerName = $concourseData['uname'];
        $ownerEmail = $concourseData['uemail'];
    }

    //Handle file upload
    if (isset($_FILES['pdf_requirements'])) {
        $uploadDir = __DIR__ . '/uploads/';  // Specify the upload directory
        $uploadFile = $uploadDir . basename($_FILES['pdf_requirements']['name']);

        

        if (move_uploaded_file($_FILES['pdf_requirements']['tmp_name'], $uploadFile)) {
            $requirements_file = $_FILES['pdf_requirements']['name'];
        } else {
            echo "File upload failed.";
            exit;
        }
    }

    // Insert application into space_application table
    $insertApplicationQuery = "INSERT INTO space_application (spacename, tenant_name, ap_email, status, tenantid, space_id, pdf_file, owner_name) VALUES ('$spaceName', '$tenantName', '$tenantEmail', 'pending', $tenantid, $SpaceID, '$requirements_file', '$ownerName')";
    $insertResult = $con->query($insertApplicationQuery);

    // Update status in space table to 'reserved'
    $updateSpaceStatusQuery = "UPDATE space SET status = 'reserved', space_tenant = '$tenantName' WHERE space_name = '$spaceName'";
    $updateResult = $con->query($updateSpaceStatusQuery);

    // Notify space owner
    notifySpaceOwner($spaceName, $tenantName);
    // Check if both queries were successful
    if ($insertResult && $updateResult) {
        $_SESSION['successMessage'] = 'Application submitted successfully!';

        // Send email notification to the owner
        $notificationSent = sendEmailToSpaceOwner($ownerEmail, $ownerName, $tenantName, $spaceName);

        if ($notificationSent) {
            echo 'Email notification sent to the owner.';
        } else {
            echo 'Error sending email notification to the owner.';
        }
        header('Location: concourses.php'); // Redirect back to concourse_view.php
        exit();
    } else {
        $_SESSION['errorMessage'] = 'Error submitting application. Please try again.';
        header('Location: concourses.php'); // Redirect back to dashboard.php
        exit();
    }
}
?>
