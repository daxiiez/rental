<?php
include '../__connect.php';
$username = $_POST['username'];
$type = $_POST['position'];
$sql = "UPDATE user set position='$type' WHERE username='$username' ";
$query = mysqli_query($conn,$sql);

echo json_encode($query);