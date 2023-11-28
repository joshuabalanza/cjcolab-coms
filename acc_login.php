<?php
// Initialize the session
session_name("user_session");
session_start();

// Check if the user is already logged in, if yes then redirect him to the welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: acc_dashboard.php");
  exit;
}

// Include config file
require_once "includes/dbconnection.php";

// Define variables and initialize with empty values
$acusername_or_acemail = $acpassword = "";
$acusername_or_acemail_err = $acpassword_err = $login_err = "";

if(isset($_POST['acclogin_btn'])){
  // Processing form data when the form is submitted
  if($_SERVER["REQUEST_METHOD"] == "POST"){
   
      // Check if acusername_or_acemail is empty
      if(empty(trim($_POST["acusername_or_acemail"]))){
          $acusername_or_acemail_err = "Please enter username or email.";
      } else{
          $acusername_or_acemail = trim($_POST["acusername_or_acemail"]);
      }
      
      // Check if password is empty
      if(empty(trim($_POST["acpassword"]))){
          $acpassword_err = "Please enter your acpassword.";
      } else{
          $acpassword = trim($_POST["acpassword"]);
      }
      
      // Valacidate credentials
      if(empty($acusername_or_acemail_err) && empty($acpassword_err)){
          // Determine if the input is a valid email
          $is_acemail = filter_var($acusername_or_acemail, FILTER_VALIDATE_EMAIL);
          
          // Prepare the login query
          if ($is_acemail) {
              $sql = "SELECT act_id, acname, acusername, acemail, acpassword FROM accountant WHERE acemail = ? and actype = 'accountant'";
          } else {
             $sql = "SELECT act_id, acname, acusername, acemail, acpassword FROM accountant WHERE acusername = ? and actype = 'accountant'";
          }
          
          if($stmt = $con->prepare($sql)){
              // Bind variables to the prepared statement as parameters
              $stmt->bind_param("s", $param_acusername_or_acemail);
              
              // Set parameters
              $param_acusername_or_acemail = $acusername_or_acemail;
              
              // Attempt to execute the prepared statement
              if($stmt->execute()){
                  // Store result
                  $stmt->store_result();
                  
                  // Check if username_or_acemail exists, if yes then verify password
                  if($stmt->num_rows == 1){                    
                      // Bind result variables
                      $stmt->bind_result($acid, $acname, $acusername, $acemail, $hashed_acpassword);
                      if($stmt->fetch()){
                          if(password_verify($acpassword, $hashed_acpassword)){
                              // acPassword is correct, so start a new session
                              session_start();

                              // Store data in session variables
                              $_SESSION["loggedin"] = true;
                              $_SESSION["act_id"] = $acid;
                              $_SESSION["acname"] = $acname;
                              $_SESSION["acusername"] = $acusername;
                              $_SESSION["actype"] = 'accountant';                            
                              
                              // Redirect user to welcome page
                              header("location: acc_dashboard.php");
                          } else{
                              // acPassword is not valacid, display a generic error message
                              $login_err = "Invalid username or password.";
                          }
                      }
                  } else{
                      // acUsername or acemail doesn't exist, display a generic error message
                      $login_err = "Invalid username or acemail or password.";
                  }
              } else{
                  echo "Oops! Something went wrong. Please try again later.";
              }
  
              // Close statement
              $stmt->close();
          }
      }
  }  
  // Close connection
  $con->close();
}
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
  .error-message {
        color: red;
    }
</style>
<div class="container">
    <div class="title">Login</div>
    <div class="content">
        <form action="" method="POST">
            <div class="user-details">  
                <div class="input-box">
                    <span class="details">Username Or Email</span>
                    <input 
                        type="text" 
                        name="acusername_or_acemail" 
                        id="acemail"
                        autocomplete="off"
                        placeholder="Enter your username or email" 
                        required>
                </div>

                <div class="input-box">
                    <span class="details">Password</span>
                    <input 
                        type="password"
                        name="acpassword"
                        id="acpassword"
                        placeholder="Enter your password" 
                        required>
                </div>
            </div>

            <div class="button">
                <input type="submit" name="acclogin_btn"> 
            </div>
        </form>
    </div>
</div>
<?php include('includes/footer.php')?>