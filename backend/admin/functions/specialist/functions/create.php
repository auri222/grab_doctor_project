<?php
session_start();
include_once(__DIR__ . "./../../../../../config/dbconnect.php");

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../../../frontend/forms/login/login.php');
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

    <?php include_once(__DIR__ . "./../../../style/style_css.php"); ?>
</head>

<body>

    <div class="d-flex">
        <!-- Sidebar -->
        <?php include_once(__DIR__ . "./../../../layout/sidebar.php"); ?>
        <!-- End Sidebar -->

        <div class="w-100">
            <!-- Navbar -->
            <?php include_once(__DIR__ . "./../../../layout/header.php"); ?>
            <!-- End Navbar -->

            <!-- Content -->
            <div id="content">
                <section class="py-3 bg-grey">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="font-weight-bold mb-0">Chuyên khoa</h1>
                                <p class="lead text-muted">Trang thêm chuyên khoa</p>
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
                                        <form action="" method="POST">
                                            <div class="form-group">
                                                <label for="specialist">Nhập tên chuyên khoa</label>
                                                <input type="text" class="form-control" id="specialist" name="specialist" data-toggle="tooltip" data-placement="top" title="Nhập tên chuyên khoa">
                                               
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-success btn-add px-5">Thêm</button>
                                        </form>
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


    <?php include_once(__DIR__ . "./../../../style/style_js.php"); ?>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-add',function(event) {
                event.preventDefault();
                var spec = $('#specialist').val();
                if (spec == "") {
                    alert("Hãy nhập tên chuyên khoa!");
                } 
                else {
                    console.log("Vo day roi");
                    console.log("spec: "+spec);
                    $.ajax({
                        url: "./add.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            spec: spec
                        },
                        success: function(response) {
                            if (response.status == 0) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi',
                                    text: '' + response.output
                                });
                            } else {
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    title: ''+ response.output,
                                    text: 'Quay lại trang quản lý chuyên khoa' ,
                                    showConfirmButton: true,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'OK',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.href = "./../index.php";
                                    }
                                });
                            }
                        }
                    });
                }
            });
        })
    </script>
</body>

</html>