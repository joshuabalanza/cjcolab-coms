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
    $concourse_id = $_GET['id']; // Concourse ID

    // Perform the appropriate action (approve or reject)
    if ($action === 'approve') {
        // Perform the approval action
        // Update the database as needed
        // You may also want to move the data to another table or perform additional actions here
        $sql = "UPDATE concourse_verification SET status = 'approved' WHERE concourse_id = $concourse_id";
        mysqli_query($con, $sql);
    } elseif ($action === 'reject') {
        // Perform the rejection action
        // Update the database as needed
        // You may also want to move the data to another table or perform additional actions here
        $sql = "UPDATE concourse_verification SET status = 'rejected' WHERE concourse_id = $concourse_id";
        mysqli_query($con, $sql);
    }
}

// Query to retrieve all concourse verification transactions
$sql = "SELECT * FROM concourse_verification";
$result = mysqli_query($con, $sql);
?>


<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
include('includes/header.php');
include('includes/nav.php');
?>
<style>
    /* Style for the User Details Modal */
    #concourseDetailsModal {
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
    #concourseDetailsForm {
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
        <?php include('includes/sidebar.php'); ?>

        <section class="col-sm-10 py-5 dashboard">
            <h4 style="color: white; opacity: 80%;">Concourse Verifications</h4>
            <table class="table table-bordered" id="datatable">
                <thead style="background-color: #c19f90;">
                    <tr>
                        <th>Concourse ID</th>
                        <th>Owner Name</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $row['concourse_id'] . '</td>';
                        echo '<td>' . $row['owner_name'] . '</td>';
                        echo '<td>' . $row['status'] . '</td>';
                        echo '<td>';
                        echo '<button type="button" class="button" onclick="openConcourseDetailsModal(' . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ')">View Owner</button>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
            <!-- Concourse Details Modal -->
            <div id="concourseDetailsModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="concourseDetailsClose">&times;</span>
                    <form id="concourseDetailsForm">
                        <!-- Similar to user verification transaction modal content, adjust as needed -->
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
<?php include('includes/footer.php') ?>
<script>
    $('#datatable').dataTable({});
    // Function to hide notifications after 3 seconds
    function hideNotifications() {
        setTimeout(function () {
            var notifications = document.querySelector('.alert');
            if (notifications) {
                notifications.style.display = 'none';
            }
        }, 3000); // 3 seconds (3000 milliseconds)
    }

    // Call the hideNotifications function when the page loads
    window.onload = function () {
        hideNotifications();
    };

    // Concourse Details Modal
    function openConcourseDetailsModal(concourse) {
        // Dynamically generate HTML for concourse details
        var concourseDetailsHTML = '';

        concourseDetailsHTML += '<div class="form-row">';
        concourseDetailsHTML += '<div class="form-group col-md-6">';
        concourseDetailsHTML += '<label for="concourseId">Concourse ID:</label>';
        concourseDetailsHTML += '<input type="text" id="concourseId" name="concourseId" value="' + concourse['concourse_id'] + '" readonly>';
        concourseDetailsHTML += '</div>';
        concourseDetailsHTML += '<div class="form-group col-md-6">';
        concourseDetailsHTML += '<label for="concourseId">Owner ID:</label>';
        concourseDetailsHTML += '<input type="text" id="ownerId" name="ownerId" value="' + concourse['owner_id'] + '" readonly>';
        concourseDetailsHTML += '</div>';
        concourseDetailsHTML += '</div>';

        concourseDetailsHTML += '<div class="form-row">';
        concourseDetailsHTML += '<div class="form-group col-md-6">';
        concourseDetailsHTML += '<label for="ownerName">Owner Name:</label>';
        concourseDetailsHTML += '<input type="text" id="ownerName" name="ownerName" value="' + concourse['owner_name'] + '" readonly>';
        concourseDetailsHTML += '</div>';
        concourseDetailsHTML += '</div>';

        concourseDetailsHTML += '<div class="form-row">';
        concourseDetailsHTML += '<div class="form-group col-md-6">';
        concourseDetailsHTML += '<label for="concourseName">Concourse Name:</label>';
        concourseDetailsHTML += '<input type="text" id="concourseName" name="concourseName" value="' + concourse['concourse_name'] + '" readonly>';
        concourseDetailsHTML += '</div>';
        concourseDetailsHTML += '<div class="form-group col-md-6">';
        concourseDetailsHTML += '<label for="concourseAddress">Concourse Address:</label>';
        concourseDetailsHTML += '<input type="text" id="concourseAddress" name="concourseAddresss" value="' + concourse['concourse_address'] + '" readonly>';
        concourseDetailsHTML += '</div>';
        concourseDetailsHTML += '</div>';

        concourseDetailsHTML += '<div class="form-row">';
        concourseDetailsHTML += '<div class="form-group col-md-6">';
        concourseDetailsHTML += '<label for="spaces">Spaces:</label>';
        concourseDetailsHTML += '<input type="text" id="spaces" name="spaces" value="' + concourse['spaces'] + '" readonly>';
        concourseDetailsHTML += '</div>';
        concourseDetailsHTML += '<div class="form-group col-md-6">';
        concourseDetailsHTML += '<label for="status">Status:</label>';
        concourseDetailsHTML += '<input type="text" id="status" name="status" value="' + concourse['status'] + '" readonly>';
        concourseDetailsHTML += '</div>';
        concourseDetailsHTML += '</div>';

        concourseDetailsHTML += '<div class="form-row">';
        concourseDetailsHTML += '<div class="form-group col-md-6">';
        concourseDetailsHTML += '<label for="concourseMap">Map/Floor Plan Image:</label>';
        concourseDetailsHTML += '<a href="../uploads/' + concourse['concourse_map'] + '" target="_blank" class="button" style="width: 125%; text-decoration: none;">View Document</a>';
        concourseDetailsHTML += '</div>';
        concourseDetailsHTML += '</div>';

        // Add more fields as needed

        concourseDetailsHTML += '<div class="form-row actions">';
        concourseDetailsHTML += '<a href="concourse_verification_approve.php?id=' + concourse['concourse_id'] + '" class="button" style="background-color: green; text-decoration: none;">Approve</a>';
        concourseDetailsHTML += '<a href="concourse_verification_reject.php?id=' + concourse['concourse_id'] + '" class="button" style="background-color: red; text-decoration: none;">Reject</a>';
        concourseDetailsHTML += '</div>';

        // Display concourse details in the modal form
        $('#concourseDetailsForm').html(concourseDetailsHTML);
        $('#concourseDetailsModal').css('display', 'block');
    }

    // Close Concourse Details Modal
    $('#concourseDetailsClose').on('click', function () {
        $('#concourseDetailsModal').css('display', 'none');
    });
</script>