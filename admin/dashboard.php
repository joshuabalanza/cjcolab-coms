<?php
include('../includes/dbconnection.php');

session_name("admin_session");
session_start();?>

<?php


if (!isset($_SESSION['aid'])) {
    header('Location: index.php');
    exit();
}
// $sql = "SELECT * FROM user"; // Replace 'user' with your user table name
// $result = mysqli_query($con, $sql);

// if ($result) {
//     // Display a table to list user profiles
//     echo '<table class="table table-hover table-striped table-bordered">';
//     echo '<tr><th>User ID</th><th>Name</th><th>Email</th><th>User Type</th><th>Phone</th><th>Date Created</th></tr>';

//     while ($row = mysqli_fetch_assoc($result)) {
//         echo '<tr>';
//         echo '<td>' . $row['uid'] . '</td>';
//         echo '<td>' . $row['uname'] . '</td>';
//         echo '<td>' . $row['uemail'] . '</td>';
//         echo '<td>' . $row['utype'] . '</td>';
//         echo '<td>' . ($row['uphone'] ? $row['uphone'] : 'N/A') . '</td>';
//         echo '<td>' . date("Y-m-d", strtotime($row['created_at'])) . '</td>';
//         echo '</tr>';
//     }

//     echo '</table>';
// } else {
//     echo "Error: " . mysqli_error($con);
// }

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
    Dashboard

</h4>
<div>

    1. total owners <br/>
    2. total tenants
</div>
<div>
<h6>pie chart</h6>
    3. total users?<br/>
    4. total verified users
</div>
<div>
<h6>pie chart?</h6>
    5. total no of map applications(all maps that are not verified)<br/>
    6. total no of maps verified     
</div>
<div>

    7. rents?<br/>
    8. total spaces    
</div>
<div>

    9. <strong>Bills?</strong><br/>
    10. <strong>Commission?</strong><br/>

</div>
    <!-- </div> -->
    </section>
</div>
</div>

<?php include('includes/footer.php')?>