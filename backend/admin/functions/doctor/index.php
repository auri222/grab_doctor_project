<?php
require_once __DIR__ . './../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../frontend/forms/login/login.php');
    exit();
}


//Lấy tổng số dòng để phân trang
$sql_row_acc_count = "SELECT COUNT(*) as totalRecord FROM taikhoan WHERE idPQ=2";
$rs_row_acc_count = mysqli_query($conn, $sql_row_acc_count);
$row_acc_count = mysqli_fetch_assoc($rs_row_acc_count);

//Tạo biến giữ tổng số dòng
$TOTAL_COUNT_RECORD = $row_acc_count['totalRecord'];

//Số dòng muốn hiển thị
$RECORD_PER_PAGE = 3;

//Tổng số trang hiển thị
$TOTAL_PAGES = ceil($TOTAL_COUNT_RECORD / $RECORD_PER_PAGE);

//Lấy trang hiện tại
$PAGE = isset($_GET['page']) ? $_GET['page'] : 1;

//Tính offset
$OFFSET = ($PAGE - 1) * $RECORD_PER_PAGE;

$sql = "SELECT bs.id AS IDBS, tk.name AS nameBS, tk.email, tk.phone ,bs.address, ck.name AS SPEC, bs.doctor_img as IMG
        FROM bacsi bs
        JOIN taikhoan tk ON tk.id = bs.idTK
        JOIN chuyenkhoa ck ON ck.id = bs.idCK
        LIMIT $OFFSET, $RECORD_PER_PAGE";
$rs = mysqli_query($conn, $sql);
$ds_docs = array();
while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
    $ds_docs[] = array(
        "id"         => $row["IDBS"],
        "nameBS"     => $row["nameBS"],
        "email"      => $row["email"],
        "phone"      => $row["phone"],
        "address"    => $row["address"],
        "SPEC"       => $row["SPEC"],
        "IMG"        => $row["IMG"]
    );
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
                                <h1 class="font-weight-bold mb-0">Bác sĩ</h1>
                                <p class="lead text-muted">Trang quản lý thông tin bác sĩ</p>
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
                                                    <input type="text" class="form-control" id="search" placeholder="Tìm tên ở đây..." aria-label="search" aria-describedby="basic-addon1">
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
                var bsID = $(this).data('doc_id');
                console.log('idBS: ' + bsID);
                $.ajax({
                    url: "./functions/fetch_doc_info.php",
                    method: "POST",
                    data: {
                        bsID: bsID
                    },
                    success: function(data) {
                        $('#view').html(data);
                        $('#viewModal').modal('show');
                    }
                });
            });


            $(document).on('click', '.btn-del', function() {
                var bsID = $(this).data('doc_id');
                Swal.fire({
                    title: 'Bạn chắc chứ?',
                    text: "Việc này sẽ xóa toàn bộ thông tin liên quan đến bác sĩ này (gồm các lịch hẹn, lịch làm việc, thông tin tài khoản)!",
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
                            url: "./functions/delete_doc.php",
                            method: "POST",
                            dataType: "JSON",
                            data: {
                                idBS: bsID
                            },
                            success: function(response) {
                                if (response.status == 0) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Lỗi rồi!',
                                        text: ' ' + response.output
                                    });
                                } else {
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Đã xóa',
                                        text: ''+response.output,
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
                <h5 class="modal-title" id="viewModalLabel">Thông tin bác sĩ chi tiết</h5>
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