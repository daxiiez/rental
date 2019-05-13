<?php
include '../__connect.php';
$rent_id = $_POST['rent_id'];
$status = $_POST['status'];
if($status=='I' || $status =='O'){
    if($status=='I'){
        $field = ",check_in_time = now()";
    }else{
        $field = ",check_out_time = now()";
    }
    $sql = "UPDATE rental_Detail set status = '$status' {$field}  where rental_id = '$rent_id'";
}else{

    $sql = "UPDATE rental_Detail set status = '$status' where rental_id = '$rent_id'";
}
$result = mysqli_query($conn,$sql);

echo $result;