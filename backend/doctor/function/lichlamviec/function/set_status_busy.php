<?php
include_once (__DIR__.'./../../../../../config/dbconnect.php');

$status = '';
$llvid = $_POST['llvID'];
$sql = "UPDATE lich_lam_viec llv
        SET
            llv.isAvailable = 1
        WHERE llv.id = $llvid ";

if(mysqli_query($conn, $sql)){
    $status = 1;
}
else{
    $status = 0;
}

$json = json_encode(array("status" => $status));
echo $json;

?>