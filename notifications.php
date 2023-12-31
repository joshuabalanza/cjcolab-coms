<!-- ******************** -->
<!-- ***** PHP CODE ***** -->
<!-- ******************** -->
<?php
session_name("user_session");
session_start();
require('includes/dbconnection.php');
// Include the modal code when the Notifications button is clicked
if (isset($_SESSION['uid'])) {
    if (isset($_GET['action']) && isset($_GET['notification_id'])) {
        $action = $_GET['action'];
        $notification_id = $_GET['notification_id'];

        if ($action === 'read') {
            // Handle reading a notification
            $uid = $_SESSION['uid'];
            $readQuery = "SELECT * FROM notifications WHERE user_id = $uid AND notification_id = $notification_id";
            $readResult = mysqli_query($con, $readQuery);
            $notification = mysqli_fetch_assoc($readResult);

            if ($notification) {
                // para mabago din value sa database, hindi lang sa frontend
                $newActiveValue = $notification['active'] == 1 ? 0 : 1;

                // Mark the notification as read (set 'active' to 0)
                // $updateQuery = "UPDATE notifications SET active = 0 WHERE user_id = $uid AND notification_id = $notification_id";
                $updateQuery = "UPDATE notifications SET active = $newActiveValue WHERE user_id = $uid AND notification_id = $notification_id";

                $updateResult = mysqli_query($con, $updateQuery);

                if ($updateResult) {
                    // Display the notification content
                    echo '<h2>' . $notification['notification_type'] . '</h2>';
                    echo '<p>' . $notification['message'] . '</p>';

                    echo json_encode(['notification_type' => $notification['notification_type'], 'message' => $notification['message']]);
                }
            }
        } elseif ($action === 'delete') {
            // Handle deleting a notification
            $uid = $_SESSION['uid'];
            $deleteQuery = "DELETE FROM notifications WHERE user_id = $uid AND notification_id = $notification_id";
            $deleteResult = mysqli_query($con, $deleteQuery);

            if ($deleteResult) {
                // Redirect back to the notifications page after deleting
                header('Location: notifications.php');
            } else {
                echo 'Failed to delete the notification.';
            }
        }
    }

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
<section style= "margin-top:90px;">
    <div class="container">
        <h2 style="color: #c19f90;">Notifications</h2>
        <table class="table table-hover table-bordered">
            <thead style="background-color: #c19f90;">
                <tr>
                    <th>From</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th class= "action-cell">Actions</th>
                </tr>
            </thead>
            <tbody style="background-color: #fff;">
                <?php
                // Fetch and display notifications from the database
                $uid = $_SESSION['uid'];
                $notificationsQuery = "SELECT * FROM notifications WHERE user_id = $uid ORDER BY timestamp DESC";
                // $notificationsQuery = "SELECT notification_id, notification_type, message, timestamp, from_user FROM notifications WHERE user_id = $uid ORDER BY timestamp DESC";

                // $notificationsQuery = "SELECT notification_id, notification_type, message, timestamp FROM notifications WHERE user_id = $uid ORDER BY timestamp DESC";

                $notificationsResult = mysqli_query($con, $notificationsQuery);

                while ($notification = mysqli_fetch_assoc($notificationsResult)) {
                    echo '<tr class="' . ($notification['active'] ? 'bold' : '') . '">';
                    echo '<td>' . $notification['from_user'] . '</td>';

                    echo '<td>' . $notification['notification_type'] . '</td>';
                    echo '<td>' . $notification['message'] . '</td>';
                    // echo '<td>' . $notification['timestamp'] . '</td>';
                    echo '<td>' . date('M, d Y', strtotime($notification['timestamp'])) . '</td>';

                    echo '<td>' . date('h:i A', strtotime($notification['timestamp'])) . '</td>';

                    // echo '<td class = "action-cell">';
                    // echo '<a href="#" class="read-notification" data-notification-id="' . $notification['notification_id'] . '"><i class="fa-solid fa-envelope"></i></a>';
                    // echo ' <span class="action-divider"></span> ';
                    // echo '<a href="#" class="delete-notification" data-notification-id="' . $notification['notification_id'] . '"><i class="fa-solid fa-trash-can"></i></a>';

                    echo '<td class="action-cell">';
                    if ($notification['active']) {
                        echo '<a href="#" class="read-notification active" data-notification-id="' . $notification['notification_id'] . '"><i class="fa-solid fa-envelope"></i></a>';
                    } else {
                        echo '<a href="#" class="read-notification inactive" data-notification-id="' . $notification['notification_id'] . '"><i class="fa-solid fa-envelope-open"></i></a>';
                    }
                    echo ' <span class="action-divider"></span>';
                    echo '<a href="#" class="delete-notification" data-notification-id="' . $notification['notification_id'] . '"><i class="fa-solid fa-trash-can"></i></a>';
                    echo '</td>';


                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</section>

<?php include('includes/footer.php'); ?>

