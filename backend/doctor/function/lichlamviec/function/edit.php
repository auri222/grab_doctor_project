<?php
require_once __DIR__ . './../../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../../../frontend/forms/login/login.php');
    exit();
}
//Lấy id lịch làm việc
if (isset($_GET['llv_id'])) {
    $llv_id = $_GET['llv_id'];

    //Lấy data lịch làm việc
    $sql_llv = "SELECT * FROM lich_lam_viec WHERE id=$llv_id";
    $rs_llv = mysqli_query($conn, $sql_llv);
    $llv = mysqli_fetch_assoc($rs_llv);

    //Lấy data bác sĩ
    $idTK = $_SESSION['id'];
    $sql_doc = "SELECT * FROM bacsi WHERE idTK=$idTK ";
    $rs_doc = mysqli_query($conn, $sql_doc);
    $row_doc_count = mysqli_num_rows($rs_doc);
    $doctor = mysqli_fetch_assoc($rs_doc);

    //Lấy data thứ
    $sql_weekday = "SELECT * FROM thu";
    $rs_weekday = mysqli_query($conn, $sql_weekday);
    $weekdays = [];
    while ($row_weekday = mysqli_fetch_array($rs_weekday, MYSQLI_ASSOC)) {
        $weekdays[] = array(
            'id'    => $row_weekday['id'],
            'name'  => $row_weekday['name']
        );
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
    if (isset($_POST['submit'])) {
        $weekDay = $_POST['weekDay'];
        $idBS = $doctor['id'];
        $isAvailable = $_POST['isAvailable'];

        //$error['time'] = $time;
        //$error['idBS'] = "ID bác sĩ ".$idBS;
        if ($row_doc_count <= 0) {
            $error['doctor_prof'] = "Bạn phải nhập thông tin profile trước!!";
        }
        //$error["info"] = "Có sẵn? ".$isAvailable." Mã lịch làm việc: ".$llv_id;
        //Nếu chỉ sửa trạng thái
        if (empty($_POST['session']) && empty($_POST['time'])) { // Ban đầu thì thứ đã được chọn rồi chỉ có buổi và khung giờ thôi

            if (count($error) == 0) {
                $query = "	UPDATE lich_lam_viec
                SET
                    isAvailable=$isAvailable
                WHERE id=$llv_id";
                if (mysqli_query($conn, $query)) {
                    echo "<script>location.href = './../index.php'; </script>";
                    exit();
                } else {
                    $error['db_failed'] = "Không thêm được, lỗi rồi option 1!";
                }
            }
        } else {
            //Nếu có chọn buổi
            $session = $_POST['session'];
            $time = $_POST['time'];
            if (empty($time)) {
                $error['missing-time'] = "Hãy chọn khung giờ";
            }
            //Chọn mà ngày khác bị trùng thứ, buổi và khung giờ
            $idBS = $doctor['id'];
            $check = "SELECT * FROM lich_lam_viec WHERE id_thu= $weekDay AND id_buoi=$session AND id_khung_gio=$time AND idBS=$idBS";
            $rs_ck = mysqli_query($conn, $check);
            if ($rs_ck) {
                $check_count = mysqli_num_rows($rs_ck);
                if ($check_count > 0) {
                    $error['error-info'] = "Trùng lịch rồi";
                }
            }
            if (count($error) === 0) {
                $query = "UPDATE lich_lam_viec
                SET
                    id_thu=$weekDay,
                    id_buoi=$session,
                    id_khung_gio=$time,
                    isAvailable=$isAvailable
                WHERE id = $llv_id";
                if (mysqli_query($conn, $query)) {
                    echo "<script>location.href = './../index.php'; </script>";
                    exit();
                } else {
                    $error['db_failed'] = "Không thêm được, lỗi rồi khi đã chọn option 2!";
                }
            }
        }
    }
} else {
    echo '<script> location.href = "/nln_test/backend/doctor/function/lichlamviec/index.php"; </script>';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Doctor</title>

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
                                <p class="lead text-muted">Chỉnh sửa lịch làm việc</p>
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

                                        <div id="data_content" class="table-responsive">
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
                                                    <label for="weekDay">Ngày làm việc: </label>
                                                    <select name="weekDay" id="weekDay" class="form-control">
                                                        <option selected disabled value="">-- Chọn thứ --</option>
                                                        <?php foreach ($weekdays as $wd) : ?>
                                                            <?php
                                                            $selected = '';
                                                            $id_thu = $wd['id'];
                                                            if ($id_thu == $llv['id_thu']) {
                                                                $selected = 'selected';
                                                            }
                                                            ?>
                                                            <option value="<?= $wd['id'] ?>" <?= $selected ?>><?= $wd['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="session">Buổi: </label>
                                                    <select name="session" id="session" class="form-control">
                                                        <option selected disabled value="">-- Chọn buổi --</option>
                                                        <?php foreach ($sessions as $sess) : ?>
                                                            <option value="<?= $sess['id'] ?>"><?= $sess['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <div class="alert alert-info" role="alert">
                                                        Bạn hãy chọn buổi trước nhé
                                                    </div>
                                                </div>
                                                <div class="form-group ">
                                                    <label for="time">Khung giờ cụ thể:</label>
                                                    <select name="time" id="time" class="form-control">
                                                        <option selected disabled>-- Chọn giờ --</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Trạng thái</label> <br />
                                                    <?php
                                                    $checked1 = "";
                                                    $checked0 = "";
                                                    if ($llv['isAvailable'] == 1) {
                                                        $checked1 = "checked";
                                                    }
                                                    if ($llv['isAvailable'] == 0) {
                                                        $checked0 = "checked";
                                                    }
                                                    ?>
                                                    <input type="radio" name="isAvailable" <?= $checked0 ?> value="0"> Trống &nbsp; &nbsp;
                                                    <input type="radio" name="isAvailable" <?= $checked1 ?> value="1"> Bận

                                                </div>
                                                <button type="submit" name="submit" class="btn btn-success px-5">Cập nhật</button>
                                            </form>
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

    <?php include_once(__DIR__ . "./../../../style/style_js.php"); ?>

    <script>
        $(document).ready(function() {
            $('#session').change(function() {
                var sessionID = $(this).val();

                console.log("ID buổi: " + sessionID);
                $.ajax({
                    url: "fetch_time.php",
                    method: "POST",
                    data: {
                        sessionID: sessionID
                    },
                    success: function(data) {
                        $('#time').html(data);
                    }
                });
            });

            $('#time').change(function() {
                var timeID = $(this).val();
                console.log("ID khung giờ: " + timeID);
            });
        });
    </script>
</body>

</html>