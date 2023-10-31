    <?php



session_name("user_session");
session_start();

if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit();
}

if (isset($_POST['submit_verification'])) {
    require('includes/dbconnection.php');
    $user_id = $_SESSION['uid'];
    $first_name = mysqli_real_escape_string($con, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($con, $_POST['last_name']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $birthday = mysqli_real_escape_string($con, $_POST['birthday']);

    // Check if the "uploads" directory exists, create it if not
    $upload_directory = 'uploads/';
    if (!file_exists($upload_directory)) {
        mkdir($upload_directory, 0777, true);
    }

    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_filename = uniqid() . '.' . $image_extension;

        $upload_path = $upload_directory . $image_filename;

        if (move_uploaded_file($image_tmp, $upload_path)) {
            // File upload was successful
        } else {
            // Handle file upload error
        }
    } else {
        // Handle file upload error
    }

    if ($_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $document_name = $_FILES['document']['name'];
        $document_tmp = $_FILES['document']['tmp_name'];
        $document_extension = pathinfo($document_name, PATHINFO_EXTENSION);
        $document_filename = uniqid() . '.' . $document_extension;

        $upload_path = $upload_directory . $document_filename;

        if (move_uploaded_file($document_tmp, $upload_path)) {
            // File upload was successful
        } else {
            // Handle file upload error
        }
    } else {
        // Handle file upload error
    }

    // Check if a verification record with the same user_id exists
    $checkExistingVerificationQuery = "SELECT verification_id FROM user_verification WHERE user_id = $user_id";
    $checkExistingVerificationResult = mysqli_query($con, $checkExistingVerificationQuery);

    if ($checkExistingVerificationResult && mysqli_num_rows($checkExistingVerificationResult) > 0) {
        // An existing verification record is found; update it
        $verificationData = mysqli_fetch_assoc($checkExistingVerificationResult);
        $verification_id = $verificationData['verification_id'];

        // Update the existing verification record
        $update_query = "UPDATE user_verification SET
            first_name = '$first_name',
            last_name = '$last_name',
            address = '$address',
            gender = '$gender',
            birthday = '$birthday',
            image_filename = '$image_filename',
            document_filename = '$document_filename'
            WHERE verification_id = $verification_id";

        if (mysqli_query($con, $update_query)) {
            // Verification data updated successfully
            header('Location: verification_account.php?success=true');
            exit();
        } else {
            // Handle database update error
            header('Location: verification_account.php?error=database');
            exit();
        }
    } else {
        // No existing verification record found; insert a new one
        $insert_query = "INSERT INTO user_verification (user_id, first_name, last_name, address, gender, birthday, image_filename, document_filename)
                        VALUES ('$user_id', '$first_name', '$last_name', '$address', '$gender', '$birthday', '$image_filename', '$document_filename')";

        if (mysqli_query($con, $insert_query)) {
            // Verification data inserted successfully
            header('Location: verification_account.php?success=true');
            exit();
        } else {
            // Handle database insertion error
            header('Location: verification_account.php?error=database');
            exit();
        }
    }

    mysqli_close($con);
} else {
    header('Location: account_verification.php');
    exit();
}
