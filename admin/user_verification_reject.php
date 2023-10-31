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
if (!isset($_SESSION['aid'])) {
    header('Location: dashboard.php'); // Redirect to the admin login page
    exit();
}

if (isset($_GET['id'])) {
    $verification_id = $_GET['id']; // Use the existing verification_id

    // Include the database connection file

    // Query to update the verification status as rejected using the existing verification_id
    $update_query = "UPDATE user_verification SET status = 'rejected' WHERE verification_id = '$verification_id'";

    if (mysqli_query($con, $update_query)) {
        // Retrieve the user_id from the user_verification record
        $userVerificationQuery = "SELECT user_id FROM user_verification WHERE verification_id = '$verification_id'";
        $userVerificationResult = mysqli_query($con, $userVerificationQuery);

        if ($userVerificationResult && $userVerificationRow = mysqli_fetch_assoc($userVerificationResult)) {
            $uid = $userVerificationRow['user_id'];

            // Insert a notification for the user
            $notificationStatus = 1;
            $notificationType = 'Account Verification';
            $notificationMessage = 'Your verification has been rejected.';
            $insertNotificationQuery = "INSERT INTO notifications (user_id, message, notification_type,active) VALUES ('$uid', '$notificationMessage', '$notificationType','$notificationStatus')";
            mysqli_query($con, $insertNotificationQuery);
        }

        $_SESSION['notifications'][] = 'Verification rejected successfully.';
        header('Location: user_verification_transactions.php');
        exit();
    } else {
        $_SESSION['notifications'][] = 'Failed to reject verification.';
        header('Location: user_verification_transactions.php');
        exit();
    }
}















// session_name("admin_session");
// session_start();

// if (!isset($_SESSION['aid'])) {
//     header('Location: dashboard.php'); // Redirect to the admin login page
//     exit();
// }

// if (isset($_GET['id'])) {
//     $verification_id = $_GET['id'];

//     // Include the database connection file
//     require('../includes/dbconnection.php');

//     // Check if a verification entry with this ID exists
//     $check_existing_query = "SELECT * FROM user_verification WHERE verification_id = '$verification_id'";
//     $check_existing_result = mysqli_query($con, $check_existing_query);

//     if (mysqli_num_rows($check_existing_result) > 0) {
//         // Update the status for an existing verification entry
//         $update_query = "UPDATE user_verification SET status = 'rejected' WHERE verification_id = '$verification_id'";
//     } else {
//         // Insert a new verification entry with the same verification ID
//         $insert_query = "INSERT INTO user_verification (verification_id, user_id, status) VALUES ('$verification_id', '$uid', 'approved')";

//         if (mysqli_query($con, $insert_query)) {
//             // Insert a notification for the user
//             $notificationMessage = 'Your verification has been rejected.';
//             $insertNotificationQuery = "INSERT INTO notifications (user_id, message) VALUES ('$uid', '$notificationMessage')";
//             mysqli_query($con, $insertNotificationQuery);
//         }
//     }

//     $_SESSION['notifications'][] = 'Verification rejected successfully.';
//     header('Location: user_verify.php');
//     exit();
// }

// session_name("admin_session");
// session_start();

// if (!isset($_SESSION['aid'])) {
//     header('Location: dashboard.php'); // Redirect to the admin login page
//     exit();
// }

// if (isset($_GET['id'])) {
//     $verification_id = $_GET['id'];

//     // Include the database connection file
//     require('../includes/dbconnection.php');

//     // Query to update the verification status as rejected
//     $update_query = "UPDATE user_verification SET status = 'rejected' WHERE verification_id = $verification_id";

//     if (mysqli_query($con, $update_query)) {
//         // Retrieve the user_id from the user_verification record
//         $userVerificationQuery = "SELECT user_id FROM user_verification WHERE verification_id = $verification_id";
//         $userVerificationResult = mysqli_query($con, $userVerificationQuery);

//         if ($userVerificationResult && $userVerificationRow = mysqli_fetch_assoc($userVerificationResult)) {
//             $uid = $userVerificationRow['user_id'];

//             // Insert a notification for the user
//             $notificationMessage = 'Your verification has been rejected.';
//             $insertNotificationQuery = "INSERT INTO notifications (user_id, message) VALUES ('$uid', '$notificationMessage')";
//             mysqli_query($con, $insertNotificationQuery);
//         }

//         $_SESSION['notifications'][] = 'Verification rejected successfully.';
//         header('Location: user_verify.php');
//         exit();
//     } else {
//         $_SESSION['notifications'][] = 'Failed to reject verification.';
//         header('Location: user_verify.php');
//         exit();
//     }
// }
