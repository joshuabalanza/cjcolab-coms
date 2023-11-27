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

   // Check the status in the user_verification table
   $verificationStatus = "Not approved"; // Default status
   $verificationQuery = "SELECT status, first_name, last_name, address, gender, birthday FROM user_verification WHERE user_id = $uid";
   $verificationResult = mysqli_query($con, $verificationQuery);

   if ($verificationResult && mysqli_num_rows($verificationResult) > 0) {
       $verificationData = mysqli_fetch_assoc($verificationResult);
       $verificationStatus = $verificationData['status'];
   }

   // Get approved maps
   $uploadDirectory = __DIR__ . '/uploads/';
   $approvedMapQuery = "SELECT * FROM concourse_verification WHERE status = 'approved'";
   $approvedMapResult = mysqli_query($con, $approvedMapQuery);
?>
<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
   include('includes/header.php');
   include('includes/nav.php');
?>
<section style= "margin-top:90px;">
   <?php
      if ($verificationStatus === 'approved' && $utype === 'Owner'): ?>
   <h1>Dashboard</h1>
   <div>
      1. total maps <br/>
      2. total spaces/map?
      -total free/vacant spaces?
      - total taken spaces?
   </div>
   <div>
      <h6>pie chart?</h6>
      3. total users <br/>
      4. total user reservation/application?
   </div>
   <div>
      <h6>pie chart?</h6>
      5. total user assigned?<br/>
      6. total bills? /monthly reports?
   </div>
   <div>
      7. adding cancel reservations<br/>
      8. monthly revenue? based on rent bill? 
   </div>
   <?php elseif ($verificationStatus === 'rejected' && $utype === 'Owner'): ?>
   <h1>Dashboard</h1>
   <div>
      1. total maps <br/>
      2. total spaces/map?
      -total free/vacant spaces?
      - total taken spaces?
   </div>
   <div>
      <h6>pie chart?</h6>
      3. total users <br/>
      4. total user reservation/application?
   </div>
   <div>
      <h6>pie chart?</h6>
      5. total user assigned?<br/>
      6. total bills? /monthly reports?
   </div>
   <div>
      7. adding cancel reservations<br/>
      8. monthly revenue? based on rent bill? 
   </div>
   <div id="verificationModal" class="prompt-modal">
      <div class="modal-content">
         <span class="close">&times;</span>
         <p>Verify your account to add concourse.</p>
         <!-- Will change this-->
         <a href="verification_account.php" class="btn-sm btn btn-success">Verify Account</a>
      </div>
   </div>
   <?php elseif ($verificationStatus === 'approved' && $utype === 'Tenant'): ?>
   <h1>Dashboard</h1>
   <?php
      $sql = "SELECT * FROM space";
      $result = mysqli_query($con, $sql);
   
      $con->close();
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
   </style>
   <h1>Available Spaces</h1>
   <div class="container">
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
         <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <div id="modalContent">
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
                  <!-- Additional form fields as needed -->
                  <button type="submit" name="apply">Apply</button>
               </form>
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
   </div>
</section>
<?php else: ?>
<div id="verificationModal" class="prompt-modal">
   <div class="modal-content">
      <span class="close">&times;</span>
      <p>Verify your account to apply for space.</p>
      <!-- Will change this-->
      <a href="verification_account.php" class="btn-sm btn btn-success">Verify Account</a>
   </div>
</div>
<?php endif; ?>
</section>
<?php include('includes/footer.php'); ?>
