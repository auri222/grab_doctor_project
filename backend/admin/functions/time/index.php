<?php
session_start();
include_once(__DIR__ . "./../../../../config/dbconnect.php");

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../../frontend/forms/login/login.php');
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
                                <h1 class="font-weight-bold mb-0">Lịch làm việc</h1>
                                <p class="lead text-muted">Trang quản lý lịch làm việc</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Content main-->
                <section class="content-main py-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="/nln_test/backend/admin/functions/time/functions/create.php" class="btn btn-primary"> Thêm mới </a>
                                <div class="card border-0">
                                    <div class="card-body px-0 py-2">
                                        <div id="data_content" class="table-responsive">
                                            <!-- Search result show here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- End content main -->
            </div>
            <!-- End Content -->
        </div>
    </div>


    <?php include_once(__DIR__ . "./../../style/style_js.php"); ?>
    <script>
        $(document).ready(function() {
            function load_data(page) {
                $.ajax({
                    url: "./functions/fetch_data.php",
                    method: "POST",
                    data: {
                        page: page,
                    },
                    success: function(data) {
                        $('#data_content').html(data);
                    }
                });
            }

            load_data(1);

            $(document).on('click', '.page-link', function() {
                var page = $(this).data('page_number');
                load_data(page);
            });

            $('.btn-delete').on(click, function() {
                var timeID = $(this).data("time-id");
                console.log("timeID: " + timeID);

                Swal.fire({
                    title: 'Bạn chắc chắn chứ?',
                    text: "Xóa khung giờ này sẽ xóa cả khung giờ trong lịch làm việc của bác sĩ!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Vâng, chắc chắn!',
                    cancelButtonText: 'Không'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.href = "./functions/delete.php?timeID=" + timeID;
                    }
                })
            });

            $(document).ready(function() {
                $('#addNew').click(function() {
                    location.href = "./functions/create.php";
                });
            })
        })
    </script>
</body>

</html>