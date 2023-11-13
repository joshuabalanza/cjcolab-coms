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

        /* Style for the User Details Modal Form */
        #userDetailsForm {
            display: grid;
            gap: 10px;
        }

        /* Style for Form Rows and Columns */
        .form-row {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat(2, 1fr);
        }

        /* Style for the Actions Section */
        .actions {
            display: flex;
            justify-content: space-between;
        }
        /* input box */
        input {
            border: none;
            border-bottom: 1px solid #ccc;
            padding: 5px;
        }
            /* Style for bold labels */
        label {
            font-weight: bold;
        }
    
        .button {
            display: inline-block;
            padding: 8px 16px; /* Adjust the padding to achieve the desired size */
            text-align: center;
            color: #ffffff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #eeeeee !important;
        }
    </style>
<div class="container-fluid">
    <div class="row">
        <?php include('includes/sidebar.php');?>

        <section class="col-sm-10 py-5 dashboard">
            <h4>User Verifications</h4>
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th>Submission ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['verification_id'] . '</td>';
                        echo '<td>' . $row['first_name'] . '</td>';
                        echo '<td>' . $row['last_name'] . '</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '<td>';
                        echo '<button type="button" class="button" onclick="openUserDetailsModal(' . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ')">View User</button>';
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
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="submissionId">Submission ID:</label>
                                <input type="text" id="submissionId" name="submissionId" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="userId">User ID:</label>
                                <input type="text" id="userId" name="userId" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="status">Status:</label>
                                <input type="text" id="status" name="status" value="' + user['status'] + '" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="firstName">First Name:</label>
                                <input type="text" id="firstName" name="firstName" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastName">Last Name:</label>
                                <input type="text" id="lastName" name="lastName" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="address">Address:</label>
                                <input type="text" id="address" name="address" value="' + user['address'] + '" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="gender">Gender:</label>
                                <input type="text" id="gender" name="gender" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="birthday">Birthday:</label>
                                <input type="text" id="birthday" name="birthday" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="verificationImage">Verification Image:</label>
                            <a href="#" id="verificationImageLink" target="_blank" class="button">View Image</a>
                        </div>

                        <div class="form-group">
                            <label for="documentLink">Verification Document:</label>
                            <a href="#" id="documentLink" target="_blank" class="button">View Document</a>
                        </div>

                        <div class="form-row actions">
                            <a href="#" id="approveLink" class="button">Approve</a>
                            <a href="#" id="rejectLink" class="button">Reject</a>
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

        userDetailsHTML += '<div class="form-row">';
        userDetailsHTML += '<div class="form-group col-md-6">';
        userDetailsHTML += '<label for="submissionId">Submission ID:</label>';
        userDetailsHTML += '<input type="text" id="submissionId" name="submissionId" value="' + user['verification_id'] + '" readonly>';
        userDetailsHTML += '</div>';
        userDetailsHTML += '<div class="form-group col-md-6">';
        userDetailsHTML += '<label for="userId">User ID:</label>';
        userDetailsHTML += '<input type="text" id="userId" name="userId" value="' + user['user_id'] + '" readonly>';
        userDetailsHTML += '</div>';
        userDetailsHTML += '</div>';

        userDetailsHTML += '<div class="form-row">';
        userDetailsHTML += '<div class="form-group col-md-6">';
        userDetailsHTML += '<label for="status">Status:</label>';
        userDetailsHTML += '<input type="text" id="status" name="status" value="' + user['status'] + '" readonly>';
        userDetailsHTML += '</div>';
        userDetailsHTML += '</div>';

        userDetailsHTML += '<div class="form-row">';
        userDetailsHTML += '<div class="form-group col-md-6">';
        userDetailsHTML += '<label for="firstName">First Name:</label>';
        userDetailsHTML += '<input type="text" id="firstName" name="firstName" value="' + user['first_name'] + '" readonly>';
        userDetailsHTML += '</div>';
        userDetailsHTML += '<div class="form-group col-md-6">';
        userDetailsHTML += '<label for="lastName">Last Name:</label>';
        userDetailsHTML += '<input type="text" id="lastName" name="lastName" value="' + user['last_name'] + '" readonly>';
        userDetailsHTML += '</div>';
        userDetailsHTML += '</div>';

        userDetailsHTML += '<div class="form-row">';
        userDetailsHTML += '<div class="form-group col-md-6">';
        userDetailsHTML += '<label for="address">Address:</label>';
        userDetailsHTML += '<input type="text" id="address" name="address" value="' + user['address'] + '" readonly>';
        userDetailsHTML += '</div>';
        userDetailsHTML += '</div>';

        userDetailsHTML += '<div class="form-row">';
        userDetailsHTML += '<div class="form-group col-md-6">';
        userDetailsHTML += '<label for="gender">Gender:</label>';
        userDetailsHTML += '<input type="text" id="gender" name="gender" value="' + user['gender'] + '" readonly>';
        userDetailsHTML += '</div>';
        userDetailsHTML += '<div class="form-group col-md-6">';
        userDetailsHTML += '<label for="birthday">Birthday:</label>';
        userDetailsHTML += '<input type="text" id="birthday" name="birthday" value="' + user['birthday'] + '" readonly>';
        userDetailsHTML += '</div>';
        userDetailsHTML += '</div>';

        userDetailsHTML += '<div class="form-row">';
        userDetailsHTML += '<div class="form-group col-md-6">';
        userDetailsHTML += '<label for="verificationImage">Verification Image:</label>';
        userDetailsHTML += '<a href="../uploads/' + user['image_filename'] + '" target="_blank" class="button" style="text-decoration: none;">View Image</a>';
        userDetailsHTML += '</div>';
        userDetailsHTML += '<div class="form-group col-md-6">';
        userDetailsHTML += '<label for="documentLink">Verification Document:</label>';
        userDetailsHTML += '<a href="../uploads/' + user['document_filename'] + '" target="_blank" class="button" style="width: 125%; text-decoration: none;">View Document</a>';
        userDetailsHTML += '</div>';
        userDetailsHTML += '</div>';
        
        userDetailsHTML += '<div class="form-row">';
        userDetailsHTML += '<div class="form-group col-md-6 actions">';
        userDetailsHTML += '<a href="user_verification_approve.php?id=' + user['verification_id'] + '" class="button" style="background-color: green; text-decoration: none;">Approve</a>';
        userDetailsHTML += '<a href="user_verification_reject.php?id=' + user['verification_id'] + '" class="button" style="background-color: red; text-decoration: none;">Reject</a>';
        userDetailsHTML += '</div>';
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
