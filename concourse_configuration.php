    <?php
    session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');

if (isset($_GET['concourse_id'])) {
    $concourse_id = $_GET['concourse_id'];

    // Query the database to fetch the detailed information for the selected concourse
    $concourseQuery = "SELECT * FROM concourse_verification WHERE concourse_id = $concourse_id";
    $concourseResult = mysqli_query($con, $concourseQuery);

    // Check if a POST request was made to update Concourse details
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $concourse_id = $_POST['concourse_id']; // You should validate and sanitize this input.

        // Retrieve form data
        $total_area = $_POST['total_area'];
        $total_spaces = $_POST['total_spaces'];
        $location = $_POST['location'];
        $contract_of_lease = $_FILES['contract_of_lease']['name']; // Handle file upload.
        $feedback = $_POST['feedback'];

        // Update the database with the new data
        $updateQuery = "UPDATE concourse_verification SET
                concourse_total_area = '$total_area',
                total_spaces = '$total_spaces',
                location = '$location',
                contract_of_lease = '$contract_of_lease',
                feedback = '$feedback'
                WHERE concourse_id = $concourse_id";

        // Execute the query
        if (mysqli_query($con, $updateQuery)) {
            echo "Data updated successfully.";
        } else {
            echo "Error updating data: " . mysqli_error($con);
        }
    }
}

// Include your database connection and other required files
?>
    <!-- ******************** -->
    <!-- **** START HTML **** -->
    <!-- ******************** -->
    <?php
include('includes/header.php');
include('includes/nav.php');
?>

<div class="concourse-container">
    <div class="concourse-details">
        <?php
        // Display the detailed information
        if ($concourseResult && mysqli_num_rows($concourseResult) > 0) {
            $concourseData = mysqli_fetch_assoc($concourseResult);

            echo '<h3>Concourse Details</h3>';
            echo '<div class="card">';
            echo '<img src="/COMS/uploads/' . $concourseData['concourse_map'] . '" class="card-img-top smaller-image" alt="Concourse Map">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $concourseData['concourse_name'] . '</h5>';
            echo '<p class="card-text">Concourse ID: ' . $concourseData['concourse_id'] . '</p>';
            echo '<p class="card-text">Owner ID: ' . $concourseData['owner_id'] . '</p>';
            echo '<p class="card-text">Owner Name: ' . $concourseData['owner_name'] . '</p>';
            // Add more details as needed
            echo '</div>';
            echo '</div>';
        } else {
            echo 'Concourse not found.';
        }
?>
    </div>

    <div class="edit-concourse-form">
        <h3>Edit Concourse Details</h3>
        <form method="post" action="concourse_configuration.php?concourse_id=<?php echo $concourse_id; ?>">
            <label for="total_area">Total Area (sq ft):</label>
            <input type="text" name="total_area" value="<?php echo $concourseData['concourse_total_area']; ?>"><br>

            <label for="total_spaces">Total Spaces:</label>
            <input type="text" name="total_spaces" value="<?php echo $concourseData['total_spaces']; ?>"><br>

            <label for="location">Location (City/Postal Code/Barangay):</label>
            <input type="text" name="location" value="<?php echo $concourseData['location']; ?>"><br>

            <label for="contract_of_lease">Contract of Lease:</label>
            <input type="file" name="contract_of_lease"><br>


            <input type="submit" value="Save">
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>
