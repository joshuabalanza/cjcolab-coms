<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
require 'vendor/autoload.php'; // Include PHPMailer autoload

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$currentdate = date('y-m-d');
$checkUnsentEmails = "SELECT * FROM `contract` LEFT JOIN `space_application` ON `contract`.space_id=`space_application`.app_id WHERE eoc_sentemailstatus = 0  and ap_email is not null";
// and eoc_sendingdate= '$currentdate'
$UnsentEmailsResult = mysqli_query($con, $checkUnsentEmails);

if ($UnsentEmailsResult && mysqli_num_rows($UnsentEmailsResult) > 0) {
    while ($Data = mysqli_fetch_assoc($UnsentEmailsResult)) {
    $tenantName = $Data["tenant_name"];
    $tenantEmail= $Data["ap_email"];
    $spaceName = $Data["spacename"];
    $contractenddate = $Data["c_end"];
    $contractid = $Data["contract_id"];

   
    $updateSpaceStatusQuery = "UPDATE contract SET eoc_sentemailstatus = 1 and contract_id= $contractid";
    $updateResult = $con->query($updateSpaceStatusQuery);

    print_r( 'EMAIL SENT');
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
                        <p>Your contract for space $spaceName is ending soon. We wanted to remind you that the contract is scheduled to end on $contractenddate.</p>
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
}
?>