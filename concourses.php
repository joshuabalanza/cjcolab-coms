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

              echo '<div class="row">';
              while ($mapData = mysqli_fetch_assoc($checkApprovedMapsResult)) {
                  echo '<div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-4">';
                  echo '<div class="card">';
                  echo '<a href="concourse_configuration.php?concourse_id=' . $mapData['concourse_id'] . '">';
                  echo '<div class="image-container">';
                  echo '<img src="/COMS/uploads/' . $mapData['concourse_map'] . '" class="card-img-top" style="width:100%; height: 300px;" alt="Concourse Map">';
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
              echo '<div class="col-lg-12">';
              echo '<p>No approved maps found.</p>';
              echo '</div>';
          }
echo '</div>';
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
   <div class="container-fluid">
      <h3>Concourses</h3>
      <div id="concourse-list" class="row">
         <!-- This div will be populated with the fetched data -->
      </div>
   </div>
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
         <h2>Add a Concourse</h2>
         <!-- <form id="concourseForm" method="POST" action="verification_concourse_process.php"> -->
         <form id="concourseForm" method="POST" action="verification_concourse_process.php" enctype="multipart/form-data">
            <label for="concourseName">Concourse Name:</label>
            <input type="text" id="concourseName" name="concourseName" required>
            <label for="concourseAddress">Concourse Address:</label>
            <input type="text" id="concourseAddress" name="concourseAddress" required>
            <label for="concourseMap">Concourse Map:</label>
            <input type="file" id="concourseMap" name="concourseMap" required>
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