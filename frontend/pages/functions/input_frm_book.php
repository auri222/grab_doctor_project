<?php
//Thêm dữ liệu vào bảng lịch hẹn => xong thì send mail báo cho khách 
include_once(__DIR__.'./../../../config/dbconnect.php');
date_default_timezone_set('Asia/Ho_Chi_Minh');

//Khởi tạo biến
$start_time = "";
$end_time = "";
$id_book = "";

//Lấy dữ liệu từ form
$uptime = date('Y-m-d H:i:s');
$name = $_POST['name'];
$gender = $_POST['gender'];
$year = (int)date("Y", strtotime($_POST['dob'])); //Ngày sinh lọc lấy năm tính tuổi

$current_year = (int)date("Y"); //năm hiện tại là 2021

$age = $current_year - $year; //Tuổi 

$email = $_POST['email'];
$phone = $_POST['phone'];
$idBS = $_POST['idBS'];
$idBN = $_POST['idBN']; //Không cần idBN
$date = $_POST['date']; //Ngày đặt
$session = $_POST['session']; //id_buoi
$time = $_POST['time']; //id_khung_gio
$symtom = $_POST['symtom'];

//Lấy tên khung giờ
$sql_time = "	SELECT name
                FROM khung_gio
                WHERE id=$time AND id_buoi=$session";
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
$is_checked = 0;


//Thêm dữ liệu vô bảng lịch hẹn
$query = "INSERT INTO lich_hen
(idBS, nameBN, genderBN, ageBN, phoneBN, emailBN, symtom, start_time, end_time, date, update_time, is_checked)
VALUES ($idBS, '$name', '$gender', $age, '$phone', '$email', '$symtom', '$start_time', '$end_time', '$date', '$uptime', 0)";

$output = '';
$status = '';
$tenbs = '';
$bsPN = '';

//Nếu thêm thành công
if(mysqli_query($conn, $query)){
    $output = 'Thêm thành công';
    $status = 1;
    $id_book = mysqli_insert_id($conn);
    $sql = "	SELECT tk.name AS HOTEN, tk.phone
                FROM bacsi bs
                JOIN taikhoan tk ON tk.id = bs.idTK
                WHERE bs.id = $idBS";
    $rs = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($rs);
    $tenbs = $row['HOTEN'];
    $bsPN = $row['phone'];
}
else{
    $output = 'Thêm thất bại';
    $status = 0;
}

$json = json_encode(array('status' => $status, 
                        'output' => $output, 
                        'appointmentID' => $id_book, 
                        'date' => $date, 
                        'name' => $tenbs, 
                        'phone' => $bsPN, 
                        'emailBN' => $email, 
                        'nameBN' => $name));
echo $json;

?>