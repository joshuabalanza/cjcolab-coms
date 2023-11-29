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
    section {
        max-width: 100%;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        margin-top: 50px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: relative; /* Add relative positioning */
    }
    h2, h3 {
        color: #c19f90;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th,
    td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #9b593c;
        color: white;
    }

    .occupied-spaces-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .occupied-spaces-table th,
    .occupied-spaces-table td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    .occupied-spaces-table th {
        background-color: #9b593c;
        color: white;
    }

    .hidden {
        display: none;
    }

    .space-row {
        cursor: pointer;
    }

    .space-row:hover {
        background-color: #c19f90;
        color: white;
    }

    #leaseModal {
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
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 600px;
        text-align: center;
    }

    .close-modal {
        cursor: pointer;
        position: absolute;
        top: 10px;
        right: 10px;
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
    }

    .close-modal:hover,
    .close-modal:focus {
        color: black;
        text-decoration: none;
    }
    .modal-overlay {
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
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 600px;
        text-align: center;
    }

    /* Add this style for the action buttons with icons */
    .action-button {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        color: #007bff;
    }

    .action-button:hover {
        color: #0056b3;
    }
</style>

<?php
// Check if concourse_id is set in the URL
if (isset($_GET['concourse_id'])) {
    $concourseId = $_GET['concourse_id'];

    // Fetch concourse details based on the concourse_id
    $concourseQuery = "SELECT * FROM concourse_verification WHERE concourse_id = '$concourseId'";
    $result = $con->query($concourseQuery);

    if ($result->num_rows > 0) {
        $concourseDetails = $result->fetch_assoc();
        ?>
        <div class="container" style="margin-top: 100px;">
            <h1>Concourse Details</h1>
            <div class="card" style="width: 100%; height: 100%; padding: 10px; margin: 0 auto;">
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
                    <h5 class="card-title"><?php echo $concourseDetails['concourse_name']; ?></h5>
                    <p class="card-text">Concourse ID: <?php echo $concourseDetails['concourse_id']; ?></p>
                    <p class="card-text">Owner ID: <?php echo $concourseDetails['owner_id']; ?></p>
                    <p class="card-text">Owner Name: <?php echo $concourseDetails['owner_name']; ?></p>
                    <!-- Add more details as needed -->
                </div>
            </div>

            <section>
                <!-- Display occupied spaces with the modified table design -->
                <h2>Occupied Spaces</h2>
                <?php
                $occupiedSpacesQuery = "SELECT * FROM space WHERE concourse_id = '$concourseId' AND status = 'occupied'";
                $occupiedSpacesResult = $con->query($occupiedSpacesQuery);

                if ($occupiedSpacesResult->num_rows > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="occupied-spaces-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Space ID</th>';
                    echo '<th>Tenant</th>';
                    echo '<th>Action</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    while ($spaceRow = $occupiedSpacesResult->fetch_assoc()) {
                        echo '<tr class="space-row">';
                        echo '<td>' . $spaceRow['space_id'] . '</td>';
                        echo '<td>' . $spaceRow['space_tenant'] . '</td>';
                        echo '<td>';
                        // Open the modal when clicking the "Create Bill" button
                        echo '<button class="action-button" data-space-id="' . $spaceRow['space_id'] . '" onclick="openCreateBillModal(this)"><i class="fas fa-file-alt"></i></button>';
                        // Open the modal when clicking the "Update Bill" button
                        echo '<button class="action-button" data-space-id="' . $spaceRow['space_id'] . '" onclick="openUpdateBillModal(this)"><i class="fas fa-edit"></i></button>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<p>No occupied spaces found.</p>';
                }
                ?>
            </section>
        </div>

        <!-- Modal overlay and content for creating a bill -->
        <div id="createBillModal" class="modal-overlay">
            <div class="modal-content">
                <span class="close-modal" id="closeCreateBillModalBtn">&times;</span>
                <h3>Create Bill for Space</h3>
                <!-- Form to create a bill -->
                <form id="createBillForm" method="post" action="process_create_bill.php">
                    <input type="hidden" name="space_id" id="createBillSpaceIdInput" value="">
                    <input type="hidden" name="concourse_id" id="createBillSpaceIdInput" value="">
                    <label for="electric">Electric Bill:</label>
                    <input type="text" name="electric" required>
                    <label for="water">Water Bill:</label>
                    <input type="text" name="water" required>
                    <!-- Add more bill details as needed -->
                    <button type="submit" name="create_bill">Create Bill</button>
                </form>
            </div>
        </div>

  <!-- Modal overlay and content for updating a bill -->
<div id="updateBillModal" class="modal-overlay">
    <div class="modal-content">
        <span class="close-modal" id="closeUpdateBillModalBtn">&times;</span>
        <h3>Update Bill for Space</h3>
        <!-- Form to update a bill -->
        <form id="updateBillForm" method="post" action="process_update_bill.php">
            <input type="hidden" name="space_id" id="updateBillSpaceIdInput" value="">
            <label for="updatedElectricInput">Current Electric Bill:</label>
            <input type="text" name="updated_electric" id="updatedElectricInput" readonly>
            <label for="updatedWaterInput">Current Water Bill:</label>
            <input type="text" name="updated_water" id="updatedWaterInput" readonly>
            <!-- Add more updated bill details as needed -->
            <button type="submit" name="update_bill">Update Bill</button>
        </form>
    </div>
</div>


        <script>
    function openCreateBillModal(button) {
        const spaceId = button.getAttribute('data-space-id');
        document.getElementById('createBillSpaceIdInput').value = spaceId;
        document.getElementById('createBillModal').style.display = 'flex';
    }

    // Open the update bill modal
    function openUpdateBillModal(button) {
        const spaceId = button.getAttribute('data-space-id');
        document.getElementById('updateBillSpaceIdInput').value = spaceId;

        // Fetch the current bill details
        fetch('get_current_bill.php?space_id=' + spaceId)
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    // Populate the form fields with current bill details
                    document.getElementById('updatedElectricInput').value = data.electric;
                    document.getElementById('updatedWaterInput').value = data.water;
                    // Add more fields if needed
                } else {
                    console.error('Error:', data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });

        document.getElementById('updateBillModal').style.display = 'flex';
    }


            // Close the create bill modal
            document.getElementById('closeCreateBillModalBtn').addEventListener('click', function () {
                document.getElementById('createBillModal').style.display = 'none';
            });

            // Close the update bill modal
            document.getElementById('closeUpdateBillModalBtn').addEventListener('click', function () {
                document.getElementById('updateBillModal').style.display = 'none';
            });

// Handle form submission for create bill
document.getElementById('createBillForm').addEventListener('submit', function (event) {
    // Allow the form to be submitted in the traditional way
});

            // Handle form submission for update bill
            document.getElementById('updateBillForm').addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('process_update_bill.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response data if needed
                    console.log(data);
                    // Close the modal
                    document.getElementById('updateBillModal').style.display = 'none';
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        </script>

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

<?php include('includes/footer.php'); ?>