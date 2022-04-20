<?php
//Kết nối database
require_once(__DIR__.'./frontend/controllers/authController.php');

$notLogin = '';
$Login = '';
//Nếu người dùng chưa đăng nhập
if (!isset($_SESSION['id']) && !isset($_SESSION['type'])) {
    $notLogin = '';
    $Login = 'style="display: none;"';
}
//Có đăng nhập và là người dùng
else if (isset($_SESSION['id'])) {
    if ($_SESSION['authority'] == 3) {
        $notLogin = 'style="display: none;"';
        $Login = '';
    } else {
        $Login = 'style="display: none;"';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grab doctor</title>

    <?php include_once(__DIR__ . './frontend/pages/style/style_css.php'); ?>
    <style>
        .introduction i{
            font-weight: bold;
            color: #35ee40;
        }

        .intro2 {
            background: linear-gradient(rgba(190, 220, 227, 0.7), rgba(0,93,168,0.7)), url('/nln_test/assets/frontend/img/covid-19-distance.png');
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            width: 100%;  
            min-height: 500px;
        }

        .intro2 .text1{
            font-size: 40px;
            font-weight: bold;
            color: white;
        }

        .intro2 .row-text{
            border: 5px solid white;
            color:white;
        }
    </style>
</head>

<body>
    <div class="page-holder">
        <!-- navbar-->
        <?php include_once(__DIR__ . './frontend/pages/layout/header.php'); ?>
        <!-- navbar end -->

        <!-- banner -->
        <section class="parallax ">
            <div class="container py-5 text-white text-center">
                <div class="row py-5">
                    <div class="col-lg-9 mx-auto">
                        <h1><span style="color: turquoise;">Grab doctor </span> - Chăm sóc sức khỏe của bạn mọi lúc mọi nơi</h1>
                        <button type="button" class="btn0 mt-2">Tìm hiểu thêm về chúng tôi</button>
                    </div>
                </div>
            </div>
        </section>
        <!-- banner end -->
        
        <!-- intro -->
        <section class="introduction my-3">
            <div class="container py-5 " style="background: linear-gradient(to right bottom, rgba(131,244,237,0.8),rgba(216,255,250,0.4));">
                <div class="row" >
                    <div class="col-lg-6">
                        <img src="/nln_test/assets/frontend/img/doctor-gear.png" alt="Từ pixabay" class="img-fluid mx-auto d-block" width="300px">
                    </div>
                    <div class="col-lg-6">
                        <h3 class="py-3">Grab Doctor sẽ có gì?</h3>
                        <ul class="list-intro" style="list-style-type: none; font-size: 20px">
                            <li class="py-2"><i class="bi bi-check2-circle mr-2"></i> Đặt lịch hẹn phù hợp với thời gian và nơi bạn sống</li>
                            <li class="py-2"><i class="bi bi-check2-circle mr-2"></i> Không quá nhiều thao tác</li>
                            <li class="py-2"><i class="bi bi-check2-circle mr-2"></i> Tiết kiệm thời gian</li>
                            <li class="py-2"><i class="bi bi-check2-circle mr-2"></i> Dễ dàng đóng góp ý kiến</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
        <!-- intro -->


        <section class="intro2" >
            <div class="container py-5 text-center">
                <div class="row py-5" >
                    <div class="col-lg-9 mx-auto">
                            <span class="text1">THỜI COVID</span>
                            <div class="row py-4 row-text d-flex justify-content-center align-items-center mt-3">
                                <div class="col-md-3"><span class="text-center text2" style="font-size: 20px; font-weight:bold;">Luôn đeo khẩu trang </span></div>
                                <div class="col-md-3"><span class="text-center text2" style="font-size: 20px; font-weight:bold;">Giữ khoảng cách </span></div>
                                <div class="col-md-3"><span class="text-center text2" style="font-size: 20px; font-weight:bold;">Khử khuẩn</span></div>
                                <div class="col-md-3"><span class="text-center text2" style="font-size: 20px; font-weight:bold;">Không tập trung đông người</span></div>
                            </div>
                        </div>
                    </div>
            </div>
        </section>


        <!-- Footer start -->
        <?php include_once(__DIR__ . './frontend/pages/style/style_js.php'); ?>
        <?php include_once(__DIR__ . './frontend/pages/layout/footer.php'); ?>
        <!-- Footer end-->
    </div>

</body>

</html>