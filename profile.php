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

$verificationStatus = ""; // Default status

$verificationQuery = "SELECT status, first_name, last_name, address, gender, birthday FROM user WHERE uid = $uid";
$verificationResult = mysqli_query($con, $verificationQuery);

if ($verificationResult && mysqli_num_rows($verificationResult) > 0) {
    $verificationData = mysqli_fetch_assoc($verificationResult);
    $verificationStatus = $verificationData['status'];
}

// // Add this code after checking verification status
// $notificationsQuery = "SELECT * FROM notifications WHERE user_id = $uid ORDER BY timestamp DESC";
// $notificationsResult = mysqli_query($con, $notificationsQuery);

// if ($notificationsResult && mysqli_num_rows($notificationsResult) > 0) {
//     echo '<div class="alert alert-info">';
//     while ($notification = mysqli_fetch_assoc($notificationsResult)) {
//         echo $notification['message'] . '<br>';
//     }
//     echo '</div>';
// }

// Fetch the "created_at" date from the database
$created_at = "Not available";

$userQuery = "SELECT created_at FROM user WHERE uid = $uid";
$userResult = mysqli_query($con, $userQuery);

if ($userResult) {
    $userRow = mysqli_fetch_assoc($userResult);
    $created_at = date("Y-m-d", strtotime($userRow['created_at']));
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
   .btn-sm:hover{
      background-color: #c19f90 !important;
   }
   h6, h3{
      color: white;
      opacity: 80%   ;
   }
   </style>
<section class="user-profiles">
   <div class="container-fluid p-4 shadow mx-auto" style="max-width: 1000px; margin-top: 10%; background-color: #b1765c; border-radius: 20px;">
      <div class="row">
         <div class="col-sm-4 col-md-3">
            <?php if (!empty($_SESSION['uimage'])): ?>
               <img src="<?php echo $_SESSION['uimage']; ?>" class="border border-primary d-block mx-auto rounded-circle" style="width: 150px; height: 150px; background-color: white;">
            <?php else: ?>
               <img src="default-image.jpg" class="border border-primary d-block mx-auto rounded-circle" style="width: 150px; height: 150px; background-color: white; opacity: 85%;">
            <?php endif; ?>
            <h6 class="text-center"><?php echo $_SESSION['utype']; ?>#<?php echo $_SESSION['uid']; ?></h6>
            <h3 class="text-center"><?php echo $_SESSION['username']; ?></h3>
            <br>
            <div class="text-center">
               <a href="profile_edit.php"><button class="btn-sm" style="background-color: #9b593c;">Edit Profile</button></a>
            </div>
         </div>
         <div class="col-sm-8 col-md-9 bg-light p-2"  style="border-radius: 10px; max-width: 600px; margin: 0 auto;">
            <table class="table" style="margin-top: 10px; margin-left: 10px; margin-right: 10px;">
               <tr>
                  <th>User Type:</th>
                  <td><?php echo $_SESSION['utype']; ?></td>
               </tr>
               <tr>
                  <th>Username:</th>
                  <td><?php echo $_SESSION['username']; ?></td>
               </tr>
               <tr>
                  <th>Email:</th>
                  <td><?php echo $_SESSION['uemail']; ?></td>
               </tr>
               <tr>
                  <th>Phone:</th>
                  <td><?php echo empty($_SESSION['uphone']) ? 'N/A' : $_SESSION['uphone']; ?></td>
               </tr>
               <tr>
                  <th>Date Created:</th>
                  <td><?php echo $created_at; ?></td>
               </tr>
               <tr>
                  <th>First Name:</th>
                  <td><?php echo $verificationData['first_name']; ?></td>
               </tr>
               <tr>
                  <th>Last Name:</th>
                  <td><?php echo $verificationData['last_name']; ?></td>
               </tr>
               <tr>
                  <th>Address</th>
                  <td><?php echo $verificationData['address']; ?></td>
               </tr>
               <tr>
                  <th>Gender:</th>
                  <td><?php echo $verificationData['gender']; ?></td>
               </tr>
               <tr>
                  <th>Birthday:</th>
                  <td><?php echo $verificationData['birthday']; ?></td>
               </tr>
               <!-- Add other verification fields as needed -->
     
            </table>
            <div class="col-sm-8 col-md-9 bg-light p-2" style="border-radius: 10px; max-width: 600px; margin: 0 auto;">
               <?php if ($verificationStatus === 'approved'): ?>
                  <!-- No message when the account is approved -->
               <?php elseif ($verificationStatus === 'rejected'): ?>
                  <p>Verification of account is rejected. Please fill up the form again:</p>
                  <form action="verification_account_process.php" method="post" enctype="multipart/form-data">
               <button type="submit" class="btn-sm" name="submit_verification" style="background-color: #9b593c;">Submit Verification</button>
               </form>
                  <?php elseif ($verificationStatus === 'pending'): ?>
                     <p>You have already submitted the verification. Please wait for approval.</p>
                  <?php else: ?>
                     <!-- <p>Please, verify your account.</p> -->
               <?php endif; ?>
            </div>
         </div>
      </div>
      <br>
   </div>
</section>
<?php include('includes/footer.php'); ?>