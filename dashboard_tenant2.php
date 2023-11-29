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
   
   
   
   // Space overview available/occupied/reserved
   $propertyOverviewQuery = "SELECT status, COUNT(*) AS count FROM space GROUP BY status";
   $propertyOverviewResult = mysqli_query($con, $propertyOverviewQuery);
   
   
   
   // Fetch Data into an associative Array
   $propertyOverviewData = [];
   while ($row = mysqli_fetch_assoc($propertyOverviewResult)) {
    $propertyOverviewData[$row['status']] = $row['count'];
   }
   
   // Calculate total spaces
   $totalSpacesQuery = "SELECT COUNT(*) AS total FROM space";
   $totalSpacesResult = mysqli_query($con, $totalSpacesQuery);
   $totalSpacesRow = mysqli_fetch_assoc($totalSpacesResult);
   $totalSpaces = $totalSpacesRow['total'];
   
   // Calculate percentage occupancy
   $percentOccupied = ($propertyOverviewData['occupied'] / $totalSpaces) * 100;
   $percentAvailable = ($propertyOverviewData['available'] / $totalSpaces) * 100;
   
   // Close result sets
   mysqli_free_result($propertyOverviewResult);
   mysqli_free_result($totalSpacesResult); 
   
   
   // Fetch tenant data for Tenant Management
   $tenantManagementQuery = "SELECT utype, COUNT(*) AS count FROM user WHERE utype = 'Tenant'";
   $tenantManagementResult = mysqli_query($con, $tenantManagementQuery);
   
   // Fetch data into an associative array
   $tenantManagementData = mysqli_fetch_assoc($tenantManagementResult);
   
   // Close result set
   mysqli_free_result($tenantManagementResult);
   
   $totalBillsQuery = "SELECT SUM(total) AS totalBills FROM bill";
   $totalBillsResult = mysqli_query($con, $totalBillsQuery);
   $totalBills = 0;
   
   if ($totalBillsResult && mysqli_num_rows($totalBillsResult) > 0) {
    $totalBillsData = mysqli_fetch_assoc($totalBillsResult);
    $totalBills = $totalBillsData['totalBills'];
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

   <style>
   .dashboard-reports {
   font-family: Arial, sans-serif;
   margin: 0;
   padding: 0;
   display: flex;
   flex-wrap: wrap;
   justify-content: space-evenly;
   text-align: center;
   }
   .row {
   display: flex;
   flex-wrap: wrap;
   justify-content: space-evenly;
   }
   section {
   flex: 1;
   padding: 20px;
   background-color: #f4f4f4;
   margin: 10px;
   border-radius: 5px;
   height: 260px;
   }
   h2 {
   color: #c19f90;
   }
   .section-content {
   display: flex;
   justify-content: space-between;
   align-items: center;
   }
   .section-item,
   .pie-chart {
   flex: 1;
   padding: 10px;
   background-color: #fff;
   border-radius: 5px;
   margin: 10px;
   height: 150px; /* Set the desired height */
   display: flex;
   flex-direction: column;
   align-items: center;
   }
   .section-item i,
   .section-item h5 {
   margin-top: auto;
   margin-bottom: 10px;
   }
   .section-item p {
   font-size: 40px; /* Set the desired font size */
   margin-top: 20px;
   }
   .pie-chart {
   text-align: center;
   padding-top: 35px;
   }
   .pie-chart canvas {
   display: flex;
   justify-content: center;
   align-items: center;
   }
   #tenantPieChart,
   #reservationPieChart,
   #propertyOverviewPieChart {
   max-width: 80px;
   height: auto;
   display: block;
   margin: 0 auto;
   }
   #feedbackSection {
   background-color: #f4f4f4;
   padding: 20px;
   border-radius: 10px;
   flex: 1; /* Take full width */
   display: flex;
   flex-direction: column;
   align-items: center;
   max-height: 300px; /* Adjust the max-height as needed */
   overflow-y: auto;
   }
   #feedbackList,
   #tenantList {
   list-style: none;
   padding: 0;
   width: 100%;
   max-width: 400px; /* Adjust the max-width as needed */
   }
   #feedbackList li,
   #tenantList li {
   margin-bottom: 10px;
   border-bottom: 1px solid #ccc;
   padding-bottom: 5px;
   text-align: center;
   }
   /* Adjusted pie chart sizes */
   canvas {
   max-width: 80px;
   height: 100%;
   display: block;
   margin: 0 auto;
   }
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

