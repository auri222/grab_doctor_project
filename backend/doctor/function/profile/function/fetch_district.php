<?php
    include_once(__DIR__.'/../../../../../config/dbconnect.php');
    $output = '';
    $provinceID = $_POST['provinceID'];
    //Lấy data quận huyện
    $sql_dist = "SELECT * FROM quanhuyen where idTT=$provinceID ";
    $result_dist = mysqli_query($conn, $sql_dist);
    $output .= '<option disabled selected>-- Chọn quận huyện --</option>';
    while ($row_dist = mysqli_fetch_assoc($result_dist)) {
        $output .= '<option value="'.$row_dist["id"].'">'.$row_dist["name"].'</option>';
    }
    echo $output;
    
?>