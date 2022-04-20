<?php

include_once(__DIR__ . '../../../../dbconnect.php');
$data = '';
$province_id = $_POST['province_id'];
$sql_quanhuyen = "select * from quanhuyen where province_id = '$province_id'";
$rs_quanhuyen = mysqli_query($conn, $sql_quanhuyen);
$data .= '<option disabled selected>-- Chọn quận/huyện --</option>';
while ($row_quanhuyen = mysqli_fetch_array($rs_quanhuyen)) {
    $data .= '<option value="' . $row_quanhuyen["id"].'">' . $row_quanhuyen["name"] . '</option>';
}

echo $data;
