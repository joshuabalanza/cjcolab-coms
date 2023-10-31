<!-- ******************** -->
<!-- ***START SESSION**** -->
<!-- ******************** -->
<?php
session_name("user_session");
session_start();
?>

<!-- ******************** -->
<!-- ***** PHP CODE ***** -->
<!-- ******************** -->
<?php
// Check if the user is logged in
if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit();
}
?>

<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
include('includes/header.php');
include('includes/nav.php');
?>
<section class="account-verification pt-5">
    <div class="container">
        <h2>Account Verification</h2>
        <form action="verification_account_process.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" name="first_name" required>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" name="last_name" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea class="form-control" name="address" required></textarea>
            </div>

            <div class="form-group">
                <label for="gender">Gender:</label>
                <select class="form-control" name="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="birthday">Birthday:</label>
                <input type="date" class="form-control" name="birthday" required>
            </div>

            <div class="form-group">
                <label for="image">Upload Image (Passport/Driver's License):</label>
                <input type="file" class="form-control-file" name="image" accept=".jpg, .jpeg, .png" required>
            </div>
            
            <div class="form-group">
                <label for="document">Upload Document (PDF/Word):</label>
                <input type="file" class="form-control-file" name="document" accept=".pdf, .doc, .docx" required>
            </div>

            <button type="submit" class="btn btn-primary" name="submit_verification">Submit Verification</button>
        </form>
    </div>
</section>
<?php include('includes/footer.php'); ?>
