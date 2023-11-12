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
// Check if the admin is logged in; redirect to the admin login page if not
if (!isset($_SESSION['aid'])) {
    header('Location: dashboard.php');  // Redirect to the admin login page
    exit();
}

// Include the necessary database connection file


if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action']; // "approve" or "reject"
    $verification_id = $_GET['id']; // Verification ID

    // Perform the appropriate action (approve or reject)
    if ($action === 'approve') {
        // Perform the approval action
        // Update the database as needed
        // You may also want to move the data to another table or perform additional actions here
        $sql = "UPDATE user_verification SET status = 'approved' WHERE verification_id = $verification_id";
        mysqli_query($con, $sql);
    } elseif ($action === 'reject') {
        // Perform the rejection action
        // Update the database as needed
        // You may also want to move the data to another table or perform additional actions here
        $sql = "UPDATE user_verification SET status = 'rejected' WHERE verification_id = $verification_id";
        mysqli_query($con, $sql);
    }
}

// Query to retrieve all user verification transactions
$sql = "SELECT * FROM user_verification";
$result = mysqli_query($con, $sql);
?>


<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
include('includes/header.php');
include('includes/nav.php');
// include('users/user_verify.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMS</title>
    <style>
        /* Style for the User Details Modal */
        #userDetailsModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
        }

        /* Style for the User Details Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }

        /* Style for the Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        /* Vertically align the table content */
        table {
            table-layout: fixed;
            width: 100%;
        }

        th, td {
            word-wrap: break-word;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include('includes/sidebar.php');?>

        <section class="col-sm-10 py-5 dashboard">
            <h4>User Verifications</h4>
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['first_name'] . '</td>';
                        echo '<td>' . $row['last_name'] . '</td>';
                        echo '<td>';
                        echo '<a href="#" onclick="openUserDetailsModal(' . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ')">View User</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <!-- User Details Modal -->
            <div id="userDetailsModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="userDetailsClose">&times;</span>
                    <form id="userDetailsForm">
                        <label for="submissionId">Submission ID:</label>
                        <input type="text" id="submissionId" name="submissionId" readonly>

                        <label for="userId">User ID:</label>
                        <input type="text" id="userId" name="userId" readonly>

                        <label for="status">Status:</label>
                        <input type="text" id="status" name="status" readonly>

                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" name="firstName" readonly>

                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" name="lastName" readonly>

                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" readonly>

                        <label for="gender">Gender:</label>
                        <input type="text" id="gender" name="gender" readonly>

                        <label for="birthday">Birthday:</label>
                        <input type="text" id="birthday" name="birthday" readonly>

                        <label for="verificationImage">Verification Image:</label>
                        <a href="#" id="verificationImageLink" target="_blank">View Image</a>

                        <label for="documentLink">Document:</label>
                        <a href="#" id="documentLink" target="_blank">View Document</a>

                        <div class="actions">
                            <a href="#" id="approveLink">Approve</a>
                            <a href="#" id="rejectLink">Reject</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
<?php include('includes/footer.php')?>
<script>
    $('#datatable').dataTable({});
    // Function to hide notifications after 3 seconds
    function hideNotifications() {
        setTimeout(function() {
            var notifications = document.querySelector('.alert');
            if (notifications) {
                notifications.style.display = 'none';
            }
        }, 3000); // 3 seconds (3000 milliseconds)
    }

    // Call the hideNotifications function when the page loads
    window.onload = function() {
        hideNotifications();
    };
    function clearUserData() {
        setTimeout(function() {
            var userDataElements = document.querySelectorAll('.user-data');
            for (var i = 0; i < userDataElements.length; i++) {
                userDataElements[i].innerHTML = ''; // Clear the content of user data elements
            }
        }, 3000); // 3 seconds (3000 milliseconds)
    }

    // Call the clearUserData function when the page loads
    window.onload = function() {
        clearUserData();
    };
    // User Details Modal
    function openUserDetailsModal(user) {
        // Dynamically generate HTML for user details
        var userDetailsHTML = '';
        userDetailsHTML += '<label for="submissionId">Submission ID:</label>';
        userDetailsHTML += '<input type="text" id="submissionId" name="submissionId" value="' + user['verification_id'] + '" readonly>';

        userDetailsHTML += '<label for="userId">User ID:</label>';
        userDetailsHTML += '<input type="text" id="userId" name="userId" value="' + user['user_id'] + '" readonly>';

        userDetailsHTML += '<label for="status">Status:</label>';
        userDetailsHTML += '<input type="text" id="status" name="status" value="' + user['status'] + '" readonly>';

        userDetailsHTML += '<label for="firstName">First Name:</label>';
        userDetailsHTML += '<input type="text" id="firstName" name="firstName" value="' + user['first_name'] + '" readonly>';

        userDetailsHTML += '<label for="lastName">Last Name:</label>';
        userDetailsHTML += '<input type="text" id="lastName" name="lastName" value="' + user['last_name'] + '" readonly>';

        userDetailsHTML += '<label for="address">Address:</label>';
        userDetailsHTML += '<input type="text" id="address" name="address" value="' + user['address'] + '" readonly>';

        userDetailsHTML += '<label for="gender">Gender:</label>';
        userDetailsHTML += '<input type="text" id="gender" name="gender" value="' + user['gender'] + '" readonly>';

        userDetailsHTML += '<label for="birthday">Birthday:</label>';
        userDetailsHTML += '<input type="text" id="birthday" name="birthday" value="' + user['birthday'] + '" readonly>';

        userDetailsHTML += '<label for="verificationImage">Verification Image:</label>';
        userDetailsHTML += '<a href="../uploads/' + user['image_filename'] + '" target="_blank">View Image</a>';

        userDetailsHTML += '<label for="documentLink">Document:</label>';
        userDetailsHTML += '<a href="../uploads/' + user['document_filename'] + '" target="_blank">View Document: ' + user['document_filename'] + '</a>';

        userDetailsHTML += '<div class="actions">';
        userDetailsHTML += '<a href="user_verification_approve.php?id=' + user['verification_id'] + '">Approve</a>';
        userDetailsHTML += '<a href="user_verification_reject.php?id=' + user['verification_id'] + '">Reject</a>';
        userDetailsHTML += '</div>';

        // Display user details in the modal form
        $('#userDetailsForm').html(userDetailsHTML);
        $('#userDetailsModal').css('display', 'block');
    }

        // Close User Details Modal
        $('#userDetailsClose').on('click', function() {
        $('#userDetailsModal').css('display', 'none');
    });

    // Function to open image modal (Assuming you have this function defined)
    function openImageModal(imagePath) {
        // Your implementation for opening the image modal
    }

    // Function to open document modal (Assuming you have this function defined)
    function openDocumentModal(documentPath) {
        // Your implementation for opening the document modal
    }
</script>
</body>
</html>
