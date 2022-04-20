<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '../../../vendors/PHPMailer/vendor/autoload.php';

include_once(__DIR__ . '../../../config/dbconnect.php');
include_once(__DIR__ . '../../../config/constant.php');
date_default_timezone_set('Asia/Saigon');
$error = array();
$username = '';
$name = '';
$dob = '';
$email = '';
$phone = '';
//------------------------------------------------------------------------//
//Xử lý cho trang đăng ký
//Nếu người dùng bấm nút "Đăng ký"
if (isset($_POST['register'])) {
    //-------------------------------------------------------------------
    //Tài khoản 
    //-------------------------------------------------------------------
    echo '<script>
        alert("Bạn có chắc về thông tin đăng ký chứ ?");
    </script>';

    $gender = $_POST['gender'];
    $acc_type = $_POST['isdoctor'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $uptime = date('Y-m-d H:i:s');
    //Kiểm tra dữ liệu nhập
    //Họ tên
    if (empty($_POST['name'])) {
        $error['name'] = "Hãy nhập họ tên";
    } else {
        $name = trim($_POST['name']);
    }

    //Ngày sinh
    if (empty($_POST['dob'])) {
        $error['dob'] = "Hãy chọn ngày sinh";
    } else {
        $dob = trim($_POST['dob']);
    }

    //Tên đăng nhập
    if (empty($_POST['username'])) {
        $error['username'] = "Hãy nhập tên đăng nhập";
    } else {
        $username = trim($_POST['username']);
    }

    if (empty($_POST['phone'])) {
        $error['phone'] = "Hãy nhập vào số điện thoại!";
    } else if (!preg_match("/^0[1-9]{1}[0-9]{8}$/i", $_POST['phone'])) {
        $error['phone'] = "Hãy nhập đúng định dạng (gồm 10 số, bắt đầu bằng 0 theo sau là một số khác 0)!";
    } else {
        $phone = $_POST['phone'];
    }

    $pass = trim($_POST['password']);
    $repass = trim($_POST['repass']);
    $charLower = preg_match('@[a-z]@', $pass);
    $charUpper = preg_match('@[A-Z]@', $pass);
    $number = preg_match('@[0-9]@', $pass);

    if (empty($_POST['password'])) {
        $error['password'] = "Hãy nhập mật khẩu!";
    } else if ($pass !== $repass) {
        $error['confirm_password'] = "Mật khẩu xác nhận chưa đúng hoặc chưa nhập";
    } else if (!$charLower || !$charUpper || !$number || strlen($pass) < 8) {
        $error['password'] = "Hãy nhập mật khẩu gồm cả chữ cái viết hoa lẫn thường thường và số, phải nhiều hơn 8 ký tự!";
    } else {
        $pd = $pass;
        $pd = mysqli_real_escape_string($conn, $pd);
    }


    if (empty($email)) {
        $error['email'] = "Hãy nhập email của bạn!";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error['email'] = "Email nhập sai! Vui lòng nhập lại.";
    }

    if (empty($phone)) {
        $error['phone'] = "Hãy nhập vào số điện thoại!";
    } else if (strlen($phone) < 10 || strlen($phone) > 11) {
        $error['phone'] = "Hãy nhập đúng số điện thoại!";
    }

    //Check email đã có trong database chưa => lọc spammer
    $sql = "select * from taikhoan where email='$email'";
    $result = mysqli_query($conn, $sql);
    $num_row = mysqli_num_rows($result);
    if ($num_row > 0) {
        $error['email'] = 'Email đã tồn tại!';
    }
    //-------------------------------------------------------------------
    //Xử lý hình avatar
    if (!isset($_FILES['avatar']) || ($_FILES['avatar']['error'] == UPLOAD_ERR_NO_FILE)) {
        $error['avatar'] = "Chưa tải hình lên";
    } else {
        $file_name = $_FILES['avatar']['name'];
        $file_size = $_FILES['avatar']['size'];
        $file_tmp = $_FILES['avatar']['tmp_name'];
        $file_type = $_FILES['avatar']['type'];
        $file_error = $_FILES['avatar']['error'];
        $file_ext = explode('.', $_FILES['avatar']['name']);
        $fileExtension = strtolower(end($file_ext));
        $extensions = array("jpeg", "png", "jpg");

        //Check extension 
        if (in_array($fileExtension, $extensions) == false) {
            $error['type'] = "Bạn không được tải file loại này lên ngoài trừ file có đuôi .jpg, .png, .jpeg";
        }
        if ($file_error !== 0) {
            $error['error'] = "Có lỗi khi tải hình ảnh của bạn. Vui lòng thử lại!!";
        }
        if ($file_size > 2097152) {
            $error['size'] = "File tải lên có kích thước phải nhỏ hơn 2MB!!";
        }
    }
    //-------------------------------------------------------------------
    //var_dump("File name: ".$file_name); die;
    //Nếu không có lỗi
    if (count($error) === 0) {
        $path = __DIR__ . './../../assets/img/upload/avatar/' . $file_name;
        $pd = password_hash($pd, PASSWORD_DEFAULT);
        $pd = trim($pd);
        $token = md5(time() . $username);
        $otp = rand(100000, 999999);
        $verified = 0;
        //check send mail
        require __DIR__ . '../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
        require __DIR__ . '../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/SMTP.php';
        require __DIR__ . '../../../vendors/PHPMailer/vendor/phpmailer/phpmailer/src/Exception.php';

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        //Server settings
        $mail->SMTPDebug = 0;
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


        //Content
        $subject = 'Xác minh tài khoản email';
        $message = '<p>Cảm ơn vì đã đăng ký tài khoản trên website của chúng tôi.</p>
        <p>Đây là mã otp để xác thực tài khoản email của bạn: <b>' . $otp . '</b></p>';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        if ($mail->send()) {
            $q = "INSERT INTO taikhoan
        (username, password, name, dob, gender, avatar, email, phone, idPQ, verified, token, otp, update_time)
        VALUES ('$username', '$pd', '$name','$dob' , '$gender', '$file_name', '$email', '$phone', $acc_type, $verified, '$token', $otp, '$uptime')";
            // echo "Data: ".$username.", ".$pd.", ".$email.", ".$phone.", ".$isdoctor.", "."$verified".", ".$token;
            //mysqli_query($conn, $q) or die("Có lỗi khi thêm dữ liệu");
            if (mysqli_query($conn, $q)) {
                move_uploaded_file($file_tmp, $path);
                $_SESSION['id'] = mysqli_insert_id($conn);
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['authority'] = $acc_type;
                $_SESSION['verified'] = $verified;
                $_SESSION['avatar'] = $row['avatar'];
                $_SESSION['message'] = "Bạn đã đăng ký thành công!";
                $_SESSION['warning'] = "Vui lòng kiểm tra mail và nhập mã OTP để sử dụng các chức năng của website!";
                echo '<script> location.href = "/nln_test/frontend/forms/login/otp.php?token=' . $token . '&type=' . $acc_type . '"; </script>';
            } else {
                $error['db_error'] = 'Có lỗi! Không tạo được tài khoản <a href="/nln_test/frontend/forms/QA/lienhe.php">Liên hệ Admin</a>';
                
            }
        } else {
            $error['email'] = "Không gửi được email! Hãy nhập lại địa chỉ email hợp lệ!";
        }
    }
}

