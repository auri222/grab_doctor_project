<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . './../../../../../vendors/PHPMailer/vendor/autoload.php';
include_once(__DIR__ . './../../../../../config/dbconnect.php');

$output = '';
$status = '';
$idTK = $_POST['idTK'];
//1. Lấy email của người dùng này
$sql_email = "SELECT email, otp FROM taikhoan WHERE id=$idTK";
$rs_email = mysqli_query($conn, $sql_email);
$row_email = mysqli_fetch_assoc($rs_email);
$email = $row_email['email'];
$otp = $row_email['otp'];
//2. Update mã otp lại
$new_otp = rand(100000, 999999);
//var_dump("otp cũ: ".$otp."otp mới: ".$new_otp); die;
$sql_otp = "UPDATE taikhoan SET otp = $new_otp WHERE id=$idTK";
if (mysqli_query($conn, $sql_otp)) {
    //3. Send mail
    require __DIR__ . './../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require __DIR__ . './../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/SMTP.php';
    require __DIR__ . './../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/Exception.php';

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

    //Recipients
    $mail->setFrom('auripine68@gmail.com', 'Grab Doctor');
    $mail->addAddress($email);     //Add a recipient


    //Content
    $subject = 'Đổi mật khẩu tài khoản';
    $message = '<p>Đây là mã otp để thay đổi mật khẩu tài khoản của bạn: <b>' . $new_otp . '</b></p>';
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    if ($mail->send()) {
        $status = 1;
        $output = "Send thành công";
    } else {
        $status = 0;
        $ouput = "Gửi mail không thành công";
    }
}else{
    $status = 0;
    $output = "Lỗi database";
}

$json = json_encode(array('status' => $status, 'message' => $output));
echo $json;

?>

