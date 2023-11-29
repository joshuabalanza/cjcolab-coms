<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('includes/dbconnection.php');

// Calculate total amount
$totalAmountQuery = "SELECT SUM(total) AS totalAmount FROM bill";
$totalAmountResult = $con->query($totalAmountQuery);
$totalAmountRow = $totalAmountResult->fetch_assoc();
$totalAmount = isset($totalAmountRow['totalAmount']) ? $totalAmountRow['totalAmount'] : 0;

// Fetch billing information
$sql = "SELECT tenant_name, space_id, total, due_date, electric, water, space_bill FROM bill";
$result = $con->query($sql);

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

    .close-modal {
        cursor: pointer;
        margin-top: 10px;
        color: #333;
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

    button:hover {
        background-color: #c19f90 !important;
    }

    #outstandingAmount,
    #totalBill,
    #paymentHistoryModal,
    #chargeBreakdownModal,
    #receiptModal {
        display: none;
    }

    #paymentHistoryModal .modal-content table {
        margin-top: 10px;
    }
</style>
</head>

<body>
    <section id="bill-table" style="margin-top: 100px;">
        <h2>Bill Summary</h2>
        <div>
            <?php
            $owner_name = $_SESSION['uname'];
            $query = "SELECT SUM(total) as totalAmount FROM bill WHERE owner_name = '$owner_name'";
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
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT space_id, due_date, total FROM bill WHERE owner_name = '$owner_name'";
                $result = mysqli_query($con, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['space_id']}</td>";
                    echo "<td>{$row['due_date']}</td>";
                    echo "<td>₱{$row['total']}</td>";
                    echo "</tr>";
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalOverlay = document.querySelector('.modal');
            modalOverlay.addEventListener('click', function (event) {
                if (event.target === modalOverlay) {
                    closeChargeBreakdownModal();
                }
            });

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
    </script>

    <?php include('includes/footer.php'); ?>
