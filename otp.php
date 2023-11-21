<?php
include('includes/dbconnection.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function verifyOTP($con, $email, $otp)
{
    $sql = "SELECT * FROM user WHERE uemail = ? AND otp = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $sql = "UPDATE user SET verified = 1 WHERE uemail = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return true;
    }

    return false;
}

if (isset($_POST['verify_otp'])) {
    $email = $_GET['email'];
    $otp = $_POST['otp'];

    $query = "SELECT * FROM user WHERE uemail = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $expiration_time = strtotime($user['otp_expiration']);

        if ($expiration_time > time()) {
            if (verifyOTP($con, $email, $otp)) {
                $successMessage = "Registration successful! You can now log in.";

                $sql = "UPDATE user SET verified = 1 WHERE uemail = ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();

                header('Location: login.php');
                exit();
            } else {
                $errorMessage = "Invalid OTP. Please try again.";
            }
        } else {
            $errorMessage = "The OTP has expired. Please click 'Resend OTP' for a new one.";
        }
    } else {
        $errorMessage = "User not found.";
    }
}

// Handle OTP Resend Logic
if (isset($_POST['resend_otp'])) {
    // Implement logic to resend the OTP
    // For example, you can generate a new OTP, update the expiration time, and send it to the user's email
    // You may need to modify your database schema and update the user's record with the new OTP and expiration time

    // After resending the OTP, you can redirect the user to the same page with a success message
    header('Location: otp-verification.php?email=' . urlencode($email) . '&success=1');
    exit();
}

$email = $_GET['email'];
$query = "SELECT * FROM user WHERE uemail = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$expiration_time = null;
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $expiration_time = strtotime($user['otp_expiration']);
}
?>

