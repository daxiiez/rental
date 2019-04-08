<?php
include '../__connect.php';
$rent_id = $_POST['rent_id'];
$status = $_POST['status'];
$sql = "UPDATE rental_Detail set status = '$status' where rental_id = '$rent_id'";
$result = mysqli_query($conn,$sql);
echo $result;