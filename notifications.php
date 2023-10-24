<?php
session_name("user_session");
session_start();
require('includes/dbconnection.php');
include('includes/header.php');
include('includes/nav.php');
// Include the modal code when the Notifications button is clicked
if (isset($_SESSION['uid'])) {
    echo '<script>$("#notificationsModal").modal("show");</script>';
}
?>


<div class="container">
    <h2>Notifications</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Notification</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch and display notifications from the database
            $uid = $_SESSION['uid'];
$notificationsQuery = "SELECT * FROM notifications WHERE user_id = $uid ORDER BY timestamp DESC";
$notificationsResult = mysqli_query($con, $notificationsQuery);

while ($notification = mysqli_fetch_assoc($notificationsResult)) {
    echo '<tr>';
    echo '<td>' . $notification['timestamp'] . '</td>';
    echo '<td>' . $notification['message'] . '</td>';
    echo '</tr>';
}
?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
