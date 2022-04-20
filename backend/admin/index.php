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

    <div class="d-flex">
        <!-- Sidebar -->
        <?php include_once(__DIR__ . "./layout/sidebar.php"); ?>
        <!-- End Sidebar -->

        <div class="w-100">
            <!-- Navbar -->
            <?php include_once(__DIR__ . "./layout/header.php"); ?>
            <!-- End Navbar -->

            <!-- Content -->
            <div id="content">
                <section class="py-3 bg-grey">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="font-weight-bold mb-0">Dashboard</h1>
                                <p class="lead text-muted">Trang tổng hợp các số liệu</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Card -->
                <section class="dashboard-card">
                    <div class="container">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6">
                                        <div class="p-3 bg-white d-flex justify-content-around align-items-center rounded card-content">
                                            <div>
                                                <p class="text-muted">Số tài khoản</p>
                                                <h3 class="font-weight-bold" id="baocao_soluong_nguoidung"></h3>
                                            </div>
                                            <i class="icon ion-md-contacts mr-2 "></i>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="p-3 bg-white d-flex justify-content-around align-items-center rounded card-content">
                                            <div>
                                                <p class="text-muted">Số bác sĩ</p>
                                                <h3 class="font-weight-bold" id="baocao_soluong_bacsi"></h3>
                                            </div>
                                            <i class="icon ion-md-medkit mr-2"></i>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="p-3 bg-white d-flex justify-content-around align-items-center rounded card-content">
                                            <div>
                                                <p class="text-muted">Số lịch hẹn</p>
                                                <h3 class="font-weight-bold" id="baocao_soluong_lichhen"></h3>
                                            </div>
                                            <i class="icon ion-md-calendar mr-2"></i>
                                        </div>

                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <div class="p-3 bg-white d-flex justify-content-around align-items-center rounded card-content">
                                            <div>
                                                <p class="text-muted">Số góp ý</p>
                                                <h3 class="font-weight-bold" id="baocao_soluong_gopy"></h3>
                                            </div>
                                            <i class="icon ion-md-paper mr-2"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End Card -->
            </div>
            <!-- End Content -->
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