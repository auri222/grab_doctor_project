<?php

include_once(__DIR__.'./../../../config/dbconnect.php');

$status= "";
$message = "";

$id = $_POST['accID'];

$file_tmp = $_FILES['avatar']['tmp_name'];
$file_name = $_FILES['avatar']['name'];
$path = "./../../../assets/img/upload/avatar/".$file_name;

$sql = "UPDATE taikhoan
        SET
            avatar='$file_name'
        WHERE id=$id";

if(mysqli_query($conn, $sql)){
    move_uploaded_file($file_tmp, $path);
    $status = 1;
    $message = "Sửa thành công";
}
else{
    $status = 0;
    $message = "Sửa không thành công";
}


$json = json_encode(array("status" => $status, "message" => $message));
echo $json;

?>