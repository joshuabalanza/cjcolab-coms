<?php
require('includes/dbconnection.php');

// Check if space_id and tenant_name are provided
if (isset($_GET['space_id'])) {
    $spaceId = $_GET['space_id'];

    // Fetch the most recent bill details for the specified space
    $billQuery = "SELECT water, electric FROM bill WHERE space_id = '$spaceId' ORDER BY created_at DESC LIMIT 1";
    $result = $con->query($billQuery);

    if ($result->num_rows > 0) {
        $billDetails = $result->fetch_assoc();

        // Set the current_bill in the session
        $_SESSION['current_bill'] = $billDetails;

        echo json_encode($billDetails);
    } else {
        echo json_encode(['error' => 'No bill found for the specified space.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request. Please provide space_id.']);
}
?>
