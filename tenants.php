<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');

include('includes/header.php');
include('includes/nav.php');

$concourseID = isset($_GET['SearchConcourse']) ? $_GET['SearchConcourse'] : '%';
$owner_id= $_SESSION['uid'];
$owner_name= $_SESSION['uname'];
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
    <?php 
        $concourseQuery = "SELECT * FROM concourse_verification WHERE owner_id = '$owner_id'  ";
        $result = $con->query($concourseQuery);
    ?>
<section>
    <div>
        <select class="form-control" id="selectConcourse" onchange="searchconcourse()" style="width:300px; position:absolute; right:20px">
            <option value="%">ALL</option>
            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = $result->fetch_assoc()) {
                    $selected= ($concourseID==$row['concourse_id']  ? 'selected' : '');
                echo '<option '.$selected.' value="'.$row['concourse_id'].'">'.$row['concourse_name'].'</option>';
                }
            }
            ?>
        </select>
    </div>
   
    <?php
    $owner = $_SESSION['uname']; // Replace this with your actual session variable

    // Fetch and display concourse information
    $concourseQuery = "SELECT DISTINCT s.concourse_id
                        FROM space s
                        WHERE s.space_owner = '$owner' and concourse_id like '$concourseID'";

    $concourseResult = mysqli_query($con, $concourseQuery);

    while ($concourse = mysqli_fetch_assoc($concourseResult)) {
        $concourseId = $concourse['concourse_id'];

        // Fetch and display tenant data for each concourse along with space details
        $tenantQuery = "SELECT * FROM concourse_verification A LEFT JOIN `space` B ON A.concourse_id=B.concourse_id left join space_application c on b.space_id=c.space_id left join contract d on c.app_id=d.space_id WHERE A.owner_name='$owner' and b.status='occupied' and a.concourse_id = '$concourseId' and d.contract_id is not null";

        $tenantResult = mysqli_query($con, $tenantQuery);

        echo '<h3 style="margin-top: 20px;">Concourse ' . $concourseId . '</h3>';
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Name</th>';
        echo '<th>Email</th>';
        echo '<th>Space Name</th>';
        echo '<th>Concourse ID</th>';
        echo '<th>Lease Start</th>';
        echo '<th>Lease End</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($tenant = mysqli_fetch_assoc($tenantResult)) {
            echo '<tr class="tenant-row">';
            echo '<td>' . $tenant['tenant_name'] . '</td>';
            echo '<td>' . $tenant['ap_email'] . '</td>';
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

<script>
    function searchconcourse(){
        var SearchConcourseID= $("#selectConcourse").val()
        location.href="tenants.php?SearchConcourse=" + SearchConcourseID
    }
</script>

<?php include('includes/footer.php'); ?>
