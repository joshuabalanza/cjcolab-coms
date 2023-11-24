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
// Check if the user is logged in
if (!isset($_SESSION['aid'])) {
    header('Location: index.php');
    exit();
}

$aid = $_SESSION['aid'];

$adminDetailsQuery = "SELECT aid, aname, aemail, ausername, aimage FROM admin WHERE aid = $aid";
$adminDetailsResult = mysqli_query($con, $adminDetailsQuery);

if ($adminDetailsResult && mysqli_num_rows($adminDetailsResult) > 0) {
    $adminData = mysqli_fetch_assoc($adminDetailsResult);
} else {
    // Handle the case where admin details are not found
    header('Location: index.php');
    exit();
}

// Add additional code if needed, e.g., notifications for admins

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
<section class="admin-profiles">
   <div class="container-fluid p-4 shadow mx-auto" style="max-width: 1000px; margin-top: 10%; background-color: #9b593c; border-radius: 20px;">
      <div class="row">
         <div class="col-sm-4 col-md-3">
            <?php if (!empty($_SESSION['aimage'])): ?>
               <img src="<?php echo $_SESSION['aimage']; ?>" class="border border-primary d-block mx-auto rounded-circle" style="width: 150px; height: 150px; background-color: white;">
            <?php else: ?>
               <img src="default-image.jpg" class="border border-primary d-block mx-auto rounded-circle" style="width: 150px; height: 150px; background-color: white; opacity: 85%;">
            <?php endif; ?>
            <h6 class="text-center"><?php echo $adminData['aemail']; ?></h6>
            <h3 class="text-center"><?php echo $adminData['ausername']; ?></h3>
            <br>
            <div class="text-center">
               <a href="profile_edit.php"><button class="btn-sm" style="background-color: #b1765c;">Edit Profile</button></a>
               <!-- Add other buttons as needed -->
            </div>
         </div>
         <div class="col-sm-8 col-md-9 bg-light p-2"  style="border-radius: 10px; max-width: 600px; margin: 0 auto;">
            <table class="table" style="margin-top: 10px; margin-left: 10px; margin-right: 10px;">
               <tr>
                  <th>Admin ID:</th>
                  <td><?php echo $adminData['aid']; ?></td>
               </tr>
               <tr>
                  <th>Name:</th>
                  <td><?php echo $adminData['aname']; ?></td>
               </tr>
               <tr>
                  <th>Email:</th>
                  <td><?php echo $adminData['aemail']; ?></td>
               </tr>
               <tr>
                  <th>Username:</th>
                  <td><?php echo $adminData['ausername']; ?></td>
               </tr>
               <!-- Add other admin details as needed -->
            </table>
            <!-- Add other sections as needed, similar to the user profile page -->
         </div>
      </div>
      <br>
   </div>
</section>
<?php include('includes/footer.php'); ?>
