<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');

include('includes/header.php');
include('includes/nav.php');
?>

<?php
// Check if space_id is set in the URL
if (isset($_GET['space_id'])) {
    $spaceId = $_GET['space_id'];

    // Fetch space details based on the space_id
    $spaceQuery = "SELECT * FROM space WHERE space_id = '$spaceId'";
    $result = $con->query($spaceQuery);

    if ($result->num_rows > 0) {
        $spaceDetails = $result->fetch_assoc();
        ?>
        <div class="container" style="margin-top: 100px;">
            <h1>Create Bill for Space</h1>
            <div class="card" style="width: 100%; height: 100%; padding: 10px; margin: 0 auto;">
                <div class="card-body">
                    <h5 class="card-title">Space ID: <?php echo $spaceDetails['space_id']; ?></h5>
                    <p class="card-text">Tenant: <?php echo $spaceDetails['space_tenant']; ?></p>
                    <p class="card-text">Concourse ID: <?php echo $spaceDetails['concourse_id']; ?></p>
                    <!-- Add more details as needed -->

                    <!-- Form to create a bill -->
                    <form method="post" action="process_create_bill.php">
                        <input type="hidden" name="space_id" value="<?php echo $spaceId; ?>">

                        <label for="electric">Electric Bill:</label>
                        <input type="text" name="electric" required>

                        <label for="water">Water Bill:</label>
                        <input type="text" name="water" required>

                        <!-- Add more bill details as needed -->

                        <button type="submit" name="create_bill">Create Bill</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="container">';
        echo '<div class="content">';
        echo 'Space not found.';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="container">';
    echo '<div class="content">';
    echo 'Invalid request. Please provide a space_id.';
    echo '</div>';
    echo '</div>';
}
?>

<?php include('includes/footer.php'); ?>
