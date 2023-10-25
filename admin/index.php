<!-- ******************** -->
<!-- ***START SESSION**** -->
<!-- ******************** -->
<?php
session_name("admin_session");
session_start();
include('../includes/dbconnection.php');
?>

<!-- ******************** -->
<!-- ***** PHP CODE ***** -->
<!-- ******************** -->
<?php
// SESSION IS IN THE HEADER.PHP
// session_start(); // Start the session
if (isset($_SESSION['aid'])) {
    // User is already logged in, redirect them to the dashboard or home page
    header('Location: dashboard.php'); // Replace 'dashboard.php' with the appropriate page
    exit();
}

if (isset($_POST['adminlogin'])) {
    if (empty($_POST['aemail']) || empty($_POST['apassword'])) {
        $error_message = "Both email and password are required";
    } else {
        $aemail = $_POST['aemail'];
        $apassword = $_POST['apassword'];

        // Query the database to check if the user exists
        $loginQuery = "SELECT * FROM admin WHERE aemail = '$aemail'";
        $result = $con->query($loginQuery);

        if (!$result) {
            die("Database error: " . $con->error); // Check for query errors
        }

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($apassword, $row['apassword'])) {
                // Password is correct, so create session variables
                $_SESSION['aid'] = $row['aid'];
                $_SESSION['aemail'] = $row['aemail'];
                $_SESSION['aname'] = $row['aname'];
                // $_SESSION['utype'] = $row['utype'];
                // $_SESSION['uphone'] = $row['uphone'];
                // $_SESSION['uimage'] = $row['uimage']; // Add this line to set the uimage session variable


                // Redirect to a protected page or the user's profile
                // header('Location: dashboard.php'); // Change 'dashboard.php' to your desired protected page
                header('Location: dashboard.php'); // Change 'dashboard.php' to your desired protected page

                exit();
            } else {
                $error_message = "Invalid password";
            }
        } else {
            $error_message = "User not found. Please register first.";
        }
    }
}
// echo "Stored email: " . $row['aemail'] . "<br>";
// echo "Entered email: " . $aemail . "<br>";
// echo "Stored Password: " . $row['apassword'] . "<br>";
// echo "Entered Password: " . $apassword . "<br>";

?>


<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
  include('includes/header.php');
// include('includes/nav.php');
?>
<section class="login-form">
  <div class="container pt-5">
    <h2 class="text-center">Admin Login</h2>
    <div class="row mt-4 mb-4">
      <div class="col-md-6 offset-md-3">
        <form action="" class="shadow-lg p-4" method="POST">
           
        <p><?php
                    if (isset($error_message)) {
                        echo $error_message;
                    }
?></p>

       
 
          <div class="form-group form">
          <input
    type="text"
    class="form-control form-input"
    name="aemail"
    id="aemail"
    autocomplete="on"
    placeholder=""
    value="<?php echo isset($_POST['aemail']) ? htmlspecialchars($_POST['aemail']) : ''; ?>"
/>

            <label for="aemail" class="form-label">
              <i class="fa-solid fa-envelope"></i>
              Email</label
            >
          </div>
          <div class="form-group form">
            <input
              type="password"
              class="form-control form-input"
              name="apassword"
              id="password"
              placeholder=""
            />
            <label for="apassword" class="form-label">
            <i class="fas fa-lock"></i>              Password</label
            >
          </div>
     
          <button
            type="submit"
            class="btn border-danger text-danger mt-3 btn-block shadow-sm font-weight-bold"
            name="adminlogin"
          >
            Login
          </button>

          <!-- <div class="text-center">

              <h6 class=" mt-5">Dont have an account?</h6>      
              <button
              class="btn-cta align-center"
              name="register"
              >
              <a class="text-white text-center" href="register.php">
                  
                  Sign Up
                </a>
            </button>  
        </div> -->
        </form>
      </div>
    </div>
  </div>
</section>


<?php include('../includes/footer.php')?>