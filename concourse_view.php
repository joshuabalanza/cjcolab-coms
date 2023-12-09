<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');

include('includes/header.php');
include('includes/nav.php');
?>
<style>
    button:hover {
        background-color: #c19f90 !important;
    }

    button {
        background-color: #9b593c;
    }

    .container {
        max-width: 800px;
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: calc(33.33% - 20px);
        margin-bottom: 20px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        text-align: unset;
        /* max-width: 400px; */
        width: 100rem;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        margin: 10px 0;
    }

    .close-btn {
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
    }

    #appModal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        z-index: 999999!important;
        /* align-items: center; */
    }

    #appModalContent {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        max-width: 400px;
        width: 100%;
    }

    .bold-label {
        font-weight: bold;
    }
</style>
<?php
// Check if concourse_id is set in the URL
if(isset($_GET['concourse_id'])) {
    $concourseId = $_GET['concourse_id'];

    // Fetch concourse details based on the concourse_id
    $concourseQuery = "SELECT * FROM concourse_verification2 WHERE concourse_id = '$concourseId'";
    $result = $con->query($concourseQuery);

    if($result->num_rows > 0) {
        $concourseDetails = $result->fetch_assoc();
        ?>
        <div class="container" style="margin-top: 120px;">
            <h5 style="color: #fff;">CONCOURSE DETAILS</h5>
            <div class="card" style="width: 100%; height: 100%; padding: 10px; margin: 0 auto; ">
                <div class="image-container">
                    <?php
                    // Display concourse image or map (similar to how you did in the previous code)
                    if(!empty($concourseDetails['concourse_image'])) {
                        echo '<img src="./uploads/featured-concourse/'.$concourseDetails['concourse_image'].'" id="concourseImage" class="card-img-top" alt="Concourse Image" style="width:100%; height: 300px;" usemap="#workmap">';
                    } elseif(!empty($concourseDetails['concourse_map'])) {
                        echo '<img src="./uploads/'.$concourseDetails['concourse_map'].'" id="concourseImage" class="card-img-top" alt="Concourse Map" style="width:100%; height: 300px;" usemap="#workmap"  <map name="workmap">
                        <area shape="circle" coords="613,146,30" alt="Space 1" href="#" id="areamap" style="cursor:pointer">
                        </map>';
                    } else {
                        echo '<img src="path_to_placeholder_image.jpg" id="concourseImage" class="card-img-top" alt="Placeholder Image" style="width:100%; height: 300px;">';
                    }
                    ?>
                   
                </div>
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo $concourseDetails['concourse_name']; ?>
                    </h5>
                    <!-- <p class="card-text">Concourse ID: <?php echo $concourseDetails['concourse_id']; ?></p>
                    <p class="card-text">Owner ID: <?php echo $concourseDetails['owner_id']; ?></p> -->
                    <p class="card-text"><span class="bold-label">Address:</span>
                        <?php echo $concourseDetails['concourse_address']; ?>
                    </p>
                    <p class="card-text"><span class="bold-label">Owner Name:</span>
                        <?php echo $concourseDetails['owner_name']; ?>
                    </p>
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
<?php
$sql = "SELECT * FROM space WHERE concourse_id = '$concourseId' and status!='occupied'";
$result = mysqli_query($con, $sql);

$con->close();
?>
<!-- <h1>Available Spaces</h1> -->
<div class="container" style="margin-top: 50px;">
    <?php
    if($result && mysqli_num_rows($result) > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div class='card' style='cursor:pointer' onclick='openModal(\"{$row['space_name']}\", \"{$row['status']}\", ".json_encode($row).")'>";
            echo "<h2>{$row['space_name']}</h2>";
            echo "<h6>{$row['status']}</h6>";
            echo "<h7>{$row['space_owner']}</h6>";
            echo "<div class='details' style='display: none;'>";
            echo "<ul>";
            echo "<li><strong>Space ID:</strong> {$row['space_id']}</li>";
            echo "<li><strong>Concourse ID:</strong> {$row['concourse_id']}</li>";
            echo "<li><strong>Owner:</strong> {$row['space_owner']}</li>";
            echo "<li><strong>Status:</strong> {$row['status']}</li>";
            echo "<li><strong>Space Width:</strong> {$row['space_width']}</li>";
            echo "<li><strong>Space Length:</strong> {$row['space_length']}</li>";
            echo "<li><strong>Space Height:</strong> {$row['space_height']}</li>";
            echo "<li><strong>Space Area:</strong> {$row['space_area']}</li>";
            echo "<li><strong>Space Dimension:</strong> {$row['space_dimension']}</li>";
            echo "<li><strong>Space Tenant:</strong> {$row['space_tenant']}</li>";
            echo "</ul>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No available spaces</p>";
    }
    ?>
    <!-- Modal for space details -->
    <div id="myModal" class="modal" style="z-index:9999 !important">
        <div class="modal-content" style="text-align: left; padding: 20px; position: relative;">
            <span class="close-btn" onclick="closeModal()"
                style="position: absolute; top: 10px; right: 10px; cursor: pointer; font-size: 20px; color: #000;">&times;</span>
            <div id="modalContent" style="margin-top: 20px;">
                <!-- Space information will be dynamically loaded here -->
            </div>
            <?php
            // Assuming you have a variable storing the selected space name
            // $selectedSpaceName = "Example Space";
            if(isset($_SESSION['status']) !== 'reserved' && isset($_SESSION['status']) !== 'occupied') {
                echo '<button id="applyButton" onclick="openAppModal()">Apply</button>';
            }
            ?>
        </div>
    </div>
</div>



<div id="appModal" class="modal">
    <div class="modal-dialog" role="document">
        <form method="POST" action='apply_space_process.php' enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="margin:auto; margin-left:0px!important">Apply for Space</h5>
                    <span class="close-btn" style="color: black!important" onclick="closeAppModal()">&times;</span>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <?php if(isset($successMessage)) {
                                echo "<p style='color: green;'>$successMessage</p>";
                            } elseif(isset($errorMessage)) {
                                echo "<p style='color: red;'>$errorMessage</p>";
                            }
                            ?>
                        </div>
                        <div class="col-xl-12 mb-10">
                            <input type="hidden" name="spacename" id="appSpacename" value="">
                            <input type="hidden" name="SpaceID" id="appSpaceID" value="">
                        </div>
                        <div class="col-xl-12" style="margin:auto;">
                            <div class="row">
                                <div class="col-md-4" style="margin:auto;">
                                    <label for="tenant_name">Tenant Name:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="tenant_name" value="<?php echo $_SESSION['uname']; ?>"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-md-4" style="margin:auto;">
                                    <label for="ap_email">Tenant Email:</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="ap_email" value="<?php echo $_SESSION['uemail']; ?>"
                                        readonly>
                                </div>
                            </div>
                            <hr />
                        </div>
                        
                        <!-- Requirements -->
                        <div class="col-xl-12 mt-2">
                            <div class="row">
                                <div class="col-md-12" style="text-align:center;">
                                    <h6>List of Requirements</h5>
                                </div>
                                <div class="col-md-6">
                                    <ul style="font-size:small; list-style-type: circle;">
                                        <li>
                                            <p>Letter of Intent</p>
                                        </li>
                                        <li>
                                            <p>DTI Certificate/SEC Registration</p>
                                        </li>
                                        <li>
                                            <p>Business Permit</p>
                                        </li>
                                        <li>
                                            <p>Barangay Clearance</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul style="font-size:small; list-style-type: circle;" >
                                        <li>
                                            <p>Sanitary Permit (for food stall)</p>
                                        </li>
                                        <li>
                                            <p>Health Certification of Personnel</p>
                                        </li>
                                        <li>
                                            <p>Proof of Billing (in the name of applicant)</p>
                                        </li>
                                        <li>
                                            <p>Photocopy of Government Issued ID</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-12 bold" margin:auto!important">
                                    Upload requirements in one pdf file:
                                </div>
                                <div class="col-md-12">
                                    <input type="file" id="pdf_requirements" name="pdf_requirements"
                                        accept="application/pdf">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" style="width:100%!important" type="submit"
                        name="apply">Submit Application</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(spaceName, spaceStatus, spaceDetails) {
        

        var modal = document.getElementById("myModal");
        var modalContent = document.getElementById("modalContent");
        var applyButton = document.getElementById("applyButton");

        // Show modal
        modal.style.display = "flex";

        // Set the spacename and status in the modalContent
        modalContent.innerHTML = `
            <img src="./uploads/${spaceDetails['space_img']}" alt="Space Image" style="display: block; margin: 0 auto; max-width: 100%; max-height: 200px;">
            <h2 style="text-align: center; font-weight: bold;">${spaceName}</h2>
             <ul>
               <li><strong>Owner:</strong> ${spaceDetails['space_owner']}</li>
               <li><strong>Space Dimension:</strong> ${spaceDetails['space_dimension']}</li>
               <li><strong>Status:</strong> ${spaceDetails['status']}</li>
               <li><strong>Rent Price:</strong> ${spaceDetails['space_price']}</li>
               <li><strong>Space Tenant:</strong> ${spaceDetails['space_tenant']}</li>
             </ul>
         `;

        // Set the spacename in the application form
        document.getElementById("appSpacename").value = spaceName;
        document.getElementById("appSpaceID").value = spaceDetails['space_id'];

        // Check if the space status is 'reserved' or 'occupied'
        if (spaceStatus === 'reserved' || spaceStatus === 'occupied') {
            // Hide the Apply button
            applyButton.style.display = "none";
        } else {
            // Show and enable the Apply button
            applyButton.style.display = "block";
            applyButton.removeAttribute('disabled');
        }
    }

    function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }

    function openAppModal() {
        // Close the first modal
        closeModal();

        var appModal = document.getElementById("appModal");
        var appSpacename = document.getElementById("appSpacename");

        // Get the spacename from the modalContent
        var selectedSpaceName = document.getElementById("modalContent").querySelector('h2').innerText;

        // Set the spacename in the application form
        appSpacename.value = selectedSpaceName;

        appModal.style.display = "flex";
    }

    function closeAppModal() {
        var appModal = document.getElementById("appModal");
        appModal.style.display = "none";
    }

    $("img").on("click", function(event) {
        console.log(event);
        var x = event.offsetX - this.offsetLeft;  
        var y = event.offsetY - this.offsetTop;
        console.log(this.offsetLeft)
        alert("X Coordinate: " + x + " Y Coordinate: " + y);
    });

</script>
<?php include('includes/footer.php'); ?>