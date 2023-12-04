<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
ob_start();
?>

<?php
if (isset($_GET['concourse_id'])) {
    $concourse_id = $_GET['concourse_id'];
    $concourseQuery = "SELECT * FROM concourse_verification WHERE concourse_id = $concourse_id";
    $concourseResult = mysqli_query($con, $concourseQuery);
} else {
    echo 'Concourse ID not provided.';
}
include('includes/header.php');
include('includes/nav.php');
?>

<style>
    /* Style for the "Add Space" button */
    .add-space-button {
        position: absolute;
        bottom: 20px;
        right: 20px;
    }

    /* Style for the container with the image */
    .image-container {
        max-width: 100%;
        height: auto;
        position: relative;
        overflow: hidden;
    }

    /* Style for the card container */
    .card-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    /* Style for each card */
    .card {
        width: 200px;
        /* Adjust the width as needed */
        margin: 10px;
        cursor: pointer;
        transition: transform 0.3s ease-in-out;
    }

    .card:hover,
    .add-space-button {
        transform: scale(1.05);
    }

    .modal-backdrop {
        z-index: 1050 !important;
    }

    .modal,
    .modal-open .modal,
    .modal-dialog {
        z-index: 1051 !important;
        margin: 50px auto;
    }

    #fp-canvas-container {
        height: 50vh;
        width: 100%;
        position: relative;
    }

    .fp-img,
    .fp-canvas,
    .fp-canvas-2,
    #fp-map {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 1;
    }

    .fp-canvas {
        z-index: 2;
        background: #0000000d;
        cursor: crosshair;
    }

    /* Style for the card container */
    .container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        /* Adjust as needed */
    }

    .card {
        width: 18%;
        /* Adjust the width as needed */
        margin-bottom: 20px;
        /* Add margin to separate the cards */
        border: 1px solid #ddd;
        /* Add borders or styling as needed */
        box-sizing: border-box;
        cursor: pointer;
    }

    @media (max-width: 1200px) {

        /* Adjustments for smaller screens, you can modify as needed */
        .card {
            width: calc(25% - 20px);
        }
    }

    @media (max-width: 992px) {

        /* Adjustments for even smaller screens, you can modify as needed */
        .card {
            width: calc(33.333% - 20px);
        }
    }


    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent black overlay */
    }

    .modal-dialog {
        margin: 50px auto;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
    }

    .modal-body {
        padding-bottom: 20px;
    }

    /* Form styles */
    #spaceDetailsForm {
        display: flex;
        flex-direction: column;
    }

    label {
        margin-top: 10px;
    }

    input,
    select,
    button {
        margin-bottom: 15px;
        padding: 8px;
        width: 100%;
        box-sizing: border-box;
    }

    button {
        background-color: #007bff;
        /* Button color */
        color: #fff;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
        /* Button color on hover */
    }


    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        cursor: pointer;
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
        max-width: 400px;
        width: 100%;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        margin: 10px 0;
    }

    /* Style for the close button */
    .close-btn {
        color: #000;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 20px;
        position: absolute;
        top: 10px;
        right: 10px;
        opacity: 0.7;
        transition: opacity 0.3s ease-in-out;
        /* Added transition for smooth hover effect */
    }

    .close-btn:hover {
        opacity: 1;
        background-color: transparent;
        /* Set background to transparent on hover */
    }
