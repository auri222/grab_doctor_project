<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include_once(__DIR__.'./../../../../../config/dbconnect.php');
require (__DIR__.'./../../../../../vendors/PHPMailer/vendor/autoload.php');
require (__DIR__.'./../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/PHPMailer.php');
require (__DIR__.'./../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/SMTP.php');
require (__DIR__.'./../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/Exception.php');
date_default_timezone_set('Asia/Ho_Chi_Minh');
//set up để send mail

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
//Server settings
$mail->SMTPDebug = false;
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = 'auripine68@gmail.com';                     //SMTP username
$mail->Password   = 'rsmlkaqkerpfoygx';                               //SMTP password
$mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
$mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);


$id = $_POST['lhID'];

$status = '';
$output = '';

//Xét trường hợp lịch hẹn đã duyệt hay chưa 
//Nếu chưa thì xóa và mail báo hủy lịch 
//Nếu đã duyệt và lố ngày thì cứ XÓA
//             và chưa tới ngày thì mail thông báo

$info = "   SELECT tk.name,lh.nameBN, lh.emailBN, lh.date, lh.start_time, lh.end_time, lh.is_checked
FROM lich_hen lh
JOIN bacsi bs ON bs.id = lh.idBS
JOIN taikhoan tk ON tk.id = bs.idTK
WHERE lh.id=$id";

$rs_info = mysqli_query($conn, $info);
$row_info = mysqli_fetch_assoc($rs_info);
$email = $row_info['emailBN'];
$doc_name = $row_info['name'];
$nameBN = $row_info['nameBN'];
$book_date = date_create($row_info['date']);
$start_time = $row_info['start_time'];
$end_time = $row_info['end_time'];
$is_checked = $row_info['is_checked'];
$body = '';

//Check ngày trong lịch hẹn => chưa tới hoặc = ngày hẹn hay đã lố ngày
$now = date('Y-m-d');
$today = date_create($now);
$diff = date_diff($today,$book_date);
$date = $diff->format("%R%a");
$nod = (int)$date; //Số ngày còn trong hạn: >0 thì còn, <0 thì lố ngày
$flag = 0;
//Nếu lịch đã duyệt và ngày bị lố thì xóa ko thông báo
if($is_checked == 1 && $nod<0){
    $flag = 1; 
}
else {
    $flag = 0;
    $body = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table td{
            padding: 10px 5px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h3>Xin chào '.$nameBN.', </h3>
        <h3>Lịch hẹn của bạn đã bị hủy: </h3>
        <table style="border: none;">
            <tr>
                <td>Mã lịch hẹn:</td>
                <td>'.$id.'</td>
            </tr>
            <tr>
                <td>Họ tên bác sĩ:</td>
                <td>'.$doc_name.'</td>
            </tr>
            <tr>
                <td>Ngày đặt:</td>
                <td>'.date("d/m/Y",strtotime($row_info['date'])).'</td>
            </tr>
            <tr>
                <td>Thời gian bắt đầu:</td>
                <td>'.$start_time.'</td>
            </tr>
            <tr>
                <td>Thời gian kết thúc:</td>
                <td>'.$end_time.'</td>
            </tr>
        </table>
        <br/>
        <h4>Xin thứ lỗi vì sự bất tiện này!</h4>
        
    </div>
</body>
</html>
';
}

//NỘI DUNG THÔNG BÁO XÓA CHUNG


$sql = "DELETE FROM lich_hen WHERE id=$id";

if(mysqli_query($conn, $sql)){  
    if($flag == 1){
        $status = 1;
        $output = "Xóa thành công";
    }
    else {
            //Recipients
        $mail->setFrom('auripine68@gmail.com', 'Grab Doctor');
        $mail->addAddress($email);     //Add a recipient

                //Content
        $subject = 'Thông báo hủy lịch hẹn';
        $message =  $body;
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        if ($mail->send()) {
            $status = 1;
            $output = "Đã gửi mail thông báo đến người hẹn!";
        } else {
            $status = 0;
            $output = "Mail thông báo đến người hẹn đã bị lỗi! Hãy liên hệ với họ qua số điện thoại!";
        }
    }
}
else{
    $status = 0;
    $output = "Xóa không thành công!";
}

$json = json_encode(array("status" => $status, "output" => $output));

echo $json;

?>