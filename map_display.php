<!-- ******************** -->
<!-- ***START SESSION**** -->
<!-- ******************** -->
<?php
// space area(sqft) width& height
// space rent bill
// space windows
// space Electrical outlets/ wall plug
// lights

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
if (isset($_GET['concourse_id'])) {
    $concourse_id = $_GET['concourse_id'];

    // Query the database to fetch the detailed information for the selected concourse
    $concourseQuery = "SELECT * FROM concourse_verification WHERE concourse_id = $concourse_id";
    $concourseResult = mysqli_query($con, $concourseQuery);


} else {
    echo 'Concourse ID not provided.';
}
?>


    <!-- ******************** -->
    <!-- **** START HTML **** -->
    <!-- ******************** -->
<?php
include('includes/header.php');
include('includes/nav.php');
?>
<?php
if ($concourseResult && mysqli_num_rows($concourseResult) > 0) {
    $concourseData = mysqli_fetch_assoc($concourseResult);

    echo '<h3>Concourse Map</h3>';
    echo '<div style ="">';
    echo '<img src="/COMS/uploads/' . $concourseData['concourse_map'] . '" alt="Concourse Map">';
    echo '</div>';
// You can add more details or customize the map display as needed
} else {
    echo 'Concourse not found.';
}
?>

<!-- Your HTML and display code goes here -->
<?php include('includes/footer.php'); ?>
