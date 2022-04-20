<?php
require_once __DIR__ . '../../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../frontend/forms/login/login.php');
    exit();
}

$idTK = $_SESSION['id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Doctor</title>

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
                                <p class="lead text-muted">Trang quản lý lịch làm việc </p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Content main-->
                <section class="content-main py-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card border-0">

                                    <div class="card-body px-0 py-2">
                                        <button type="button" class="btn btn-primary mb-2" id="addNew">
                                            Thêm mới
                                        </button>
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
            var idtk = "<?= $idTK ?>";
            console.log("ID: " + idtk);

            function load_data(id, page) {
                $.ajax({
                    url: "./function/fetch_data.php",
                    method: "POST",
                    data: {
                        id: id,
                        page: page
                    },
                    success: function(data) {
                        $('#data_content').html(data);
                    }
                });
            }

            load_data(idtk, 1);

            $(document).on('click', '.page-link', function() {
                var page = $(this).data('page_number');

                load_data(idtk, page);
            });

            $('#addNew').click(function() {
                location.href = "./function/create.php";
            });

            $(document).on('click', '.btn-not-checked', function() {
                var llvID = $(this).data("llv-id");
                Swal.fire({
                    title: 'Bạn chắc chứ?',
                    text: "Đặt trạng thái BẬN cho lịch làm việc này này?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đặt',
                    cancelButtonText: 'Đóng'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "./function/set_status_busy.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                llvID: llvID
                            },
                            success: function(response) {
                                if (response.status == 0) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: 'Hiện không đặt được!'
                                    });
                                } else {
                                    $(function() {
                                        function reload_page() {
                                            location.reload();
                                        }
                                        window.setTimeout(reload_page, 1000);
                                    });
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Đã đặt trạng thái thành công',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            }
                        });
                    }
                })
            });
            $(document).on('click', '.btn-checked', function() {
                var llvID = $(this).data("llv-id");
                Swal.fire({
                    title: 'Bạn chắc chứ?',
                    text: "Đặt trạng thái CÓ SẴN cho lịch làm việc này này?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đặt',
                    cancelButtonText: 'Đóng'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "./function/set_status_not_busy.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                llvID: llvID
                            },
                            success: function(response) {
                                if (response.status == 0) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: 'Hiện không đặt được!'
                                    });
                                } else {
                                    $(function() {
                                        function reload_page() {
                                            location.reload();
                                        }
                                        window.setTimeout(reload_page, 1000);
                                    });
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Đã đặt trạng thái thành công',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            }
                        });
                    }
                })
            });

            $(document).on('click', '.btn-del', function() {
                var llvID = $(this).data("llv-id");
                console.log("llvid: "+llvID);
                Swal.fire({
                    title: 'Bạn chắc chứ?',
                    text: "Xóa lịch làm việc này?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Đóng'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "./function/delete.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                llvID: llvID
                            },
                            success: function(response) {
                                if (response.status == 0) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: 'Xóa không được rồi!'
                                    });
                                } else {
                                    $(function() {
                                        function reload_page() {
                                            location.reload();
                                        }
                                        window.setTimeout(reload_page, 1000);
                                    });
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Đã xóa',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            }
                        });
                    }
                })
            });
        });
    </script>
</body>

</html>