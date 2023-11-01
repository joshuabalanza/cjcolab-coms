<?php
include('includes/dbconnection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['resend_otp'])) {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM user WHERE uemail = '$email'";
    $result = $con->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $uemail = $row['uemail'];


        // Generate a new OTP
        $new_otp = str_shuffle('0123456789');
        $new_otp = substr($new_otp, 0, 5);

        // Update the OTP in the database
        $update_sql = "UPDATE user SET otp = '$new_otp' WHERE uemail = '$uemail'";
        $con->query($update_sql);
        // Send OTP to the user's email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'coms.system.adm@gmail.com'; // Your Gmail email address
            $mail->Password = 'wdcbquevxahkehla'; // Your Gmail password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('coms.system.adm@gmail.com', 'Concessionaire Monitoring Operation System');
            $mail->addAddress($uemail); // User's email address
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body = 'Your OTP is: ' . $new_otp;

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Redirect back to the OTP verification page with a success message
        header('Location: otp.php?email=' . $uemail . '&success=1');
        exit();
    } else {
        $error_message = "Email not found in our records. Please sign up first.";
    }
}
?>
<!-- HTML -->
<?php include('includes/header.php')?>
<?php include('includes/nav.php')?>

<section class="otp-resend-form">
    <div class="container pt-5">
        <h2 class="text-center">Resend OTP</h2>
        <div class="row mt-4 mb-4">
            <div class="col-md-6 offset-md-3">
                <form action="" class="shadow-lg p-4" method="POST">
                    <p><?php
                    if (isset($error_message)) {
                        echo $error_message;
                    }
?></p>
                    <div class="form-group form">
                        <input type="email" class="form-control form-input" name="email" id="email" autocomplete="off" placeholder="">
                        <label for="email" class="form-label">
                            <i class="fa-solid fa-envelope"></i>
                            Email
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 btn-block shadow-sm font-weight-bold" name="resend_otp">Resend OTP</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include('includes/footer.php')?>
