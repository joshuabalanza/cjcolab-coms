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
    if (empty($_POST['uemail']) || empty($_POST['upassword'])) {
        $error_message = "Both email and password are required";
    } else {
        $uemail = $_POST['uemail'];
        $upassword = $_POST['upassword'];

        // Query the database to check if the user exists
        $loginQuery = "SELECT * FROM user WHERE uemail = '$uemail'";
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
                    // header('Location: dashboard.php'); // Change 'dashboard.php' to your desired protected page
                    header('Location: index.php'); // Change 'dashboard.php' to your desired protected page

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
<section class="login-section" style= "margin-top:75px;">
   <div id="overlay">
      <div class="login-form-wrapper">
         <div class="left-panel">
            <a href="">
               <h1>COMS</h1>
            </a>
         </div>
         <div class="right-panel">
            <form action="" class="login-form shadow-lg p-4" method="POST">
               <div class="form-group form">
                  <input
                     type="text"
                     class="form-control form-input"
                     name="uemail"
                     id="uemail"
                     autocomplete="off"
                     placeholder=""
                     />
                  <label for="uemail" class="form-label">
                  <i class="fa-solid fa-envelope"></i>
                  Email</label
                     >
               </div>
               <div class="form-group form">
                  <input
                     type="password"
                     class="form-control form-input"
                     name="upassword"
                     id="upassword"
                     placeholder=""
                     />
                  <label for="upassword" class="form-label">
                  <i class="fas fa-lock"></i> Password</label
                     >
               </div>
               <button
                  type="submit"
                  class="btn border-danger text-danger mt-3 btn-block shadow-sm font-weight-bold"
                  name="login"
                  >
               Login
               </button>
               <div class="text-center">
                  <h6 class="mt-5">Dont have an account?</h6>
                  <button class="btn-cta align-center" name="register">
                  <a class="text-white text-center" href="register.php"> Sign Up </a>
                  </button>
               </div>
            </form>
         </div>
      </div>
   </div>
   <!-- <h2 class="text-center">Login</h2>
      <form action="" class="shadow-lg p-4" method="POST">
       
      
      
      
        <div class="form-group form">
          <input
            type="text"
            class="form-control form-input"
            name="uemail"
            id="uemail"
            autocomplete="off"
            placeholder=""
          />
          <label for="uemail" class="form-label">
            <i class="fa-solid fa-envelope"></i>
            Email</label
          >
        </div>
        <div class="form-group form">
          <input
            type="password"
            class="form-control form-input"
            name="upassword"
            id="upassword"
            placeholder=""
          />
          <label for="upassword" class="form-label">
          <i class="fas fa-lock"></i>               Password</label
          >
        </div>
      
        <button
          type="submit"
          class="btn border-danger text-danger mt-3 btn-block shadow-sm font-weight-bold"
          name="login"
        >
          Login
        </button>
      
        <div class="text-center">
      
            <h6 class=" mt-5">Dont have an account?</h6>      
            <button
            class="btn-cta align-center"
            name="register"
            >
            <a class="text-white text-center" href="register.php">
                
                Sign Up
              </a>
          </button>  
      </div>
      </form> -->
</section>
<?php include('includes/footer.php')?>