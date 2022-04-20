<?php

include_once(__DIR__.'./../../../config/dbconnect.php');

$idBuoi = $_POST['idBuoi'];
$idThu = $_POST['idThu'];
$idBS = $_POST['bsID'];

$sql = "SELECT * 
        FROM lich_lam_viec llv
        WHERE llv.id_thu = $idThu AND llv.idBS = $idBS AND llv.id_buoi = $idBuoi";
$rs = mysqli_query($conn, $sql);
$num_count = mysqli_num_rows($rs);
$status = '';
$response = '';
$buoi = ''; //Trữ id buổi hợp lệ 
$thu = ''; //Trữ id thứ hợp lệ
if($num_count >= 1){
    $status = 1;
    $response = 'Buổi hợp lệ';
    $buoi = $idBuoi;
    $thu = $idThu;
}
else{
    $status = 0;
    $response = 'Buổi không khớp với lịch khám của bác sĩ!';
}
$json = json_encode(array('status' => $status, 'response' => $response, 'buoi' => $buoi, 'thu' => $idThu));
echo $json;


?>