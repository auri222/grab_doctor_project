<?php
    include_once(__DIR__.'../../../../dbconnect.php');
    $data = '';
    $district_id = $_POST['district_id'];
    $sql_phuongxa = "select * from xa where district_id = '$district_id' ";
    $rs_phuongxa = mysqli_query($conn, $sql_phuongxa);
    $data .= '<option disabled selected>-- Chọn phường/xã --</option>';
    while($row_phuongxa = mysqli_fetch_array($rs_phuongxa)){
        $data .= '<option value="'.$row_phuongxa["id"].'">' .$row_phuongxa["name"]. '</option>';
    }

    echo $data;
?>