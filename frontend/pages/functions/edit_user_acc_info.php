<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include_once(__DIR__ . './../../../config/dbconnect.php');
require __DIR__.'./../../../vendors/PHPMailer/vendor/autoload.php';

$status = "";

$message = "";

$id = $_POST['id'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$token = md5(time() . $username);
$otp = rand(100000, 999999);
$flag = 0;
$check = "SELECT email, idPQ from taikhoan WHERE id=$id";
$rs_check = mysqli_query($conn, $check);
$row_email = mysqli_fetch_assoc($rs_check);

if($email === $row_email['email']){
    $sql = "UPDATE taikhoan
            SET
                username='$username',
                phone='$phone'
            WHERE id=$id" ;
    $flag = 0;
}else{
    //Nếu có thay email thì phải xác minh lại
    $sql = "UPDATE taikhoan
            SET
                username='$username',
                email='$email',
                phone='$phone',
                verified=0,
                token='$token',
                otp=$otp
            WHERE id=$id" ;
    $flag = 1;
}


if (mysqli_query($conn, $sql)) {
    if($flag ==0){
        $status = 1;
        $message = "Sửa thành công";
    }
    else{
        //send mail gửi mã otp mới 
        require __DIR__.'./../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require __DIR__.'./../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/SMTP.php';
        require __DIR__.'./../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/Exception.php';

        $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            //Server settings
            $mail->SMTPDebug = 0;
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
            $subject = 'Xác minh tài khoản email';
            $message = '<p>Cảm ơn vì đã đăng ký tài khoản trên website của chúng tôi.</p>
            <p>Đây là mã otp để xác thực tài khoản email của bạn: <b>' . $otp . '</b></p>';
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;

            if($mail->send()){
                $status =1;
                $message = "Sửa thành công";
            }
            else{
                $status = 0;
                $message = "Sửa không thành công. Xem lại tài khoản email của bạn!";
            }

    }
} 

else {
    $status = 0;
    $message = "Lỗi rồi! Thử lại sao! :<";
}


$json = json_encode(array("status" => $status, "message" => $message, 'flag' => $flag, 'token' => $token, 'type' => $row_email['idPQ']));

echo $json;
