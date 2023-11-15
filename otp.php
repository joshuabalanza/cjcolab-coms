<?php
include('includes/dbconnection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function verifyOTP($con, $email, $otp) {
    $sql = "SELECT * FROM user WHERE uemail = '$email' AND otp = '$otp'";
    $result = $con->query($sql);

    if ($result->num_rows == 1) {
        $sql = "UPDATE user SET verified = 1 WHERE uemail = '$email'";
        $con->query($sql);
        return true;
    }

    return false;
}

// Handle OTP verification
if (isset($_POST['verify_otp'])) {
    $email = $_GET['email'];
    $otp = $_POST['otp'];

    if (verifyOTP($con, $email, $otp)) {
        $successMessage = "Registration successful! You can now log in.";
    } else {
        $errorMessage = "Invalid OTP. Please try again.";
    }
}
?>

<!-- HTML -->
<?php include('includes/header.php') ?>
<?php include('includes/nav.php') ?>

<section class="otp-form">
    <div class="container pt-5">
        <h2 class="text-center">OTP Verified</h2>
        <div class="row mt-4 mb-4">
            <div class="col-md-6 offset-md-3">

                <?php if (isset($successMessage)) : ?>
                    <div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Registration Successful!</h4>
                        <p>You can now log in.</p>
                        <hr>
                        <p class="mb-0">
                            <a href="login.php" class="btn btn-primary">Go to Login</a>
                        </p>
                    </div>
                <?php else : ?>
                    <form action="" class="shadow-lg p-4" method="POST">
<?php if (isset($errorMessage)) : ?>
    <div class="alert alert-danger" role="alert">
        <strong>Error:</strong> <?php echo $errorMessage; ?>
    </div>
<?php elseif (isset($_GET['success']) && $_GET['success'] == 1) : ?>
    <div class="alert alert-info" role="alert">
        OTP has been successfully resent. Please check your email.
    </div>
<?php endif; ?>

                        <div class="form-group form">
                            <input type="text" class="form-control form-input" name="otp" id="otp" autocomplete="off" placeholder="Enter OTP">
                            <label for="otp" class="form-label">
                                <i class="fa-solid fa-key"></i>
                                OTP
                            </label>
                        </div>
                        <button type="submit" class="btn btn-danger mt-3 btn-block shadow-sm font-weight-bold" name="verify_otp">Verify OTP</button>
                    </form>

                    <!-- OTP Resend -->
                    <form action="otp-resend.php" method="POST">
                        <div class="form-group form">
                            <input type="email" class="form-control form-input" name="email" id="email" autocomplete="off" placeholder="Enter your email">
                            <label for "email" class="form-label">Email</label>
                        </div>
                        <button type="submit" class="btn btn-primary" name="resend_otp">Resend OTP</button>
                    </form>
                    <!-- Otp resend -->
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php') ?>
