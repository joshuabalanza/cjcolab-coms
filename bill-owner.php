<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('includes/dbconnection.php');

if (isset($_POST['SavaChangesBilling'])) {
    $billingElectricAmount = $_POST['billingElectricAmount'];
    $billingWaterAmount = $_POST['billingWaterAmount'];
                
    if (empty($_POST['billingElectricAmount']) || empty($_POST['billingWaterAmount'])) {
        $error_message = "<span style='color: red;'>All fields are required</span> <br/>";
    } 
    else {
        $sql1 = "INSERT INTO billing_setup (BillingCode, BillingName, Amount) VALUES ('ElectricBillRate','Electric Bill Rate','$billingElectricAmount'),  ('WaterBillRate','Water Bill Rate','$billingWaterAmount')";
        $con->query($sql1);

        $billingquery = "SELECT * FROM billing_setup WHERE billingcode = 'WaterBillRate' ORDER BY DateAsof DESC LIMIT 1";
        $billingresult = $con->query($billingquery);
        if ($billingresult->num_rows > 0) {
            $row = $billingresult->fetch_assoc();
            $varbillingWaterAmount = $row['Amount'];
            $varbillingAmountAsOf = $row['DateAsOf'];
        }

        $billingquery2 = "SELECT * FROM billing_setup WHERE billingcode = 'ElectricBillRate' ORDER BY DateAsof DESC LIMIT 1";
        $billingresult2 = $con->query($billingquery2);
        if ($billingresult2->num_rows > 0) {
            $row2 = $billingresult2->fetch_assoc();
            $varbillingElectricAmount = $row2['Amount'];
        }     
        // RefreshContent(); 
        $showSuccessModal = true;
        
    }
}


// Calculate total amount
$totalAmountQuery = "SELECT SUM(total) AS totalAmount FROM bill";
$totalAmountResult = $con->query($totalAmountQuery);
$totalAmountRow = $totalAmountResult->fetch_assoc();    
$totalAmount = isset($totalAmountRow['totalAmount']) ? $totalAmountRow['totalAmount'] : 0;

// Fetch billing information
$sql = "SELECT tenant_name, space_id, total, due_date, electric, water, space_bill FROM bill";
$result = $con->query($sql);

$varbillingWaterAmount="";
$varbillingAmountAsOf="";
$varbillingElectricAmount="";


$billingquery = "SELECT * FROM billing_setup WHERE billingcode = 'WaterBillRate' ORDER BY DateAsof DESC LIMIT 1";
$billingresult = $con->query($billingquery);
if ($billingresult->num_rows > 0) {
    $row = $billingresult->fetch_assoc();
    $varbillingWaterAmount = $row['Amount'];
    $varbillingAmountAsOf = $row['DateAsOf'];
}

$billingquery2 = "SELECT * FROM billing_setup WHERE billingcode = 'ElectricBillRate' ORDER BY DateAsof DESC LIMIT 1";
$billingresult2 = $con->query($billingquery2);
if ($billingresult2->num_rows > 0) {
    $row2 = $billingresult2->fetch_assoc();
    $varbillingElectricAmount = $row2['Amount'];
}     


// Close the database connection
$con->close();

?>
 
<?php
include('includes/header.php');
include('includes/nav.php');
?>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #9b593c;
    }

    h2, h3 {
        color: #c19f90;
    }

    section {
        max-width: 950px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #9b593c;
        color: white;
    }


    tbody tr:hover {
        cursor: pointer;
        background-color: #c19f90;
        color: white;}
 
        tbody tr:hover {
        cursor: pointer;
        background-color: #c19f90;
        color: white;
    }

    .pay-btn,
    .history-btn,
    .receipt-btn {
        padding: 5px 10px;
        margin-right: 5px;
        background-color: #9b593c;
        color: #fff;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    #outstandingAmount,
    #totalBill,
    #paymentHistoryModal,
    #chargeBreakdownModal,
    #receiptModal {
        display: none;
    }

    .modal{
        overflow-y: initial !important
    }

    .modal-content, .modal-body, .modal-dialog{
        width:1000px !important;
        max-width: 800px !important;
    }

    #paymentHistoryModal .modal-content table {
        margin-top: 10px;
    }
