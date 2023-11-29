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

    h2,
    h3 {
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

    .tenant-row {
        cursor: pointer;
    }

    .tenant-row:hover {
        background-color: #c19f90;
        color: white;
    }
</style>

<section>
    <?php
    $owner = $_SESSION['uname']; // Replace this with your actual session variable

    // Fetch and display concourse information
    $concourseQuery = "SELECT DISTINCT s.concourse_id
                        FROM space s
                        WHERE s.space_owner = '$owner'";

    $concourseResult = mysqli_query($con, $concourseQuery);

    while ($concourse = mysqli_fetch_assoc($concourseResult)) {
        $concourseId = $concourse['concourse_id'];

        // Fetch and display tenant data for each concourse along with space details
        $tenantQuery = "SELECT u.*, s.space_name, c.c_start, c.c_end
                        FROM user u
                        JOIN space s ON u.uname = s.space_tenant
                        LEFT JOIN contract c ON s.space_tenant = c.tenant_name
                        WHERE s.space_owner = '$owner' AND s.concourse_id = '$concourseId'";

        $tenantResult = mysqli_query($con, $tenantQuery);

        echo '<h3 style="margin-top: 20px;">Concourse ' . $concourseId . '</h3>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Name</th>';
        echo '<th>Email</th>';
        echo '<th>Phone</th>';
        echo '<th>Space Name</th>';
        echo '<th>Concourse ID</th>';
        echo '<th>Lease Start</th>';
        echo '<th>Lease End</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($tenant = mysqli_fetch_assoc($tenantResult)) {
            echo '<tr class="tenant-row">';
            echo '<td>' . $tenant['uname'] . '</td>';
            echo '<td>' . $tenant['uemail'] . '</td>';
            echo '<td>' . $tenant['uphone'] . '</td>';
            echo '<td>' . $tenant['space_name'] . '</td>';
            echo '<td>' . $concourseId . '</td>';
            echo '<td>' . $tenant['c_start'] . '</td>';
            echo '<td>' . $tenant['c_end'] . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    }
    ?>
</section>

<?php include('includes/footer.php'); ?>
