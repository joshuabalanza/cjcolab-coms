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
    $verification_id = $_GET['id'];


    // Check if a verification entry with this ID exists
    $check_existing_query = "SELECT * FROM user_verification WHERE verification_id = '$verification_id'";
    $check_existing_result = mysqli_query($con, $check_existing_query);

    if (mysqli_num_rows($check_existing_result) > 0) {
        // Update the status for an existing verification entry
        $update_query = "UPDATE user_verification SET status = 'approved' WHERE verification_id = '$verification_id'";

        if (mysqli_query($con, $update_query)) {
            // Retrieve the user_id from the user_verification record
            $userVerificationQuery = "SELECT user_id FROM user_verification WHERE verification_id = '$verification_id'";
            $userVerificationResult = mysqli_query($con, $userVerificationQuery);

            if ($userVerificationResult && $userVerificationRow = mysqli_fetch_assoc($userVerificationResult)) {
                $uid = $userVerificationRow['user_id'];

                // Insert a notification for the user
                $notificationType = 'Account Verification';
                $notificationMessage = 'Your verification has been approved.';
                $insertNotificationQuery = "INSERT INTO notifications (user_id, message, notification_type) VALUES ('$uid', '$notificationMessage', '$notificationType')";
                mysqli_query($con, $insertNotificationQuery);
            }

            $_SESSION['notifications'][] = 'Verification approved successfully.';
            header('Location: user_verification_transactions.php');
            exit();
        } else {
            $_SESSION['notifications'][] = 'Failed to approve verification.';
            header('Location: user_verification_transactions.php');
            exit();
        }
    } else {
        $_SESSION['notifications'][] = 'Verification ID not found.';
        header('Location: user_verification_transactions.php');
        exit();
    }
}
