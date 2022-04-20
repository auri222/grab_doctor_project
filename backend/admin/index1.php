<?php
require_once __DIR__ . '../../../frontend/controllers/authController.php';

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
    <title>Trang Admin</title>

    <?php include_once(__DIR__ . "./style/style_css.php"); ?>
</head>

<body>

    <?php include_once(__DIR__ . './layout/header.php'); ?>

    <div class="wrapper d-flex">
        <?php include_once(__DIR__ . './layout/sidebar.php') ?>
        <div class="content">
            <main>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                                <h1 class="h2">Dashboard</h1>
                            </div>

                            <!-- API  -->
                            <div class="row">
                                <!-- API BÁO CÁO TỔNG SỐ LƯỢNG BÁC SĨ -->
                                <div class="col-12 col-md-3">
                                    <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                                        <div class="card-header text-center">Số lượng bác sĩ</div>
                                        <div class="card-body">
                                            <h5 class="card-title text-center" id="baocao_soluong_bacsi"></h5>
                                        </div>
                                    </div>
                                </div>
                                <!-- API BÁO CÁO TỔNG SỐ LƯỢNG NGƯỜI DÙNG -->
                                <div class="col-12 col-md-3">
                                    <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
                                        <div class="card-header text-center">Số lượng người dùng</div>
                                        <div class="card-body">
                                            <h5 class="card-title text-center" id="baocao_soluong_nguoidung"></h5>
                                        </div>
                                    </div>
                                </div>
                                <!-- API BÁO CÁO TỔNG SỐ LƯỢNG LỊCH HẸN -->
                                <div class="col-12 col-md-3">
                                    <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                                        <div class="card-header text-center">Số lượng lịch hẹn</div>
                                        <div class="card-body">
                                            <h5 class="card-title text-center" id="baocao_soluong_lichhen"></h5>
                                        </div>
                                    </div>
                                </div>
                                <!-- API BÁO CÁO TỔNG SỐ LƯỢNG GÓP Ý -->
                                <div class="col-12 col-md-3">
                                    <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                                        <div class="card-header text-center">Tổng số góp ý</div>
                                        <div class="card-body">
                                            <h5 class="card-title text-center" id="baocao_soluong_gopy"></h5>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- END API -->
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <?php include_once(__DIR__ . "./style/style_js.php"); ?>

    <script>
        $(document).ready(function() {
            //fetch api góp ý
            $.ajax({
                url: "./api/tongsogopy_api.php",
                dataType: "json",
                success: function(data) {
                    $('#baocao_soluong_gopy').html(data.TOTAL);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#baocao_soluong_gopy').html("Không tìm thấy thông tin!");
                }
            });

            //fetch api lịch hẹn khám
            $.ajax({
                url: "./api/tongsolichhen_api.php",
                dataType: "json",
                success: function(data) {
                    $('#baocao_soluong_lichhen').html(data.TOTAL);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#baocao_soluong_lichhen').html("Không tìm thấy thông tin!");
                }
            });

            //fetch api số lượng bác sĩ
            $.ajax({
                url: "./api/tongsobacsi_api.php",
                dataType: "json",
                success: function(data) {
                    $('#baocao_soluong_bacsi').html(data.TOTAL);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#baocao_soluong_bacsi').html("Không tìm thấy thông tin!");
                }
            });

            //fetch api số lượng người dùng thông thường
            $.ajax({
                url: "./api/tongsonguoidung_api.php",
                dataType: "json",
                success: function(data) {
                    $('#baocao_soluong_nguoidung').html(data.TOTAL);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#baocao_soluong_nguoidung').html("Không tìm thấy thông tin!");
                }
            });
        });
    </script>
</body>

</html>