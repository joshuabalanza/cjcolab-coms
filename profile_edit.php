<!-- ******************** -->
<!-- ***START SESSION**** -->
<!-- ******************** -->
<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
?>

<!-- ******************** -->
<!-- ***** PHP CODE ***** -->
<!-- ******************** -->
<?php
// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header('Location: login.php'); // Redirect to the login page if not logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $uid = $_SESSION['uid'];
    $newName = $_POST['newName'];
    $newPhone = $_POST['newPhone'];

    // Handle image upload
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === 0) {
        $imagePath = 'uploads/profile/' . $_FILES['profileImage']['name']; // Define the path where the image will be saved
        move_uploaded_file($_FILES['profileImage']['tmp_name'], $imagePath); // Move the uploaded image to the defined path
    } else {
        $imagePath = ''; // If no new image is uploaded, use the existing image path
    }

    // Update the user's name, phone, and profile image in the database
    $checkUsernameQuery = "SELECT username FROM user WHERE username = '$newName' AND uid != $uid";
    $result = mysqli_query($con, $checkUsernameQuery);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username '$newName' is already taken. Please choose a different username.";
    } else {
        // Update the user's name, phone, and profile image in the database
        $sql = "UPDATE user SET username = '$newName', uphone = '$newPhone', uimage = '$imagePath' WHERE uid = $uid";
        if (mysqli_query($con, $sql)) {
            $_SESSION['username'] = $newName;
            $_SESSION['uphone'] = $newPhone;
            if (!empty($imagePath)) {
                $_SESSION['uimage'] = $imagePath;
            }
            $message = "Profile updated successfully!";
            header('Location: profile.php'); // Redirect to the profile page

        } else {
            $error = "Error updating profile: " . mysqli_error($con);
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
    .change-btn{
        background-color: #9b593c;
    }
   .change-btn:hover{
        background-color: #c19f90 !important;
   }
   h6, h3{
        color: white;
        opacity: 80%   ;
   }
   .btn{
        margin-left: 10px;
        background-color: #9b593c;
        border: none;
   }
   .btn:hover{
        background-color: #c19f90;
   }
   
   .form {
        max-width: 400px; /* Adjust the max-width as needed */
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 15px;
    }
</style>
<section class="user-profile">
    <div class="container-fluid p-4 shadow mx-auto"  style="max-width: 1000px; margin-top: 10%; background-color: #b1765c; border-radius: 20px;">
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <form method="POST" enctype="multipart/form-data"> <!-- Add enctype attribute for file uploads -->
                <!-- Display user profile image if available -->
                <?php if (!empty($_SESSION['uimage'])): ?>
                    <img src="<?php echo $_SESSION['uimage']; ?>" class="border border-primary d-block mx-auto rounded-circle" style="width: 150px; height: 150px; background-color: white;">
                <?php else: ?>
                    <img src="default-image.jpg" class="border border-primary d-block mx-auto rounded-circle" style="width: 150px; height: 150px; background-color: white; opacity: 85   %;">
                <?php endif; ?>              
                <h6 class="text-center"><?php echo $_SESSION['utype']; ?>#<?php echo $_SESSION['uid']; ?></h6>
                <h3 class="text-center"><?php echo $_SESSION['username']; ?></h3> 
                <br>
                
                <div class="text-center">
                <div class="form-group">

                <label for="profileImage"></label>
                <input type="file" class="form-control-file" id="profileImage" name="profileImage" style="display: none;">
                <label for="profileImage" class="custom-file-upload change-btn">
                    Change Photo
                </label>
            </div>   
        </div>
    </div>
<div class="col-sm-8 col-md-9 bg-light p-2" style="border-radius: 10px; margin-top: 10px; margin-left: 125px; max-width: 400px;">
    <div class="form" style="margin-left: 10px; margin-right: 10px;">
        <div class="form-group">
            <label for="newName" style="font-weight: bold;">Username:</label>
            <input type="text" class="form-control" name="newName" id="newName" autocomplete="off" placeholder="Enter your username" required value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>">
            <div id="availability-message"></div>
        </div>
        <div class="form-group">
            <label for="newPhone" style="font-weight: bold;">Phone:</label>
            <input type="text" class="form-control" id="newPhone" name="newPhone" value="<?php echo isset($_SESSION['uphone']) ? $_SESSION['uphone'] : ''; ?>">

            <!-- <input type="text" class="form-control" id="newPhone" name="newPhone" value="<?php echo $_SESSION['uphone']; ?>"> -->
        </div>
    </div>
        <button type="submit" class="btn btn-primary"  style="float: right; margin-right: 10px; margin-top: 10px;">Save Changes</button>
            </form>
                <?php
                    if (isset($message)) {
                        echo "<div class='alert alert-success'>$message</div>";
                    }
                    if (isset($error)) {
                        echo "<div class='alert alert-danger'>$error</div>";
                    }
                ?>
            </div>
        </div>
    <br>
</div>
</section>
<script>
document.getElementById('newName').addEventListener('input', function () {
    const enteredUsername = this.value.trim();
    const availabilityMessage = document.getElementById('availability-message');

    // Remove any existing messages
    availabilityMessage.innerHTML = '';

    if (enteredUsername !== '') {
        const url = `check_username_availability.php?username=${enteredUsername}`;

        // Make an AJAX request to check username availability
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    // Display "Available" message
                    availabilityMessage.innerHTML = '<span style="color: green;">Username is available!</span>';
                } else {
                    availabilityMessage.innerHTML = '<span style="color: red;">Username is already taken. Please choose a different username.</span>';
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
</script>
<?php include('includes/footer.php'); ?>
