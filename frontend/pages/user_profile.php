<?php
session_start();
//Kết nối database
include_once(__DIR__ . '/../../config/dbconnect.php');

$notLogin = '';
$Login = '';
$user = array();
//Nếu người dùng chưa đăng nhập
if (!isset($_SESSION['id']) && !isset($_SESSION['authority'])) {
    $notLogin = '';
    $Login = 'style="display: none;"';
    echo '<script> location.href = "/nln_test/index.php"; </script>';
    exit();
}
//Có đăng nhập và là người dùng
else if (isset($_SESSION['id'])) {
    if ($_SESSION['authority'] == 3) {

        $notLogin = 'style="display: none;"';

        $Login = '';

        $userID =  $_SESSION['id'];

        $sql = "SELECT tk.id, tk.username, tk.name, tk.dob, tk.gender, tk.avatar, tk.email, tk.phone
        FROM taikhoan tk WHERE tk.id = $userID";

        $rs = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            $user[] = array(
                "id"            => $row['id'],
                "username"      => $row['username'],
                "name"          => $row['name'],
                "dob"           => $row['dob'],
                "gender"        => $row['gender'],
                "avatar"        => $row['avatar'],
                "email"         => $row['email'],
                "phone"         => $row['phone']

            );
        }
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

    <?php include_once(__DIR__ . './style/style_css.php'); ?>

    <link rel="stylesheet" href="/nln_test/assets/frontend/css/style_user_profile.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

</head>

<body>
    <div class="page-holder">
        <!-- navbar-->
        <?php include_once(__DIR__ . './layout/header.php'); ?>
        <!-- navbar end -->

        <!-- banner -->
        <section class="parallax-user-profile">
            <div class="container py-5 text-white text-center">
                <div class="row py-5">
                    <div class="col-lg-9 mx-auto">
                        <h1><span style="color: turquoise;">Thông tin của bạn </span></h1>
                        <p class="text-center mt-2">
                            Tập luyện thể dục thể thao thường xuyên cũng là một cách để ngăn ngừa bệnh tật, giúp cơ thể khỏe mạnh.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <!-- banner end -->

        <section class="doctor-profile">
            <div class="container py-3">
                <div class="row  ">
                    <div class="col-lg-12">
                        <?php foreach ($user as $u) : ?>
                            <div class="row my-4">

                                <div class="col-md-3">
                                    <?php
                                    if (!empty($u['avatar'])) {
                                        echo '<img src="/nln_test/assets/img/upload/avatar/' . $u['avatar'] . '" class="mx-auto d-block rounded-circle shadow rounded" width="120px" height="120px">';
                                    } else {
                                        echo '<p class="text-center">Bạn chưa thêm avatar</p>';
                                    }
                                    ?>
                                    <button type="button" class="btn btn-info btn-block mt-3" data-toggle="modal" data-target="#avatarModal">Sửa avatar</button>
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h5>Thông tin cá nhân</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="#" type="button" class="btn-edit-info " data-toggle="modal" data-target="#infoModal"><i class="bi bi-pencil-square"></i></a>
                                        </div>
                                    </div>

                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Họ tên</th>
                                                <td> <?= $u['name'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Ngày sinh</th>
                                                <td> <?= date('d-m-Y', strtotime($u['dob'])) ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Giới tính</th>
                                                <td> <?= $u['gender'] ?> </td>
                                            </tr>
                                            <tr>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-10">
                                            <h5>Thông tin tài khoản</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="#" type="button" class="btn-edit-acc " data-toggle="modal" data-target="#accInfoModal"><i class="bi bi-pencil-square"></i></a>
                                        </div>
                                    </div>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th scope="row">Tên đăng nhập:</th>
                                                <td> <?= $u['username'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Số điện thoại</th>
                                                <td> <?= $u['phone'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Email</th>
                                                <td> <?= $u['email'] ?> </td>
                                            </tr>
                                            <tr>
                                        </tbody>
                                    </table>
                                    <hr>

                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        </section>




        <!-- Footer start -->
        <?php include_once(__DIR__ . './style/style_js.php'); ?>
        <?php include_once(__DIR__ . './layout/footer.php'); ?>
        <!-- Footer end-->
    </div>

</body>

</html>

<!-- Modal sửa thông tin tài khoản -->
<div class="modal fade" id="accInfoModal" tabindex="-1" aria-labelledby="accInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="accInfoModalLabel">Sửa thông tin tài khoản</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php foreach ($user as $u) : ?>
                <form id="frmAcc">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="username" class="col-form-label">Tên đăng nhập:</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?= $u['username'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="phone" class="col-form-label">Số điện thoại:</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= $u['phone'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="email" class="col-form-label">Email:</label>
                            <input type="text" class="form-control" id="email" name="email" value="<?= $u['email'] ?>">
                            <small>Bạn cần phải nhập email chính xác và hợp lệ!</small>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary btn-save-acc" data-user-id-acc="<?= $u['id'] ?>">Lưu thay đổi</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal sửa thông tin cá nhận -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Sửa thông tin cá nhân</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php foreach ($user as $u) : ?>
                <form id="frmInfo">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="col-form-label">Họ tên:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= $u['name'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="dob" class="col-form-label">Ngày sinh:</label> &nbsp;
                            <input type="date" class="form-control" id="dob" name="dob" value="<?= $u['dob'] ?>">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label">Giới tính:</label>
                            <?php
                            $male = "";
                            $female = "";
                            if ($u['gender'] == "Nam") {
                                $male = "checked";
                            } else {
                                $female = "checked";
                            }
                            ?>
                            <input type="radio" name="gender" id="male" value="Nam" <?= $male ?>> Nam
                            <input type="radio" name="gender" id="female" <?= $female ?> value="Nữ"> Nữ
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary btn-save-info" data-user-id-info="<?= $u['id'] ?>">Lưu thay đổi</button>
                    </div>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal sửa avatar -->
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">Sửa avatar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <?php foreach ($user as $u) : ?>

                    <?php
                    if (!empty($u['avatar'])) {
                        echo '<img src="/nln_test/assets/img/upload/avatar/' . $u['avatar'] . '" class="img-fluid">';
                    } else {
                        echo '<p class="text-center">Bạn chưa thêm avatar</p>';
                    }
                    ?>
                    <form id="frmAvatar" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="avatar" class="col-form-label">Sửa avatar:</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" />
                            <input type="hidden" class="form-control" name="accID" value="<?= $u['id']?>" />
                        </div>
                        <div class="form-group">
                            <input type="submit" id="submit" name="submit" value="Lưu" class="btn btn-primary btn-block">
                        </div>
                    </form>
                <?php endforeach; ?>
            </div>
            <div class=" modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.btn-save-info').on('click', function() {
            var userID = $(this).attr('data-user-id-info');
            console.log("UID info: " + userID);

            var name = $('#name').val();
            var dob = $('#dob').val();
            var gender = $('input[name=gender]:checked', '#frmInfo').val();
            console.log("name: " + name + ", dob: " + dob + ", gender: " + gender);

            $.ajax({
                url: "./functions/edit_user_info.php",
                method: "POST",
                dataType: "json",
                data: {
                    id: userID,
                    name: name,
                    dob: dob,
                    gender: gender
                },
                success: function(response) {
                    if (response.status == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Có vẻ không sửa được :( !'
                        });
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Sửa thành công',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        $(function() {
                            function reload_info_modal() {
                                $('#infoModal').modal('hide');
                                location.reload();
                            }
                            window.setTimeout(reload_info_modal, 1500);
                        });
                    }
                }
            });
        });

        $('.btn-save-acc').on('click', function() {
            var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            var userID = $(this).attr('data-user-id-acc');
            console.log("UID acc: " + userID);
            var username = $('#username').val();
            var phone = $('#phone').val();
            var email = $('#email').val();
            var error = true;

            //Check username có bị để trống
            if (username === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Vui lòng nhập tên đăng nhập!'
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

            if (phone === "") {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Vui lòng nhập số điện thoại!'
                });
                error = false;
            } else if (phone.length >= 11 || phone.length < 10) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Số điện thoại không hợp lệ!'
                });
                error = false;
            }


            console.log("username: " + username + ", phone: " + phone + ", email: " + email);
            if (error) {
                $.ajax({
                    url: "./functions/edit_user_acc_info.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        id: userID,
                        username: username,
                        phone: phone,
                        email: email
                    },
                    success: function(response) {
                        if (response.status == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: 'Có vẻ không sửa được :( !'
                            });
                        } else if(response.flag == 0){
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: '' + response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $(function() {
                                function reload_acc_modal() {
                                    $('#accInfoModal').modal('hide');
                                    location.reload();
                                }
                                window.setTimeout(reload_acc_modal, 1500);
                            });
                        }else{
                            console.log(response.message+"- token: "+response.token+"- type: "+response.type);
                            Swal.fire({
                                title: ''+response.message,
                                text: "Chúng tôi đã gửi lại mã otp cho tài khoản của bạn. Vui lòng nhấn OK để đi đến trang xác nhận!",
                                icon: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'OK',
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            location.href = "/nln_test/frontend/forms/login/otp.php?token="+response.token+"&type="+response.type;
                                                        }
                                                    })
                        }
                    }
                });
            }

        });

        $('#frmAvatar').on('submit', function(event) {
            event.preventDefault();
            var avatar_name = $('#avatar').val();
            if (avatar_name == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Hãy chọn hình!'
                });
                return false;
            } else {
                var extension = $('#avatar').val().split('.').pop().toLowerCase();
                if (jQuery.inArray(extension, ['jpg','png','jpeg']) == -1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'File ảnh không hợp lệ!'
                    });
                    $('#avatar').val('');
                    return false;
                } else {
                    $.ajax({
                        url: "./functions/edit_avatar.php",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        dataType: "json",
                        success: function(output) {
                            if (output.status == 0) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: 'Không thêm được ảnh!'
                                });
                            }
                            else{
                                Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: ''+output.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            $(function() {
                                function reload_avatar_modal() {
                                    $('#frmAvatar')[0].reset();
                                    $('#avatarModal').modal('hide');
                                    location.reload();
                                }
                                window.setTimeout(reload_avatar_modal, 1500);
                            });
                            }
                        }
                    });
                }
            }
        })
    });
</script>