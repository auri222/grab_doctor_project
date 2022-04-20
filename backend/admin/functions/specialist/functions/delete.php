<?php

include_once(__DIR__."./../../../../../config/dbconnect.php");

$output = "";
$status = "";

$specID = $_POST['specID'];

$query = "DELETE FROM chuyenkhoa WHERE id=$specID";

    if(mysqli_query($conn, $query)){
        $status = 1;
        $output = "Xóa thành công!";
    }
    else{
        $status = 0;
        $output = "Xóa thất bại!";
    }

    $json = json_encode(array("status" => $status, "output" => $output));
echo $json;
