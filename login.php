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
// SESSION IS IN THE HEADER.PHP
// session_start(); // Start the session
if (isset($_SESSION['uid'])) {
    // User is already logged in, redirect them to the dashboard or home page
    header('Location: index.php'); // Replace 'dashboard.php' with the appropriate page
    exit();
}

if (isset($_POST['login'])) {
    if (empty($_POST['uname_or_uemail']) || empty($_POST['upassword'])) {
        $error_message = "Both email and password are required";
    } else {
        $uname_or_uemail = $_POST['uname_or_uemail'];
        // $uname = $_POST['uname'];
        $upassword = $_POST['upassword'];

        // Query the database to check if the user exists
        $is_uname_or_uemail = filter_var($uname_or_uemail, FILTER_VALIDATE_EMAIL);
          // Prepare the login query
        if ($is_uname_or_uemail) {
          $loginQuery = "SELECT * FROM user WHERE uemail = '$uname_or_uemail'";
        } else{
          $loginQuery = "SELECT * FROM user WHERE uname = '$uname_or_uemail'";
        }
        $result = $con->query($loginQuery);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();


            if ($row['verified'] == 1) {

                // Verify the password
                if (password_verify($upassword, $row['upassword'])) {
                    // Password is correct, so create session variables
                    $_SESSION['uid'] = $row['uid'];
                    $_SESSION['uemail'] = $row['uemail'];
                    $_SESSION['uname'] = $row['uname'];
                    $_SESSION['utype'] = $row['utype'];
                    $_SESSION['uphone'] = $row['uphone'];
                    $_SESSION['uimage'] = $row['uimage']; // Add this line to set the uimage session variable


                    // Redirect to a protected page or the user's profile
                    header('Location: dashboard.php'); // Change 'dashboard.php' to your desired protected page
                    // header('Location: index.php'); // Change 'dashboard.php' to your desired protected page

                    exit();
                } else {
                    $error_message = "Invalid password";
                }
            } else {
                $error_message = "Account not verified. Please verify your account by entering the OTP.";
            }
        } else {
            $error_message = "User not found. Please register first.";
        }
    }
}
?>
<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
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
    /* background: linear-gradient(135deg, #00ADB5, #393E46); */
    background-color: #9b593c;
  }
  .container{
    max-width: 350px;
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
    /* background: linear-gradient(135deg, #00ADB5, #393E46); */
    background-color: #9b593c;
  }
  .content form .user-details{

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
  form .gender-details .gender-title{
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
    /* background: linear-gradient(135deg, #00ADB5, #393E46); */
    background-color: #9b593c;
  }
  form .button input:hover{
    /* transform: scale(0.99); */
    /* background: linear-gradient(-135deg, #00ADB5, #393E46); */
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
<div class="container">
    <div class="title">Login</div>
    <div class="content">
    <?php if(isset($error_message)) : ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="user-details">  
                <div class="input-box">
                    <span class="details">Username Or Email</span>
                    <input 
                        type="text" 
                        name="uname_or_uemail" 
                        id="uemail"
                        autocomplete="off"
                        placeholder="Enter your username or email" 
                        required>
                </div>

                <div class="input-box">
                    <span class="details">Password</span>
                    <input 
                        type="password"
                        name="upassword"
                        id="upassword"
                        placeholder="Enter your password" 
                        required>
                </div>
            </div>

            <div class="button">
                <input type="submit" name="login"> 
            </div>

            <div class="text-center">
                <p>Don't have an account? <a href="register.php" style="text-decoration: none;">Sign Up</a></p>
            </div>

        </form>
    </div>
</div>
<?php include('includes/footer.php')?>