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
  background-color: #9b593c;
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
   background-color: #9b593c;
 }
 form .button input:hover{
  /* transform: scale(0.99); */
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
<!-- Start Create Account -->
<div class="container">
    <div class="title">Registration</div>
    <div class="content" >
        <form action="" method="POST">
            <p>
                <?php
                    if (isset($error_message)) {
                        echo $error_message;
                    }
?>
            </p>

                <!-- <div class="form-group form">
                    <label for="usertype" class= ""> <i class="fa-solid fa-users"></i>User Type</label>
                    <select class="form-control" id="usertype" name="usertype">
                        
                    <option selected="true" disabled="true" >--SELECT USER TYPE--</option>
                        <option value="Owner">Owner</option>
                        <option value="Tenant">Tenant</option>
                    </select>
                </div> -->
            <div class="user-details">
                <div class="input-box">
                    <span for="aname" class="details">Name</span>
                    <input type="text" 
                           name="aname"
                           id="aname" 
                           autocomplete="off" 
                           placeholder="Enter your name"
                           required>
                </div>

                <div class="input-box">
                    <span for="aemail" class="details">Email</span>
                    <input type="text" 
                           name="aemail"
                           id="aemail" 
                           autocomplete="off" 
                           placeholder="Enter your email"
                           required>
                </div>

                <div class="input-box">
                    <span for="apassword" class="details">Password</span>
                    <input type="password" 
                           name="apassword"
                           id="apassword" 
                           placeholder="Enter your password"
                           required>
                </div>

                <div class="input-box">
                    <span for="apassword" class="details">Confirm Password</span>
                    <input type="password" 
                           name="confirm_password" 
                           id="confirm_password" 
                           placeholder="Confirm your password"
                           required>
                </div>
            </div>

            <div class="button">
            <input type="submit" name="aregister">
            </div>
        </form>
    </div>
</div>