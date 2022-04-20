<?php

include_once(__DIR__."./../../../../../config/dbconnect.php");

$id = $_POST["contactID"];

$status = "";

$message = "";

$sql = "DELETE FROM lien_he WHERE id=$id";
if(mysqli_query($conn, $sql)){
    $status = 1;
}
else {
    $status = 0;
    $message = "Không xóa được";
}

$json = json_encode(array("status" => $status, "message" => $message));

echo $json;

?>