<div class="dashboard-body" style= "margin-top:90px;">
<h1>Dashboard</h1>
<div class="dashboard-reports" >
   <!-- **********************************-->
   <!-- ************ OWNER ***************-->
   <!-- **********************************-->
   <?php if ($verificationStatus === 'approved' && $utype === 'Owner'): ?>
   <div class ="row">
      <section>
         <h2>Space Overview</h2>
         <div class="section-content">
            <div class="section-item">
               <p><?php echo $propertyOverviewData['occupied']; ?></p>
               <i class="fas fa-map-marker-alt"></i> <!-- Icon for Maps -->
            </div>
            <div class="section-item">
               <p><?php echo $totalSpaces; ?></p>
               <i class="fas fa-th-large"></i> <!-- Icon for Spaces -->
            </div>
            <div class="pie-chart">
               <canvas id="propertyOverviewPieChart"></canvas>
            </div>
         </div>
      </section>
      <section style="width: 600px;">
         <h2>Tenant Management</h2>
         <div class="section-content">
            <?php
               // Count the number of active and inactive tenants
               $activeTenantQuery = "SELECT COUNT(*) as count FROM `user` WHERE `utype` = 'Tenant' AND `status` = 'active'";
               $inactiveTenantQuery = "SELECT COUNT(*) as count FROM `user` WHERE `utype` = 'Tenant' AND `status` = 'inactive'";
               
               $activeTenantResult = mysqli_query($con, $activeTenantQuery);
               $inactiveTenantResult = mysqli_query($con, $inactiveTenantQuery);
               
               $activeTenantCount = ($activeTenantResult && mysqli_num_rows($activeTenantResult) > 0) ? mysqli_fetch_assoc($activeTenantResult)['count'] : 0;
               $inactiveTenantCount = ($inactiveTenantResult && mysqli_num_rows($inactiveTenantResult) > 0) ? mysqli_fetch_assoc($inactiveTenantResult)['count'] : 0;
               ?>
            <div class="section-item">
               <p><?php echo $activeTenantCount; ?></p>
               <i class="fas fa-users"></i> <!-- Icon for Active Tenants -->
            </div>
            <div class="section-item">
               <p><?php echo $inactiveTenantCount; ?></p>
               <i class="fas fa-users"></i> <!-- Icon for Inactive Tenants -->
            </div>
            <div class="pie-chart">
               <canvas id="tenantPieChart"></canvas>
            </div>
         </div>
      </section>
      <section>
         <h2>Reservation Tracking</h2>
         <div class="section-content">
            <div class="section-item">
               <p>28</p>
               <i class="fas fa-calendar-check"></i> <!-- Icon for Reservations -->
            </div>
            <div class="section-item">
               <p>65</p>
               <i class="fas fa-file-alt"></i> <!-- Icon for Applications -->
            </div>
            <div class="pie-chart">
               <canvas id="reservationPieChart"></canvas>
            </div>
         </div>
      </section>
   </div>
   <div class="row">
      <section>
         <h2>Financial Overview</h2>
         <div class="section-content">
            <div class="section-item">
               <p>$<?php echo number_format($totalBills, 2); ?></p>
               <h5>Total Bills</h5>
            </div>
            <div style="padding-top: 35px;" class="section-item">
               <button style="margin-bottom: 10px;">Billing Information</button>
               <button>Financial Reports</button>
            </div>
         </div>
      </section>
      <section style="width: 600px;" id="feedbackSection">
         <h2>Feedback</h2>
         <div class="section-content" style="width: 300px;">
            <ul id="feedbackList">
               <li>Ekis dito</li>
               <li>Walang kuryente</li>
               <li>4 years online parin</li>
               <li>Midterm na </li>
               <li>1 palang ftf class </li>
               <li>Ngyek</li>
            </ul>
            <ul id="tenantList">
               <li>Walter White</li>
               <li>Jesse Pinkman</li>
               <li>Mike Ermanshrout</li>
               <li>Gus Fring</li>
               <li>Isagi Yoichi</li>
               <li>Eren Yeager</li>
            </ul>
         </div>
      </section>
   </div>
   <!-- **********************************-->
   <!-- ************ OWNER NOT VERIFIED **************-->
   <!-- **********************************-->
   <?php elseif ($verificationStatus === 'rejected' && $utype === 'Owner'): ?>
   <div id="verificationModal" class="prompt-modal">
      <div class="modal-content">
         <span class="close">&times;</span>
         <p>Verify your account to add concourse.</p>
         <a href="verification_account.php" class="btn-sm btn btn-success">Verify Account</a>
      </div>
   </div>
   <!-- **********************************-->
   <!-- ************ TENANT VERIFIED **************-->
   <!-- **********************************-->
   <?php elseif ($verificationStatus === 'approved' && $utype === 'Tenant'): ?>
   <!-- <h1>Dashboard</h1> -->
   <?php
      $sql = "SELECT * FROM space";
      $result = mysqli_query($con, $sql);
      
      $con->close();
      ?>
   <!-- <h1>Available Spaces</h1> -->
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
   </div>
</div>
<?php endif; ?>

<script>
  function openModal(spaceName, spaceStatus, spaceDetails) {
         var modal = document.getElementById("myModal");
         var modalContent = document.getElementById("modalContent");
         var applyButton = document.getElementById("applyButton");
     
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
