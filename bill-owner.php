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
$sql = "SELECT tenant_name, space_id, total, due_date, status AS payment_status, electric, water, space_bill FROM bill";
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
        color: white;
    }
</style>
</head>
<body style="margin-top: 150px;">
    <section>
        <h2>Overview</h2>
        <p>
            Total Amount: <?php echo number_format($totalAmount, 2); ?>
        </p>

        <h2>Billing Information</h2>
        <table id="billingTable">
            <thead>
                <tr>
                    <th>Tenant Name</th>
                    <th>Space</th>
                    <th>Total Charges</th>
                    <th>Due Date</th>
                    <th>Payment Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr data-electric='{$row['electric']}' data-water='{$row['water']}' data-space-bill='{$row['space_bill']}'>";
                        echo "<td>{$row['tenant_name']}</td>";
                        echo "<td>{$row['space_id']}</td>";
                        echo "<td>{$row['total']}</td>";
                        echo "<td>{$row['due_date']}</td>";
                        echo "<td>{$row['payment_status']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
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
