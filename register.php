<!-- ******************** -->
<!-- ***START SESSION**** -->
<!-- ******************** -->

<?php
session_name("user_session");
session_start();
include('includes/dbconnection.php');
?>

<!-- ******************** -->
<!-- ***** PHP CODE ***** -->
<!-- ******************** -->
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Redirect to the index page if the user is not logged in
// if (!isset($_SESSION['uid'])) {
//     header('Location: index.php');
//     exit();
// }

require 'vendor/autoload.php';
if (isset($_POST['register'])) {
    $error_message="";
    if (empty($_POST['uname']) || empty($_POST['uemail']) || empty($_POST['upassword']) || empty($_POST['username'])) {
        $error_message = "<span style='color: red;'>All fields are required</span> <br/>";
    } else {
        $uname = $_POST['uname'];
        $uemail = $_POST['uemail'];
        $upassword = $_POST['upassword'];
        $confirm_password = $_POST['confirm_password'];
        $username = $_POST['username'];
        $usertype = $_POST['usertype'];
        $birthdate_validate = $_POST['validate_birthdate'];

        if ($upassword !== $confirm_password) {
            $error_message = "<span style='color: red;'>Passwords do not match. Please confirm your password properly.</span> <br/>";
        } else {
            // Check if the email is already registered
            $emailExists = false;
            $emailNotVerified = false;

            $checkEmailQuery = "SELECT uemail, verified FROM user WHERE uemail = '$uemail'";
            $result = $con->query($checkEmailQuery);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                if ($row['verified'] == 0) {
                    // Account not verified, redirect to OTP page
                    header('Location: otp.php?email=' . $uemail);
                    exit();
                }

                $emailExists = true;
            }

            // Check if the username is already taken
            $usernameExists = false;
            $checkUsernameQuery = "SELECT username FROM user WHERE username = '$username'";
            $result = $con->query($checkUsernameQuery);

            if ($result->num_rows > 0) {
                $usernameExists = true;
            }

            if ($emailExists || $usernameExists || $birthdate_validate=="below_18") {
                
                if ($emailExists) {
                  $error_message .= "<span style='color: red;'>Email is already registered. Please use a different email.</span> <br/>";
                } 
                if ($usernameExists) {
                  $error_message .= "<span style='color: red;'>Username is already taken. Please choose a different username.</span> <br/>";
                } 
                if ($birthdate_validate=="below_18") {
                  $error_message .= "<span style='color: red;'>We're sorry, but you must be at least 18 years old to register.</span>";
                } 
            } else {
                $hashedPassword = password_hash($upassword, PASSWORD_DEFAULT);

                // Generate OTP
                $otp_str = str_shuffle('0123456789');
                $otp = substr($otp_str, 0, 5);

                // Generate Activation Code
                $act_str = rand(100000, 10000000);
                $activation_code = str_shuffle('abcdefghijklmno' . $act_str);

                // Insert data into the database
                $expiration_time = date("Y-m-d H:i:s", strtotime('+1 minutes'));
                $created_at = date("Y-m-d H:i:s");
                $sql = "INSERT INTO user (uname, uemail, upassword, otp, activation_code, utype, created_at, otp_expiration, username, verified) VALUES ('$uname', '$uemail', '$hashedPassword', '$otp', '$activation_code', '$usertype', '$created_at', '$expiration_time', '$username',1)";
                $con->query($sql);

                // Send OTP to the user's email
                // $mail = new PHPMailer(true);
                // try {
                //     $mail->isSMTP();
                //     $mail->Host = 'smtp.gmail.com'; // Your SMTP server
                //     $mail->SMTPAuth = true;
                //     $mail->Username = 'coms.system.adm@gmail.com'; // Your Gmail email address
                //     $mail->Password = 'wdcbquevxahkehla'; // Your Gmail password
                //     $mail->SMTPSecure = 'tls';
                //     $mail->Port = 587;

                //     $mail->setFrom('coms.system.adm@gmail.com', 'Concessionaire Monitoring Operation System');
                //     $mail->addAddress($uemail); // User's email address
                //     $mail->isHTML(true);
                //     $mail->Subject = 'Email Verification';
                //     $mail->Body = 'Your OTP is: ' . $otp;

                //     $mail->send();
                // } catch (Exception $e) {
                //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                // }

                // Redirect to OTP verification page
                //  header('Location: otp.php?email=' . $uemail);

                $showSuccessModal = true;
                $showSuccessModal = true;
                // exit();
            }
        }
    }
}
?>

