<!-- ******************** -->
<!-- ***** PHP CODE ***** -->
<!-- ******************** -->
<?php
session_name("user_session");
session_start();
require('includes/dbconnection.php');
// Include the modal code when the Notifications button is clicked
if (isset($_SESSION['uid'])) {
    echo '<script>$("#notificationsModal").modal("show");</script>';
}
?>

<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
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
</section>

<script>
// This function checks for new notifications and updates the bell icon.
function checkForNewNotifications() {
    // Use AJAX to check for new notifications.
    $.ajax({
        url: 'check_notifications.php', // Create a new PHP file for this purpose
        type: 'POST',
        data: { userId: <?php echo $_SESSION['uid']; ?> },
        success: function(response) {
            if (response === 'true') {
                // There are new notifications, update the bell icon.
                const bellIcon = document.querySelector('.fa-solid.fa-bell');
                bellIcon.classList.add('notification-icon');
            }
        }
    });
}

// Call the checkForNewNotifications function at regular intervals.
setInterval(checkForNewNotifications, 5000); // Check every 5 seconds (adjust the interval as needed)
</script>

<?php include('includes/footer.php'); ?>
