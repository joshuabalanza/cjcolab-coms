<?php
include('includes/dbconnection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function verifyOTP($con, $email, $otp)
{
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

    // Fetch the user's data from the database based on the email
    $query = "SELECT * FROM user WHERE uemail = '$email'";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $expiration_time = strtotime($user['otp_expiration']);

        // Check if the timer has expired
        if ($expiration_time > time()) {
            if (verifyOTP($con, $email, $otp)) {
                $successMessage = "Registration successful! You can now log in.";

                // Update user verification status
                $sql = "UPDATE user SET verified = 1 WHERE uemail = '$email'";
                $con->query($sql);

                // Redirect to success page or login page
                header('Location: login.php');
                exit();
            } else {
                $errorMessage = "Invalid OTP. Please try again.";
            }
        } else {
            $errorMessage = "The OTP has expired. Please request a new OTP.";
        }
    } else {
        $errorMessage = "User not found.";
    }
}

// Fetch the user's data from the database based on the email
$email = $_GET['email'];
$query = "SELECT * FROM user WHERE uemail = '$email'";
$result = $con->query($query);

$expiration_time = null;

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $expiration_time = strtotime($user['otp_expiration']);
}
?>

<!-- HTML -->
<?php include('includes/header.php') ?>
<?php include('includes/nav.php') ?>

<style>
    /* Add your CSS styles here */
    /* ... */
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins',sans-serif;
}
body{
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px;
  background: linear-gradient(135deg, #71b7e6, #393E46);
}
.container{
  max-width: 700px;
  width: 100%;
  background-color: #fff;
  padding: 25px 30px;
  border-radius: 5px;
  box-shadow: 0 5px 10px rgba(0,0,0,0.15);
}
.container .title{
  font-size: 25px;
  font-weight: 500;
  position: relative;
}
.container .title::before{
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  height: 3px;
  width: 30px;
  border-radius: 5px;
  background: linear-gradient(135deg, #71b7e6, #393E46);
}
.content form .user-details{
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  margin: 20px 0 12px 0;
}
form .user-details .input-box{
  margin-bottom: 15px;
  width: 100%;
}
form .input-box span.details{
  display: block;
  font-weight: 500;
  margin-bottom: 5px;
}
.user-details .input-box input{
  height: 45px;
  width: 100%;
  outline: none;
  font-size: 16px;
  border-radius: 5px;
  padding-left: 15px;
  border: 1px solid #ccc;
  border-bottom-width: 2px;
  transition: all 0.3s ease;
}
.user-details .input-box input:focus,
.user-details .input-box input:valid{
  border-color: #393E46;
}
 form .use-details .use-title{
  font-size: 20px;
  font-weight: 500;
 }
 form .category{
   display: flex;
   width: 80%;
   margin: 14px 0 ;
   justify-content: space-between;
 }
 form .category label{
   display: flex;
   align-items: center;
   cursor: pointer;
 }
 form .category label .dot{
  height: 18px;
  width: 18px;
  border-radius: 50%;
  margin-right: 10px;
  background: #d9d9d9;
  border: 5px solid transparent;
  transition: all 0.3s ease;
}
 #dot-1:checked ~ .category label .one,
 #dot-2:checked ~ .category label .two,
 #dot-3:checked ~ .category label .three{
   background: #393E46;
   border-color: #d9d9d9;
 }
 form input[type="radio"]{
   display: none;
 }
 form .button{
   height: 45px;
   margin: 35px 0
 }
 form .button input{
   height: 100%;
   width: 100%;
   border-radius: 5px;
   border: none;
   color: #fff;
   font-size: 18px;
   font-weight: 500;
   letter-spacing: 1px;
   cursor: pointer;
   transition: all 0.3s ease;
   background: linear-gradient(135deg, #71b7e6, #393E46);
 }
 form .button input:hover{
  /* transform: scale(0.99); */
  background: linear-gradient(-135deg, #71b7e6, #393E46);
  }
 @media(max-width: 584px){
 .container{
  max-width: 100%;
}
form .user-details .input-box{
    margin-bottom: 15px;
    width: 100%;
  }
  form .category{
    width: 100%;
  }
  .content form .user-details{
    max-height: 300px;
    overflow-y: scroll;
  }
  .user-details::-webkit-scrollbar{
    width: 5px;
  }
  }
  @media(max-width: 459px){
  .container .content .category{
    flex-direction: column;
  }
}
</style>
</style>

<div class="container">
    <div class="title">OTP Verification</div>
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

            <div class="content">
                <p>
                    <?php
                    if (isset($errorMessage)) {
                        echo $errorMessage;
                    }
?>
                </p>
                <div class="user-details">
                    <div class="input-box">
                        <span for="otp" class="details">OTP</span>
                        <input type="text"
                               name="otp"
                               id="otp"
                               autocomplete="off"
                               placeholder="An OTP (One-Time Password) has been sent to your email."
                               required>
                    </div>
                </div>

                <div class="button">
                    <!-- <input type="submit" name="verify_otp"> -->
                    <button type="submit" class="btn btn-danger mt-3 btn-block shadow-sm font-weight-bold" name="verify_otp">Verify OTP</button>

                </div>
            </div>
        </form>

        <!-- Countdown Timer -->
        <div class="text-center" id="timer"></div>
    <?php endif; ?>
</div>
</div>

<script>
    // Set the expiration time for the countdown timer to 3 minutes
    var expirationTime = <?php echo time() + 3 * 60; ?> * 1000;

    if (expirationTime > 0) {
        // Update the countdown every second
        var x = setInterval(function () {
            var now = new Date().getTime();
            var distance = expirationTime - now;

            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the timer
            document.getElementById("timer").innerHTML = "Time remaining: " + minutes + "m " + seconds + "s ";

            // If the timer reaches zero, redirect the user or take appropriate action
            if (distance < 0) {
                clearInterval(x);
                // Handle expired timer (e.g., redirect to a different page)
                window.location.href = "otp-resend.php";
            }
        }, 1000);
    }
</script>

<?php include('includes/footer.php') ?>
