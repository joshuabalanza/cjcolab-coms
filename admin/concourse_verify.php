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
    $concourse_id = $_GET['id']; // Verification ID

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

// Query to retrieve all user verification transactions
$sql = "SELECT * FROM concourse_verification";
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
<div class="container-fluid">
  <div class="row">
<?php include('includes/sidebar.php');?>

<section class="col-sm-10 py-5 dashboard">
    <!-- <div class="dashboard"> -->
<h4>
    User Verifications

</h4>
<table class="table table-bordered" id="datatable">
    <thead>
        <tr>
            <th>Concourse ID</th>
            <th>Owner ID</th>
            <th>Owner Name</th>
            <!-- <th>Status</th> -->

            <th>Concourse Name</th>
            <!-- <th>Address</th> -->
            <th>Map/Floor Plan Image</th>
            <th>Spaces</th>
            
                <th>Status</th>
                <th>Actions</th>
            <!-- Add more table headers as needed -->
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['concourse_id'] . '</td>';
            echo '<td>' . $row['owner_id'] . '</td>';
            echo '<td>' . $row['owner_name'] . '</td>';
            echo '<td>' . $row['concourse_name'] . '</td>';
            // echo '<td>' . $row['concourse_map'] . '</td>';
            echo '<td><a href="#" onclick="openImageModal(\'../uploads/' . $row['concourse_map'] . '\')">View Image</a></td>';
            echo '<td>' . $row['spaces'] . '</td>';
            echo '<td>' . $row['status'] . '</td>';
            // echo '<td>' . $row['birthday'] . '</td>';

            // // Add more table data as needed
            // echo '<td><a href="#" onclick="openDocumentModal(\'../uploads/' . $row['document_filename'] . '\')">View Document: ' . $row['document_filename'] . '</a></td>';

            echo '<td>';
            echo '<a href="concourse_verification_approve.php?id=' . $row['concourse_id'] . '">Approve</a>';
            echo '<a href="concourse_verification_reject.php?id=' . $row['concourse_id'] . '">Reject</a>';
            echo '</td>';
            echo '</tr>';

        }
?>
    </tbody>
</table>
<!-- Image Modal -->
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
    
    <!-- </div> -->
    </section>
</div>
</div>

<?php include('includes/footer.php')?>
<script>
    $('#datatable').dataTable({});
    // Function to hide notifications after 3 seconds
    // function hideNotifications() {
    //     setTimeout(function() {
    //         var notifications = document.querySelector('.alert');
    //         if (notifications) {
    //             notifications.style.display = 'none';
    //         }
    //     }, 3000); // 3 seconds (3000 milliseconds)
    // }

    // // Call the hideNotifications function when the page loads
    // window.onload = function() {
    //     hideNotifications();
    // };
    // function clearUserData() {
    //     setTimeout(function() {
    //         var userDataElements = document.querySelectorAll('.user-data');
    //         for (var i = 0; i < userDataElements.length; i++) {
    //             userDataElements[i].innerHTML = ''; // Clear the content of user data elements
    //         }
    //     }, 3000); // 3 seconds (3000 milliseconds)
    // }

    // // Call the clearUserData function when the page loads
    // window.onload = function() {
    //     clearUserData();
    // };
</script>