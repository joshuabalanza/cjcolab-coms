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
    header('Location: login.php');
    exit();
}

$uid = $_SESSION['uid'];
$utype = $_SESSION['utype'];

// Check the status in the user_verification table
$verificationStatus = "Not approved"; // Default status

$verificationQuery = "SELECT status, first_name, last_name, address, gender, birthday FROM user_verification WHERE user_id = $uid";
$verificationResult = mysqli_query($con, $verificationQuery);

if ($verificationResult && mysqli_num_rows($verificationResult) > 0) {
    $verificationData = mysqli_fetch_assoc($verificationResult);
    $verificationStatus = $verificationData['status'];
}
?>

<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
include('includes/header.php');
// include('includes/nav.php');
?>
<?php                     echo 'Hi, ' . $_SESSION['uname'] . ' (' . $_SESSION['utype'] . ')';

echo $utype;
?>
 


    <div class="text-center">
               <!-- <a href="edit_profile.php"> <button class="btn-sm btn btn-success">Edit Profile</button> </a> -->
               <?php if ($verificationStatus === 'approved' && $utype === 'Owner'): ?>
                <a href="owner-add-concourse.php">
                    <button class="btn-sm btn btn-success">Add a Concourse</button>
                </a>
                <?php elseif ($verificationStatus === 'approved' && $utype === 'Tenant'): ?>
                    <a href="tenant-apply-space.php">
                    <button class="btn-sm btn btn-success">Apply For Space</button>
                </a>
               <?php else: ?>
                <!-- You need to verify your account -->

                <!-- ************************************ -->
                <!-- *******TO BE EDITED    ****** -->
                <!-- ************************************ -->

               <a href="account_verification.php">
               <button class="btn-sm btn btn-success">Verify Account to access concourses</button>
               </a>
               <?php endif; ?>
            </div>







<?php include('includes/footer.php'); ?>