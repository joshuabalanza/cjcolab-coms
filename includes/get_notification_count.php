<?php

// session_name("user_session");
// session_start();
require('includes/dbconnection.php');

// if (isset($_SESSION['uid'])) {
//     $uid = $_SESSION['uid'];
//     $notificationsQuery = "SELECT COUNT(*) as count FROM notifications WHERE user_id = $uid AND active = 1";
//     $notificationsResult = mysqli_query($con, $notificationsQuery);
//     $notificationCount = 0;

//     if ($notificationsResult && mysqli_num_rows($notificationsResult) > 0) {
//         $row = mysqli_fetch_assoc($notificationsResult);
//         $notificationCount = $row['count'];
//     }

//     // echo $notificationCount;
// }
if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    $notificationsQuery = "SELECT COUNT(*) as count FROM notifications WHERE user_id = $uid AND active = 1";
    $notificationsResult = mysqli_query($con, $notificationsQuery);
    $notificationCount = 0;

    if ($notificationsResult && mysqli_num_rows($notificationsResult) > 0) {
        $row = mysqli_fetch_assoc($notificationsResult);
        $notificationCount = $row['count'];
    }

    // echo $notificationCount;
}
