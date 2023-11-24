<?php
// Include your database connection or any necessary configurations here

if (isset($_GET['username'])) {
    $enteredUsername = $_GET['username'];

    // Implement logic to generate alternative username suggestions
    $alternativeUsernames = generateAlternativeUsernames($enteredUsername);

    // Return the suggestions as JSON
    echo json_encode(['suggestions' => $alternativeUsernames]);
} elseif (isset($_GET['acusername'])) {
    $enteredacUsername = $_GET['acusername'];

    // Implement logic to generate alternative username suggestions
    $alternativeUsernames = generateAlternativeUsernames($enteredacUsername);

    // Return the suggestions as JSON
    echo json_encode(['suggestions' => $alternativeUsernames]);
}else {
    // Handle the case when the 'username' parameter is not set
    echo json_encode(['error' => 'Invalid request']);
}

function generateAlternativeUsernames($enteredUsername) {

    //Adding numbers to the username
    $suggestions = [];
    for ($i = 1; $i <= 5; $i++) {
        $suggestedUsername = $enteredUsername . $i;
        // Check if the suggested username is not already in use
        $usernameAvailable = checkUsernameAvailability($suggestedUsername);

        if ($usernameAvailable) {
            $suggestions[] = $suggestedUsername;
        }
    }

    return $suggestions;
}

function checkUsernameAvailability($username) {
    // Return true if the username is available, false otherwise
    return true;
}
function generateAlternativeacUsernames($enteredacUsername) {

    //Adding numbers to the username
    $suggestions = [];
    for ($i = 1; $i <= 5; $i++) {
        $suggestedacUsername = $enteredacUsername . $i;
        // Check if the suggested username is not already in use
        $usernameAvailable = checkUsernameAvailability($suggestedacUsername);

        if ($usernameAvailable) {
            $suggestions[] = $suggestedacUsername;
        }
    }

    return $suggestions;
}

function checkacUsernameAvailability($acusername) {
    // Return true if the username is available, false otherwise
    return true;
}
?>
