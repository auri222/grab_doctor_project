<?php
session_start();
include_once(__DIR__ . './../../../../config/dbconnect.php');

date_default_timezone_set("Asia/Ho_Chi_Minh");

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../frontend/forms/login/login.php');
    exit();
}

$error = array();

if(isset($_POST['register'])){
    $uptime = date("Y-m-d H:i:s");

    if(empty($_POST['name'])){
        $error['name'] = "Nhập họ tên!";
    }
    else{
        $name = trim($_POST['name']);
    }

    if(empty($_POST['email'])){
        $error['email'] = "Nhập vào địa chỉ mail!";
    }else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
        $error['email'] = "Nhập vào địa chỉ mail hợp lệ!";
    }
    else{
        $email = trim($_POST['email']);
    }

    if(empty($_POST['content'])){
        $error['content'] = "Hãy nhập nội dung góp ý!";
    }
    else{
        $content = trim($_POST['content']);
    }

    $phone = (empty($_POST['phone']))?"Trống":$_POST['phone'];

    if(count($error) == 0){
        $sql = "INSERT INTO lien_he
        (name, phone, email, content, is_checked, up_time)
        VALUES ('$name', '$phone', '$email', '$content', 0, '$uptime')";
        if(mysqli_query($conn, $sql)){
            echo '<script>
                alert("Góp ý thành công!");
                location.href = "/nln_test/backend/doctor/index.php";
                </script>';
        }
        else{
            $error['database'] = "Thêm không thành công";
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
    <title>Trang góp ý</title>

    <!-- custom css -->
    <link rel="stylesheet" href="/nln_test/assets/frontend/css/signup.css">

    <!-- bootstrap css -->
    <link rel="stylesheet" href="/nln_test/vendors/bootstrap/css/bootstrap.min.css">
</head>

<body>
    <div class="container-fluid" style="min-height: 100vh;">
        <div class="row" style="min-height: 100vh;">
            <div class="col-12 col-md-1 col-xl-2"></div>
            <div class="col-12 col-md-10 col-xl-8 content">
                <div class="wrapper px-3 pt-3 pb-4 ">
                    <h2 class="title text-center my-4">Góp ý</h2>
                    <!-- Check lỗi -->
                    <?php if (count($error) > 0) : ?>
                        <div class="alert alert-warning" role="alert">
                            <?php foreach ($error as $err) : ?>
                                <li><?= $err; ?></li>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">

                        <div class="form-group">
                            <label for="name">Họ tên <span style="color: red;">*</span></label>
                            <input type="text" class="form-control " id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span style="color: red;">*</span></label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại </label>
                            <input type="text" class="form-control" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="content">Nội dung góp ý <span style="color: red;">*</span></label>
                            <textarea class="form-control" id="content" name="content" rows="5"></textarea>
                        </div>

                        <button type="submit" name="register" class="btn btn-primary btn-block my-3 btn-submit">Đăng ký</button>

                    </form>
                </div>
            </div>
            <div class="col-12 col-md-1 col-xl-2"></div>
        </div>

        <script src="/nln_test/vendors/jquery/jquery-3.6.0.min.js"></script>
        <script src="/nln_test/vendors/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>