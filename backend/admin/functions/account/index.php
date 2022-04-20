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
                                <h1 class="font-weight-bold mb-0">Tài khoản</h1>
                                <p class="lead text-muted">Trang quản thông tin tài khoản</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Content main-->
                <section class="content-main py-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card border-0 ">
                                    <div class="card-body px-0 py-2">
                                        <div class="row justify-content-end">
                                            <div class="col-md-4">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="search" placeholder="Tìm kiếm username ở đây..." aria-label="search" aria-describedby="basic-addon1">
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

            $(document).on('click', '.btn-view', function() {
                var tkID = $(this).data("tk-id");
                console.log("tkid: " + tkID);
                $.ajax({
                    url: "./functions/fetch_account.php",
                    method: "POST",
                    data: {
                        tkID: tkID
                    },
                    success: function(data) {
                        $('#view').html(data);
                        $('#viewModal').modal('show');
                    }
                });
            });

            $(document).on('click', '.btn-del', function() {
                var tkID = $(this).data("tk-id");
                Swal.fire({
                    title: 'Bạn chắc chứ?',
                    text: "Hành động này sẽ xóa các mục liên quan đến lịch hẹn và các thông tin tương đương!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#3085d6',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Xóa',
                    cancelButtonText: 'Đóng'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            position: 'center',
                            icon: 'warning',
                            title: 'Đang xóa! Vui lòng đợi trong vài giây!',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'OK',
                            timer: 2000
                        });
                        $.ajax({
                            url: "./functions/delete.php",
                            method: "POST",
                            dataType: "json",
                            data: {
                                tkID: tkID
                            },
                            success: function(response) {
                                if (response.status == 0) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: '' + response.message
                                    });
                                } else {
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Đã xóa',
                                        text: ''+response.message,
                                        showConfirmButton: true,
                                        confirmButtonColor: '#3085d6',
                                        confirmButtonText: 'Đóng',
                                    }).then((result)=> {
                                        if(result.isConfirmed){
                                            location.reload();
                                        }
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


<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Thông tin tài khoản chi tiết</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="view">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>