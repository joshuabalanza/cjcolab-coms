<?php
include('../includes/dbconnection.php');

session_name("admin_session");
session_start();?>

<?php
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

<?php include('includes/header.php');

include('includes/nav.php');
// include('includes/sidebar.php');
// include('users/user_verify.php');

?>


<!-- dashboard -->
<?php include('includes/footer.php')?>