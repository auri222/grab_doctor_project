<?php
session_start();
include_once(__DIR__ . "./../../../../../config/dbconnect.php");

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../../../frontend/forms/login/login.php');
    exit();
}

$specID = $_GET['specID'];

$query = "SELECT name FROM chuyenkhoa WHERE id=$specID";
$rs = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($rs);

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
                                <p class="lead text-muted">Trang sửa chuyên khoa</p>
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
                                                <input type="text" class="form-control" id="specialist" name="specialist" value="<?= $row['name']?>" data-toggle="tooltip" data-placement="top" title="Nhập tên chuyên khoa">
                                                <input type="hidden" name="specID" id="specID" value="<?= $specID?>">
                                                
                                            </div>
                                            <button type="submit" name="submit" class="btn btn-success btn-edit px-5">Sửa</button>
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
            $('.btn-edit').on('click', function(event) {
                event.preventDefault()
                var specID = $('#specID').val();
                var spec = $('#specialist').val();
                if (spec == "" || spec == " ") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Hãy nhập tên chuyên khoa!'
                    });
                } else {
                    console.log(specID+" - "+spec);
                    $.ajax({
                        url: "./update.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            specID:specID,
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