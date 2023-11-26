<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('includes/dbconnection.php');
?>

<?php
include('includes/header.php');
include('includes/nav.php');
?>
<div class="container-fluid">
  <div class="row">

    <section class="col-sm-10 py-5 dashboard">
        <h4>Bills</h4>
        
        <!-- Add a button to redirect to acc_create_accountant.php -->
        <div>
            <a href="acc_create_accountant.php" class="btn btn-primary">Create Accountant</a>
        </div>

    </section>
  </div>
</div>

<?php include('includes/footer.php'); ?>
