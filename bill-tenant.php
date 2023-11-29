<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('includes/dbconnection.php');
?>

<?php
include('includes/header.php');
include('includes/nav.php');
?>
<style>
    /* Styles for the Tenant Billings Page */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #9b593c;
        margin-top: 150px;
    }

    section {
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h2, h3 {
        color: #c19f90;
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
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }

    /* Add this style to change cursor to pointer on hover */
    tbody tr:hover {
        cursor: pointer;
        background-color: #c19f90;
        color: white;
    }

    .pay-btn, .history-btn, .receipt-btn {
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

    button:hover {
        background-color: #c19f90 !important;
    }

    #outstandingAmount {
        margin-top: 20px;
        font-weight: bold;
    }

    #totalBill {
        margin-top: 20px;
        font-weight: bold;
    }

    #paymentHistoryModal, #chargeBreakdownModal, #receiptModal {
        display: none;
    }

    #paymentHistoryModal .modal-content table {
        margin-top: 10px;
    }
</style>
</head>
<body>
<section id="bill-table">
    <h2>Bill Summary</h2>
    <div>
      <?php 
      $query = "SELECT SUM(total) as totalAmount FROM bill";
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
            <th>Space</th>
            <th>Due Date</th>
            <th>Outstanding Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Fetch data from the 'bill' table
        $query = "SELECT * FROM bill";
        $result = mysqli_query($con, $query);
        

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr data-spaceid='{$row['space_id']}' 
                        data-electric='{$row['electric']}' 
                        data-water='{$row['water']}' 
                        data-spacebill='{$row['space_bill']}' 
                        class='billing-row'>";
            echo "<td>{$row['space_id']}</td>";
            echo "<td>{$row['due_date']}</td>";
            echo "<td>\${$row['total']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>";

            // Buttons logic based on the bill status
            if ($row['status'] == 'paid') {
                echo "<button class='history-btn' onclick='viewPaymentHistory(\"{$row['space_id']}\")'>History</button>";
            } elseif ($row['status'] == 'unpaid' || $row['status'] == 'partially_paid') {
                echo "<button class='pay-btn' onclick='redirectPayment(\"{$row['space_id']}\")'>Pay</button>";
                echo "<button class='history-btn' onclick='viewPaymentHistory(\"{$row['space_id']}\")'>History</button>";
                echo "<button class='receipt-btn' onclick='uploadReceipt(\"{$row['space_id']}\")'>Receipt</button>";
            }

            echo "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>

    <div id="chargeBreakdownModal" class="modal">
        <div class="modal-content">
            <span onclick="closeChargeBreakdownModal()" style="float: right; cursor: pointer;">&times;</span>
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

    <div id="paymentHistoryModal" class="modal">
        <div class="modal-content">
            <span onclick="closePaymentHistoryModal()" style="float: right; cursor: pointer;">&times;</span>
            <h3>Payment History</h3>
            <table id="paymentHistoryTable">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>
                <!-- Dummy Payment History Data -->
                <tr>
                    <td>2023-05-15</td>
                    <td>$500.00</td>
                </tr>
                <tr>
                    <td>2023-06-01</td>
                    <td>$200.00</td>
                </tr>
                <!-- Add more rows as needed -->
                </tbody>
            </table>
        </div>
    </div>

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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var rows = document.querySelectorAll("#bill-table tbody tr");
        rows.forEach(function (row) {
            row.addEventListener("click", function () {
                var chargeDetails = [
                    { chargeType: "Electric", amount: "$" + row.dataset.electric },
                    { chargeType: "Water", amount: "$" + row.dataset.water },
                    { chargeType: "Space Bill", amount: "$" + row.dataset.spacebill },
                ];
                openChargeBreakdownModal(chargeDetails);
            });
        });

        // Calculate and display outstanding amount
        var outstandingAmountElement = document.getElementById("outstandingAmount");
        var totalOutstandingAmount = calculateTotalOutstandingAmount(rows);
        outstandingAmountElement.textContent = "Outstanding Amount: $" + totalOutstandingAmount.toFixed(2);

        // Calculate and display total bill
        var totalBillElement = document.getElementById("totalBill");
        var totalBill = calculateTotalBill(rows);
        totalBillElement.textContent = "Total Bill: $" + totalBill.toFixed(2);
    });

    function calculateTotalOutstandingAmount(rows) {
        var totalOutstandingAmount = 0;
        rows.forEach(function (row) {
            var amountString = row.querySelector("td:nth-child(3)").textContent; // Selecting the amount column
            var amount = parseFloat(amountString.replace("$", ""));
            totalOutstandingAmount += amount;
        });
        return totalOutstandingAmount;
    }

    function calculateTotalBill(rows) {
        var totalBill = 0;
        rows.forEach(function (row) {
            var amountString = row.querySelector("td:nth-child(3)").textContent; // Selecting the amount column
            var amount = parseFloat(amountString.replace("$", ""));
            totalBill += amount;
        });
        return totalBill;
    }

    function redirectPayment(space) {
        // Logic to redirect to the payment page for the selected space
        alert('Redirecting to payment page for ' + space);
    }

    function viewPaymentHistory(space) {
        // Logic to view payment history for the selected space
        openPaymentHistoryModal();
    }

    function uploadReceipt(space) {
        // Logic to handle receipt upload for the selected space
        openReceiptModal();
    }

    function openChargeBreakdownModal(chargeDetails) {
        var modalContent = document.getElementById("chargeBreakdownTable").querySelector('tbody');
        modalContent.innerHTML = ""; // Clear previous content

        // Populate the charge breakdown table
        chargeDetails.forEach(charge => {
            var row = document.createElement('tr');
            row.innerHTML = `
                <td>${charge.chargeType}</td>
                <td>${charge.amount}</td>
            `;
            modalContent.appendChild(row);
        });

        // Show the charge breakdown modal
        document.getElementById("chargeBreakdownModal").style.display = 'flex';
    }

    function closeChargeBreakdownModal() {
        document.getElementById("chargeBreakdownModal").style.display = 'none';
    }

    function openPaymentHistoryModal() {
        // Dummy Payment History Data
        var paymentHistoryData = [
            { date: "2023-05-15", amount: "$500.00" },
            { date: "2023-06-01", amount: "$200.00" },
        ];

        var modalContent = document.getElementById("paymentHistoryTable").querySelector('tbody');
        modalContent.innerHTML = ""; // Clear previous content

        // Populate the payment history table
        paymentHistoryData.forEach(payment => {
            var row = document.createElement('tr');
            row.innerHTML = `
                <td>${payment.date}</td>
                <td>${payment.amount}</td>
            `;
            modalContent.appendChild(row);
        });

        // Show the payment history modal
        document.getElementById("paymentHistoryModal").style.display = 'flex';
    }

    function closePaymentHistoryModal() {
        document.getElementById("paymentHistoryModal").style.display = 'none';
    }

    function openReceiptModal() {
        // Show the receipt upload modal
        document.getElementById("receiptModal").style.display = 'flex';
    }

    function closeReceiptModal() {
        document.getElementById("receiptModal").style.display = 'none';
    }

    // Close modals when clicking outside
    window.addEventListener('click', function (event) {
        var chargeModal = document.getElementById('chargeBreakdownModal');
        var paymentModal = document.getElementById('paymentHistoryModal');
        var receiptModal = document.getElementById('receiptModal');

        if (event.target === chargeModal) {
            closeChargeBreakdownModal();
        }

        if (event.target === paymentModal) {
            closePaymentHistoryModal();
        }

        if (event.target === receiptModal) {
            closeReceiptModal();
        }
    });
</script>

<?php include('includes/footer.php'); ?>