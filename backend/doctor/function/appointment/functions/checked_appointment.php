<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include_once(__DIR__.'./../../../../../config/dbconnect.php');
require (__DIR__.'./../../../../../vendors/PHPMailer/vendor/autoload.php');
require (__DIR__.'./../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/PHPMailer.php');
require (__DIR__.'./../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/SMTP.php');
require (__DIR__.'./../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/Exception.php');

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
$ouput = '';
//Lấy email user BN
$sql_email = "SELECT tk.name, lh.nameBN, lh.emailBN, lh.symtom, lh.date, lh.start_time, lh.end_time
            FROM lich_hen lh
            JOIN bacsi bs ON bs.id = lh.idBS
            JOIN taikhoan tk ON tk.id = bs.idTK
            WHERE lh.id=$id";
$rs_email = mysqli_query($conn, $sql_email);
$row_email = mysqli_fetch_assoc($rs_email);

$sql = "UPDATE lich_hen
        SET is_checked=1
        WHERE id=$id";

if(mysqli_query($conn, $sql)){
    //Recipients
$mail->setFrom('auripine68@gmail.com', 'Grab Doctor');
$mail->addAddress($row_email['emailBN']);     //Add a recipient

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
        <h3>Xin chào '.$row_email["nameBN"].', </h3>
        <h3>Lịch hẹn của bạn đã được bác sĩ duyệt: </h3>
        <table style="border: none;">
            <tr>
                <td>Mã lịch hẹn:</td>
                <td>'.$id.'</td>
            </tr>
            <tr>
                <td>Họ tên bác sĩ:</td>
                <td>'.$row_email['name'].'</td>
            </tr>
            <tr>
                <td>Ngày đặt:</td>
                <td>'.date("d/m/Y",strtotime($row_email["date"])).'</td>
            </tr>
            <tr>
                <td>Thời gian bắt đầu:</td>
                <td>'.$row_email["start_time"].'</td>
            </tr>
            <tr>
                <td>Thời gian kết thúc:</td>
                <td>'.$row_email["end_time"].'</td>
            </tr>
        </table>
        <br/>
        <h4>Bạn có thể kiểm tra thông tin về lịch hẹn ở website. Nếu có vấn đề thì bác sĩ sẽ liên hệ với bạn sau.</h4>
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
    $output = "Đã gửi mail thông báo đến người hẹn!";
} else {
    $status = 0;
    $output = "Mail thông báo đến người hẹn đã bị lỗi! Hãy liên hệ với họ qua số điện thoại!";
}
}
else{
    $status = 0;
    $output = "Không duyệt được! Hãy liên hệ admin ở trang liên hệ!";
}

$json = json_encode(array("status" => $status, "output" => $ouput));

echo $json;

?>