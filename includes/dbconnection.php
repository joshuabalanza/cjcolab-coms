<?php

$con = mysqli_connect("localhost", "root", "", "coms");
// print_r("Connected");
if(mysqli_connect_errno()) {
    echo "Connection Fail" . mysqli_connect_error();
}