</style>
<section style="margin-top: 80px;">
    <?php
    if ($concourseResult->num_rows > 0) {
        $concourseDetails = $concourseResult->fetch_assoc();
        ?>
        <div class="container">
            <h1 style="color: #c19f90;">Concourse Details</h1>
            <div class="card" style="width: 100%; height: 100%; padding: 10px; margin: 0 auto; position: relative;">
                <div class="image-container">
                    <?php
                    // Display concourse image or map (similar to how you did in the previous code)
                    if (!empty($concourseDetails['concourse_image'])) {
                        echo '<img src="/COMS/uploads/featured-concourse/' . $concourseDetails['concourse_image'] . '" id="concourseImage" class="card-img-top" alt="Concourse Image" style="width:100%; height: 300px;">';
                    } elseif (!empty($concourseDetails['concourse_map'])) {
                        echo '<img src="/COMS/uploads/' . $concourseDetails['concourse_map'] . '" id="concourseImage" class="card-img-top" alt="Concourse Map" style="width:100%; height: 300px;">';
                    } else {
                        echo '<img src="path_to_placeholder_image.jpg" id="concourseImage" class="card-img-top" alt="Placeholder Image" style="width:100%; height: 300px;">';
                    }
                    ?>

                </div>
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo $concourseDetails['concourse_name']; ?>
                    </h5>
                    <p class="card-text"><span class="bold-label">Address:</span>
                        <?php echo $concourseDetails['concourse_address']; ?>
                    </p>
                    <p class="card-text"><span class="bold-label">Owner Name:</span>
                        <?php echo $concourseDetails['owner_name']; ?>
                    </p>
                    <!-- Add more details as needed -->
                </div>
                <div class="add-space-button">
                    <button class="btn btn-primary" style="background-color:#9b593c;border:none;"
                        onclick="showSpaceDetailsForm()">Add Space</button>
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
    ?>
    </div>
    <!-- <div class="col-md-4 space-sidebar-form">
                    <h3><?php echo isset($concourseData['concourse_name']) ? $concourseData['concourse_name'] : "Concourse"; ?></h3>
                </div> -->
    <div class="modal fade" id="spaceDetailsModal" tabindex="-1" role="dialog" aria-labelledby="spaceDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="spaceDetailsForm" action="submit_space.php" method="post" enctype="multipart/form-data">
                        <!-- Space details form fields go here -->
                        <input type="hidden" name="concourse_id" value="<?php echo $concourse_id; ?>">
                        <h3>Space Details</h3>
                        <label for="space_name_modal">Space Name:</label>
                        <input type="text" id="space_name_modal" name="space_name" required>
                        <!-- Add other space details fields as needed -->
                        <label for="space_width_modal">Space Width:</label>
                        <input type="number" id="space_width_modal" name="space_width" required>
                        <label for="space_length_modal">Space Length:</label>
                        <input type="number" id="space_length_modal" name="space_length" required>
                        <!-- <label for="space_height_modal" hidden>Space Height:</label> -->
                        <!-- <input type="number" id="space_height_modal" name="space_height" hidden> -->
                        <label for="space_price">Rent Price:</label>
                        <input type="number" id="space_price" name="space_price" required>
                        <label for="status_modal">Space Status:</label>
                        <select id="status_modal" name="status">
                            <option value="available">Available</option>
                            <option value="reserved">Reserved</option>
                            <option value="occupied">Occupied</option>
                        </select>
                        <label for="space_image_modal">Space Image:</label>
                        <input type="file" id="space_image_modal" name="space_image_modal" accept="image/*">
                        <button type="submit" name="submit_space_modal"
                            style="background-color: #9b593c; border:none;">Submit Space</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    <?php
    $sql = "SELECT * FROM `space` WHERE concourse_id = $concourse_id ORDER BY space_id ASC";
    $result = mysqli_query($con, $sql);
    $con->close();
    ?>
    <h1 style="color: #c19f90;">SPACES</h1>
    <div class="container">
        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card' onclick='openSpaceModal(\"{$row['space_name']}\", \"{$row['status']}\", " . json_encode($row) . ")'>";
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
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div id="modalContent">
            </div>
        </div>
    </div>
</section>
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
<script>
    $(document).ready(function () {
        var spaceNameParam = getParameterByName('inserted_space');
        if (spaceNameParam) {
            openModalForSpace(spaceNameParam);
        }

    });

    //autocompute price
    $("#space_width_modal").on("input", function () {
        var space_length = (document.getElementById("space_length_modal").value);
        space_length = space_length.length > 0 ? space_length : 0;
        var space_width = document.getElementById("space_width_modal").value;
        space_width = space_width > 0 ? space_width : 0;

        var compute_price = (space_width * space_length) * 25;


        document.getElementById("space_price").value = compute_price;
    });

    $("#space_length_modal").on("input", function () {
        var space_length = (document.getElementById("space_length_modal").value);
        space_length = space_length.length > 0 ? space_length : 0;
        var space_width = document.getElementById("space_width_modal").value;
        space_width = space_width > 0 ? space_width : 0;

        var compute_price = (space_width * space_length) * 25;


        document.getElementById("space_price").value = compute_price;
    });

    function openModalForSpace(spaceName) {
        var cardIndex = findCardIndexBySpaceName(spaceName);
        if (cardIndex !== -1) {
            openModal(cardIndex);
        }
    }

    function findCardIndexBySpaceName(spaceName) {
        var cards = $('.card');
        for (var i = 0; i < cards.length; i++) {
            var card = cards.eq(i);
            var cardSpaceName = card.find('h2').text().replace('Space ', '');

            if (cardSpaceName === spaceName) {
                return i + 1; // Index is 1-based
            }
        }

        return -1; // Card not found
    }

    function getParameterByName(name) {
        var url = window.location.href = 'map_display.php?concourse_id=<?php echo $concourse_id; ?>&inserted_space=' + insertedSpaceName;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    function openModal(spaceNumber) {
        // Update space name in the space details form
        var spaceName = $('#spaceForm_' + spaceNumber + ' #space_name_' + spaceNumber).val();
        $('#space_name_modal').val(spaceName);

        // Show the space details modal
        $('#spaceDetailsModal').modal('show');
    }

    function openSpaceModal(spaceName, spaceStatus, spaceDetails) {
        var modal = document.getElementById("myModal");
        var modalContent = document.getElementById("modalContent");

        // Show modal
        modal.style.display = "flex";

        // Set the spacename and status in the modalContent
        modalContent.innerHTML = `
            <img src="/COMS/uploads/${spaceDetails['space_img']}" alt="Space Image" style="max-width: 100%; max-height: 200px;">
            <h2>${spaceName}</h2>
            <p>Status: ${spaceStatus}</p>
            <ul>
                <li><strong>Space ID:</strong> ${spaceDetails['space_id']}</li>
                <li><strong>Concourse ID:</strong> ${spaceDetails['concourse_id']}</li>
                <li><strong>Owner:</strong> ${spaceDetails['space_owner']}</li>
                <li><strong>Status:</strong> ${spaceDetails['status']}</li>
                <li><strong>Space Width:</strong> ${spaceDetails['space_width']}</li>
                <li><strong>Space Length:</strong> ${spaceDetails['space_length']}</li>
                <li><strong>Space Height:</strong> ${spaceDetails['space_height']}</li>
                <li><strong>Space Area:</strong> ${spaceDetails['space_area']}</li>
                <li><strong>Space Dimension:</strong> ${spaceDetails['space_dimension']}</li>
                <li><strong>Space Tenant:</strong> ${spaceDetails['space_tenant']}</li>
            </ul>
        `;
    }

    function closeModal() {
        var modal = document.getElementById("myModal");
        modal.style.display = "none";
    }

    // Updated function to directly show the space details modal
    function showSpaceDetailsForm() {
        $('#spaceDetailsModal').modal('show');
    }
</script>

</script>
<?php include('includes/footer.php'); ?>