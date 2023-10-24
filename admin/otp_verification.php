<!-- TO BE DELETED -->
<?php include('includes/header.php')?>
<?php include('../includes/dbconnection.php');?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if (isset($_POST['verify_otp'])) {
    $email = $_GET['email'];
    $otp = $_POST['otp'];

    // Check if the entered OTP matches the stored OTP
    $sql = "SELECT * FROM admin WHERE aemail = '$email' AND a_otp = '$otp'";
    $result = $con->query($sql);

    if ($result->num_rows == 1) {
        // Update the user's status as verified (you can add a 'verified' column in the user table)
        $sql = "UPDATE admin SET verified = 1 WHERE aemail = '$email'";
        $con->query($sql);

        // Redirect the user to a success page or login page
        header('Location: login.php'); // Create a success page for successful verification
        exit();
    } else {
        $error_message = "Invalid OTP. Please try again.";
    }
}
?>
<!-- HTML -->
<?php include('includes/nav.php')?>

<section class="otp-form">

    <div class="container pt-5">
        <h2 class="text-center">OTP Verification</h2>
    <div class="row mt-4 mb-4">
        <div class="col-md-6 offset-md-3">
            <form action="" class="shadow-lg p-4" method="POST">
                <p><?php
                if (isset($error_message)) {
                    echo $error_message;
                }
?></p>
                <div class="form-group form">
                    <input type="text" class="form-control form-input" name="otp" id="otp" autocomplete="off" placeholder="">
                    <label for="otp" class="form-label">
                        <i class="fa-solid fa-key"></i>
                        OTP
                    </label>
                </div>
                <button type="submit" class="btn btn-danger mt-3 btn-block shadow-sm font-weight-bold" name="verify_otp">Verify OTP</button>
            </form>
        </div>
    </div>
</div>
</section>
<?php include('includes/footer.php')?>