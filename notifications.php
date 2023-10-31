<!-- ******************** -->
<!-- ***** PHP CODE ***** -->
<!-- ******************** -->
<?php
session_name("user_session");
session_start();
require('includes/dbconnection.php');
// Include the modal code when the Notifications button is clicked
if (isset($_SESSION['uid'])) {


}


?>

<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<!-- **** START HTML **** -->
<?php
include('includes/header.php');
include('includes/nav.php');
?>
<section class="notification-section">
    <div class="container">
        <h2>Notifications</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch and display notifications from the database
                $uid = $_SESSION['uid'];
$notificationsQuery = "SELECT * FROM notifications WHERE user_id = $uid ORDER BY timestamp DESC";
// $notificationsQuery = "SELECT notification_id, notification_type, message, timestamp FROM notifications WHERE user_id = $uid ORDER BY timestamp DESC";

$notificationsResult = mysqli_query($con, $notificationsQuery);

while ($notification = mysqli_fetch_assoc($notificationsResult)) {
    echo '<tr>';
    echo '<td>' . $notification['notification_type'] . '</td>';
    echo '<td>' . $notification['message'] . '</td>';
    echo '<td>' . $notification['timestamp'] . '</td>';
    echo '<td>';
    echo '<a href="read_notification.php?id=' . $notification['notification_id'] . '">Read</a>';
    echo ' | ';
    echo '<a href="delete_notification.php?id=' . $notification['notification_id'] . '">Delete</a>';
    echo '</td>';
    echo '</tr>';
}
?>
            </tbody>
        </table>
    </div>
</section>

<?php include('includes/footer.php'); ?>

