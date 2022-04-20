<?php

include_once(__DIR__.'./../../../../../config/dbconnect.php');

$id = $_POST['llvID'];

$status = '';

$sql = "DELETE 
        FROM lich_lam_viec
        WHERE id=$id";

if(mysqli_query($conn, $sql)){
    $status = 1;
}
else{
    $status = 0;
}

$json = json_encode(array("status" => $status));

echo $json;

?>