
<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
?>
<?php
if (!isset($_SESSION['act_id'])) {
    header('Location: acc_login.php');
    exit();
}

include('includes/header.php');
include('includes/nav.php');
?>
<?php
if (isset($_SESSION['act_id'])) {
    $ownerId = $_SESSION['act_id'];

    // Fetch concourses based on the accountant's owner_id
    $concourseQuery = "SELECT c.* FROM concourse_verification c
                       JOIN accountant a ON c.owner_id = a.owner_id
                       WHERE a.act_id = '$ownerId'";

    $result = $con->query($concourseQuery);

    if ($result->num_rows > 0) {
        // Display concourses horizontally
        echo '<div class="container" style="margin-top: 100px; max-width: 1500px;">';
        echo '<h4 class="title" style="text-align:center; font-weight: bold;color:#fff;">Concourses</h4>';
        echo '<div class="row">';

        while ($row = $result->fetch_assoc()) {
            echo '<div class="col-lg-3 col-md-3 col-sm-6 col-12 mb-2">';
            echo '<div class="card" style="width: 100%; height: 100%; padding: 10px; margin: 0 auto;">';

            echo '<a href="view_concourse.php?concourse_id=' . $row['concourse_id'] . '" style="text-decoration: none; color: black;">';
            echo '<div class="image-container">';
            if (!empty($row['concourse_image'])) {
                // Display the concourse_image if it exists
                echo '<img src="./uploads/featured-concourse/' . $row['concourse_image'] . '" id="concourseImage" class="card-img-top smaller-image" alt="Concourse Image" style="width:100%; height: 300px;">';
            } elseif (!empty($row['concourse_map'])) {
                // Display the concourse_map if concourse_image is not available
                echo '<img src="./uploads/' . $row['concourse_map'] . '" id="concourseImage" class="card-img-top smaller-image" alt="Concourse Map" style="width:100%; height: 300px;">';
            } else {
                // Handle the case when both concourse_image and concourse_map are empty, e.g., display a placeholder image
                echo '<img src="path_to_placeholder_image.jpg" id="concourseImage" class="card-img-top smaller-image" alt="Placeholder Image" style="width:100%; height: 300px;">';
            }
            echo '</div>';
            
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $row['concourse_name'] . '</h5>';
            echo '<p class="card-text">Concourse ID: ' . $row['concourse_id'] . '</p>';
            echo '<p class="card-text">Owner ID: ' . $row['owner_id'] . '</p>';
            echo '<p class="card-text">Owner Name: ' . $row['owner_name'] . '</p>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
        }

        echo '</div>';
        echo '</div>';
    } else {
        // No concourses found for the logged-in accountant
        echo '<div class="container">';
        echo '<div class="title">Concourses</div>';
        echo '<div class="content">';
        echo 'No concourses found for the logged-in accountant.';
        echo '</div>';
        echo '</div>';
    }

    // Your existing footer code here
} else {
    // Accountant is not logged in
    // Redirect or handle accordingly
    header('Location: acc_login.php'); // Adjust the redirection URL as needed
    exit();
}
?>
<?php include('includes/footer.php'); ?>
