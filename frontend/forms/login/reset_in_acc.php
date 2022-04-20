<?php
session_start();
include_once(__DIR__ . '../../../../config/dbconnect.php');

if (!isset($_SESSION['id'])) {
    header('location: ./../login.php');
    exit();
}

$error = [];
$success = '';
if ((isset($_GET['success'])) && (isset($_GET['id']))) {
    $idTK = $_GET['id'];
} else {
    $error['error'] = "Có gì đó sai sai";
}

//Nếu người dùng đã "Đặt lại"
if (isset($_POST['reset'])) {
    //Check password
    if (empty($_POST['pd'])) {
        $error['password'] = "Hãy nhập password";
    } else {
        $password = trim($_POST['pd']);
        $password = mysqli_real_escape_string($conn, $password);
    }

    $repass = trim($_POST['repd']);
    $repass = mysqli_real_escape_string($conn, $repass);
    if (empty($repass)) {
        $error['confirm_password'] = "Hãy nhập ô nhập lại mật khẩu";
    } else if ($repass !== $password) {
        $error['confirm_password'] = "Mật khẩu không khớp";
    }
    //$error['info'] = "repass:".$repass."pd:".$password."idtk:".$idTK."hash:".$pd;
    if (count($error) === 0) {
        $pd = password_hash($password, PASSWORD_DEFAULT);
        $pd = trim($pd);
        $sql = "UPDATE taikhoan SET password='$pd' WHERE id=$idTK ";
        if (mysqli_query($conn, $sql)) {
            $success = 'success';
            session_destroy();
            unset($_SESSION['id']);
            unset($_SESSION['username']);
            unset($_SESSION['email']);
            unset($_SESSION['authority']);
            unset($_SESSION['phone']);
            unset($_SESSION['avatar']);
            echo '<script> location.href = "/nln_test/frontend/forms/login/login.php?reset=' . $success . '" </script>';
            exit();
        } else {
            $error['db'] = "Sai ở đâu đó rồi";
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
    <title>Trang đổi mật khẩu</title>

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
                    <h2 class="title text-center my-4">Đặt lại mật khẩu</h2>

                    <?php if (count($error) > 0) : ?>
                        <div class="alert alert-warning" role="alert">
                            <?php foreach ($error as $err) : ?>
                                <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="pd">Nhập mật khẩu mới</label>
                            <input type="password" class="form-control" id="pd" name="pd">
                        </div>
                        <div class="form-group">
                            <label for="repd">Nhập lại mật khẩu</label>
                            <input type="password" class="form-control" id="repd" name="repd">
                        </div>
                        <button type="submit" name="reset" class="btn btn-primary btn-block my-3">Đặt lại</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-2 col-xl-3"></div>
        </div>

        <script src="/nln_test/vendors/jquery/jquery-3.6.0.min.js"></script>
        <script src="/nln_test/vendors/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>