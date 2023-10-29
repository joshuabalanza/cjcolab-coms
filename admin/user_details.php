<?php
session_name("admin_session");
session_start();
include('../includes/dbconnection.php');

?>



<?php


?>

<?php
include('includes/header.php');
include('includes/nav.php');
// include('users/user_verify.php');

?>
<div class="container-fluid">
  <div class="row">
<?php include('includes/sidebar.php');?>

<section class="col-sm-10 py-5 dashboard">
    <!-- <div class="dashboard"> -->
<h4>
    User Details

</h4>
    
    <!-- </div> -->
    </section>
</div>
</div>
<?php include('includes/footer.php')?>