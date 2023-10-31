<?php

session_name("user_session");
session_start();
require('includes/dbconnection.php');

if (isset($_SESSION['uid']) && isset($_GET['id'])) {
    $notificationId = $_GET['id'];

    // Mark the notification as "read" (you might have a column like 'is_read' in your notifications table)
    $updateQuery = "UPDATE notifications SET active = 1 WHERE notification_id = $notificationId";
    mysqli_query($con, $updateQuery);

    header('Location: notifications.php');
    exit();
} else {
    // Handle unauthorized access or missing notification ID
    header('Location: notifications.php');
    exit();
}
