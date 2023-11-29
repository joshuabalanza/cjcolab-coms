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
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        margin-top: 150px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        position: relative; /* Add relative positioning */
    }
    h2, h3{
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

    .leases-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .leases-table th,
    .leases-table td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    .leases-table th {
        background-color: #9b593c;
        color: white;
    }

    .hidden {
        display: none;
    }

    .tenant-row {
        cursor: pointer;
    }
    .tenant-row:hover {
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
        margin-top: 30px;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 600px;
        overflow-y: auto; /* Make the modal content scrollable */
        max-height: 80vh; /* Set a maximum height */
        margin: 0 auto;
        text-align: center;
        margin-top: 75px;
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
</style>
<section>
    <h2>Tenant Information</h2>
    <table id="tenantTable">
        <thead>
            <tr>
                <th>Tenant ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Fetch and display tenant data
                $tenantQuery = "SELECT * FROM `user` WHERE `utype` = 'Tenant'";
                $tenantResult = mysqli_query($con, $tenantQuery);

                while ($tenant = mysqli_fetch_assoc($tenantResult)) {
                    echo '<tr data-tenantid="' . $tenant['uid'] . '" class="tenant-row">';
                    echo '<td>' . $tenant['uid'] . '</td>';
                    echo '<td>' . $tenant['uname'] . '</td>';
                    echo '<td>' . $tenant['uemail'] . '</td>';
                    echo '<td>' . $tenant['uphone'] . '</td>';
                    echo '<td>' . $tenant['first_name'] . '</td>';
                    echo '<td>' . $tenant['last_name'] . '</td>';
                    echo '<td>' . $tenant['address'] . '</td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>

    <div id="leaseModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" id="tenantClose">&times;</span>
            <h3>Leases</h3>
            <table id="leasesTable" class="leases-table">
                <thead>
                    <tr>
                        <th>Concourse ID</th>
                        <th>Space ID</th>
                        <th>Space Name</th>
                        <th>Lease Start</th>
                        <th>Lease End</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Fetch and display lease data
                        $leaseQuery = "SELECT `concourse_id`, `space_id`, `space_name`, `lease_start`, `lease_end` FROM `space` WHERE `space_tenant` IS NOT NULL";
                        $leaseResult = mysqli_query($con, $leaseQuery);

                        while ($lease = mysqli_fetch_assoc($leaseResult)) {
                            echo '<tr>';
                            echo '<td>' . $lease['concourse_id'] . '</td>';
                            echo '<td>' . $lease['space_id'] . '</td>';
                            echo '<td>' . $lease['space_name'] . '</td>';
                            echo '<td>' . $lease['lease_start'] . '</td>';
                            echo '<td>' . $lease['lease_end'] . '</td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tenantTable = document.getElementById('tenantTable');
        const leaseModal = document.getElementById('leaseModal');
        const closeLeaseModalBtn = document.querySelector('.close-modal');

        tenantTable.addEventListener('click', function (event) {
            const target = event.target.closest('.tenant-row');
            if (target) {
                // Show the modal
                leaseModal.style.display = 'block';
            }
        });

        closeLeaseModalBtn.addEventListener('click', function () {
            // Close the modal
            leaseModal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            // Close the modal if clicked outside the modal content
            if (event.target === leaseModal) {
                leaseModal.style.display = 'none';
            }
        });
    });

    $('#tenantClose').on('click', function() {
        $('#leaseModal').css('display', 'none');
    });
</script>
<?php include('includes/footer.php'); ?>