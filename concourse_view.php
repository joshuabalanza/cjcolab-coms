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
   align-items: center;
   }
   #appModalContent {
   background-color: #fff;
   border-radius: 8px;
   box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
   padding: 20px;
   max-width: 400px;
   width: 100%;
   }
   .bold-label{
    font-weight:bold;
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
        <div class="container" style="margin-top: 120px;">
            <h5 style="color: #fff;">CONCOURSE DETAILS</h5>
            <div class="card" style="width: 100%; height: 100%; padding: 10px; margin: 0 auto;">
                <div class="image-container">
                    <?php
                    // Display concourse image or map (similar to how you did in the previous code)
                    if (!empty($concourseDetails['concourse_image'])) {
                        echo '<img src="./uploads/featured-concourse/' . $concourseDetails['concourse_image'] . '" id="concourseImage" class="card-img-top" alt="Concourse Image" style="width:100%; height: 300px;">';
                    } elseif (!empty($concourseDetails['concourse_map'])) {
                        echo '<img src="./uploads/' . $concourseDetails['concourse_map'] . '" id="concourseImage" class="card-img-top" alt="Concourse Map" style="width:100%; height: 300px;">';
                    } else {
                        echo '<img src="path_to_placeholder_image.jpg" id="concourseImage" class="card-img-top" alt="Placeholder Image" style="width:100%; height: 300px;">';
                    }
        ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo $concourseDetails['concourse_name']; ?></h5>
                    <!-- <p class="card-text">Concourse ID: <?php echo $concourseDetails['concourse_id']; ?></p>
                    <p class="card-text">Owner ID: <?php echo $concourseDetails['owner_id']; ?></p> -->
                    <p class="card-text"><span class="bold-label">Address:</span> <?php echo $concourseDetails['concourse_address']; ?></p>
                    <p class="card-text"><span class="bold-label">Owner Name:</span> <?php echo $concourseDetails['owner_name']; ?></p>
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
         if ($result && mysqli_num_rows($result) > 0) {
             while ($row = $result->fetch_assoc()) {
                 echo "<div class='card' onclick='openModal(\"{$row['space_name']}\", \"{$row['status']}\", " . json_encode($row) . ")'>";
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
      <div id="myModal" class="modal">
      <div class="modal-content" style="text-align: left; padding: 20px; position: relative;">
         <span class="close-btn" onclick="closeModal()" style="position: absolute; top: 10px; right: 10px; cursor: pointer; font-size: 20px; color: #000;">&times;</span>
            <div id="modalContent" style="margin-top: 20px;">
               <!-- Space information will be dynamically loaded here -->
            </div>
            <?php
               // Assuming you have a variable storing the selected space name
               // $selectedSpaceName = "Example Space";
               if (isset($_SESSION['status']) !== 'reserved' && isset($_SESSION['status']) !== 'occupied') {
                   echo '<button id="applyButton" onclick="openAppModal()">Apply</button>';
               }
               ?>
         </div>
      </div>
      <div id="appModal" class="modal">
         <div class="modal-content">
            <span class="close-btn" onclick="closeAppModal()">&times;</span>
            <div>
               <?php
                  if (isset($successMessage)) {
                      echo "<p style='color: green;'>$successMessage</p>";
                  } elseif (isset($errorMessage)) {
                      echo "<p style='color: red;'>$errorMessage</p>";
                  }
                  ?>
               <h2>Apply for Space</h2>
               <form method="POST" action='apply_space_process.php'>
                  <input type="hidden" name="spacename" id="appSpacename" value="">
                  <label for="tenant_name">Tenant Name:</label>
                  <input type="text" name="tenant_name" value="<?php echo $_SESSION['uname']; ?>" readonly>
                  <label for="ap_email">Tenant Email:</label>
                  <input type="email" name="ap_email" value="<?php echo $_SESSION['uemail']; ?>" readonly>
                  <label for="requirements">Attach copy of requirements:</label>
                  <input type="file" id="requirements" name="requirements" accept="application/pdf">
                  <!-- Additional form fields as needed -->
                  <button type="submit" name="apply">Apply</button>
               </form>
            </div>
         </div>
      </div>
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
            <img src="/COMS/uploads/${spaceDetails['space_img']}" alt="Space Image" style="display: block; margin: 0 auto; max-width: 100%; max-height: 200px;">
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
     
</script>
<?php include('includes/footer.php'); ?>