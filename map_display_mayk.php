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
    .modal-backdrop {
        z-index: 1050 !important;
    }

    .modal {
        z-index: 1051 !important;
    }

    .modal-open .modal {
        z-index: 1051 !important;
    }

    .modal-dialog {
        margin: 50px auto;
    }
    #fp-canvas-container {
        height: 50vh;
        width: calc(100%);
        position: relative;
    }

    .fp-img,
    .fp-canvas,
    .fp-canvas-2 {
        position: absolute;
        width: calc(100%);
        height: calc(100%);
        top: 0;
        left: 0;
        z-index: 1;
    }

    #fp-map {
        position: absolute;
        width: calc(100%);
        height: calc(100%);
        top: 0;
        left: 0;
        z-index: 1;
    }

    .fp-canvas {
        z-index: 2;
        background: #0000000d;
        cursor: crosshair;
    }

    #fp-map {
        z-index: 1;
    }
</style>

<section style="margin-top: 80px;">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Spaces</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Add this button at the top of the image section -->
                <div class="col-md-2">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#spaceNumberModal">How many spaces are for rent?</button>
                </div>
            </div>
            <div class="row">
                <div id="fp-canvas-container" class="col-md-6">
                    <?php
                    if ($concourseResult && mysqli_num_rows($concourseResult) > 0) {
                        $concourseData = mysqli_fetch_assoc($concourseResult);
                        echo '<img src="/COMS/uploads/' . $concourseData['concourse_map'] . '" alt="Concourse Map" class="fp-img" id="fp-img" usemap="#fp-map">';
                    } else {
                        echo 'Concourse not found.';
                    }
                    ?>
                </div>

                <!-- Container for generated cards -->
                <div class="col-md-6" id="generatedCardsContainer"></div>
            </div>


                <!-- Modal for inputting number of spaces -->
                <div class="modal fade" id="spaceNumberModal" tabindex="-1" role="dialog" aria-labelledby="spaceNumberModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="spaceNumberModalLabel">Enter Number of Spaces for Rent</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label for="numSpaces">Number of Spaces:</label>
                                <input type="number" id="numSpaces" class="form-control" placeholder="Enter the number of spaces">
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary">Close</button>
                                <button type="button" class="btn btn-primary" onclick="generateSpaceCards()">Generate Cards</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 space-sidebar-form">
                    <h3><?php echo isset($concourseData['concourse_name']) ? $concourseData['concourse_name'] : "Concourse"; ?></h3>
                </div>
                <div class="modal fade" id="spaceDetailsModal" tabindex="-1" role="dialog" aria-labelledby="spaceDetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="spaceDetailsModalLabel">Space Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                                <label for="space_height_modal">Space Height:</label>
                                <input type="number" id="space_height_modal" name="space_height" required>
                                <label for="status_modal">Space Status:</label>
                                <select id="status_modal" name="status">
                                    <option value="available">Available</option>
                                    <option value="reserved">Reserved</option>
                                    <option value="occupied">Occupied</option>
                                </select>
                                <label for="space_image_modal">Space Image:</label>
                                <input type="file" id="space_image_modal" name="space_image_modal" accept="image/*">
                                <button type="submit" name="submit_space_modal">Submit Space</button>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
$sql = "SELECT * FROM `space` WHERE concourse_id = $concourse_id ORDER BY space_id ASC";
$qry = $con->query($sql);
$tbl = array();
while ($row = $qry->fetch_assoc()) :
    $tbl[$row['space_id']] = array(
        "id" => $row['space_id'],
        "tbl_no" => $row['space_id'],
        "name" => $row['space_name'],
    );
    ?>
    <tr>
        <td class="text-center p-0"><?php echo $row['space_id'] ?></td>
        <td class="py-0 px-1"><?php echo $row['space_name'] ?></td>
    </tr>
<?php endwhile; ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Add your JavaScript at the end of the file -->
<script>
    $(document).ready(function () {
        // Check if the URL has the inserted_space parameter
        var insertedSpace = getParameterByName('inserted_space');
        if (insertedSpace) {
            // Open the modal for the inserted space
            openModalForSpace(insertedSpace);
        }
    });

    function openModalForSpace(spaceName) {
        // Find the card corresponding to the inserted space
        var cardIndex = findCardIndexBySpaceName(spaceName);

        // If the card is found, open its modal
        if (cardIndex !== -1) {
            openModal(cardIndex);
        }
    }

    function findCardIndexBySpaceName(spaceName) {
        // Loop through the cards to find the index of the card with the given spaceName
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
        var url = window.location.href = 'map_display_mayk.php?concourse_id=<?php echo $concourse_id; ?>&inserted_space=' + insertedSpaceName;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
    function generateSpaceCards() {
        var numSpaces = $('#numSpaces').val();
        var concourse_id = <?php echo $concourse_id; ?>;

// Dynamically generate the cards based on the number of spaces
var cardContainer = $('#generatedCardsContainer');

// Clear the existing content of the card container
cardContainer.html('');

for (var i = 1; i <= numSpaces; i++) {
    var cardHtml = `<div class='col-md-4'>
        <div class='card' onclick='openModal(${i})'>
            <h2>Space ${i}</h2>
            <div class='details' style='display: none;'>
                <form class='space-details-form' id='spaceForm_${i}'>
                    <!-- Add form fields as needed -->
                    <label for='space_name_${i}'>Space Name:</label>
                    <input type='text' id='space_name_${i}' name='space_name_${i}' required>
                    <button type='button' onclick='submitSpace(${i})'>Submit Space</button>
                </form>
            </div>
        </div>
    </div>`;

    cardContainer.append(cardHtml);
}


        $('#spaceNumberModal').modal('hide');

        // Enable the screen after hiding the modal
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    }
    function openModal(spaceNumber) {
    // Update space name in the space details form
    var spaceName = $('#spaceForm_' + spaceNumber + ' #space_name_' + spaceNumber).val();
    $('#space_name_modal').val(spaceName);

    // Show the space details modal
    $('#spaceDetailsModal').modal('show');
}


function submitSpace(spaceNumber) {
    // Get the form data for the specific space
    var formData = $('#spaceForm_' + spaceNumber).serialize();
    console.log("Form Data:", formData);  // Add this line for debugging

    // Send the data to the server using AJAX
    $.ajax({
        type: 'POST',
        url: 'submit_space.php',
        data: formData,
        success: function(response) {
            // Handle the server response if needed
            console.log(response);
        },
        error: function(error) {
            // Handle errors if any
            console.error(error);
        }
    });
}

</script>

<?php include('includes/footer.php'); ?>
