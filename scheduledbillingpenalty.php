<?php
session_name("user_session");
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
require('includes/dbconnection.php');
require 'vendor/autoload.php'; // Include PHPMailer autoload

$currentdate = date('y-m-d');

try{
    $unpaidbilling = "SELECT *, ROUND(DATEDIFF(CURDATE(),due_date)/7,0) as 'Weeks' FROM `bill` WHERE paymentstatus='UnPaid'";
    $unpaidbillingResult = mysqli_query($con, $unpaidbilling);
    
    if ($unpaidbillingResult && mysqli_num_rows($unpaidbillingResult) > 0) {
        while ($Data = mysqli_fetch_assoc($unpaidbillingResult)) {
            if ($Data["Weeks"] > 0)
            {
                $new_total_with_penalty= $Data["electric"] + $Data["water"] + $Data["space_bill"] + (50 * $Data["Weeks"]);
                $billId =  $Data["bill_id"]; 
                echo $Data["due_date"];
                echo ' | ';
                echo $Data["Weeks"];
                echo ' | ';
                echo $new_total_with_penalty; 
                echo '<br/>';

                $updateSpaceStatusQuery = "UPDATE bill SET total = $new_total_with_penalty where bill_id= $billId";
                $updateResult = $con->query($updateSpaceStatusQuery);
            }
        }
    }
}
catch (Exception $e){
echo json_encode(['error'=>'Caught exception: ',  $e->getMessage(), "\n"]);
}		
echo 'penalty list';

?>