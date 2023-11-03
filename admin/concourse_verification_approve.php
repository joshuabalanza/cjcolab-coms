<!-- ******************** -->
<!-- ***START SESSION**** -->
<!-- ******************** -->
<?php
session_name("admin_session");
session_start();
require('../includes/dbconnection.php');
?>


<!-- ******************** -->
<!-- ***** PHP CODE ***** -->
<!-- ******************** -->
<?php
// Check if the user is logged in
if (!isset($_SESSION['aid'])) {
    header('Location: dashboard.php'); // Redirect to the admin login page
    exit();
}

if (isset($_GET['id'])) {
    $concourse_id = $_GET['id'];


    // Check if a verification entry with this ID exists
    $check_existing_query = "SELECT * FROM concourse_verification WHERE concourse_id = '$concourse_id'";
    $check_existing_result = mysqli_query($con, $check_existing_query);

    if (mysqli_num_rows($check_existing_result) > 0) {
        // Update the status for an existing verification entry
        $update_query = "UPDATE concourse_verification SET status = 'approved' WHERE concourse_id = '$concourse_id'";

        if (mysqli_query($con, $update_query)) {
            // Retrieve the user_id from the user_verification record
            $userVerificationQuery = "SELECT owner_id FROM concourse_verification WHERE concourse_id = '$concourse_id'";
            $userVerificationResult = mysqli_query($con, $userVerificationQuery);

            if ($userVerificationResult && $userVerificationRow = mysqli_fetch_assoc($userVerificationResult)) {
                $uid = $userVerificationRow['owner_id'];

                // Insert a notification for the user
                $notificationFrom =  $_SESSION['aname'];
                $notificationStatus = 1;
                $notificationType = 'MAP APPLICATION';
                $notificationMessage = 'Your Concourse application has been approved.';
                $insertNotificationQuery = "INSERT INTO notifications (from_user, user_id, message, notification_type,active) VALUES ('$notificationFrom', '$uid', '$notificationMessage', '$notificationType','$notificationStatus')";
                mysqli_query($con, $insertNotificationQuery);
            }

            $_SESSION['notifications'][] = 'Verification approved successfully.';
            header('Location: concourse_verify.php');
            exit();
        } else {
            $_SESSION['notifications'][] = 'Failed to approve verification.';
            header('Location: concourse_verify.php');
            exit();
        }
    } else {
        $_SESSION['notifications'][] = 'Verification ID not found.';
        header('Location: concourse_verify.php');
        exit();
    }
}
