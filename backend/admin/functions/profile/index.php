<?php
require_once __DIR__ . '../../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../frontend/forms/login/login.php');
    exit();
}
$idTK = $_SESSION['id'];
//Lấy profile account
$sql_acc = "	SELECT username, name, dob, gender, avatar, email, phone
                FROM taikhoan WHERE id=$idTK";
$result_acc = mysqli_query($conn, $sql_acc);
$row_acc = mysqli_fetch_assoc($result_acc);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang admin</title>

    <?php include_once(__DIR__ . "./../../style/style_css.php"); ?>
</head>

<body>



    <div class="d-flex">
        <!-- Sidebar -->
        <?php include_once(__DIR__ . "./../../layout/sidebar.php"); ?>
        <!-- End Sidebar -->

        <div class="w-100">
            <!-- Navbar -->
            <?php include_once(__DIR__ . "./../../layout/header.php"); ?>
            <!-- End Navbar -->

            <!-- Content -->
            <div id="content">
                <section class="py-3 bg-grey">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="font-weight-bold mb-0">Profile</h1>
                                <p class="lead text-muted">Trang thông tin admin</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- main content -->
                <section class="content-main">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9">
                                <table class="table table-borderless mt-2">
                                    <tbody>
                                        <tr>
                                            <th colspan="2" style="text-align: center;">
                                                <img src="/nln_test/assets/img/upload/avatar/<?= $row_acc['avatar'] ?>" alt="Avatar" class="rounded-circle shadow rounded" width="120px" height="120px">

                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Tên đăng nhập</th>
                                            <td><?= $row_acc['username'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Họ tên: </th>
                                            <td><?= $row_acc['name'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Ngày sinh: </th>
                                            <td><?= date('d-m-Y', strtotime($row_acc['dob'])) ?></td>
                                        </tr>
                                        <tr>
                                            <th>Giới tính: </th>
                                            <td><?= $row_acc['gender'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Email: </th>
                                            <td><?= $row_acc['email'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Số điện thoại: </th>
                                            <td><?= $row_acc['phone'] ?></td>
                                        </tr>


                                    </tbody>
                                </table>
                                <div class="btn mb-3">
                                    <a href="/nln_test/backend/admin/functions/profile/functions/edit.php?idTK=<?= $idTK ?>" class="btn btn-info mb-2">Chỉnh sửa thông tin tài khoản</a>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
            </div>
            </section>
            <!-- End main content -->
        </div>
        <!-- End Content -->
    </div>
    </div>

    <?php include_once(__DIR__ . "./../../style/style_js.php"); ?>

</body>

</html>