<?php include('includes/header.php') ?>
<?php include('includes/nav.php') ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
        background: linear-gradient(135deg, #71b7e6, #393E46);
    }

    .container {
        max-width: 700px;
        width: 100%;
        background-color: #fff;
        padding: 25px 30px;
        border-radius: 5px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
    }

    .container .title {
        font-size: 25px;
        font-weight: 500;
        position: relative;
    }

    .container .title::before {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        width: 30px;
        border-radius: 5px;
        background: linear-gradient(135deg, #71b7e6, #393E46);
    }

    .content form .user-details {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        margin: 20px 0 12px 0;
    }

    form .user-details .input-box {
        margin-bottom: 15px;
        width: 100%;
    }

    form .input-box span.details {
        display: block;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .user-details .input-box input {
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
    .user-details .input-box input:valid {
        border-color: #393E46;
    }

    form .use-details .use-title {
        font-size: 20px;
        font-weight: 500;
    }

    form .category {
        display: flex;
        width: 80%;
        margin: 14px 0;
        justify-content: space-between;
    }

    form .category label {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    form .category label .dot {
        height: 18px;
        width: 18px;
        border-radius: 50%;
        margin-right: 10px;
        background: #d9d9d9;
        border: 5px solid transparent;
        transition: all 0.3s ease;
    }

    #dot-1:checked~.category label .one,
    #dot-2:checked~.category label .two,
    #dot-3:checked~.category label .three {
        background: #393E46;
        border-color: #d9d9d9;
    }

    form input[type="radio"] {
        display: none;
    }

    form .button {
        height: 45px;
        margin: 35px 0;
    }

    form .button input,
    form .button button {
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
    }

    form .button input:hover,
    form .button button:hover {
        background: linear-gradient(-135deg, #71b7e6, #393E46);
    }

    @media(max-width: 584px) {
        .container {
            max-width: 100%;
        }

        form .user-details .input-box {
            margin-bottom: 15px;
            width: 100%;
        }

        form .category {
            width: 100%;
        }

        .content form .user-details {
            max-height: 300px;
            overflow-y: scroll;
        }

        .user-details::-webkit-scrollbar {
            width: 5px;
        }
    }

    @media(max-width: 459px) {
        .container .content .category {
            flex-direction: column;
        }
    }

    .countdown-message {
        color: red;
        font-weight: 500;
        margin-top: 10px;
        text-align: center;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        z-index: 1;
    }

    .modal-content {
        background-color: #fefefe;
        padding: 20px;
        border: 1px solid #888;
        max-width: 40%; /* Adjust this value to make the modal smaller or larger */
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative;
    }

    .button-modal {
        display: flex;
        justify-content: space-between; /* Add space between buttons */
        margin: 10px;
    }

    .button-modal button {
        flex: 1; /* Equal width for both buttons */
        height: 45px;
        border-radius: 5px;
        border: none;
        font-size: 18px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease; /* Add transition property */
        margin: 0 5px; /* Adjust the margin for space between buttons */
    }

    .button-modal button.yes {
        background: linear-gradient(-135deg, #393E46, #71b7e6);
    }

    .button-modal button.no {
        background: linear-gradient(-135deg, #e07171, #46393E);
        transition: background 0.3s ease;
    }

    .button-modal button:hover {
        background: #71b7e6;
    }

    .button-modal button.yes:hover {
        background: linear-gradient(-135deg, #71b7e6, #393E46);
    }

    .button-modal button.no:hover {
        background: linear-gradient(-135deg, #46393E, #e07171);
    }
    
    .close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover {
        color: black;
    }
</style>

<!-- Modal Structure -->
<div id="confirmationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Alert Warning!</h2> <!-- Add this line -->
        <p>Your OTP is not yet expired. Are you sure you want to resend OTP now?</p>
        <div class="button-modal">
            <button class="yes" onclick="handleResend()">Yes</button>
            <button class="no" onclick="closeModal()">No</button>
        </div>
    </div>
</div>


<div class="container">
    <div class="title">OTP Verification</div>
    <?php if (isset($successMessage)) : ?>
        <!-- Success Message -->
    <?php else : ?>
        <!-- Form Section -->
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
                <div class="user-details">
                    <div class="input-box">
                        <span class="details" for="otp">OTP Input Box</span>
                        <input type="text" name="otp" id="otp" autocomplete="off" placeholder="An OTP (One-Time Password) has been sent to your email." required>
                    </div>
                </div>

                <div class="button">
                    <button type="submit" class="btn btn-danger mt-3 btn-block shadow-sm font-weight-bold" name="verify_otp">Verify OTP</button>
                </div>

                <!-- Resend OTP Button -->
                <div class="button" id="resendButton">
                    <button type="button" class="btn btn-info mt-3 btn-block shadow-sm font-weight-bold" onclick="resendOTP()">Resend OTP</button>
                </div>
            </div>
                                <!-- Countdown Timer -->
                                <div class="text-center" id="timer"></div>
            <div class="countdown-message" id="countdownMessage"></div>
        </form>
    <?php endif; ?>
</div>

<script>
// Function to open the modal
function openModal() {
    var modal = document.getElementById('confirmationModal');
    modal.style.display = 'block';
}

// Function to close the modal
function closeModal() {
    var modal = document.getElementById('confirmationModal');
    modal.style.display = 'none';
}

// Check if coming from otp-resend.php and reset the timer
var resetTimer = <?php echo json_encode(isset($_GET['reset_timer'])); ?>;

if (resetTimer) {
    var expirationTime = <?php echo time() + 3.04 * 60; ?> * 1000;
} else {
    var expirationTime = sessionStorage.getItem('otpExpiration') || <?php echo time() + 3.04 * 60; ?> * 1000;
}

// Display the timer
function displayTimer() {
    if (expirationTime > 0) {
        var x = setInterval(function () {
            var now = new Date().getTime();
            var distance = expirationTime - now;

            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("timer").innerHTML = "Time Remaining: " + minutes + "m " + seconds + "s ";

            if (distance < 0) {
                clearInterval(x);
                displayExpirationMessage();
            }
        }, 1000);
    } else {
        displayExpirationMessage();
    }
}

// Display expiration message
function displayExpirationMessage() {
    document.getElementById("timer").innerHTML = "";
    document.getElementById("countdownMessage").innerHTML = "OTP validity has expired. Please click 'Resend OTP' for a new one.";
    document.getElementById("resendButton").style.display = "block"; // Show Resend OTP button
}

// Call the displayTimer function
displayTimer();

// Function to handle the Resend OTP button
function resendOTP() {
    var countdownMessage = document.getElementById("countdownMessage").innerHTML.trim();

    if (countdownMessage !== "") {
        handleResend();
    } else {
        closeModal(); // Close any existing modal before opening a new one
        openModal(); // Open the confirmation modal
    }
}

// Function to handle the actual Resend action
function handleResend() {
    sessionStorage.removeItem('otpExpiration'); // Remove expiration time from storage
    window.location.href = "otp-resend.php?email=" + encodeURIComponent("<?php echo $email; ?>&reset_timer=true");
    closeModal(); // Close the modal after clicking "Yes"
}
</script>


<?php include('includes/footer.php') ?>