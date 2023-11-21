<!-- ******************** -->
<!-- ***START SESSION**** -->
<!-- ******************** -->
<?php
session_name("admin_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('../includes/dbconnection.php');
?>

<!-- ******************** -->
<!-- ***** PHP CODE ***** -->
<!-- ******************** -->
<?php
// Check if the admin is logged in
if (!isset($_SESSION['aid'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $aid = $_SESSION['aid'];
    $newAdminName = $_POST['newAdminName'];
    $newAdminEmail = $_POST['newAdminEmail'];

    // Handle image upload
    if (isset($_FILES['adminProfileImage']) && $_FILES['adminProfileImage']['error'] === 0) {
        $imagePath = '../uploads/profile/' . $_FILES['adminProfileImage']['name']; // Define the path where the image will be saved
        move_uploaded_file($_FILES['adminProfileImage']['tmp_name'], $imagePath); // Move the uploaded image to the defined path
    } else {
        $imagePath = ''; // If no new image is uploaded, use the existing image path
    }

    // Update the admin's name, email, and profile image in the database
    $checkAdminUsernameQuery = "SELECT ausername FROM admin WHERE ausername = '$newAdminName' AND aid != $aid";
    $result = mysqli_query($con, $checkAdminUsernameQuery);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username '$newAdminName' is already taken. Please choose a different username.";
    } else {
// Update the admin's name, email, and profile image in the database
$sql = "UPDATE admin SET ausername = '$newAdminName', aemail = '$newAdminEmail', aimage = '$imagePath' WHERE aid = $aid";
if (mysqli_query($con, $sql)) {
    // Update session variables
    $_SESSION['ausername'] = $newAdminName;
    $_SESSION['aemail'] = $newAdminEmail;
    if (!empty($imagePath)) {
        $_SESSION['aimage'] = $imagePath;
    }
    $message = "Profile updated successfully!";
    header('Location: profile.php'); // Redirect to the admin profile page
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
    .change-btn {
        background-color: #b1765c;
    }

    .change-btn:hover {
        background-color: #c19f90 !important;
    }

    h6,
    h3 {
        color: white;
        opacity: 80%;
    }

    .btn {
        margin-left: 10px;
        background-color: #b1765c;
        border: none;
    }

    .btn:hover {
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
<section class="admin-profile">
    <div class="container-fluid p-4 shadow mx-auto" style="max-width: 1000px; margin-top: 10%; background-color: #9b593c;  border-radius: 20px;">
        <div class="row">
            <div class="col-sm-4 col-md-3" style="margin-left: 80px; margin-top: 15px;">
                <form method="POST" enctype="multipart/form-data" style="background: none;"> <!-- Add enctype attribute for file uploads -->
                    <!-- Display admin profile image if available -->
                    <?php if (!empty($_SESSION['aimage'])): ?>
                        <img src="<?php echo $_SESSION['aimage']; ?>" class="border border-primary d-block mx-auto rounded-circle" style="width: 150px; height: 150px; background-color: white;">
                    <?php else: ?>
                        <img src="default-image.jpg" class="border border-primary d-block mx-auto rounded-circle" style="width: 150px; height: 150px; background-color: white; opacity: 85%;">
                    <?php endif; ?>
                    <h6 class="text-center"><?php echo isset($_SESSION['aemail']) ? $_SESSION['aemail'] : ''; ?></h6>
                    <h3 class="text-center"><?php echo isset($_SESSION['ausername']) ? $_SESSION['ausername'] : ''; ?></h3>

                    <br>
                    <div class="text-center">
                        <div class="form-group">
                            <label for="adminProfileImage"></label>
                            <input type="file" class="form-control-file" id="adminProfileImage" name="adminProfileImage" style="display: none;">
                            <label for="adminProfileImage" class="custom-file-upload change-btn">
                                Change Photo
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8 col-md-9 bg-light p-2" style="border-radius: 10px; margin-top: 15px; margin-left: 125px; max-width: 400px; margin-bottom: 20px;">
                    <div class="form" style="margin-left: 10px; margin-right: 10px;">
                        <div class="form-group">
                            <label for="newAdminName" style="font-weight: bold;">Username:</label>
                            <input type="text" class="form-control" name="newAdminName" id="newAdminName" autocomplete="off" placeholder="Enter your username" required value="<?php echo isset($_SESSION['ausername']) ? $_SESSION['ausername'] : ''; ?>">
                            <div id="availability-message"></div>
                        </div>
                        <div class="form-group">
                            <label for="newAdminEmail" style="font-weight: bold;">Email:</label>
                            <input type="email" class="form-control" id="newAdminEmail" name="newAdminEmail" value="<?php echo isset($_SESSION['aemail']) ? $_SESSION['aemail'] : ''; ?>">
                        </div>
                    </div>

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
            <div class="d-flex justify-content" style="margin-left: 620px;">
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Save Changes</button>
                <a href="profile.php" class="btn btn-secondary" style="margin-top: 10px;">Cancel</a>
            </div>
            <br>
        </form>
    </div>
</div>
</section>
<script>
document.getElementById('newAdminName').addEventListener('input', function () {
    const enteredAdminUsername = this.value.trim();
    const adminAvailabilityMessage = document.getElementById('availability-message');

    // Remove any existing messages
    adminAvailabilityMessage.innerHTML = '';

    // Check availability only if the entered username is different from the current username
    if (isset($_SESSION['ausername']) && enteredAdminUsername !== $_SESSION['ausername']) {
        const adminUrl = `check_admin_username_availability.php?username=${enteredAdminUsername}`;

        // Make an AJAX request to check admin username availability
        fetch(adminUrl)
            .then(response => response.json())
            .then(data => {
                if (data.available) {
                    // Display "Available" message
                    adminAvailabilityMessage.innerHTML = '<span style="color: green;">Username is available!</span>';
                } else {
                    adminAvailabilityMessage.innerHTML = '<span style="color: red;">Username is already taken. Please choose a different username.</span>';
                }
            })
            .catch(error => console.error('Error:', error));
    }
});
</script>
<?php include('includes/footer.php'); ?>
