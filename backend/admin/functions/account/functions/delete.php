<?php

include_once(__DIR__ . './../../../../../config/dbconnect.php');

$idtk = $_POST['tkID'];

$status = "";
$message = "";

//Lấy idPQ kiểm tra
$sql_check = "  SELECT idPQ
                FROM taikhoan WHERE id=$idtk";
$rs_check = mysqli_query($conn, $sql_check);
$row_check = mysqli_fetch_assoc($rs_check);
$idPQ = $row_check['idPQ'];
//-----------------------------------------------------------------------------------------------
//Set up send mail
//Xóa: 
//1. Lịch hẹn của bác sĩ đó
//2. Lịch làm việc
//3. Thông tin bác sĩ 
//4. Tài khoản của bác sĩ đó
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include_once(__DIR__ . './../../../../../config/constant.php');
//Set up để gửi email thông báo HỦY LỊCH HẸN
require __DIR__ . './../../../../../vendors/PHPMailer/vendor/autoload.php';
require __DIR__ . './../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require __DIR__ . './../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/Exception.php';
require __DIR__ . './../../../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/SMTP.php';

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
date_default_timezone_set('Asia/Ho_Chi_Minh');

//-----------------------------------------------------------------------------------------------
//1: admin, 2: doctor, 3:user
//Chỉ kiểm tra doctor vs user
if ($idPQ == 2) { //doctor trước có 4 trường hợp

    $LICHHEN_COUNT = '';
    $LICHLAMVIEC_COUNT = '';
    $DOCINFO_COUNT = '';

    //CHECK INFO
    $sql_lichhen_count = "SELECT COUNT(*) AS TOTAL
                        FROM taikhoan tk
                        JOIN bacsi bs ON bs.idTK = tk.id
                        JOIN lich_lam_viec llv ON llv.idBS = bs.id
                        JOIN lich_hen lh ON lh.idBS = bs.id
                        WHERE tk.id = $idtk";
    $rs_lichhen_count = mysqli_query($conn, $sql_lichhen_count);

    $sql_lichlamviec_count = "SELECT COUNT(*) AS TOTAL
                                FROM taikhoan tk
                                JOIN bacsi bs ON bs.idTK = tk.id
                                JOIN lich_lam_viec llv ON llv.idBS = bs.id
                                WHERE tk.id = $idtk";
    $rs_lichlamviec_count = mysqli_query($conn, $sql_lichlamviec_count);

    $sql_bacsiinfo_count = "SELECT COUNT(*) AS TOTAL
                            FROM taikhoan tk
                            JOIN bacsi bs ON bs.idTK = tk.id
                            WHERE tk.id = $idtk";
    $rs_bacsiinfo_count = mysqli_query($conn, $sql_bacsiinfo_count);

    $LICHHEN_COUNT = mysqli_num_rows($rs_lichhen_count);
    $LICHLAMVIEC_COUNT = mysqli_num_rows($rs_lichlamviec_count);
    $DOCINFO_COUNT = mysqli_num_rows($rs_bacsiinfo_count);

    //----------------------------------------------------------------------------------------------
    //Có lịch hẹn thì xóa lịch hẹn trước
    if ($LICHHEN_COUNT > 0) {
        $now = date('Y-m-d');
        $today = date_create($now); //Lấy ngày hiện tại
        $ds_lh_checked = array(); //Giữ lịch hẹn đã được check và lố ngày hẹn
        $ds_lh_not_checked = array(); //Giữ lịch hẹn chưa check hoặc check mà chưa tới ngày hẹn

        //Lấy dữ liệu để kiểm tra
        $sql = "SELECT lh.id, lh.nameBN, lh.emailBN, lh.start_time, lh.end_time, lh.date, lh.is_checked, tk.name
    FROM lich_hen lh
    JOIN bacsi bs ON bs.id = lh.idBS
    JOIN taikhoan tk ON tk.id = bs.idTK
    WHERE tk.id = $idtk";
        $rs = mysqli_query($conn, $sql);
        $ds = array();
        while ($row = mysqli_fetch_assoc($rs)) {
            $ds[] = $row;
        }

        $now = date('Y-m-d');
        $today = date_create($now);

        //var_dump($ds);
        $ds_not_checked = array();
        $ds_checked = array();
        //Lấy dữ liệu cho 2 mảng
        foreach ($ds as $d) {
            $book_date = date_create($d['date']);
            $diff = date_diff($today, $book_date);
            $days = $diff->format('%R%a'); // Số ngày: >=0 còn trong hạn, <0 lố hạn
            $nod = (int)$days;
            if ($d['is_checked'] == 1 && $nod < 0) {
                $ds_checked[] = array(
                    'id'    => $d['id'],
                    'name'    => $d['nameBN'],
                    'email'    => $d['emailBN'],
                    'start'    => $d['start_time'],
                    'end'    => $d['end_time'],
                    'date'    => $d['date'],
                    'is_checked'    => $d['is_checked'],
                    'docname' => $d['name']
                );
            } else {
                $ds_not_checked[] = array(
                    'id'    => $d['id'],
                    'name'    => $d['nameBN'],
                    'email'    => $d['emailBN'],
                    'start'    => $d['start_time'],
                    'end'    => $d['end_time'],
                    'date'    => $d['date'],
                    'is_checked'    => $d['is_checked'],
                    'docname' => $d['name']
                );
            }
        }

        //Send mail cho lịch hẹn chưa duyệt và đã duyệt còn hạn
        $mail->setFrom('auripine68@gmail.com', 'Grab Doctor');

        foreach ($ds_not_checked as $not_checked) {
            $mail->addAddress($not_checked['email']);
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
                    <h3>Xin chào ' . $not_checked['name'] . ', </h3>
                    <h3>Lịch hẹn của bạn với bác sĩ ' . $not_checked['docname'] . ' đã bị hủy! </h3>
                    <table style="border: none;">
                        <tr>
                            <td>Mã lịch hẹn:</td>
                            <td>' . $not_checked['id'] . '</td>
                        </tr>
                        <tr>
                            <td>Ngày đặt:</td>
                            <td>' . date("d/m/Y", strtotime($not_checked['date'])) . '</td>
                        </tr>
                        <tr>
                            <td>Thời gian bắt đầu:</td>
                            <td>' . $not_checked['start'] . '</td>
                        </tr>
                        <tr>
                            <td>Thời gian kết thúc:</td>
                            <td>' . $not_checked['end'] . '</td>
                        </tr>
                    </table>
                    <br/>
                    <h3>Xin lỗi vì sự bất tiện này!</h3>
                    <h4>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi</h4>
                </div>
            </body>
            </html>
            ';
            $subject = 'Hủy lịch hẹn từ Grab Doctor';
            $message =  $body;
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            try {
                $mail->send();
                echo "Email send to: " . $not_checked['name'];
            } catch (Exception $e) {
                echo 'Cannot send email for ' . $not_checked['name'];
                $mail->getSMTPInstance()->reset();
            }
            //Xóa email address cho lần gởi kế tiếp
            $mail->clearAddresses();
        }
        $sql_doctor_appointment = "DELETE FROM lich_hen lh
                                    JOIN bacsi bs ON bs.id = lh.idBS
                                    JOIN taikhoan tk ON  bs.idTK = tk.id
                                    WHERE tk.id = $idtk";
        if (mysqli_query($conn, $sql_doctor_appointment)) {
            $status = 1;
            $message .= "Xóa lịch hẹn thành công. ";
        } else {
            $status = 0;
            $message = "Lỗi khi xóa lịch hẹn!";
        }
    }

    if ($LICHLAMVIEC_COUNT > 0) {
        $sql_doctor_work_calendar = "DELETE FROM lich_lam_viec llv
                                JOIN bacsi bs ON bs.id = llv.idBS
                                JOIN taikhoan tk ON  bs.idTK = tk.id
                                WHERE tk.id = $idtk";
        if (mysqli_query($conn, $sql_doctor_work_calendar)) {
            $status = 1;
            $message .= "<li> Xóa lịch làm việc thành công. ";
        } else {
            $status = 0;
            $message = "Lỗi khi xóa lịch làm việc!";
        }
    }

    if ($DOCINFO_COUNT > 0) {
        $sql_doctor_info = "DELETE FROM bacsi WHERE idTK=$idtk";
        if (mysqli_query($conn, $sql_doctor_info)) {
            $status = 1;
            $message .= "Xóa thông tin bác sĩ thành công. ";
        } else {
            $status = 0;
            $message = "Lỗi khi xóa thông tin bác sĩ!";
        }
    }

    $sql_doctor_account = "DELETE FROM taikhoan WHERE id=$idtk";

    if (mysqli_query($conn, $sql_doctor_account)) {
        $status = 1;
        $message .= "Xóa tài khoản bác sĩ thành công. ";
    } else {
        $status = 0;
        $message = "Lỗi khi xóa tài khoản bác sĩ!";
    }
}
if ($idPQ == 3) {

    $sql_user_account = "DELETE FROM taikhoan WHERE id=$idtk";


    if (mysqli_query($conn, $sql_user_account)) {
        $status = 1;
        $message = "Xóa thành công";
    } else {
        $status = 0;
        $message = "Lỗi khi xóa tài khoản người dùng!";
    }
}

$json = json_encode(array("status" => $status, "message" => $message));

echo $json;
