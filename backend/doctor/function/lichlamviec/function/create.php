<?php
require_once __DIR__ . './../../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../../../frontend/forms/login/login.php');
    exit();
}


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
    $weekDay = empty($_POST['weekDay']) ? "" : $_POST['weekDay'];
    $session = empty($_POST['session']) ? "" : $_POST['session'];
    $idBS = $doctor['id'];

    if (empty($_POST['time'])) {
        $time = "NULL";
    } else {
        $time = $_POST['time'];
    }

    //$error['time'] = $time;
    //$error['idBS'] = "ID bác sĩ ".$idBS;
    if ($row_doc_count <= 0) {
        $error['doctor_prof'] = "Bạn phải nhập thông tin profile trước!!";
    }
    //Kiểm tra dữ liệu nhập
    if ($weekDay === "") {
        $error['weekDay'] = "Bạn cần chọn thứ";
    }
    if ($session === "") {
        $error['session'] = "Bạn cần chọn buổi";
    }
    if ($time === "") {
        $error['time'] = "Bạn cần chọn khung giờ";
    }

    $isAvailable = 0;
    $count = "SELECT COUNT(*) AS TOTAL 
	FROM lich_lam_viec llv
	WHERE llv.idBS = $idBS AND llv.id_thu=$weekDay AND llv.id_buoi = $session AND llv.id_khung_gio = $time";
    $rs_count = mysqli_query($conn,$count);
    $row = mysqli_fetch_assoc($rs_count);
    if($row['TOTAL'] > 0){
        $error['duplicate'] = "Bạn đã chọn trùng. Vui lòng chọn lại!";
    }

    if (count($error) === 0) {
        $query = "INSERT INTO lich_lam_viec (id_thu, id_buoi, id_khung_gio, idBS, isAvailable) VALUES ($weekDay, $session, $time, $idBS, $isAvailable)";
        if (mysqli_query($conn, $query)) {
            echo "<script>location.href = './../index.php'; </script>";
            exit();
        } else {
            $error['db_failed'] = "Không thêm được. Có thể bạn đã nhập trùng buổi!";
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
                                <p class="lead text-muted">Thêm lịch làm việc</p>
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
                                                            <option value="<?= $wd['id'] ?>"><?= $wd['name'] ?></option>
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
                                                <!-- <div class="form-group">
                                    <div class="alert alert-info" role="alert">
                                        Bạn có thể chọn khung giờ cụ thể hoặc không bởi các khung giờ xác định theo buổi và nhớ chọn buổi trước nhé
                                    </div>
                                </div> -->
                                                <div class="form-group ">
                                                    <label for="time">Khung giờ cụ thể:</label>
                                                    <select name="time" id="time" class="form-control">
                                                        <option selected disabled>-- Chọn giờ --</option>
                                                    </select>
                                                </div>
                                                <button type="submit" name="submit" class="btn btn-success px-5">Thêm</button>
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