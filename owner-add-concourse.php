<!-- ******************** -->
<!-- ***START SESSION**** -->
<!-- ******************** -->
<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
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

$uid = $_SESSION['uid'];
$utype = $_SESSION['utype'];
$owner_id = $uid; // Assuming $uid is the owner's ID

?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the form is submitted
    if (isset($_POST['map_name']) && isset($_POST['spaces']) && isset($_FILES['fp'])) {
        // Handle the form submission
        $map_name = $_POST['map_name'];
        $spaces = $_POST['spaces'];
        $owner_id = $uid; // You can use the owner's ID from the session

        // Handle file upload
        $file_name = $_FILES['fp']['name'];
        $file_tmp = $_FILES['fp']['tmp_name'];
        $file_type = $_FILES['fp']['type'];
        $file_size = $_FILES['fp']['size'];

        // Check if the uploaded file is an image
        $allowed_types = array('image/png', 'image/jpeg', 'image/jpg');
        if (in_array($file_type, $allowed_types)) {
            // Upload the file to a directory
            $upload_dir = './uploads/';
            $file_path = $upload_dir . $file_name;
            if (move_uploaded_file($file_tmp, $file_path)) {
                // File uploaded successfully, now insert data into the database
                require('includes/dbconnection.php');

                // $query = "INSERT INTO map (map_name, spaces, map_image owner_id) VALUES (?, ?, ?)";
                // $stmt = $con->prepare($query);
                // $stmt->bind_param("sii", $map_name, $spaces, $owner_id);

                $query = "INSERT INTO map (map_name, spaces, map_image, owner_id) VALUES (?, ?, ?, ?)";
                $stmt = $con->prepare($query);
                $stmt->bind_param("sisi", $map_name, $spaces, $file_path, $owner_id);

                if ($stmt->execute()) {
                    // Data inserted successfully
                    echo "Data inserted successfully.";
                } else {
                    echo "Error inserting data into the database: " . $con->error;
                }
            } else {
                echo "Error uploading the file.";
            }
        } else {
            echo "Invalid file type. Only PNG, JPEG, and JPG files are allowed.";
        }
    }
}
?>


<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
include('includes/header.php');

// include('includes/nav.php');
?>





   
<div class="card h-100 d-flex flex-column">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Floor Plan</h3>
        <div class="card-tools align-middle">
            <button class="btn btn-dark btn-sm py-1 rounded-0" type="button" id="update_fp">Insert a Map Plan</button>
        </div>
    </div>
    <div class="card-body">
        <div class="col-md-12">
            <!-- <img src="./../uploads/floorplan.png?v=<?php echo time() ?>" alt="Floor Plan" id="fp-img-main" class="w-100"> -->
            <img src="./uploads/<?php echo $file_name; ?>" alt="Floor Plan" id="fp-img-main" class="w-100">

        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="mapPlanModal" tabindex="-1" role="dialog" aria-labelledby="mapPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapPlanModalLabel">Insert a Map Plan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
            <!-- <form action="" method="post" enctype="multipart/form-data" id="fp-form"> -->
            <!-- <form method="post" enctype="multipart/form-data" id="fp-form"> -->
            <!-- <form method="post" enctype="multipart/form-data" action="owner-add-concourse.php" id="fp-form"> -->
            <form method="post" enctype="multipart/form-data" action="" id="fp-form">

    <div class="form-group">
        <label for="map_name" class="control-label">Map Name</label>
        <input type="text" name="map_name" class="form-control form-control-sm rounded-0" id="map_name" required>
    </div>
    <div class="form-group">
        <label for="spaces" class="control-label">Spaces</label>
        <input type="number" name="spaces" class="form-control form-control-sm rounded-0" id="spaces" required>
    </div>
    <div class="form-group">
        <label for="fp" class="control-label">Floor Plan Image</label>
        <!-- <input type="file" name="fp" class="form-control form-control-sm rounded-0" id="fp" accept="image/png, image/jpeg, image/jpg" required> -->
        <input type="file" name="fp" class="form-control form-control-sm rounded-0" id="fp" onchange="readURL(this)" accept="image/png, image/jpeg, image/jpg" required>

    </div>
    <div class="form-group text-center">
        <img src="" alt="Floor Plan Image" id="img-fp">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>


                <!-- Add the content of your modal here -->
                <!-- For example, an iframe to display a map -->
                <!-- <iframe src="your-map-url" width="100%" height="400" frameborder="0"></iframe> -->
            </div>
            <!-- <div class="modal-footer"> -->
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Submit</button> -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Add any other buttons you need here -->
            </div>
        </div>
    </div>
</div>





<script>
    document.getElementById('update_fp').addEventListener('click', function () {
        $('#mapPlanModal').modal('show');
    });
</script>

<!-- <style>
    #img-fp{
        width:calc(100%);
        height:35vh
    }
</style>
<div class="container-fluid">
    <form action="" id="fp-form">
        <div class="form-group">
            <label for="fp" class="control-label">Floor Plan Image</label>
            <input type="file" name="fp" class="form-control form-control-sm rounded-0" id="fp" onchange="readURL(this)" accept="image/png, image/jpeg, image/jpg" required>
        </div>
        <div class="form-group text-center">
            <img src="./../uploads/floorplan.png" alt="Floor Plan Image" id="img-fp">
        </div>
    </form>
</div> -->

<script>
    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('img-fp').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

</script>
<?php include('includes/footer.php'); ?>