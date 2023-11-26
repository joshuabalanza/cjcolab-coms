<?php
   session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
?>
<?php
if (!isset($_SESSION['act_id'])) {
    header('Location: acc_login.php');
    exit();
}
// $act_id = $_SESSION['act_id'];
// $query = "SELECT act_id FROM accountant WHERE act_id= '$act_id'";
// $result = mysqli_query($con, $query);

?>

<?php
include('includes/header.php');
include('includes/nav.php');
?>


<?php include('includes/footer.php'); ?>