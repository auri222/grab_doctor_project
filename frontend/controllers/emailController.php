<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include_once (__DIR__.'/PHPMailer/vendor/autoload.php') ;

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

function send_email($recipient, $subject, $content){
    global $mail;
    
    //Recipients
    $mail->setFrom('auripine68@gmail.com', 'Grab Doctor');
    $mail->addAddress($recipient);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $content;

    if($mail->send()){
        return true;
    }
    else return false;
}

?>