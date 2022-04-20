<?php
require_once __DIR__ . './../../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../../frontend/forms/login/login.php');
    exit();
}

if (isset($_GET['idTK'])) {
    $idTK = $_GET['idTK'];

    //Lấy data profile
    $sql_acc = "SELECT * FROM taikhoan WHERE id=$idTK";
    $result_acc = mysqli_query($conn, $sql_acc);
    $row_acc = mysqli_fetch_assoc($result_acc);
}

//Nếu có cập nhật
if (isset($_POST['update'])) {
    //-------------------------------------------------------------------
    //Tài khoản 
    //-------------------------------------------------------------------
    $phone = $_POST['phone'];
    //Kiểm tra dữ liệu nhập
    //Họ tên
    if (empty($_POST['name'])) {
        $error['name'] = "Hãy nhập họ tên";
    } else {
        $name = trim($_POST['name']);
    }

    //Ngày sinh
    if (empty($_POST['dob'])) {
        $error['dob'] = "Hãy chọn ngày sinh";
    } else {
        $dob = trim($_POST['dob']);
    }

    if (empty($_POST['username'])) {
        $error['username'] = "Hãy nhập tên đăng nhập";
    } else {
        $username = trim($_POST['username']);
    }

    if (empty($phone)) {
        $error['phone'] = "Hãy nhập vào số điện thoại!";
    } else if (strlen($phone) < 10 || strlen($phone) > 11) {
        $error['phone'] = "Hãy nhập đúng số điện thoại!";
    }

    //-------------------------------------------------------------------
    $flag = "";
    //Xử lý hình avatar
    if (!isset($_FILES['avatar']) || ($_FILES['avatar']['error'] == UPLOAD_ERR_NO_FILE)) {
        $flag = 0;
    } else {
        $flag = 1;
        $file_name = $_FILES['avatar']['name'];
        $file_size = $_FILES['avatar']['size'];
        $file_tmp = $_FILES['avatar']['tmp_name'];
        $file_type = $_FILES['avatar']['type'];
        $file_error = $_FILES['avatar']['error'];
        $file_ext = explode('.', $_FILES['avatar']['name']);
        $fileExtension = strtolower(end($file_ext));
        $extensions = array("jpeg", "png", "jpg");

        //Check extension 
        if (in_array($fileExtension, $extensions) == false) {
            $error['type'] = "Bạn không được tải file loại này lên ngoài trừ file có đuôi .jpg, .png, .jpeg";
        }
        if ($file_error !== 0) {
            $error['error'] = "Có lỗi khi tải hình ảnh của bạn. Vui lòng thử lại!!";
        }
        if ($file_size > 2097152) {
            $error['size'] = "File tải lên có kích thước phải nhỏ hơn 2MB!!";
        }
    }
    //-------------------------------------------------------------------

    // $move = move_uploaded_file($file_tmp, $path);
    // var_dump("File name: ".$file_name."Path: ".$path."Move dc ko: ".$move); die;
    //Nếu không có lỗi
    if (count($error) === 0) {
        if ($flag == 0) {
            $q = "UPDATE taikhoan
                    SET
                        username='$username',
                        name='$name',
                        dob='$dob',
                        phone='$phone'
                    WHERE id=$idTK";
        } else {

            $path = './../../../../../assets/img/upload/avatar/' . $file_name;
            $q = "UPDATE taikhoan
                            SET
                                username='$username',
                                name='$name',
                                dob='$dob',
                                avatar='$file_name',
                                phone='$phone'
                            WHERE id=$idTK";
        }

        if (mysqli_query($conn, $q)) {
            move_uploaded_file($file_tmp, $path);
            echo '<script> location.href = "/nln_test/backend/admin/functions/profile/index.php"; </script>';
        } else {
            $error['db_error'] = "Có gì đó sai sai với database rồi!";
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
    <title>Trang Doctor</title>

    <?php include_once(__DIR__ . "./../../../style/style_css.php"); ?>

</head>

<body>


    <div class="d-flex">
        <!-- Sidebar -->
        <?php include_once(__DIR__ . "./../../../layout/sidebar.php"); ?>
        <!-- End Sidebar -->

        <div class="w-100">
            <!-- Navbar -->
            <?php include_once(__DIR__ . "./../../../layout/header.php"); ?>
            <!-- End Navbar -->

            <!-- Content -->
            <div id="content">
                <section class="py-3 bg-grey">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="font-weight-bold mb-0">Profile</h1>
                                <p class="lead text-muted">Form chỉnh sửa thông tin admin</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- main content -->
                <section class="content-main py-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if (count($error) > 0) : ?>
                                    <div class="alert alert-info" role="alert">
                                        <?php foreach ($error as $err) : ?>
                                            <li><?= $err ?></li>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="row mx-2">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Họ tên <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control " id="name" name="name" value="<?= $row_acc['name'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="dob">Ngày sinh <span style="color: red;">*</span></label>
                                                <input type="date" class="form-control " id="dob" name="dob" value="<?= $row_acc['dob'] ?>">
                                                <small><span style="color: red;">*</span> Nếu chỉ có năm hãy chọn ngày 01 tháng 01</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="avatar">Hình đại diện <span style="color: red;">*</span></label><br />
                                                <input type="file" id="avatar" name="avatar" value="<?= $row_acc['avatar'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="username">Tên đăng nhập <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control " id="username" name="username" value="<?= $row_acc['username'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="phone">Số điện thoại <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" id="phone" name="phone" value="<?= $row_acc['phone'] ?>">
                                            </div>
                                        </div>

                                    </div>
                                    <button type="submit" name="update" class="btn btn-primary btn-block my-3 btn-submit">Cập nhật lại</button>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
            </section>
            <!-- End main content -->
        </div>
        <!-- End Content -->
    </div>
    </div>

    <?php include_once(__DIR__ . "./../../../style/style_js.php"); ?>
    <script>
        $(document).ready(function() {
            //Lấy dữ liệu quận huyện theo tỉnh thành
            $("#province").change(function() {
                var provinceID = $(this).val();
                console.log("ID province: " + provinceID);
                $.ajax({
                    url: "/nln_test/backend/doctor/function/profile/function/fetch_district.php",
                    method: "POST",
                    data: {
                        provinceID: provinceID
                    },
                    success: function(data) {
                        $("#district").html(data);
                    }
                });
            });
            //Lấy dữ liệu phường xã theo quận huyện
            $("#district").change(function() {
                var districtID = $(this).val();
                console.log("ID district: " + districtID);
                $.ajax({
                    url: "/nln_test/backend/doctor/function/profile/function/fetch_ward.php",
                    method: "POST",
                    data: {
                        districtID: districtID
                    },
                    success: function(data) {
                        $("#ward").html(data);
                    }
                });
            });
        });
    </script>
</body>

</html>