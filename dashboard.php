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

// $mappp = $_SESSION['approved_concourse'];

// Check if there are approved concourse details in the session

// **********************
// ***USER VERIFY********
// **********************
// Check the status in the user_verification table
$verificationStatus = "Not approved"; // Default status
$verificationQuery = "SELECT status, first_name, last_name, address, gender, birthday FROM user_verification WHERE user_id = $uid";
$verificationResult = mysqli_query($con, $verificationQuery);

if ($verificationResult && mysqli_num_rows($verificationResult) > 0) {
    $verificationData = mysqli_fetch_assoc($verificationResult);
    $verificationStatus = $verificationData['status'];
}


// **********************
// ***MAP VERIFY*********
// **********************

// **************************************

$uploadDirectory = __DIR__ . '/uploads/';

$approvedMapQuery = "SELECT * FROM concourse_verification WHERE status = 'approved'";
$approvedMapResult = mysqli_query($con, $approvedMapQuery);

?>
<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
include('includes/header.php');

include('includes/nav.php');
?>
<section style= "margin-top:90px;">
   <?php
   //    echo 'Hi, ' . $_SESSION['uname'] . ' (' . $_SESSION['utype'] . ')';
   // echo $utype;
?>
   <div class="container-fluid">
      <!-- ********************************************************************** -->
      <!-- **** CTA BUTTON DISPLAY DEPENDING ON USER TYPE AND ACCOUNT STATUS **** -->
      <!-- ********************************************************************** -->
      <!-- OWNER -->
      <?php if ($verificationStatus === 'approved' && $utype === 'Owner'): ?>
      <h1>Dashboard</h1>
      <div>

1. total maps <br/>
2. total spaces/map?
 -total free/vacant spaces?
- total taken spaces?
</div>
<div>
<h6>pie chart?</h6>
3. total users <br/>
4. total user reservation/application?
</div>
<div>
<h6>pie chart?</h6>
5. total user assigned?<br/>
6. total bills? /montly reports?   
</div>
<div>

7. adding cancel reservations<br/>
8. montly revenue? based on rent bill? 
</div>
      <?php elseif ($verificationStatus === 'rejected' && $utype === 'Owner'): ?>
        <h1>Dashboard</h1>
      <div>

1. total maps <br/>
2. total spaces/map?
 -total free/vacant spaces?
- total taken spaces?
</div>
<div>
<h6>pie chart?</h6>
3. total users <br/>
4. total user reservation/application?
</div>
<div>
<h6>pie chart?</h6>
5. total user assigned?<br/>
6. total bills? /montly reports?   
</div>
<div>

7. adding cancel reservations<br/>
8. montly revenue? based on rent bill? 
</div>
      <div id="verificationModal" class="prompt-modal">
         <div class="modal-content">
            <span class="close">&times;</span>
            <p>Verify your account to add concourse.</p><!-- Will change this-->
            <a href="verification_account.php" class="btn-sm btn btn-success">Verify Account</a>
         </div>
      </div>
      <!-- TENANT -->
      <?php elseif ($verificationStatus === 'approved' && $utype === 'Tenant'): ?>
        <h1>Dashboard</h1>
      <div>

1. total space? <br/>
2. total bills??
</div>
<div>
<h6></h6>
3. Requirements? <br/>

</div>
<div>


      <!-- <a href="tenant-apply-space.php">
      <button class="btn-sm btn btn-success">Apply For Space</button>
      </a> -->
      <?php else: ?>
      <div id="verificationModal" class="prompt-modal">
         <div class="modal-content">
            <span class="close">&times;</span>
            <p>Verify your account to apply for space.</p> <!-- Will change this-->
            <a href="verification_account.php" class="btn-sm btn btn-success">Verify Account</a>
         </div>
      </div>
      <?php endif; ?>
  


  



</section>

<?php include('includes/footer.php'); ?>