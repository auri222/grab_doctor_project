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
                                <h1 class="font-weight-bold mb-0">Lịch hẹn</h1>
                                <p class="lead text-muted">Trang quản lý lịch hẹn khám bệnh</p>
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
                                        <div class="row justify-content-start">
                                            <div class="col-md-4">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-search"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="search" placeholder="Tìm kiếm tên ở đây..." aria-label="search" aria-describedby="basic-addon1">
                                                </div>
                                            </div>
                                            <div class="col-md-6 ">
                                                <div class="row ">
                                                    <div class="col-md-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1">Từ ngày</span>
                                                            </div>
                                                            <input type="date" class="form-control" id="fromdate" placeholder="Tìm kiếm tên ở đây..." aria-label="search" aria-describedby="basic-addon1"
                                                            data-toggle="tooltip" data-placement="top" title="Chọn TỪ NGÀY trước chọn ĐẾN NGÀY sau">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="basic-addon1">Đến ngày</span>
                                                            </div>
                                                            <input type="date" class="form-control" id="todate" placeholder="Tìm kiếm tên ở đây..." aria-label="search" aria-describedby="basic-addon1" data-toggle="tooltip" data-placement="top" title="Chọn TỪ NGÀY trước chọn ĐẾN NGÀY sau">
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                            <button class="btn btn-warning btn-filter font-weight-bold" data-toggle="tooltip" data-placement="top" title="Xóa bộ lọc ngày">Xóa bộ lọc</button>
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
            function load_data(page, query = '', fromdate, todate) {
                $.ajax({
                    url: "./functions/fetch_data.php",
                    method: "POST",
                    data: {
                        page: page,
                        query: query,
                        fromdate: fromdate,
                        todate: todate
                    },
                    success: function(data) {
                        $('#data_content').html(data);
                    }
                });
            }
            
            function checkdate(fromdate, todate){
                var from = new Date(fromdate);
                var to = new Date(todate);
                if(from > to){
                    return false;
                }
                return true;
            }

            load_data(1);

            $('#search').keyup(function() {
                var search = $(this).val();
                load_data(1, search);
            });

            $('#todate').on("change", function(){
                var todate = $(this).val();
                var fromdate = $('#fromdate').val(); 
                if(fromdate == ''){
                    Swal.fire({
                                        icon: 'warning',
                                        text: 'Chưa chọn từ ngày!'
                                    });
                }
                else if(!checkdate(fromdate, todate)){
                    Swal.fire({
                                        icon: 'warning',
                                        text: 'Chọn ngày không hợp lệ'
                                    });
                }
                else{
                    load_data(1,'',fromdate,todate);
                }
            });

            $('#fromdate').on("change", function(){
                var fromdate = $(this).val();
                var todate = $('#todate').val(); 
                if(todate == ''){
                    Swal.fire({
                                        icon: 'warning',
                                        text: 'Chưa chọn đến ngày!'
                                    });
                }
                else if(!checkdate(fromdate, todate)){
                    Swal.fire({
                                        icon: 'warning',
                                        text: 'Chọn ngày không hợp lệ'
                                    });
                }
                else{
                    load_data(1,'',fromdate,todate);
                }
            });

            $(document).on('click', '.page-link', function() {
                var page = $(this).data('page_number');
                var search = $('#search').val();
                var fromdate = $('#fromdate').val(); //Y-m-d
                var todate = $('todate').val();

                if(checkdate(fromdate, todate)){
                    load_data(page, search, fromdate, todate);
                }
                else{
                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi',
                                        text: 'Ngày không hợp lệ'
                                    });
                }
                
            });

            $(document).on('click', '.btn-filter', function(){
                $('#fromdate').val('');
                $('#todate').val('');
                load_data(1);
            });

            $(document).on('click', '.btn-view', function() {
                var lhID = $(this).data("lh-id");
                console.log("lhid: " + lhID);
                $.ajax({
                    url: "./functions/fetch_appointment.php",
                    method: "POST",
                    data: {
                        lhID: lhID
                    },
                    success: function(data) {
                        $('#view').html(data);
                        $('#viewModal').modal('show');
                    }
                });
            });
        });
    </script>
</body>


<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Lịch hẹn chi tiết</h5>
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

</html>