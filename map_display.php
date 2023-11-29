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
    $spacesQuery = "SELECT * FROM space WHERE concourse_id = $concourse_id";
    $spacesResult = mysqli_query($con, $spacesQuery);
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

    /* Styles for the card */
    .card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
        cursor: pointer;
    }

    /* Styles for the modal */
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
      .close-btn {
      background-color: #3498db;
      color: #fff;
      border: none;
      padding: 8px 16px;
      border-radius: 4px;
      cursor: pointer;
      }
</style>
<section style="margin-top: 80px;">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Spaces</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Button to trigger spaceNumberModal -->
                <div class="col-md-2">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#spaceNumberModal">Add Space</button>
                </div>
            </div>
            <div class="row">
                    <!-- Container for concourse map -->
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
                <div class="col-md-6" id="generatedCardsContainer">
                    <?php
                    if ($spacesResult && mysqli_num_rows($spacesResult) > 0) {
                        while ($spaceData = mysqli_fetch_assoc($spacesResult)) {
                            echo "<div class='col-md-4'>
                                    <div class='card' onclick='openModal(\"{$spaceData['space_name']}\", \"{$spaceData['status']}\", " . json_encode($spaceData) . ")'>
                                        <h2>{$spaceData['space_name']}</h2>
                                        <div class='details' style='display: none;'>
                                            <form class='space-details-form' id='spaceForm_{$spaceData['space_id']}'>
                                                <label for='space_name_modal'>Space Name:</label>
                                                <input type='text' id='space_name_modal' name='space_name' required>
                                                <button type='button' onclick='submitSpace({$spaceData['space_id']})'>Submit Space</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>";
                        }
                    } else {
                        echo "<p>No spaces found for the selected concourse.</p>";
                    }
?>
                </div>
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
<?php
$sql = "SELECT * FROM `space` WHERE concourse_id = $concourse_id ORDER BY space_id ASC";
$result = mysqli_query($con, $sql);
$con->close();
?>
    <h1>SPACES</h1>
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
        <div id="myModal" class="modal">
        <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <div id="modalContent">
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
        var cardContainer = $('#generatedCardsContainer');
        cardContainer.html('');

        for (var i = 1; i <= numSpaces; i++) {
            var spaceName = 'Space ' + i;
            var cardHtml = `<div class='col-md-4'>
                                <div class='card' onclick='openModal(${i})'>
                                    <h2>${spaceName}</h2>
                                    <div class='details' style='display: none;'>
                                        <form class='space-details-form' id='spaceForm_${i}'>
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

    function submitSpace(spaceNumber) {
        var formData = $('#spaceForm_' + spaceNumber).serialize();
        console.log("Form Data:", formData);

        $.ajax({
            type: 'POST',
            url: 'submit_space.php',
            data: formData,
            success: function(response) {
                console.log(response);
            },
            error: function(error) {
                console.error(error);
            }
        });
    }
</script>
<?php include('includes/footer.php'); ?>
