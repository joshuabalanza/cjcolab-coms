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
    $sql = "UPDATE user SET uname = '$newName', uphone = '$newPhone', uimage = '$imagePath' WHERE uid = $uid";
    if (mysqli_query($con, $sql)) {
        $_SESSION['uname'] = $newName;
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

?>

<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
include('includes/header.php');
include('includes/nav.php');
?>
<section class="user-profile">
    <div class="container-fluid p-4 shadow mx-auto" style="max-width: 1000px;">
        <div class="row">
            <div class="col-sm-4 col-md-3">
                <form method="POST" enctype="multipart/form-data"> <!-- Add enctype attribute for file uploads -->
                <!-- Display user profile image if available -->
                <?php if (!empty($_SESSION['uimage'])): ?>
                    <img src="<?php echo $_SESSION['uimage']; ?>" class="border border-primary d-block mx-auto rounded-circle" style="width:150px; height:150px">
                <?php else: ?>
                    <img src="default-image.jpg" class="border border-primary d-block mx-auto rounded-circle" style="width:150px; height:150px">
                <?php endif; ?>              
                <h6 class="text-center"><?php echo $_SESSION['utype']; ?>#<?php echo $_SESSION['uid']; ?></h6>
                <h3 class="text-center"><?php echo $_SESSION['uname']; ?></h3> 
                <br>
                
                <div class="text-center">
                <div class="form-group">

    <label for="profileImage"></label>
    <input type="file" class="form-control-file" id="profileImage" name="profileImage" style="display: none;">
    <label for="profileImage" class="custom-file-upload">
        Change Photo
    </label>
</div>   
                </div>
            </div>
            <div class="col-sm-8 col-md-9 bg-light p-2">
 <div class="form-group">
                        <label for="newName">Name:</label>
                        <input type="text" class="form-control" id="newName" name="newName" value="<?php echo isset($_SESSION['uname']) ? $_SESSION['uname'] : ''; ?>">

                    </div>
                    <div class="form-group">
                        <label for="newPhone">Phone:</label>
                        <input type="text" class="form-control" id="newPhone" name="newPhone" value="<?php echo isset($_SESSION['uphone']) ? $_SESSION['uphone'] : ''; ?>">

                        <!-- <input type="text" class="form-control" id="newPhone" name="newPhone" value="<?php echo $_SESSION['uphone']; ?>"> -->
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
<?php include('includes/footer.php'); ?>
