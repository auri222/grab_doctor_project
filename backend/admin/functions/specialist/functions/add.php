<?php

include_once(__DIR__ . "./../../../../../config/dbconnect.php");

$status = "";
$output = "";

    $spec = $_POST['spec'];
    //Kiểm tra trùng
    $sql = "SELECT ck.name FROM chuyenkhoa ck WHERE ck.name='".$spec."' ";
    $rs = mysqli_query($conn, $sql);
    $row = mysqli_num_rows($rs);

    if ($row > 0) {
        $status = 0;
        $output = "Tên chuyên khoa bị trùng! Hãy nhập lại!";
    }
    else{
        $query = "INSERT INTO chuyenkhoa (name)
            VALUES ('$spec')";

        if (mysqli_query($conn, $query)) {
            $status = 1;
            $output = "Thêm thành công!";
        } else {
            $status = 0;
            $output = "Thêm thất bại!";
        }

    }

$json = json_encode(array("status" => $status, "output" => $output));

echo $json;


