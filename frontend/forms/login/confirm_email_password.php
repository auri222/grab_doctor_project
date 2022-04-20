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
                    <h2 class="title text-center my-4">Xác nhận email</h2>

                    <div class="alert alert-info" role="alert">
                        Hãy nhập vào tài khoản email mà bạn đã đăng ký!
                    </div>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="email">Nhập email:</label>
                            <input type="text" class="form-control" id="email" name="email" >
                        </div>
                        <button type="button" name="confirm" class="btn btn-primary btn-block btn-confirm my-3">Xác nhận</button>
                    </form>

                </div>
            </div>
            <div class="col-12 col-md-2 col-xl-3"></div>
        </div>

        <script src="/nln_test/vendors/jquery/jquery-3.6.0.min.js"></script>
        <script src="/nln_test/vendors/bootstrap/js/bootstrap.min.js"></script>
        <script src="/nln_test/vendors/sweetalert2/sweetalert2.all.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.btn-confirm').on('click', function() {
                    var email = $('#email').val();
                    console.log("OK IDK");
                    console.log("Email là: " + email);
                    //Check email có phải email đã được đăng ký không?
                    $.ajax({
                        url: "./functions/fetch_email.php",
                        method: "POST",
                        dataType: "json",
                        data: {
                            email: email
                        },
                        success: function(response) {
                            console.log("status = "+response.status);
                            console.log("message = "+response.message);
                        
                            if (response.status == 1) {
                                alert("Chúng tôi sẽ gửi mã otp đến email. Xin hãy đợi tí!");
                                $.ajax({
                                    url: "./functions/send_otp_rspw.php",
                                    method: "POST",
                                    dataType: "json",
                                    data: {
                                        email: email
                                    },
                                    success: function(output) {
                                        var token = output.token;
                                        console.log("token: "+token);
                                        if (output.status == 1) {
                                            alert("Bạn hãy kiểm tra email của mình. Chúng tôi vừa gởi mã đến email của bạn!");
                                            location.href = "/nln_test/frontend/forms/login/otp_rspw.php?token="+token+"&email="+email;
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Có lỗi rồi!',
                                                text: output.message
                                            });
                                        }
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Sai thông tin',
                                    text: response.message
                                });
                                
                            }
                        }
                    });
                });

            });
        </script>
</body>


</html>