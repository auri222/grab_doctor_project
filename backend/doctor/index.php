<?php
require_once __DIR__ . './../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../frontend/forms/login/login.php');
    exit();
}

$id = $_SESSION['id'];

$sql = "SELECT bs.id AS bsID
        FROM taikhoan tk
        JOIN bacsi bs ON bs.idTK = tk.id
        WHERE tk.id = $id";
$rs = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($rs);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Doctor</title>

    <?php include_once(__DIR__ . "./style/style_css.php"); ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" /> -->
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
                <section class="dashboard-card py-3">
                    <div class="container">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <div class="p-3 bg-white d-flex justify-content-around align-items-center rounded card-content">
                                                    <div>
                                                        <p class="text-muted">Lịch hẹn</p>
                                                        <h3 class="font-weight-bold" id="baocao_soluong_lichhen"></h3>
                                                    </div>
                                                    <i class="bi bi-calendar-check-fill mr-2 "></i>
                                                </div>

                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <div class="p-3 bg-white d-flex justify-content-around align-items-center rounded card-content">
                                                    <div>
                                                        <p class="text-muted">Lịch làm việc</p>
                                                        <h3 class="font-weight-bold" id="baocao_soluong_llv"></h3>
                                                    </div>
                                                    <i class="bi bi-calendar2-week-fill mr-2"></i>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-8">
                                        <div id="calendar" style="color: #000000;"></div>
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


    
    <script src="/nln_test/vendors/jquery/jquery-3.6.0.min.js" ></script>
    <script src="/nln_test/vendors/bootstrap/js/bootstrap.bundle.min.js" ></script>
    <script src="/nln_test/vendors/sweetalert2/sweetalert2.all.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="/nln_test/assets/backend/js/dashboard_doctor_script.js"></script>
    <script>
        $(document).ready(function() {
            var bsID = "<?= (empty($row['bsID']) ? "" : $row['bsID']); ?>";
            //fetch api lich hen
            $.ajax({
                url: "./function/api/tongsolichhen_api.php",
                method: "POST",
                data: {
                    bsID: bsID
                },
                dataType: "json",
                success: function(data) {
                    $('#baocao_soluong_lichhen').html(data.TOTAL);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#baocao_soluong_lichhen').html("Không tìm thấy thông tin!");
                }
            });

            //fetch api lich lam viec 
            $.ajax({
                url: "./function/api/tongsolichlamviec_api.php",
                method: "POST",
                data: {
                    bsID: bsID
                },
                dataType: "json",
                success: function(data) {
                    $('#baocao_soluong_llv').html(data.TOTAL);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#baocao_soluong_llv').html("Không tìm thấy thông tin!");
                }
            });

            if (bsID !== "") {
                var calendar = $('#calendar').fullCalendar({
                    aspectRatio: 2,
                    header: {
                        left: '',
                        center: 'title',
                        right: 'prev, next today'
                    },
                    events: './function/api/lich_hen_event.php?bsID=' + bsID,
                    selectable: true,
                    eventClick: function(calEvent, jsEvent, view) {
                        alert("Sự kiện bắt đầu từ " + calEvent.title);
                    }
                });
            } else {
                var calendar = $('#calendar').fullCalendar({
                    aspectRatio: 2,
                    header: {
                        left: '',
                        center: 'title',
                        right: 'prev, next today'
                    }
                });
            }

        });
    </script>
</body>

</html>