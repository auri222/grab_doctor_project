<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('location: ./../../frontend/forms/login/login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang xác nhận thành công</title>
    <!-- Custom css -->
    <link rel="stylesheet" href="/nln_test/assets/frontend/css/style_login.css">

    <!-- Bootstrap css -->
    <link rel="stylesheet" href="/nln_test/vendors/bootstrap/css/bootstrap.min.css">

</head>

<body>
    <div class="container-fluid" style="min-height: 100vh;">
        <div class="row" style="min-height: 100vh;">
            <div class="col-12 col-md-2 col-xl-3"></div>
            <div class="col-12 col-md-8 col-xl-6 content">
                <div class="wrapper px-3 pt-3 pb-4 ">

                    <?php if (isset($_GET['success']) && ($_GET['success'] == 1)) : ?>


                        <h3 class="text-center">Xin chào <?= $_SESSION['username'] ?>!</h3>
                        <div class="text-center alert alert-success my-2">
                            Bạn đã xác nhận tài khoản thành công !! 
                        </div>
                        <a href="/nln_test/index.php" class="badge badge-info">Đi đến TRANG CHỦ</a> <br/>
                        <a href="/nln_test/frontend/forms/login/login.php?success=<?php echo 'success'; ?>" class="badge badge-info">Quay lại TRANG ĐĂNG NHẬP</a>
                    <?php else : ?>
                        <div class="text-center alert alert-warning my-2">
                            Có vẻ như bạn chưa xác nhận mã otp, hay là đã xảy ra lỗi gì đó vui lòng liên hệ với admin ở trang liên hệ!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-12 col-md-2 col-xl-3"></div>
        </div>


        <script src="/nln_test/vendors/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>