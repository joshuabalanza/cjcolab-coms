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
$uimage = $_SESSION['uimage'];

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

$approvedMapQuery = "SELECT * FROM concourse_verification";
$approvedMapResult = mysqli_query($con, $approvedMapQuery);

?>
<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
include('includes/header.php');
include('includes/nav.php');
?>

<style>
   h3{
        color: #c19f90;
    }
    .btn{
        border: none;
        margin-bottom: 10px;
   }
    .btn:hover{
        background-color: #c19f90;
   }
</style>
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
      <?php if ($utype === 'Owner'): ?>
         
      <h5 style="text-align: center; color:white" class="pt-1">YOUR CONCOURSES</h5>
      <button id="openAddConcourseModal" class="btn-sm btn btn-success" style="display: block; margin: 0 auto; margin-bottom: 10px;">Add Concourse</button>
      <!-- <a href="concourse_add.php">
         <button class="btn-sm btn btn-success">Add a Concourse</button>
         </a> -->
         <?php
            $checkApprovedMapsQuery = "SELECT * FROM concourse_verification WHERE owner_id = '$uid'";
          $checkApprovedMapsResult = mysqli_query($con, $checkApprovedMapsQuery);

          if ($checkApprovedMapsResult && mysqli_num_rows($checkApprovedMapsResult) > 0) {

              echo '<div class="row">';
              while ($mapData = mysqli_fetch_assoc($checkApprovedMapsResult)) {
                  echo '<div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">';
                  echo '<div class="card" style="width: 100%; height: 100%; padding: 10px; margin: 0 auto;">';

                  echo '<a href="map_display.php?concourse_id=' . $mapData['concourse_id'] . '">';
                  echo '<div class="image-container">';
                  if (!empty($mapData['concourse_image'])) {
                      // Display the concourse_image if it exists
                      echo '<img src="./uploads/featured-concourse/' . $mapData['concourse_image'] . '" id="concourseImage" class="card-img-top smaller-image" alt="Concourse Image" style="width:100%; height: 300px;">';
                  } elseif (!empty($mapData['concourse_map'])) {
                      // Display the concourse_map if concourse_image is not available
                      echo '<img src="./uploads/' . $mapData['concourse_map'] . '" id="concourseImage" class="card-img-top smaller-image" alt="Concourse Map" style="width:100%; height: 300px;">';
                  } else {
                      // Handle the case when both concourse_image and concourse_map are empty, e.g., display a placeholder image
                      echo '<img src="path_to_placeholder_image.jpg" id="concourseImage" class="card-img-top smaller-image" alt="Placeholder Image" style="width:100%; height: 300px;">';
                  }
                  // echo '<img src="/COMS/uploads/' . $mapData['concourse_map'] . '" class="card-img-top" style="width:100%; height: 300px;" alt="Concourse Map">';
                  echo '</div>';
                  echo '</a>';
                  echo '<div class="card-body">';
                  echo '<h5 class="card-title">' . $mapData['concourse_name'] . '</h5>';
                  echo '<p class="card-text">Concourse ID: ' . $mapData['concourse_id'] . '</p>';
                  echo '<p class="card-text">Owner ID: ' . $mapData['owner_id'] . '</p>';
                  echo '<p class="card-text">Owner Name: ' . $mapData['owner_name'] . '</p>';
                  echo '</div>';
                  echo '</div>';
                  echo '</div>';
              }
          } else {
              echo '<div class="col-lg-12 text-center text-white mt-5">';
              echo '<p>No maps found yet.</p>';
              echo '</div>';
          }
echo '</div>';
?>

      <?php elseif ($verificationStatus !== 'rejected' && $utype === 'Owner'): ?>
      <div id="verificationModal" class="prompt-modal">
         <div class="modal-content">
            <span class="close">&times;</span>
            <p>Setup your account to add concourse.</p>
            <a href="verification_account.php" class="btn-sm btn btn-success">Verify Account</a>
         </div>
      </div>
      <!-- TENANT -->
      <?php elseif ($verificationStatus === 'approved' && $utype === 'Tenant'): ?>
      <!-- <a href="tenant-apply-space.php">
      <button class="btn-sm btn btn-success">Apply For Space</button>
      </a> -->
      <div class="container-fluid">
      <div class="pt-3"></div>
      <h5 style="text-align: center; color:#fff">CONCOURSES</h5>
      <div class="pt-3"></div>
      <div id="concourse-list" class="row" style="width: 80%; margin: 0 auto;">
         <!-- This div will be populated with the fetched data -->
      </div>
   </div>
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

   <div id="pagination" class="text-center"></div>


   <!-- <div id="pagination"></div> -->
   <!-- Pagination controls -->
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
         <h4>ADD A CONCOURSE</h4>
         <!-- <form id="concourseForm" method="POST" action="verification_concourse_process.php"> -->
         <form id="concourseForm" method="POST" action="verification_concourse_process.php" enctype="multipart/form-data">
            <label for="concourseName">Concourse Name:</label>
            <input type="text" id="concourseName" name="concourseName" required>
            <label for="concourseAddress">Concourse Address:</label>
            <input type="text" id="concourseAddress" name="concourseAddress" required>
            <label for="concourseMap">Concourse Map:</label>
            <input type="file" id="concourseMap" name="concourseMap" required>
            <!-- <label for="concourseSpaces">Spaces:</label> -->
            <!-- <textarea id="concourseSpaces" name="concourseSpaces" required></textarea> -->
            <!-- <input type="number" id="concourseSpaces" name="concourseSpaces" required> -->
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function loadConcourses(page) {
        $.ajax({
            type: 'GET',
            url: 'get_concourse.php',
            data: { page: page },
            success: function (data) {
                $('#concourse-list').html(data);
            }
        });
    }

    $(document).ready(function () {
        loadConcourses(1); // Load the first page by default

        // Pagination click event handler
        $(document).on('click', '.page-link', function (event) {
            event.preventDefault(); // Prevent the default link behavior
            var page = $(this).data('page');
            loadConcourses(page);
        });
    });
</script>
<?php include('includes/footer.php'); ?>