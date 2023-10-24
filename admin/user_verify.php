<?php
session_name("admin_session");
session_start();

if (!isset($_SESSION['aid'])) {
    header('Location: dashboard.php');
    exit();
}

require('../includes/dbconnection.php');

// Query to retrieve user verification submissions that are not approved or rejected
$sql = "SELECT * FROM user_verification WHERE status IS NULL";
$result = mysqli_query($con, $sql);
?>

<?php include('includes/header.php'); ?>

<div class="container">
    <h2>User Verification Submissions</h2>
    <?php
    // Check for notifications
    if (isset($_SESSION['notifications']) && !empty($_SESSION['notifications'])) {
        echo '<div class="alert alert-success">';
        foreach ($_SESSION['notifications'] as $notification) {
            echo $notification . '<br>';
        }
        echo '</div>';

        // Clear the notifications
        $_SESSION['notifications'] = array();
    }
?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Submission ID</th>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>Gender</th>
                <th>Birthday</th>
                <th>Verification Image</th>
                <th>Documents</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
        while ($row = mysqli_fetch_assoc($result)) {
            $verification_id = $row['verification_id'];

            echo '<tr>';
            echo '<td>' . $verification_id . '</td>';
            echo '<td>' . $row['user_id'] . '</td>';
            echo '<td>' . $row['first_name'] . '</td>';
            echo '<td>' . $row['last_name'] . '</td>';
            echo '<td>' . $row['address'] . '</td>';
            echo '<td>' . $row['gender'] . '</td>';
            echo '<td>' . $row['birthday'] . '</td>';

            echo '<td><a href="#" onclick="openImageModal(\'../uploads/' . $row['image_filename'] . '\')">View Image</a></td>';
            echo '<td><a href="#" onclick="openDocumentModal(\'../uploads/' . $row['document_filename'] . '\')">View Document: ' . $row['document_filename'] . '</a></td>';

            echo '<td>';
            echo '<a href="approve_verification.php?id=' . $verification_id . '">Approve</a>';
            echo '<a href="reject_verification.php?id=' . $verification_id . '">Reject</a>';
            echo '</td>';

            echo '</tr>';
        }
?>
        </tbody>
    </table>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <span class="close" id="imageClose">&times;</span>
    <img class="modal-content" id="imageContent">
</div>

<!-- Document Modal -->
<div id="documentModal" class="modal">
    <span class="close" id="documentClose">&times;</span>
    <iframe class="modal-content" id="documentContent"></iframe>
</div>

<script>
    function hideNotifications() {
        setTimeout(function () {
            var notifications = document.querySelector('.alert');
            if (notifications) {
                notifications.style.display = 'none';
            }
        }, 3000);
    }

    window.onload = function () {
        hideNotifications();
    };
</script>

<?php include('includes/footer.php'); ?>
