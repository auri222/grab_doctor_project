<?php
session_start();
//Kết nối database
include_once(__DIR__ . '../../../../config/dbconnect.php');
$error = array();
$success = array();
$hidden = '';
if(!isset($_SESSION['id'])){
    $error['login'] = "Session không tồn tại";
}
if( (isset($_GET['token'])) && (isset($_GET['type'])) && (isset($_SESSION['id'])) ){
    $akey = $_GET['token']; 
    $type = $_GET['type'];
}

if(isset($_POST['confirm'])){
    if(empty($_POST['otp']) && empty($akey) && empty($type)){
        $error['otp'] = "Hãy nhập mã otp từ email chúng tôi vừa gửi cho bạn";
    }
    else {
        $code = trim($_POST['otp']);
        $sql = "SELECT * FROM taikhoan WHERE token='$akey' AND otp='$code'";
        $rs = mysqli_query($conn, $sql);
        $num_row = mysqli_num_rows($rs);
        if($num_row == 1 ) {
            $update = "UPDATE taikhoan SET verified=1 WHERE token='$akey' AND otp='$code' ";
            if(mysqli_query($conn, $update)){
                $success= 1;
                if($type == 2){
                    echo '<script> location.href = "/nln_test/backend/doctor/verify_doctor.php?success='.$success.'"; </script>';
                }
                
                if($type == 3){
                    echo '<script> location.href = "/nln_test/frontend/pages/functions/verify_user.php?success='.$success.'"; </script>';
                }
            }
        }
        else{
            $error['account'] = "Mã otp không đúng!";
        }
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

<body >
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

                    <?php if (isset($_SESSION['warning'])) : ?>
                        <div class="alert alert-info" role="alert">
                            <?php echo $_SESSION['warning']; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" >
                        <div class="form-group">
                            <label for="otp">Mã OTP:</label>
                            <input type="text" class="form-control" id="otp" name="otp" ">
                        </div>
                        <button type="submit" name="confirm" class="btn btn-primary btn-block my-3">Xác nhận</button>
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