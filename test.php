<?php

session_name("user_session");
session_start();
require('includes/dbconnection.php');
$uid = $_SESSION['uid'];

$notificationsQuery = "SELECT * FROM `notifications`";
// $notificationsQuery = "SELECT notification_id, notification_type, message, timestamp FROM notifications WHERE user_id = $uid ORDER BY timestamp DESC";

$notificationsResult = mysqli_query($con, $notificationsQuery);

while ($notification = mysqli_fetch_assoc($notificationsResult)) {
    echo '<tr>';
    echo '<td>' . $notification['notification_id'] . '</td>';
    echo '<td>' . $notification['user_id'] . '</td>';
    echo '<td>' . $notification['subject'] . '</td>';
    echo '<td>' . $notification['message'] . '</td>';
    echo '<td>' . $notification['status'] . '</td>';
    // echo '<td>' . $notification['message'] . '</td>';
    // echo '<td>' . $notification['timestamp'] . '</td>';
    // echo '<td>' . $notification['notification_id'] . '</td>';
    // echo '<td>';
    // echo '<a href="read_notification.php?id=' . $notification['notification_id'] . '">Read</a>';
    echo ' | ';
    // echo '<a href="delete_notification.php?id=' . $notification['id'] . '">Delete</a>';
    // echo '</td>';
    echo '</tr>';
}
echo'hello';
