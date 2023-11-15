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
<style>
    .form-group label {
        text-align: left;
    }
    h2{
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .btn{
        margin-bottom: 10px;
        background-color: #9b593c;
        border: none;
    }
    .btn:hover{
        background-color: #c19f90;
    }
</style>
<section class="col-sm-8 col-md-9 bg-light p-2 mx-auto" style="border-radius: 10px; margin-top: 8%; max-width: 550px;">
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
