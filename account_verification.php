<?php
session_name("user_session");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit();
}

include('includes/header.php');
include('includes/nav.php');
?>

<section class="account-verification pt-5">
    <div class="container">
        <h2>Account Verification</h2>
        <form action="process_account_verification.php" method="post" enctype="multipart/form-data">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" required>

            <label for="address">Address:</label>
            <textarea name="address" required></textarea>

            <label for="gender">Gender:</label>
            <select name="gender">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="birthday">Birthday:</label>
            <input type="date" name="birthday" required>

            <label for="image">Upload Image (Passport/Driver's License):</label>
            <input type="file" name="image" accept=".jpg, .jpeg, .png" required>
            
            <label for="document">Upload Document (PDF/Word):</label>
<input type="file" name="document" accept=".pdf, .doc, .docx" required>


            <input type="submit" name="submit_verification" value="Submit Verification">
        </form>
    </div>
</section>

<?php include('includes/footer.php'); ?>
