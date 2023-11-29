<?php
// payment.php

session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');

if (!isset($_SESSION['act_id'])) {
    header('Location: acc_login.php');
    exit();
}

include('includes/header.php');
include('includes/nav.php');

// Handle form submission for updating payment statuses
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form
    $paymentId = $_POST['payment_id'];
    $newStatus = $_POST['status'];

    // Update the payment status in the database
    $updateQuery = "UPDATE payments SET status = '$newStatus' WHERE payment_id = $paymentId";
    mysqli_query($con, $updateQuery);

    // Redirect back to the payments page
    header("Location: payment.php?payment_id=$paymentId");
    exit();
}

// Fetch payment details if payment ID is provided
if (isset($_GET['payment_id'])) {
    $paymentId = $_GET['payment_id'];
    $paymentQuery = "SELECT * FROM payments WHERE payment_id = $paymentId";
    $paymentResult = mysqli_query($con, $paymentQuery);
    $payment = mysqli_fetch_assoc($paymentResult);
}
?>

<style>
    section {
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        margin-top: 150px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: relative;
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

    #paymentsTable {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    #paymentsTable th,
    #paymentsTable td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    #paymentsTable th {
        background-color: #9b593c;
        color: white;
    }

    .payment-row {
        cursor: pointer;
    }

    .payment-row:hover {
        background-color: #c19f90;
        color: white;
    }

    #paymentModal {
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

    .receipt-img {
        max-width: 100%;
        margin-top: 10px;
    }

    #statusInput {
        margin-top: 10px;
    }

    #archiveBtn {
        display: none;
        margin-top: 10px;
    }
</style>

<section>
    <h2>Payments</h2>
    <table id="paymentsTable">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Fetch data from the 'payments' table
                $query = "SELECT * FROM payments";
                $result = mysqli_query($con, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr class='payment-row'>";
                    echo "<td>{$row['payment_id']}</td>";
                    echo "<td>{$row['amount']}</td>";
                    echo "<td>{$row['date']}</td>";
                    echo "<td>{$row['status']}</td>";
                    echo "<td>";

                    // Display "Archive" button only if status is 'Paid'
                    if ($row['status'] === 'Paid') {
                        echo "<button class='archive-btn'>Archive</button>";
                    }

                    echo "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</section>

<div id="paymentModal" class="modal">
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Manage Payment</h2>
        <div>
            <!-- Display payment details here -->
            <p>Payment ID: <span id="paymentId"></span></p>
            <p>Amount: <span id="paymentAmount"></span></p>
            <p>Date: <span id="paymentDate"></span></p>
            <p>Status: <span id="paymentStatus"></span></p>

            <!-- Display receipt image -->
            <img class="receipt-img" id="receiptImage" src="" alt="Receipt">

            <!-- Update status dropdown -->
            <label for="statusInput">Update Status:</label>
            <select id="statusInput">
                <option value="Unpaid">Unpaid</option>
                <option value="Partially Paid">Partially Paid</option>
                <option value="Paid">Paid</option>
            </select>

            <!-- Add a submit button to save changes -->
            <button id="saveChangesBtn">Save Changes</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentsTable = document.getElementById('paymentsTable');
        const paymentModal = document.getElementById('paymentModal');
        const closePaymentModalBtn = document.querySelector('.close-modal');
        const saveChangesBtn = document.getElementById('saveChangesBtn');
        const archiveBtn = document.getElementById('archiveBtn');

        paymentsTable.addEventListener('click', function (event) {
            const target = event.target.closest('.payment-row');
            if (target) {
                // Show the modal
                paymentModal.style.display = 'block';

                // Fetch payment details and display in the modal
                const rowCells = target.getElementsByTagName('td');
                const paymentId = rowCells[0].innerText;
                const paymentAmount = rowCells[1].innerText;
                const paymentDate = rowCells[2].innerText;
                const paymentStatus = rowCells[3].innerText;
                const receiptImage = rowCells[4].innerText;

                document.getElementById('paymentId').innerText = paymentId;
                document.getElementById('paymentAmount').innerText = paymentAmount;
                document.getElementById('paymentDate').innerText = paymentDate;
                document.getElementById('paymentStatus').innerText = paymentStatus;
                document.getElementById('receiptImage').src = receiptImage;

                // Set the current status in the dropdown
                const statusInput = document.getElementById('statusInput');
                const statusOption = Array.from(statusInput.options).find(option => option.value === paymentStatus);
                statusOption.selected = true;

                // Show/hide Archive button based on payment status
                if (paymentStatus === 'Paid') {
                    archiveBtn.style.display = 'inline-block';
                } else {
                    archiveBtn.style.display = 'none';
                }
            }
        });

        closePaymentModalBtn.addEventListener('click', function () {
            // Close the modal
            paymentModal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            // Close the modal if clicked outside the modal content
            if (event.target === paymentModal) {
                paymentModal.style.display = 'none';
            }
        });

        saveChangesBtn.addEventListener('click', function () {
            // Get the selected status from the dropdown
            const newStatus = document.getElementById('statusInput').value;

            // Get the payment ID from the modal content
            const paymentId = document.getElementById('paymentId').innerText;

            // Create a FormData object and append data
            const formData = new FormData();
            formData.append('payment_id', paymentId);
            formData.append('status', newStatus);

            // Use fetch API to send a POST request
            fetch('payment.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                // Check if the request was successful
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                // Close the modal after successful update
                paymentModal.style.display = 'none';
                // Optionally, you can reload the page to reflect the changes
                window.location.reload();
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
        });
    });
</script>

<?php include('includes/footer.php'); ?>