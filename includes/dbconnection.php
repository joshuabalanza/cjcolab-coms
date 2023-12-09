<?php

// For Local
$con = mysqli_connect("localhost", "root", "", "test_coms4");

//For Live
// $con = mysqli_connect("localhost", "u556873115_coms", "123!@#Prodcoms", "u556873115_live_coms");

// print_r("Connected");
if(mysqli_connect_errno()) {
    echo "Connection Fail" . mysqli_connect_error();
}
