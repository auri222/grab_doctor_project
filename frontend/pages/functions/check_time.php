<?php
include_once(__DIR__."./../../../config/dbconnect.php");

$idBS = $_POST['idBS'];
$idTime = $_POST['idTime'];
$idBuoi = $_POST['idBuoi'];
$bookDate = $_POST['bookdate'];
$status = "";
$output = "";

//-----------------------------------------------------------
//Lấy khung giờ
$sql_time = "	SELECT name
                FROM khung_gio
                WHERE id=$idTime AND id_buoi=$idBuoi";
$rs_time = mysqli_query($conn, $sql_time); //??
$row_time = mysqli_fetch_assoc($rs_time);
//Xử lý thời gian trước => tách ra 
$new_time = substr($row_time['name'],0,-1);
$arr_time = explode('-',$new_time);

$start_time1 = $arr_time[0].':00';
$end_time1 = $arr_time[1].':00';
//Ghép để đổi định dạng giờ
$start_time = date('H:i:s', strtotime($start_time1));
$end_time = date('H:i:s', strtotime($end_time1));
// => 7:00:00 (Giờ được định dạng)

// -----------------------------------------------------------
// CHECK giờ và lịch trùng với khung giờ 
$sql_check = "SELECT COUNT(*) AS TOTAL FROM lich_hen lh
            WHERE 
            lh.idBS = $idBS AND
            lh.date = '$bookDate' AND
            lh.start_time = '$start_time' AND 
            lh.end_time = '$end_time' ";
$rs_check = mysqli_query($conn,$sql_check);
$row_check = mysqli_fetch_assoc($rs_check);
$total = $row_check['TOTAL'];

if($total > 0){
    $status = 0;
    $output = "Giờ đã bị trùng. Hãy chọn lại.";
}
else{
    $status = 1;
    $output = "Giờ hợp lệ";
}
// -----------------------------------------------------------

$json = json_encode(array("status" => $status,
                          "output" => $output,
                          "total_row" => $total,
                          "start_time" => $start_time,
                          "end_time" => $end_time));
echo $json;
?>