<!-- ... (rest of the code remains unchanged) ... -->

<?php
include('includes/header.php');
include('includes/nav.php');
?>
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
    /* background: linear-gradient(135deg, #71b7e6, #393E46); */
    background-color: #9b593c;
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
    /* background: linear-gradient(135deg, #71b7e6, #393E46); */
    background-color: #c19f90;
  }
  .content form .user-details{
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin: 20px 0 12px 0;
  }
  form .user-details .input-box{
    margin-bottom: 15px;
    width: calc(100% / 2 - 20px);
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
    /* background: linear-gradient(135deg, #71b7e6, #393E46); */
    background-color: #9b593c;
  }
  form .button input:hover{
    /* transform: scale(0.99); */
    /* background: linear-gradient(-135deg, #71b7e6, #393E46); */
    background-color: #c19f90;
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

  .birthdate{
    margin-top:5px
  }
</style>
<div class="container" style="margin-top: 5%;">
    <div class="title">Registration</div>
    <div class="content">
        <form action="" method="POST">
            <br/>
            <p class="">
                <?php
                if (isset($error_message)) {
                    echo $error_message;
                }
              ?>
            </p>

            <div class="user-details">
                <div class="input-box">
                    <span for="uname" class="details">Full Name</span>
                    <input type="text" name="uname" id="uname" autocomplete="off" placeholder="Enter your name" required>
                </div>

                <div class="input-box">
                    <span for="uemail" class="details">Email</span>
                    <input type="text" name="uemail" id="uemail" autocomplete="off" placeholder="Enter your email" required>
                </div>

                <div class="input-box">
                    <span for="upassword" class="details">Password</span>
                    <input type="password" name="upassword" id="upassword" placeholder="Enter your password" required>
                </div>

                <div class="input-box">
                    <span for="confirm_password" class="details">Confirm Password</span>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
                </div>

                <div class="input-box">
                    <span for="username" class="details">Username</span>
                    <input type="text" name="username" id="username" autocomplete="off" placeholder="Enter your username" required>
                    <!-- Suggested username based on the entered full name -->
                    <div class="suggested-username" id="suggested-username"></div>
                </div>
                <div class="input-box">
                    <span for="birthdate" class="details">Birthday</span>
                    <input type="date" class="birthdate" name="birthdate" id="birthdate" autocomplete="off" style="padding-top:10px" required>
                    <input type="hidden" name="validate_birthdate"  id="validate_birthdate">
                </div>
            </div>

            <div class="use-details">
                <input type="radio" value="Owner" name="usertype" id="dot-1" required>
                <input type="radio" value="Tenant" name="usertype" id="dot-2" required>
                <input type="radio" value="Accountant" name="usertype" id="dot-3" required>
                <span class="use-title">User Type</span>
                <div class="category">
                    <label for="dot-1">
                        <span class="dot one"></span>
                        <span class="use">Owner</span>
                    </label>
                    <label for="dot-2">
                        <span class="dot two"></span>
                        <span class="use">Tenant</span>
                    </label>
                </div>
            </div>

            <br>
            <div class="input-box">
                <input type="checkbox" name="agree_terms" id="agree_terms" required>
                <label for="agree_terms" class="details" id="termsLabel">
                    By signing up, you agree to the <a href="javascript:void(0);" id="termsLink">Terms and Conditions</a>
                    of the system
                </label>
            </div>

            <div class="button">
                <input type="submit" name="register">
            </div>
        </form>
    </div>
</div>

<div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeSuccessModal()">&times;</span>
        <h2>Registration Successful!</h2>
        <p>Congratulations! Your are now registed.</p>
        <div class="button-modal">
            <button class="yes" onclick="redirectToLogin()">Go To Login</button>
        </div>
    </div>
</div>

<script>
document.getElementById('username').addEventListener('input', function () {
    const enteredUsername = this.value.trim();
    const suggestedUsername = document.getElementById('suggested-username');

    // Remove any existing suggestions
    suggestedUsername.innerHTML = '';

    if (enteredUsername !== '') {
        const url = `check_username_availability.php?username=${enteredUsername}`;

        // Make an AJAX request to check username availability
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    // Display "Available" message
                    suggestedUsername.innerHTML = '<span style="color: green;">Username is available!</span>';
                } else {
                    // Get alternative suggestions if username is taken
                    fetch(`get_alternative_usernames.php?username=${enteredUsername}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.suggestions.length > 0) {
                                const suggestionsList = data.suggestions.map(suggestion => {
                                    return `<a href="javascript:void(0);" onclick="fillUsername('${suggestion}')">${suggestion}</a>`;
                                }).join(', ');
                                suggestedUsername.innerHTML = `<span style="color: red;">Username is already taken. Try one of the following:</span><br>${suggestionsList}`;
                            } else {
                                suggestedUsername.innerHTML = '<span style="color: red;">Username is already taken.</span>';
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            })
            .catch(error => console.error('Error:', error));
    }
});

function openSuccessModal() {
    var modal = document.getElementById('successModal');
    modal.style.display = 'block';
}

function closeSuccessModal() {
    var modal = document.getElementById('successModal');
    modal.style.display = 'none';
}

function redirectToLogin() {
    window.location.href = "login.php";
}

<?php if (isset($showSuccessModal) && $showSuccessModal) : ?>
    window.onload = function() {
        openSuccessModal();
    };
<?php endif; ?>
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var termsLink = document.getElementById("termsLink");
        var termsLabel = document.getElementById("termsLabel");
        var agreeTermsCheckbox = document.getElementById("agree_terms");

        termsLink.addEventListener("click", function () {
            // You can replace the placeholder with the actual path to your terms and conditions document
            var termsAndConditionsURL = "uploads/terms_and_conditions.pdf";
            window.open(termsAndConditionsURL, "_blank");
        });

        // Add event listener for form submission
        var registrationForm = document.querySelector('form');
        registrationForm.addEventListener('submit', function (event) {
            if (!agreeTermsCheckbox.checked) {
                // If the checkbox is not checked, add red color to the checkbox and text
                agreeTermsCheckbox.style.outline = '1px solid red';
                termsLabel.style.color = 'red';

                // Prevent form submission
                event.preventDefault();
            } else {
                // Reset styles if the checkbox is checked
                agreeTermsCheckbox.style.outline = 'none';
                termsLabel.style.color = '';
            }
        });
    });

    function getAge(birthDateString) {
      var today = new Date();
      var birthDate = new Date(birthDateString);
      var age = today.getFullYear() - birthDate.getFullYear();
      var m = today.getMonth() - birthDate.getMonth();
      if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
          age--;
      }
      console.log(age)
      return age;
    }

    $("#birthdate").change(function(){
        // alert("The text has been changed.");
        var birthdate = $("#birthdate").val()
        var validate_birthdate
        console.log(birthdate);
        if(getAge(birthdate) < 18) {
          validate_birthdate = "below_18"
          console.log("You are not 18 years old and above");
        } 
        else{
          validate_birthdate = "over_18"
        }
        $("#validate_birthdate").val(validate_birthdate)
        console.log(validate_birthdate)
    });

</script>

<?php include('includes/footer.php')?>