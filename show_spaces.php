<?php
session_start(); // Add session_start at the beginning
session_name("user_session");
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');

// Check if the user is not logged in
if (!isset($_SESSION['uid'])) {
    header('Location: login.php');
    exit();
}

$sql = "SELECT * FROM space WHERE status = 'available'";
$result = $con->query($sql);

$con->close();
?>
<?php
include('includes/header.php');
include('includes/nav.php');
?>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
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
</head>
<body>
    <h1>Available Spaces</h1>
    <div class="container">
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card' onclick='openModal(\"{$row['space_name']}\")'>";
                echo "<h2>{$row['space_name']}</h2>";
                echo "<div class='details' style='display: none;'>";
                echo "<ul>";
                echo "<li><strong>Space ID:</strong> {$row['space_id']}</li>";
                echo "<li><strong>Concourse ID:</strong> {$row['concourse_id']}</li>";
                echo "<li><strong>Status:</strong> {$row['status']}</li>";
                echo "<li><strong>Space Width:</strong> {$row['space_width']}</li>";
                echo "<li><strong>Space Length:</strong> {$row['space_length']}</li>";
                echo "<li><strong>Space Height:</strong> {$row['space_height']}</li>";
                echo "<li><strong>Space Area:</strong> {$row['space_area']}</li>";
                echo "<li><strong>Space Dimension:</strong> {$row['space_dimension']}</li>";
                echo "<li><strong>Space Status:</strong> {$row['space_status']}</li>";
                echo "<li><strong>Space Tenant:</strong> {$row['space_tenant']}</li>";
                echo "</ul>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No available spaces</p>";
        }
        ?>

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <div id="modalContent">
                    <!-- Space information will be dynamically loaded here -->
                </div>
            </div>
        </div>

        <script>
            function openModal(spaceName) {
                var modal = document.getElementById("myModal");
                var modalContent = document.getElementById("modalContent");

                // Find the details div corresponding to the clicked space
                var details = document.querySelector(".card div.details");

                // Hide details divs for all cards
                var allDetails = document.querySelectorAll(".card div.details");
                allDetails.forEach(function(item) {
                    item.style.display = "none";
                });

                // Show details for the clicked card
                details.style.display = "block";

                modalContent.innerHTML = details.innerHTML;
                modal.style.display = "flex";
            }

            function closeModal() {
                var modal = document.getElementById("myModal");
                modal.style.display = "none";
            }
        </script>
    </div>
<?php include('includes/footer.php'); ?>