</style>
</head>

<body>


<div id="BillDetailsModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Bill Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeBillDetails()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <table style="width:90%">
            <thead>
                <tr><th>Due Date</th>
                    <th>Outstanding Amount</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $space_id = $_GET["spaceid"];
                try{
                    $query = "SELECT * FROM bill where space_id = '$space_id' ORDER BY bill_id DESC";
                    $result = mysqli_query($con, $query);
    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['due_date']}</td>";
                        echo "<td>₱{$row['total']}</td>";
                        echo "<td>{$row['paymentstatus']}</td>";
                        echo "</tr>";
                    }
                }
                catch (Exception $e) {
                    return false; // Email sending failed
                }
               
                ?>
            </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<section id="bill-table" style="margin-top: 100px;">
    <button class="btn btn-primary" onclick="redirectCreateAccountant()">Create Accountant</button>
    <button class="btn btn-primary" onclick="showBillingModal()">Manage Billing Amount</button>
    <div class="pt-3"></div>
        <h2>Bill Summary</h2>
        <div>
            <?php
            $owner_name = $_SESSION['uname'];
            $query = "WITH latestbilling AS ( SELECT m.*, ROW_NUMBER() OVER (PARTITION BY space_id ORDER BY bill_id DESC) AS rn FROM bill AS m WHERE owner_name = '$owner_name'  ) SELECT SUM(total) as totalAmount FROM latestbilling WHERE rn = 1";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);

            $totalAmount = $row['totalAmount'];
            echo "<p> Total Amount: P$totalAmount</p>"
            ?>
        </div>
        <div id="totalBill"></div>
        <table>
            <thead>
                <tr>
                    <th>Space id</th>
                    <th>Tenant Name</th>
                    <th>Due Date</th>
                    <th>Outstanding Amount</th>
                    <th>Payment Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php

                try{
                    $query = "
                    WITH latestbilling AS ( SELECT m.*, ROW_NUMBER() OVER (PARTITION BY space_id ORDER BY bill_id DESC) AS rn FROM bill AS m WHERE owner_name = '$owner_name'  ) SELECT * FROM latestbilling WHERE rn = 1";
                    $result = mysqli_query($con, $query);
    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>{$row['space_id']}</td>";
                        echo "<td>{$row['tenant_name']}</td>";
                        echo "<td>{$row['due_date']}</td>";
                        echo "<td>₱{$row['total']}</td>";
                        echo "<td>{$row['paymentstatus']}</td>";
                        echo '<td><button class="action-button" data-space-id="' . $row['space_id'] . '" onclick="openBillingDetailsModal(this)"><i class="fas fa-file-alt"></i></button></td>';
                        echo "</tr>";
                    }
                }
                catch (Exception $e) {
                    return false; // Email sending failed
                }
               
                ?>
            </tbody>
        </table>

        <div id="receiptModal" class="modal">
            <div class="modal-content">
                <span onclick="closeReceiptModal()" style="float: right; cursor: pointer;">&times;</span>
                <h3>Upload Receipt</h3>
                <p>Please upload an image of your receipt.</p>
                <!-- File input for receipt upload -->
                <input type="file" id="receiptUpload" accept="image/*">
                <button onclick="submitReceipt()">Submit Receipt</button>
            </div>
        </div>
    </section>

    
    <div id="chargeBreakdownModal" class="modal">
        <div class="modal-content" id="chargeBreakdownContent">
            <span class="close-modal" onclick="closeChargeBreakdownModal()">&times;</span>
            <h3>Charge Breakdown</h3>
            <table id="chargeBreakdownTable">
                <thead>
                    <tr>
                        <th>Charge Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows dynamically populated with JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <div id="billingModal" class="modal">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    <span class="close" onclick="closeBillingModal()">&times;</span>
                    <h4>Manage Billing</h4>
                    <div>
                        <label for="billingElectricAmount">Electric Bill Rate (per kilowats)</label>
                        <input type="text" name="billingElectricAmount" id="billingElectricAmount" value="<?php echo $varbillingElectricAmount ?>"/>
                    </div>
                    <div>
                        <label for="billingWaterAmount">Water Bill Rate (per cubic)</label>
                        <input type="text" name="billingWaterAmount" id="billingWaterAmount" value="<?php echo $varbillingWaterAmount ?>"/>
                    </div>
                    <div class="pt-1" style="color:gray">Last Modified: <?php echo $varbillingAmountAsOf ?></div>
                    <div class="button pt-3">
                        <input type="submit" name="SavaChangesBilling" />
                    </div>
                </form>
            </div>
        </div>  
    </div>
    <div id="successModal" class="modal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <span class="close" onclick="closeSuccessModal()">&times;</span>
                <h4>Successful!</h4>
                <p>Changes on Billing Rate has been saved</p>
            </div>
        </div>
    </div>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const modalOverlay = document.querySelector('.modal');
            modalOverlay.addEventListener('click', function (event) {
                if (event.target === modalOverlay) {
                    closeChargeBreakdownModal();
                }
              
            });
                let searchParams = new URLSearchParams(window.location.search)
                console.log(searchParams.has('spaceid'))
                if (searchParams.has('spaceid')){
                    document.getElementById('BillDetailsModal').style.display = 'flex';
                }

            const billingTable = document.getElementById('billingTable');
            const tbody = billingTable.querySelector('tbody');
            const chargeBreakdownContent = document.getElementById('chargeBreakdownContent');

            tbody.addEventListener('click', function (event) {
                const tr = event.target.closest('tr');
                if (tr) {
                    const electric = tr.getAttribute('data-electric');
                    const water = tr.getAttribute('data-water');
                    const spaceBill = tr.getAttribute('data-space-bill');
                    showChargeBreakdown({ electric, water, spaceBill });
                }
            });
        });
     
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }

         function closeBillDetails() {
                document.getElementById('BillDetailsModal').style.display = 'none';
                location.href = "bill-owner.php";
            };

        function openBillingDetailsModal(button) {
            var spaceId = button.getAttribute('data-space-id');
            
            let searchParams = new URLSearchParams(window.location.search)
            console.log(searchParams.has('spaceid'))
            if (searchParams.has('spaceid') == false){
                location.href = "bill-owner.php?spaceid="+spaceId;
                
            }
            document.getElementById('BillDetailsModal').style.display = 'flex';

            console.log(spaceId,'spaceId');
        }

        function showChargeBreakdown(data) {
            const chargeBreakdownData = [
                { chargeType: 'Electric', amount: data.electric },
                { chargeType: 'Water', amount: data.water },
                { chargeType: 'Space Bill', amount: data.spaceBill },
                // Add more charge breakdown data as needed
            ];

            const chargeBreakdownTable = document.getElementById('chargeBreakdownTable');
            const tbody = chargeBreakdownTable.querySelector('tbody');
            tbody.innerHTML = '';
            chargeBreakdownData.forEach(charge => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${charge.chargeType}</td><td>${charge.amount}</td>`;
                tbody.appendChild(tr);
            });

            const modal = document.getElementById('chargeBreakdownModal');
            modal.style.display = 'flex';
        }

        function closeChargeBreakdownModal() {
            const modal = document.getElementById('chargeBreakdownModal');
            modal.style.display = 'none';
        }
        function redirectCreateAccountant() {
            window.location.href = 'acc_create_accountant.php';
        }

        function showBillingModal() {
            var modal = document.getElementById('billingModal');
            modal.style.display = 'block';
        }

        function openSuccessModal() {
            var modal = document.getElementById('successModal');
            modal.style.display = 'block';
        }

        function closeSuccessModal() {
            var modal = document.getElementById('successModal');
            modal.style.display = 'none';
        }

        function closeBillingModal() {
            var modal = document.getElementById('billingModal');
            modal.style.display = 'none';
        }
        <?php if (isset($showSuccessModal) && $showSuccessModal) : ?>
            openSuccessModal()
        <?php endif; ?>
    </script>
   
    <?php include('includes/footer.php'); ?>

    