//-----------------------------------------------------------------------//
//Xử lý cho trang đăng nhập
//Nếu người dùng bấm nút "Đăng nhập"
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Kiểm tra dữ liệu nhập
    if (empty($username)) {
        $error['username'] = "Nhập vào username hoặc email.";
    }

    if (empty($password)) {
        $error['password'] = 'Nhập vào password.';
    }



    if (count($error) === 0) {
        $sql = "select * from taikhoan where username='$username'";
        $result = mysqli_query($conn, $sql);

        $user_count = mysqli_num_rows($result);
        if ($user_count > 0) {
            $row = mysqli_fetch_assoc($result);
            $check = password_verify($password, $row['password']);
            if ($check) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                //1:admin - 2:doctor - 3:user
                $_SESSION['authority'] = $row['idPQ'];
                $_SESSION['phone'] = $row['phone'];
                $_SESSION['avatar'] = $row['avatar'];
                $verified = $row['verified'];
                $token = $row['token'];
                if ($verified == 0) {
                    $_SESSION['warning'] = "Bạn chưa xác minh tài khoản email! Vui lòng kiểm tra mail và nhập mã OTP để sử dụng các chức năng của website!";
                    echo '<script> location.href = "/nln_test/frontend/forms/login/otp.php?token=' . $token . '&type=' . $row['idPQ'] . '"; </script>';
                }
                if ($row['idPQ'] == 1) {
                    echo '<script> location.href = "/nln_test/backend/admin/index.php" </script>';
                }
                if ($row['idPQ'] == 2) {
                    echo '<script> location.href = "/nln_test/backend/doctor/index.php" </script>';
                }
                if ($row['idPQ'] == 3) {
                    echo '<script> location.href = "/nln_test/index.php" </script>';
                }
            } else {
                $error['login_failed'] = "Nhập sai mật khẩu";
            }
        } else {
            $error['username'] = "Username không tồn tại";
        }
    }
}
