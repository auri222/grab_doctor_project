<?php
include_once(__DIR__.'/../../../../../config/dbconnect.php');

    $output = '';
    $districtID = $_POST['districtID'];
    //Lấy data quận huyện
    $sql_ward = "SELECT * FROM phuongxa WHERE idQH='$districtID' ORDER BY name";
    $result_ward = mysqli_query($conn, $sql_ward );
    $output .= '<option disabled selected>-- Chọn phường xã --</option>';
    while ($row_ward = mysqli_fetch_assoc($result_ward)) {
        $output .= '<option value="'.$row_ward["id"].'">'.$row_ward["prefix"].' '.$row_ward["name"].'</option>';
    }
    echo $output;


?>