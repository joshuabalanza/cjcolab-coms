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
    if (empty($_POST['uname']) || empty($_POST['uemail']) || empty($_POST['upassword'])) {
        $error_message = "All fields are required";
    } else {
        $uname = $_POST['uname'];
        $uemail = $_POST['uemail'];
        $upassword = $_POST['upassword'];
        $confirm_password = $_POST['confirm_password'];
        $usertype = $_POST['usertype'];

        if ($upassword !== $confirm_password) {
            $error_message = "Passwords do not match. Please confirm your password correctly.";
        } else {
            // Check if the email is already registered
            $emailExists = false;
            $checkEmailQuery = "SELECT uemail FROM user WHERE uemail = '$uemail'";
            $result = $con->query($checkEmailQuery);
            if ($result->num_rows > 0) {
                $emailExists = true;
            }

            if ($emailExists) {
                $error_message = "Email is already registered. Please use a different email.";
            } else {
                $hashedPassword = password_hash($upassword, PASSWORD_DEFAULT);

                // Generate OTP
                $otp_str = str_shuffle('0123456789');
                $otp = substr($otp_str, 0, 5);

                // Generate Activation Code
                $act_str = rand(100000, 10000000);
                $activation_code = str_shuffle('abcdefghijklmno' . $act_str);

                // Insert data into the database
                // $sql = "INSERT INTO user (uname, uemail, upassword, otp, activation_code, utype) VALUES ('$uname', '$uemail', '$hashedPassword', '$otp', '$activation_code','$usertype')";
                // $created_at = date("Y-m-d H:i:s"); // Get the current date and time
                // $sql = "INSERT INTO user (uname, uemail, upassword, otp, activation_code, utype, created_at) VALUES ('$uname', '$uemail', '$hashedPassword', '$otp', '$activation_code', '$usertype', '$created_at')";
                // Set OTP expiration time (5 minutes)
                $expiration_time = date("Y-m-d H:i:s", strtotime('+1 minutes'));

                // Insert data into the database
                $created_at = date("Y-m-d H:i:s");
                $sql = "INSERT INTO user (uname, uemail, upassword, otp, activation_code, utype, created_at, otp_expiration) VALUES ('$uname', '$uemail', '$hashedPassword', '$otp', '$activation_code', '$usertype', '$created_at', '$expiration_time')";
                $con->query($sql);

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
                    $mail->Body = 'Your OTP is: ' . $otp;

                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }

                // Redirect to OTP verification page
                header('Location: otp.php?email=' . $uemail);
                exit();
            }
        }
    }
}
?>

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
</style>
  <div class="container" style="margin-top: 5%;">
    <div class="title">Registration</div>
    <div class="content">
      <form action="" method="POST">
      <p>
        <?php
          if (isset($error_message)) {
              echo $error_message;
          }
?>
      </p>

        <div class="user-details">
          <div class="input-box">
            <span for="uname" class="details">Full Name</span>
            <input type="text" name="uname"id="uname" autocomplete="off" placeholder="Enter your name" required>
          </div>
          
          <div class="input-box">
            <span for="uemail" class="details">Email</span>
            <input type="text" name="uemail"id="uemail" autocomplete="off" placeholder="Enter your email" required>
          </div>
          
          <div class="input-box">
            <span for="upassword" class="details">Password</span>
            <input type="password" name="upassword"id="upassword" placeholder="Enter your password" required>
          </div>
          
          <div class="input-box">
            <span for="upassword" class="details">Confirm Password</span>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
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
            <label for="dot-3">
              <span class="dot three"></span>
              <span class="use">Accountant</span>
            </label>
          </div>
        </div>
        
        <div class="button">
          <input type="submit" name="register">
        </div>
      </form>
    </div>
  </div>

<?php include('includes/footer.php')?>