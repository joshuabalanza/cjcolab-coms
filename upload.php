<?php

// upload.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if a file was uploaded
    if (isset($_FILES["fp"]) && $_FILES["fp"]["error"] == 0) {
        // Define the directory where uploaded files will be stored
        $uploadDir = "uploads/";

        // Generate a unique file name for the uploaded image
        $filename = uniqid() . "_" . $_FILES["fp"]["name"];
        $uploadFilePath = $uploadDir . $filename;

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["fp"]["tmp_name"], $uploadFilePath)) {
            // Insert the file path into the "map" table in the database
            require('includes/dbconnection.php');

            $sql = "INSERT INTO map (image_path) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $uploadFilePath);

            if ($stmt->execute()) {
                // Image uploaded and inserted successfully
                header('Location: your_success_page.php');
                exit();
            } else {
                // Database error
                header('Location: your_error_page.php');
                exit();
            }
        } else {
            // Error moving the uploaded file
            header('Location: your_error_page.php');
            exit();
        }
    } else {
        // No file was uploaded
        header('Location: your_error_page.php');
        exit();
    }
} else {
    // Invalid request method
    header('Location: your_error_page.php');
    exit();
}
