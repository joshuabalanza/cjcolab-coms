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
    if (empty($_POST['ausername_or_aemail']) || empty($_POST['apassword'])) {
        $error_message = "Both email and password are required";
    } else {
        $ausername_or_aemail = $_POST['ausername_or_aemail'];
        // $uname = $_POST['uname'];
        $apassword = $_POST['apassword'];

        // Query the database to check if the user exists
        $is_ausername_or_aemail = filter_var($ausername_or_aemail, FILTER_VALIDATE_EMAIL);

        // Prepare the login query
        if ($is_ausername_or_aemail) {
            $loginQuery = "SELECT * FROM admin WHERE aemail = '$ausername_or_aemail'";
        } else {
            $loginQuery = "SELECT * FROM admin WHERE ausername = '$ausername_or_aemail'";
        }

        // Execute the query
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
                $_SESSION['ausername'] = $row['ausername'];
                $_SESSION['aimage'] = $row['aimage'];
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
  height: 4px;
  width: 30px;
  border-radius: 5px;
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
<div class="container">
  <div class="title">Admin Login</div>
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
          <span class="details">Email</span>
          <!-- <input
            type="text"
            name="aemail"
            id="aemail"
            autocomplete="on"
            placeholder="Enter your email"
            value="<?php echo isset($_POST['aemail']) ? htmlspecialchars($_POST['aemail']) : ''; ?>"
            required> -->
            <input 
            type="text" 
            class="form-control form-input" 
            name="ausername_or_aemail" 
            id="ausername_or_aemail" 
            autocomplete="on"
            placeholder="Enter your Email"
          value="<?php echo isset($_POST['ausername_or_aemail']) ? htmlspecialchars($_POST['ausername_or_aemail']) : ''; ?>"/>
        </div>

        <div class="input-box">
          <span class="details">Password</span>
          <!-- <input
            type="password"
            name="apassword"
            id="password"
            placeholder="Enter your password"
            required> -->
            <input 
            type="password" 
            class="form-control form-input"
             name="apassword" 
             id="password" 
             placeholder="Password  "/>
        </div>

        <div class="button">
          <input type="submit" name="adminlogin"> 
        </div>
      </div>

          <!-- <div class="text-center">
	@@ -129,11 +264,7 @@ class="btn-cta align-center"
                </a>
            </button>  
        </div> -->
    </form>
  </div>
</div>
<?php include('../includes/footer.php')?>
