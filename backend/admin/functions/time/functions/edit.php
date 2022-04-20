<?php
require_once __DIR__ . './../../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../../frontend/forms/login/login.php');
    exit();
}

$id = $_GET['time_id'];

$error = array();

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

//Lấy dữ liệu khung giờ
$sql = "SELECT * FROM khung_gio WHERE id=$id";
$rs = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($rs);

$time_tmp = substr($row['name'], 0, -1);

$time = explode("-", $time_tmp);

$start = $time[0];
$end = $time[1];

//Đã bấm "Thêm"
if (isset($_POST['submit'])) {

    if (empty($_POST['session'])) {
        $error['session'] = "Chọn buổi";
    } else {
        $session = $_POST['session'];
    }

    if (empty($_POST['start'])) {
        $error['start_time'] = "Nhập thời gian bắt đầu";
    } else {
        $start = $_POST['start'];
    }

    if (empty($_POST['end'])) {
        $error['end_time'] = "Nhập thời gian kết thúc";
    } else {
        $end = $_POST['end'];
    }

    //Check khung giờ nhập đúng chưa?
    //Check trùng
    $time = $start . '-' . $end . 'h';

    $check = "SELECT * FROM khung_gio WHERE id_buoi=$session AND name='$time'";
    $rs = mysqli_query($conn, $check);
    $num_count = mysqli_num_rows($rs);
    if ($num_count > 0) {
        $error['match'] = "Khung giờ bị trùng! Hãy nhập lại!";
    }

    //check hợp lệ
    function checkcolon($time)
    {
        $colon = ":";

        $find_colon = strpos($time, $colon);

        if (($find_colon == false)) {
            return true;
        } else
            return false;
    }


    function checkTimeSign($time)
    {
        $timeSign = "h";

        $find_timeSign = strpos($time, $timeSign);

        if (($find_timeSign == false)) {
            return true;
        } else
            return false;
    }

    if (checkcolon($start) == false) {
        $error["invalid_time"] =  "Giờ không hợp lệ (không thể ghi là 7:30)";
    }
    if (checkcolon($end) == false) {
        $error["invalid_time"] =  "Giờ không hợp lệ (không thể ghi là 7:30)";
    }
    if (checkTimeSign($start) == false) {
        $error["invalid_time"] =  "Giờ không hợp lệ (không thể ghi là 7h30)";
    }
    if (checkTimeSign($end) == false) {
        $error["invalid_time"] =  "Giờ không hợp lệ (không thể ghi là 7h30)";
    }

    if (count($error) == 0) {
        $sql = "UPDATE khung_gio
                SET name='$time', id_buoi=$session WHERE id=$id ";
        if (mysqli_query($conn, $sql)) {
            echo
            '<script> 
                    alert("Sửa thành công");
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
                                <p class="lead text-muted">Form sửa lịch làm việc</p>
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
                                    <select name="session" id="session" class="form-control">
                                        <option selected disabled value="">-- Chọn buổi --</option>
                                        <?php foreach ($sessions as $sess) : ?>
                                            <?php
                                            $selected = "";
                                            $current = $sess['id'];
                                            if ($current == $row['id_buoi']) {
                                                $selected = "selected";
                                            }
                                            ?>
                                            <option value="<?= $sess['id'] ?>" <?= $selected ?>><?= $sess['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="start">Giờ bắt đầu</label>
                                        <input type="text" class="form-control" id="start" name="start" value="<?= $start ?>">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="end">Giờ kết thúc</label>
                                        <input type="text" class="form-control" id="end" name="end" value="<?= $end ?>">
                                    </div>
                                </div>
                                <button type="submit" name="submit" class="btn btn-success px-5">Sửa</button>
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