<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');

include('includes/header.php');
include('includes/nav.php');

// Fetch space applications
$owner_id= $_SESSION['uid'];
$owner_name= $_SESSION['uname'];

$concourseID = isset($_GET['SearchConcourse']) ? $_GET['SearchConcourse'] : '%';

$spaceApplicationsQuery = "SELECT * FROM space a LEFT JOIN space_application b on a.space_id=b.space_id  WHERE b.status = 'pending' and a.space_owner = '$owner_name' and a.concourse_id like '$concourseID' ";
$spaceApplicationsResult = $con->query($spaceApplicationsQuery);
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
        /* Add relative positioning */
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
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 600px;
        /* margin: 0 auto; */
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

    .applications-table th,
    .applications-table td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    .applications-table th {
        background-color: #9b593c;
        color: white;
    }
</style>
<?php 
        $concourseQuery = "SELECT * FROM concourse_verification WHERE owner_id = '$owner_id'  ";
        $result = $con->query($concourseQuery);
    ?>
<section>
    <div class="row">
        <div class="col"><h2>Space Applications</h2></div>
        <div class="col-auto">
        <select class="form-control" id="selectConcourse" onchange="searchconcourse()" style="width:300px">
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
    </div>
    <table id="applicationsTable" class="applications-table">
        <thead>
            <tr>
                <th>Application ID</th>
                <th>Space Name</th>
                <th>Tenant Name</th>
                <th>Tenant Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($spaceApplicationsResult && $spaceApplicationsResult->num_rows > 0) {
                while ($row = $spaceApplicationsResult->fetch_assoc()) {
                    echo "<tr data-applicationid='{$row['app_id']}' class='application-row'>";
                    echo "<td>{$row['app_id']}</td>";
                    echo "<td>{$row['spacename']}</td>";
                    echo "<td>{$row['tenant_name']}</td>";
                    echo "<td>{$row['ap_email']}</td>";
                    echo "<td>{$row['status']}</td>";
                    echo '<td><button class="action-btn" onclick="ViewApplication(' . "'{$row['app_id']}'" . ',' . "'{$row['pdf_file']}'" . ',' . "'{$row['tenant_name']}'" . ')"> View </button></td>';
                    echo "</tr>";

                }
            } else {
                echo "<tr><td colspan='6'>No space applications</td></tr>";
            }
            ?>
        </tbody>
    </table>
</section>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>

    

    function ViewApplication(app_id, pdf_file, tenant) {
        var action = "approve";
        var applicationId = app_id;
        var approverRemarks = "";

        Swal.fire({
            title: "<strong>" + tenant + "</strong>",
            html:
                "<h5>Uploaded FIle</h5>" +
                "<embed src='uploads/" + pdf_file + "' type='application/pdf' width='100%' height='350px' />",
            showCloseButton: true,
            showDenyButton: true,
            confirmButtonText:
                '<i class="fa fa-thumbs-up"></i> Approve!',
            denyButtonText:
                '<i class="fa fa-thumbs-down"></i> Reject'
        }).then((result) => {
            if (result.isConfirmed) {
                action = "approve";
                $.ajax({
                    url: 'update_application_status.php',
                    method: 'POST',
                    data: { applicationId, action, approverRemarks },
                    success: function (response) {
                        // Handle the response as needed
                        // alert("Successfully Approved");
                        Swal.fire({
                            title: "Success",
                            text: "Application Approved",
                            icon: "success"
                        }).then((result) => {
                            window.location.reload();
                        });
                    },
                    error: function () {
                        alert('Error updating application status');
                    }
                });
            }
            else if(result.isDenied){
                console.log(result);
                Swal.fire({
                    title: "Remarks",
                    text: "Write something:",
                    input: 'text',
                    showCancelButton: true
                }).then((result) => {
                    if (result.value) {
                        action = "reject";
                        approverRemarks = result.value;
                        $.ajax({
                            url: 'update_application_status.php',
                            method: 'POST',
                            data: { applicationId, action, approverRemarks },
                            success: function (response) {
                                // Handle the response as needed
                                // alert("Successfully Rejected");
                                Swal.fire({
                                    title: "Success",
                                    text: "Application Rejected",
                                    icon: "success"
                                }).then((result) => {
                                    window.location.reload();
                                });

                            },
                            error: function () {
                                alert('Error updating application status');
                            }
                        });
                    }
                    else {
                        Swal.fire({
                            title: "Oops!",
                            text: "You are required to input a remarks!",
                            icon: "error"
                        });
                    }
                });
            }
        });
    }

    function searchconcourse(){
        var SearchConcourseID= $("#selectConcourse").val()
        location.href="applications.php?SearchConcourse=" + SearchConcourseID
    }

</script>

<?php include('includes/footer.php'); ?>