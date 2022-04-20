<?php

include_once(__DIR__.'./../../../config/dbconnect.php');

$date = $_POST['ngay'];
$bsID = $_POST['bsID'];

$date = date('Y-m-d', strtotime($date));
$today = date('Y-m-d');
$today = date('Y-m-d', strtotime($today));
//Check thứ của ngày được chọn có trong lịch của bác sĩ hay không
//Nếu không thì alert người dùng chọn ngày theo lịch của bác sĩ
//Có thì show option ở buổi và giờ
$wD = date('w', strtotime($date)); //0: cn, 1:T2, 2:T3, 3:T4, 4:T5, 5:T6, 6:T7
$weekDay = 0;

for($i=0;$i<7;$i++){
    if($i == 0 && $i == $wD){
        $weekDay = $i + 7; //Tương ứng vs ID trong bảng thu
    }
    else if($wD == $i){
        $weekDay = $i;
    }
}
$idThu = 0;
$error = '';
$status = '';
$idllv = 0;
$wD_llv = [];
$sql_llv = "SELECT t.id AS MaThu
            FROM lich_lam_viec llv
            JOIN thu t ON t.id = llv.id_thu
            JOIN buoi b ON b.id = llv.id_buoi
            WHERE llv.idBS = $bsID";
$rs_llv = mysqli_query($conn, $sql_llv);
while($row_llv = mysqli_fetch_assoc($rs_llv)){
    $wD_llv[] = $row_llv['MaThu'];
}




if($date <= $today){
    $error = 'Ngày không hợp lệ!';
    $status = 0;
} else if(!in_array($weekDay,$wD_llv)){
    $error = 'Không khớp với lịch của bác sĩ. Hãy chọn ngày khác';
    $status = 0;
}
else{
    $idThu = $weekDay;
    $status = 1;
}

$json = json_encode(array('error' => $error, 'status' => $status, 'wDID' => $idThu));
echo $json;
