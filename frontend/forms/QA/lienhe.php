<?php
session_start();
//Kết nối database
include_once(__DIR__ . './../../../config/dbconnect.php');

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

    <?php include_once(__DIR__ . './../../pages/style/style_css.php'); ?>

    <link rel="stylesheet" href="/nln_test/assets/frontend/css/style_contact.css">

</head>

<body>
    <div class="page-holder">
        <!-- navbar-->
        <?php include_once(__DIR__ . './../../pages/layout/header.php'); ?>
        <!-- navbar end -->

        <!-- banner -->
        <section class="contact">
            <div class="container py-5 text-white text-center">
                <div class="row py-5">
                    <div class="col-lg-9 mx-auto">
                        <h1>Liên hệ với chúng tôi</h1>
                        <p>Bằng cách điền vào form bên dưới</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- banner end -->


        <section class="contact_form" style="background: linear-gradient(120deg,rgba(229, 235, 236, 0.7), rgba(192,244,248,0.7))">
            <div class="container py-5">
                <div class="row py-5">
                    <div class="col-lg-6 mx-auto rounded" style="background: linear-gradient(to right bottom, rgba(255,255,255,0.8),rgba(255,255,255,0.4));">
                        
                        <form action="" method="POST" class="py-5 px-3">
                            <h2 class="text-center py-3">FORM GÓP Ý KIẾN</h2>
                            <div class="form-group ">
                                <label for="name">Họ và tên</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="form-group ">
                                <label for="email">Email <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="email" id="email"  placeholder="example@gmail.com">
                            </div>
                            <div class="form-group ">
                                <label for="phone">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone" id="phone"  placeholder="0284729xxx (10 số)">
                            </div>
                            <div class="form-group ">
                                <label for="content">Nội dung góp ý <span style="color: red;">*</span></label>
                                <textarea class="form-control" name="content" rows="3" id="content"  placeholder="Ghi nội dung bạn muốn góp ý vào đây"></textarea>
                            </div>

                            <button type="button" name="submit" class="btn btn-primary form-control mt-2 btn-submit ">Gửi góp ý</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>


        <!-- Footer start -->
        <?php include_once(__DIR__ . './../../pages/style/style_js.php'); ?>
        <?php include_once(__DIR__ . './../../pages/layout/footer.php'); ?>
        <!-- Footer end-->

        <script>
            $(document).ready(function() {
                $('.btn-submit').on('click', function() {
                    var name = $('#name').val();
                    var email = $('#email').val();
                    var phone = $('#phone').val();
                    var content = $('#content').val();

                    var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

                    var error = true;

                    //Check name có bị để trống
                    if (name === "") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Vui lòng nhập họ tên!'
                        });
                        error = false;
                    }

                    //Check content có bị để trống
                    if (content === "") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Vui lòng nhập nội dung góp ý!'
                        });
                        error = false;
                    }

                    //Check email hợp lệ
                    if (email === "") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Vui lòng nhập địa chỉ mail!'
                        });
                        error = false;
                    } else if ($('#email').val().match(re)) {
                        email = $('#email').val();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Địa chỉ email không hợp lệ!'
                        });
                        error = false;
                    }

                    if(phone === ""){
                        phone = "Trống";
                    }

                    if (error == true) {
                        $.ajax({
                            url: "./function/input_contact.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                name: name,
                                email: email,
                                phone: phone,
                                content: content
                            },
                            success: function(response) {
                                if (response.status == 0) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: 'Có vẻ không góp ý được :( !'
                                    });
                                } else {
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Góp ý thành công',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $(function() {
                                        function reload_page() {
                                            location.reload();
                                        }
                                        window.setTimeout(reload_page, 1500);
                                    });
                                }
                            }
                        });
                    }

                });
            });
        </script>
    </div>

</body>

</html>