<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . './../../../vendors/PHPMailer/vendor/autoload.php';
include_once(__DIR__ . './../../../config/dbconnect.php');
include_once(__DIR__ . './../../../config/constant.php');

$output = '';
$status = '';
$start_time = '';
$end_time = '';
$appID = $_POST['appointmentID'];
$book_date = $_POST['book_date'];
$bs_name = $_POST['bs_name'];
$bs_phone = $_POST['bs_phone'];
$email = $_POST['email'];
$nameBN = $_POST['nameBN'];

$sql = "SELECT start_time as START, end_time as END
        FROM lich_hen where id=$appID";
$rs = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($rs);
$start_time = $row['START'];
$end_time = $row['END'];

require __DIR__ . './../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . './../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/SMTP.php';
require __DIR__ . './../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/Exception.php';

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
//Server settings
$mail->SMTPDebug = false;
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = $admin_email;                     //SMTP username
$mail->Password   = $admin_email_pass;                               //SMTP password
$mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
$mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

//Recipients
$mail->setFrom('auripine68@gmail.com', 'Grab Doctor');
$mail->addAddress($email);     //Add a recipient

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
        <h3>Sau đây là thông tin lịch hẹn bạn đã đăng ký: </h3>
        <table style="border: none;">
            <tr>
                <td>Mã lịch hẹn:</td>
                <td>'.$appID.'</td>
            </tr>
            <tr>
                <td>Họ tên bác sĩ:</td>
                <td>'.$bs_name.'</td>
            </tr>
            <tr>
                <td>Số điện thoại:</td>
                <td>'.$bs_phone.'</td>
            </tr>
            <tr>
                <td>Ngày đặt:</td>
                <td>'.date("d/m/Y",strtotime($book_date)).'</td>
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
        <h4>Bạn có thể kiểm tra thông tin về lịch hẹn ở website. Bác sĩ sẽ liên hệ với bạn trong khoảng thời gian gần!</h4>
        <h4>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi</h4>
    </div>
</body>
</html>
';

//Content
$subject = 'Thông tin đặt lịch hẹn tại Grab Doctor';
$message =  $body;
$mail->isHTML(true);                                  //Set email format to HTML
$mail->Subject = $subject;
$mail->Body    = $message;

if ($mail->send()) {
    $status = 1;
    $output = "Send thành công";
} else {
    $status = 0;
    $ouput = "Gửi mail không thành công! Hãy check lại tài khoản email của bạn";
}


$json = json_encode(array('status' => $status, 'message' => $output));
echo $json;
