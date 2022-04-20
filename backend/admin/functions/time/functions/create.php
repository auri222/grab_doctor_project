<?php
session_start();
include_once(__DIR__ . "./../../../../../config/dbconnect.php");

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../../../frontend/forms/login/login.php');
    exit();
}

//Lấy data buổi
$sql_session = "SELECT * FROM buoi";
$rs_session = mysqli_query($conn, $sql_session);
$sessions = [];
while ($row_session = mysqli_fetch_array($rs_session, MYSQLI_ASSOC)) {
    $sessions[] = array(
        'id'    => $row_session['id'],
        'name'  => $row_session['name']
    );
}

$error = array();

//Đã bấm "Thêm"
if (isset($_POST['submit'])) {

    if (empty($_POST['session'])) {
        $error['session'] = "Chọn buổi";
    } else {
        $session = $_POST['session'];
    }

    if (empty($_POST['start'])) {
        $error['start_time'] = "Nhập thời gian bắt đầu";
    }
    else if((!is_numeric($_POST['start']))){
        $error['start_time'] = "Hãy nhập số";
    }
    else if($_POST['start'] > 24 || $_POST['start'] < 0){
        $error['start_time'] = "Hãy nhập giờ hợp lệ";
    }
    else {
        $start = $_POST['start'];
    }

    if (empty($_POST['end'])) {
        $error['end_time'] = "Nhập thời gian kết thúc";
    }
    else if((!is_numeric($_POST['start']))){
        $error['end_time'] = "Hãy nhập số";
    }
    else if($_POST['start'] > 24 || $_POST['start'] < 0){
        $error['end_time'] = "Hãy nhập giờ hợp lệ";
    } 
    else {
        $end = $_POST['end'];
    }

    if($_POST['start'] > $_POST['end']){
        $error['time'] = "Giờ không hợp lệ";
    }

    if (count($error) == 0) {
        $time = $start . '-' . $end . 'h';

        $check = "SELECT * FROM khung_gio WHERE id_buoi=$session AND name='$time'";
        $rs = mysqli_query($conn, $check);
        $num_count = mysqli_num_rows($rs);
        if ($num_count > 0) {
            $error['match'] = "Khung giờ bị trùng! Hãy nhập lại!";
        }
        $sql = "INSERT INTO khung_gio
                (name, id_buoi)
                VALUES ('$time', $session)";
        if (mysqli_query($conn, $sql)) {
            echo
            '<script> 
                    alert("Thêm thành công");
                    location.href = "/nln_test/backend/admin/functions/time/index.php";
                </script>';
            exit();
        } else {
            $error['database'] = "Lỗi không thêm được";
        }
    }
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
                                <h1 class="font-weight-bold mb-0">Lịch làm việc</h1>
                                <p class="lead text-muted">Form thêm lịch làm việc</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- main content -->
                <section class="content-main py-2">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <?php if (count($error) > 0) : ?>
                                            <div class="alert alert-warning" role="alert">
                                                <?php foreach ($error as $err) : ?>
                                                    <li><?= $err ?></li>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group">
                                        <label for="session">Buổi: </label>
                                        <select name="session" id="session" class="form-control" data-toggle="tooltip" data-placement="top" title="Chọn buổi">
                                            <option selected disabled value="">-- Chọn buổi --</option>
                                            <?php foreach ($sessions as $sess) : ?>
                                                <option value="<?= $sess['id'] ?>"><?= $sess['name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="start">Giờ bắt đầu</label>
                                            <input type="text" class="form-control" id="start" name="start" placeholder="7" data-toggle="tooltip" data-placement="top" title="Chỉ ghi SỐ">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="end">Giờ kết thúc</label>
                                            <input type="text" class="form-control" id="end" name="end" placeholder="8" data-toggle="tooltip" data-placement="top" title="Chỉ ghi SỐ">
                                        </div>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-success px-5">Thêm</button>
                                </form>
                            </div>
                        </div>
                    </div>
            </div>
            </section>
            <!-- End main content -->
        </div>
        <!-- End Content -->
    </div>
    
    <?php include_once(__DIR__ . "./../../../style/style_js.php"); ?>
</body>

</html>