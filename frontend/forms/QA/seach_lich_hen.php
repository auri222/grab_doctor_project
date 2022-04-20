<?php
session_start();
//Kết nối database
include_once(__DIR__ . './../../../config/dbconnect.php');


$notLogin = '';
$Login = '';
$idBN = '';
$nameBN = '';
$dobBN = '';
$genderBN = '';
$emailBN = '';
$phoneBN = '';
//Nếu người dùng chưa đăng nhập
if (!isset($_SESSION['id']) && !isset($_SESSION['type'])) {
    $notLogin = '';
    $Login = 'style="display: none;"';
}
//Có đăng nhập và là người dùng
else if (isset($_SESSION['id'])) {
    if ($_SESSION['authority'] == 3) {
        $idBN = $_SESSION['id'];
        $notLogin = 'style="display: none;"';
        $Login = '';
        $sql_bn = "SELECT name, dob, gender, email, phone
        FROM taikhoan
        WHERE id = $idBN";
        $rs_bn = mysqli_query($conn, $sql_bn);
        $khach = mysqli_fetch_assoc($rs_bn);
        $nameBN = $khach['name'];
        $dobBN = $khach['dob'];
        $genderBN = $khach['gender'];
        $emailBN = $khach['email'];
        $phoneBN = $khach['phone'];
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

    <?php include_once(__DIR__ . './../../pages/style/style_css.php'); ?>

</head>

<body>
    <div class="page-holder">
        <!-- navbar-->
        <?php include_once(__DIR__ . './../../pages/layout/header.php'); ?>
        <!-- navbar end -->

        <section class="jumbotron-doctor">
            <div class="jumbotron jumbotron-fluid mb-0">
                <div class="container">
                    <h1 class="display-5">Tìm kiếm lịch hẹn đã hẹn</h1>
                    <p class="lead">Bạn có thể xem về thông tin lịch hẹn bằng cách điền vô ô bên dưới</p>
                </div>
            </div>
        </section>

        <section class="doctor-profile py-5">
            <div class="container">
                <div class="row ">
                    <div class="col-lg-9 mx-auto">
                        <h4 class="text-center py-3">TÌM KIẾM THEO MÃ LỊCH HẸN</h4>
                        <input type="text" class="form-control mb-4" id="search" autocomplete="off" autofocus>

                        <div id="search_result"></div>
                        
                    </div>

                </div>
            </div>
        </section>


        <!-- Footer start -->
        <?php include_once(__DIR__ . './../../pages/style/style_js.php'); ?>
        <?php include_once(__DIR__ . './../../pages/layout/footer.php'); ?>
        <!-- Footer end-->
    </div>

    <script>
        $(document).ready(function(){
            $('#search').keyup(function(){
                var keyword = $(this).val();
                console.log("key: "+keyword);

                if(keyword !== "" && !isNaN(keyword)){
                    $.ajax({
                        url: "./function/fetch_lichhen.php",
                        method: "POST",
                        data: {
                            keyword: keyword
                        },
                        success:function(data){
                            $('#search_result').html(data);
                        }
                    });
                }else{
                    location.reload();
                }
            });
        })
    </script>
</body>

</html>