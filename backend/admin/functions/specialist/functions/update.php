<?php

include_once(__DIR__."./../../../../../config/dbconnect.php");

$output = "";
$status = "";

$spec = $_POST['spec'];
$specID = $_POST['specID'];

    $query = "UPDATE chuyenkhoa
	SET
		name='$spec'
	WHERE id=$specID";

    if(mysqli_query($conn, $query)){
        $status = 1;
        $output = "Sửa thành công!";
    }
    else{
        $status = 0;
        $output = "Sửa thất bại!";
    }



$json = json_encode(array("status" => $status, "output" => $output));
echo $json;
