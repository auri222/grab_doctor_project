<?php
require_once __DIR__ . './../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../frontend/forms/login/login.php');
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
                                <h1 class="font-weight-bold mb-0">Góp ý</h1>
                                <p class="lead text-muted">Trang quản lý thông tin góp ý</p>
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
                                        <div class="row justify-content-end">
                                            <div class="col-md-4">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="search" placeholder="Tìm tên ở đây..." aria-label="search" aria-describedby="basic-addon1" data-toggle="tooltip" data-placement="bottom" title="Không tìm hãy XÓA TEXT đi!">
                                                </div>
                                            </div>
                                        </div>

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
            function load_data(page, query = '') {
                $.ajax({
                    url: "./functions/fetch_data.php",
                    method: "POST",
                    data: {
                        page: page,
                        query: query
                    },
                    success: function(data) {
                        $('#data_content').html(data);
                    }
                });
            }

            load_data(1);

            $('#search').keyup(function() {
                var search = $(this).val();
                load_data(1, search);
            });

            $(document).on('click', '.page-link', function() {
                var page = $(this).data('page_number');
                var search = $('#search').val();
                load_data(page, search);
            });

            $(document).on('click', '.btn-check',function() {
                var contactID = $(this).data("contact-id");
                Swal.fire({
                    title: 'Bạn chắc chứ?',
                    text: "Duyệt cho góp ý này!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ừ, duyệt!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "./functions/confirm.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                contactID: contactID
                            },
                            success: function(response) {
                                if (response.status == 0) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: 'Không duyệt được rồi! ' + response.message
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
                                        title: 'Đã duyệt',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            }
                        });
                    }
                })
            })


            $(document).on('click', '.btn-del',function() {
                var contactID = $(this).data("contact-id");
                Swal.fire({
                    title: 'Bạn chắc chứ?',
                    text: "Xóa đấy nhá!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ừ, xóa đi!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "./functions/delete.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                contactID: contactID
                            },
                            success: function(response) {
                                if (response.status == 0) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: 'Xóa không được rồi! ' + response.message
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