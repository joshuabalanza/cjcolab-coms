<!-- TO BE DELETED -->

<?php session_start();?>

<?php include('../includes/dbconnection.php');?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Redirect to the index page if the user is not logged in
// if (!isset($_SESSION['uid'])) {
//     header('Location: index.php');
//     exit();
// }

require '../vendor/autoload.php';

if (isset($_POST['aregister'])) {
    if (empty($_POST['aname']) || empty($_POST['aemail']) || empty($_POST['apassword'])) {
        $error_message = "All fields are required";
    } else {
        $aname = $_POST['aname'];
        $aemail = $_POST['aemail'];
        $apassword = $_POST['apassword'];
        $confirm_password = $_POST['confirm_password'];

        if ($apassword !== $confirm_password) {
            $error_message = "Passwords do not match. Please confirm your password correctly.";
        } else {
            // // Check if the email is already registered
            // $emailExists = false;
            // $checkEmailQuery = "SELECT uemail FROM user WHERE uemail = '$uemail'";
            // $result = $con->query($checkEmailQuery);
            // if ($result->num_rows > 0) {
            //     $emailExists = true;
            // }

            // if ($emailExists) {
            // $error_message = "Email is already registered. Please use a different email.";
            // } else {
            $hashedPassword = password_hash($apassword, PASSWORD_DEFAULT);

            // Generate OTP
            $otp_str = str_shuffle('0123456789');
            $otp = substr($otp_str, 0, 5);

            // Generate Activation Code
            $act_str = rand(100000, 10000000);
            $activation_code = str_shuffle('abcdefghijklmno' . $act_str);

            // Insert data into the database
            // $sql = "INSERT INTO user (uname, uemail, upassword, otp, activation_code, utype) VALUES ('$uname', '$uemail', '$hashedPassword', '$otp', '$activation_code','$usertype')";
            $created_at = date("Y-m-d H:i:s"); // Get the current date and time
            $sql = "INSERT INTO admin (aname, aemail, apassword, a_otp, a_activation_code) VALUES ('$aname', '$aemail', '$hashedPassword', '$otp', '$activation_code' )";

            $con->query($sql);

            // Send OTP to the user's email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'coms.capstone@gmail.com'; // Your Gmail email address
                $mail->Password = 'qszvfgehwgqnypze
                    '; // Your Gmail password
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('coms.capstone@gmail.com', 'COMS');
                $mail->addAddress($aemail); // User's email address
                $mail->isHTML(true);
                $mail->Subject = 'OTP Verification';
                $mail->Body = 'Your OTP is: ' . $otp;

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            // Redirect to OTP verification page
            header('Location: otp_verification.php?email=' . $aemail);
            exit();
        }
    }
}

?>
<!-- HTML -->

<?php include('includes/header.php')?>
<?php include('includes/nav.php')?>
<!-- Start Create Account -->
<section class="register-form">
<div class="container pt-5">
    <h2 class="text-center">Create an Account</h2>
    <div class="row mt-4 mb-4" >
        <div class="col-md-6 offset-md-3">
            <form action="" class="shadow-lg p-4" method="POST">
                <p><?php
                if (isset($error_message)) {
                    echo $error_message;
                }
?></p>

    <div class="form-group form">
        <!-- <label for="usertype" class= ""> <i class="fa-solid fa-users"></i>User Type</label>
                    <select class="form-control" id="usertype" name="usertype">
                        
                    <option selected="true" disabled="true" >--SELECT USER TYPE--</option>
                        <option value="Owner">Owner</option>
                        <option value="Tenant">Tenant</option>
                    </select>
                </div> -->
                <div class="form-group form">
                <input type="text" class="form-control form-input" name="aname"id="aname" autocomplete="off" placeholder="">
                <label for="aname" class="form-label">
                <i class="fa-solid fa-user"></i>    
                Name</label>
            </div>
                <div class="form-group form">
                    <input type="text" class="form-control form-input" name="aemail"id="aemail" autocomplete="off" placeholder="">
                <label for="aemail" class="form-label">
                    <i class="fa-solid fa-envelope"></i>    
                Email</label>
            </div>
                <div class="form-group form">
                <input type="password" class="form-control form-input" name="apassword"id="apassword" placeholder="">
                <label for="apassword" class="form-label">
                    <i class="fa-solid fa-key"></i>
                Password</label>
                </div>
                <div class="form-group form">
                    <input type="password" class="form-control form-input" name="confirm_password" id="confirm_password" placeholder="">
                    <label for="apassword" class="form-label">
                        <i class="fa-solid fa-key"></i>
                        
                        Confirm Password</label>

                    </div>
                    <button type="submit" class="btn btn-danger mt-3 btn-block shadow-sm font-weight-bold" name="aregister">Sign Up</button>
            </form>
        </div>
    </div>
</div>
</section>
<!-- End Create Account -->


<?php include('includes/footer.php')?>