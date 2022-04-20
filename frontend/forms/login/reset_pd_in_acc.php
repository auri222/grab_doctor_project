<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('location: ./../login.php');
    exit();
}


//Kết nối database
include_once(__DIR__ . './../../../config/dbconnect.php');
$error = array();
$success = '';
$sendmail = '';
$hidden = '';
if (!isset($_SESSION['id'])) {
    $error['login'] = "Session không tồn tại";
}

$idTK = $_GET['id'];

if(isset($_POST['confirm'])){
    if(empty($_POST['otp'])){
        $error['otp'] = "Hãy nhập mã otp";
    }else{
        $otp = trim($_POST['otp']);
    }

    $sql = "SELECT * FROM taikhoan WHERE otp=$otp";
    $rs = mysqli_query($conn, $sql);
    $num_row = mysqli_num_rows($rs);
    if($num_row == 1){
        $success = 'success';
        echo '<script> location.href = "/nln_test/frontend/forms/login/reset_in_acc.php?success='.$success.'&id='.$idTK.'" </script>';
        exit();
    }else{
        $error['otp'] = "Mã otp không khớp";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang xác nhận</title>

    <!-- custom css -->
    <link rel="stylesheet" href="/nln_test/assets/frontend/css/style_login.css">

    <!-- bootstrap css -->
    <link rel="stylesheet" href="/nln_test/vendors/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container-fluid" style="min-height: 100vh;">
        <div class="row" style="min-height: 100vh;">
            <div class="col-12 col-md-2 col-xl-3"></div>
            <div class="col-12 col-md-8 col-xl-6 content">
                <div class="wrapper px-3 pt-3 pb-4 ">
                    <h2 class="title text-center my-4">Xác nhận mã OTP</h2>
                    <?php if (count($error) > 0) : ?>
                        <div class="alert alert-warning" role="alert">
                            <?php foreach ($error as $err) : ?>
                                <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($sendmail == 1) : ?>
                        <div class="alert alert-success" role="alert">
                            Chúng tôi đã gởi mail cho bạn. Hãy kiểm tra lại mail và nhập mã otp vào ô bên dưới.
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="otp">Mã OTP:</label>
                            <input type="text" class="form-control" id="otp" name="otp" >
                        </div>
                        <button type=" submit" name="confirm" class="btn btn-primary btn-block my-3">Xác nhận</button>
                    </form>

                </div>
            </div>
            <div class="col-12 col-md-2 col-xl-3"></div>
        </div>

        <script src="/nln_test/vendors/jquery/jquery-3.6.0.min.js"></script>
        <script src="/nln_test/vendors/bootstrap/js/bootstrap.min.js"></script>
        <script src="/nln_test/vendors/sweetalert2/sweetalert2.all.min.js"></script>
</body>

</html>