<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
?>
<?php

if (isset($_POST['register']) &&  isset($_SESSION['uid']) && $_SESSION['utype'] == 'owner' ){
    if (empty($_POST['acname']) || empty($_POST['acemail']) || empty($_POST['acpassword']) || empty($_POST['acusername'])) {
        $error_message = "<span style='color: red;'>All fields are required</span>";
    } else {
        $ownerId = $_SESSION['uid'];
        $acname = $_POST['acname'];
        $acemail = $_POST['acemail'];
        $acusername = $_POST['acusername'];
        $acpassword = $_POST['acpassword'];
        $confirm_password = $_POST['confirm_password'];
       
        $usertype = 'accountant'; 

        if ($acpassword !== $confirm_password) {
            $error_message = "<span style='color: red;'>Passwords do not match. Please confirm your password properly.</span>";

            // Retain the values entered by the user except for the password
            $acname_value = htmlspecialchars($acname);
            $acemail_value = htmlspecialchars($acemail);
            $acusername_value = htmlspecialchars($acusername);
        } else {
            // Check if the email is already registered
            $emailExists = false;
            $emailNotVerified = false;

            $checkEmailQuery = "SELECT acemail FROM accountant WHERE acemail = '$acemail'";
            $result = $con->query($checkEmailQuery);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                $emailExists = true;
            }

            // Check if the acusername is already taken
            $acusernameExists = false;
            $checkacUsernameQuery = "SELECT acusername FROM accountant WHERE acusername = '$acusername'";
            $result = $con->query($checkacUsernameQuery);

            if ($result->num_rows > 0) {
                $acusernameExists = true;
            }

            if ($emailExists) {
                $error_message = "<span style='color: red;'>Email is already registered. Please use a different email.</span>";
            } elseif ($acusernameExists) {
                $error_message = "<span style='color: red;'>acUsername is already taken. Please choose a different acusername.</span>";
            } else {
                $hashedPassword = password_hash($acpassword, PASSWORD_DEFAULT);

                $created_at = date("Y-m-d H:i:s");
                // Insert data into the database
// Prepare and bind the SQL statement
$sql = "INSERT INTO accountant (acname, acemail, acusername, acpassword, actype, owner_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("sssssis", $acname, $acemail, $acusername, $hashedPassword, $usertype, $ownerId, $created_at);

// Execute the statement
$stmt->execute();

// Close the statement
$stmt->close();
                // Display the modal
                echo '<script>openModal();</script>';
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
    /* Style for the modal and close button */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
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
        <span for="acname" class="details">Full Name</span>
        <input type="text" name="acname" id="acname" autocomplete="off" placeholder="Enter your name" required value="<?php echo isset($acname_value) ? $acname_value : ''; ?>">
    </div>

    <div class="input-box">
        <span for="acemail" class="details">Email</span>
        <input type="text" name="acemail" id="acemail" autocomplete="off" placeholder="Enter your email" required value="<?php echo isset($acemail_value) ? $acemail_value : ''; ?>">
    </div>

    <div class="input-box">
        <span for="acpassword" class="details">Password</span>
        <input type="password" name="acpassword" id="acpassword" placeholder="Enter your password" required>
    </div>

    <div class="input-box">
        <span for="confirm_password" class="details">Confirm Password</span>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm your password" required>
    </div>

    <div class="input-box">
        <span for="username" class="details">Username</span>
        <input type="text" name="acusername" id="acusername" autocomplete="off" placeholder="Enter your acusername" required value="<?php echo isset($acusername_value) ? $acusername_value : ''; ?>">
        <!-- Suggested acusername based on the entered full name -->
        <div class="suggested-acusername" id="suggested-acusername"></div>
    </div>
</div>
<input type="hidden" name="owner_id" value="<?php echo $ownerId; ?>">
<input type="hidden" name="usertype" value="<?php echo $usertype; ?>">
            <!-- <div class="use-details">
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
            </div> -->

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
<div id="accountCreatedModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Account created successfully. Please advise your accountant to verify their account.</p>
    </div>
</div>

<script>
document.getElementById('acusername').addEventListener('input', function () {
    const enteredacUsername = this.value.trim();
    const suggestedacUsername = document.getElementById('suggested-acusername');

    // Remove any existing suggestions
    suggestedacUsername.innerHTML = '';

    if (enteredacUsername !== '') {
        const url = `check_acusername_availability.php?acusername=${enteredacUsername}`;

        // Make an AJAX request to check acusername availability
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    // Display "Available" message
                    suggestedacUsername.innerHTML = '<span style="color: green;">acUsername is available!</span>';
                } else {
                    // Get alternative suggestions if acusername is taken
                    fetch(`get_alternative_acusernames.php?acusername=${enteredacUsername}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.suggestions.length > 0) {
                                const suggestionsList = data.suggestions.map(suggestion => {
                                    return `<a href="javascript:void(0);" onclick="fillacUsername('${suggestion}')">${suggestion}</a>`;
                                }).join(', ');
                                suggestedacUsername.innerHTML = `<span style="color: red;">acUsername is already taken. Try one of the following:</span><br>${suggestionsList}`;
                            } else {
                                suggestedacUsername.innerHTML = '<span style="color: red;">acUsername is already taken.</span>';
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
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
        // Function to display the modal
        function openModal() {
        document.getElementById('accountCreatedModal').style.display = 'block';
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('accountCreatedModal').style.display = 'none';
    }
</script>

<?php include('includes/footer.php'); ?>