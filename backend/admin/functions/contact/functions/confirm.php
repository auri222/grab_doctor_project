<?php

include_once(__DIR__.'./../../../../../config/dbconnect.php');

$id = $_POST['contactID'];
$status = "";
$message = "";

$sql = "UPDATE lien_he
        SET
            is_checked=1
        WHERE id=$id";
if(mysqli_query($conn, $sql)){
    $status =1;
}else{
    $status =0;
    $message = "Không cập nhật được!";
}

$json = json_encode(array("status" => $status, "message" => $message));

echo $json;

?>