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
      <h3>Your Concourse</h3>
      <button id="openAddConcourseModal" class="btn-sm btn btn-success">Add a Concourse</button>
      <!-- <a href="concourse_add.php">
         <button class="btn-sm btn btn-success">Add a Concourse</button>
         </a> -->
         <?php

$checkApprovedMapsQuery = "SELECT * FROM concourse_verification WHERE owner_id = '$uid' AND status = 'approved'";
          $checkApprovedMapsResult = mysqli_query($con, $checkApprovedMapsQuery);

          if ($checkApprovedMapsResult && mysqli_num_rows($checkApprovedMapsResult) > 0) {
              //   echo '<h3>Your Concourse</h3>';
              echo '<div class="card-deck">';
              while ($mapData = mysqli_fetch_assoc($checkApprovedMapsResult)) {
                  echo '<div class="card">';
                  echo '<img src="/COMS/uploads/' . $mapData['concourse_map'] . '" class="card-img-top" alt="Concourse Map">';
                  echo '<div class="card-body">';
                  echo '<h5 class="card-title">' . $mapData['concourse_name'] . '</h5>';
                  echo '<p class="card-text">Concourse ID: ' . $mapData['concourse_id'] . '</p>';
                  echo '<p class="card-text">Owner ID: ' . $mapData['owner_id'] . '</p>';
                  echo '<p class="card-text">Owner Name: ' . $mapData['owner_name'] . '</p>';
                  echo '</div>';
                  echo '</div>';
              }
              echo '</div>';
          } else {
              echo 'No approved maps found.';
          }
?>
      <?php elseif ($verificationStatus === 'rejected' && $utype === 'Owner'): ?>
      <div id="verificationModal" class="prompt-modal">
         <div class="modal-content">
            <span class="close">&times;</span>
            <p>Verify your account to add concourse.</p>
            <a href="verification_account.php" class="btn-sm btn btn-success">Verify Account</a>
         </div>
      </div>
      <!-- TENANT -->
      <?php elseif ($verificationStatus === 'approved' && $utype === 'Tenant'): ?>
      <a href="tenant-apply-space.php">
      <button class="btn-sm btn btn-success">Apply For Space</button>
      </a>
      <?php else: ?>
      <div id="verificationModal" class="prompt-modal">
         <div class="modal-content">
            <span class="close">&times;</span>
            <p>Verify your account to apply for space.</p>
            <a href="verification_account.php" class="btn-sm btn btn-success">Verify Account</a>
         </div>
      </div>
      <?php endif; ?>
  



  
   
 
   </div>

   <!-- **************************************** -->
   <!-- ******DISPLAYED FEATURED CONCOURSE****** -->
   <!-- **************************************** -->
   <div class= "container-fluid">
      <h3>Concourses</h3>


      <?php
    // Query to retrieve approved concourses
    $approvedConcoursesQuery = "SELECT * FROM concourse_verification WHERE status = 'approved' ORDER BY `concourse_verification`.`concourse_id` DESC";
$approvedConcoursesResult = mysqli_query($con, $approvedConcoursesQuery);

if ($approvedConcoursesResult && mysqli_num_rows($approvedConcoursesResult) > 0) {
    echo '<div class="card-deck">';

    while ($concourseData = mysqli_fetch_assoc($approvedConcoursesResult)) {
        echo '<div class="card">';
        echo '<img src="/COMS/uploads/' . $concourseData['concourse_map'] . '" class="card-img-top" alt="Concourse Map">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $concourseData['concourse_name'] . '</h5>';
        echo '<p class="card-text">Concourse ID: ' . $concourseData['concourse_id'] . '</p>';
        echo '<p class="card-text">Owner ID: ' . $concourseData['owner_id'] . '</p>';
        echo '<p class="card-text">Owner Name: ' . $concourseData['owner_name'] . '</p>';
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo 'No approved concourses found.';
}
?>
   </div>


   <!-- **************************************** -->
   <!-- *****END DISPLAYED FEATURED CONCOURSE*** -->
   <!-- **************************************** -->

   <!-- //////////////////////////////////////// -->

   <!-- **************************************** -->
   <!-- ********ADD CONCOURSE MODAL************* -->
   <!-- **************************************** -->
   <div id="addConcourseModal" class="modal">
      <div class="modal-content">
         <span class="close" id="closeAddConcourseModal">&times;</span>
         <h2>Add a Concourse</h2>
         <!-- <form id="concourseForm" method="POST" action="verification_concourse_process.php"> -->
         <form id="concourseForm" method="POST" action="verification_concourse_process.php" enctype="multipart/form-data">

            <label for="concourseName">Concourse Name:</label>
            <input type="text" id="concourseName" name="concourseName" required>
            <label for="concourseAddress">Concourse Address:</label>
            <input type="text" id="concourseAddress" name="concourseAddress" required>
            <label for="concourseImage">Concourse Image:</label>
            <input type="file" id="concourseImage" name="concourseImage" required>
            <label for="concourseSpaces">Spaces:</label>
            <!-- <textarea id="concourseSpaces" name="concourseSpaces" required></textarea> -->
            <input type="number" id="concourseSpaces" name="concourseSpaces" required>
            <button type="submit" class="btn btn-success"name="submit_concourse" >Submit</button>
         </form>
      </div>
   </div>
   <!-- **************************************** -->
   <!-- ********END OF ADD CONCOURSE MODAL****** -->
   <!-- **************************************** -->
</section>
<?php
// echo $_SESSION['uemail'];
// echo $_SESSION['approved_concourse'];
?>
<?php include('includes/footer.php'); ?>