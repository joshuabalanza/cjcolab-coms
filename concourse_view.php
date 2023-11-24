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
// Check if concourse_id is set in the URL
if (isset($_GET['concourse_id'])) {
    $concourseId = $_GET['concourse_id'];

    // Fetch concourse details based on the concourse_id
    $concourseQuery = "SELECT * FROM concourse_verification WHERE concourse_id = '$concourseId'";
    $result = $con->query($concourseQuery);

    if ($result->num_rows > 0) {
        $concourseDetails = $result->fetch_assoc();
        ?>
        <div class="container" style="margin-top: 100px;">
            <h1>Concourse Details</h1>
            <div class="card" style="width: 100%; height: 100%; padding: 10px; margin: 0 auto;">
                <div class="image-container">
                    <?php
                    // Display concourse image or map (similar to how you did in the previous code)
                    if (!empty($concourseDetails['concourse_image'])) {
                        echo '<img src="/COMS-GENERIC/uploads/featured-concourse/' . $concourseDetails['concourse_image'] . '" id="concourseImage" class="card-img-top" alt="Concourse Image" style="width:100%; height: 300px;">';
                    } elseif (!empty($concourseDetails['concourse_map'])) {
                        echo '<img src="/COMS-GENERIC/uploads/' . $concourseDetails['concourse_map'] . '" id="concourseImage" class="card-img-top" alt="Concourse Map" style="width:100%; height: 300px;">';
                    } else {
                        echo '<img src="path_to_placeholder_image.jpg" id="concourseImage" class="card-img-top" alt="Placeholder Image" style="width:100%; height: 300px;">';
                    }
        ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $concourseDetails['concourse_name']; ?></h5>
                    <p class="card-text">Concourse ID: <?php echo $concourseDetails['concourse_id']; ?></p>
                    <p class="card-text">Owner ID: <?php echo $concourseDetails['owner_id']; ?></p>
                    <p class="card-text">Owner Name: <?php echo $concourseDetails['owner_name']; ?></p>
                    <!-- Add more details as needed -->
                </div>
            </div>
        </div>
        <?php
    } else {
        echo '<div class="container">';
        echo '<div class="content">';
        echo 'Concourse not found.';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="container">';
    echo '<div class="content">';
    echo 'Invalid request. Please provide a concourse_id.';
    echo '</div>';
    echo '</div>';
}
?>

<?php include('includes/footer.php'); ?>