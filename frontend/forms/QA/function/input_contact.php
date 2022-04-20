<?php

include_once(__DIR__."./../../../../config/dbconnect.php");
date_default_timezone_set("Asia/Ho_Chi_Minh");

$status = "";

$message = "";

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$content = $_POST['content'];
$uptime = date("Y-m-d H:i:s");

$sql = "INSERT INTO lien_he
    (name, phone, email, content, is_checked, up_time)
    VALUES ('$name', '$phone', '$email', '$content', 0, '$uptime')" ;

if (mysqli_query($conn, $sql)) {
    $status = 1;
    $message = "Góp ý thành công";
} 

else {
    $status = 0;
    $message = "Lỗi rồi! Thử lại sao! :<";
}

$json = json_encode(array("status" => $status, "message" => $message));

echo $json;

?>