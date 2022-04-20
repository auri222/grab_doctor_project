<?php

include_once(__DIR__."./../../../config/dbconnect.php");

$status = "";

$id = $_POST['id'];
$name = $_POST['name'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];

$sql = "UPDATE taikhoan
        SET
            name='$name',
            dob='$dob',
            gender='$gender'
        WHERE id=$id ";
if(mysqli_query($conn, $sql)){
    $status = 1;
}
else $status = 0;

$json = json_encode(array("status" => $status));

echo $json;

?>