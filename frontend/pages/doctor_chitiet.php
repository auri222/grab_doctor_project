<?php
session_start();
//Kết nối database
include_once(__DIR__ . './../../config/dbconnect.php');


$notLogin = '';
$Login = '';
$idBN = '';
$nameBN = '';
$dobBN = '';
$genderBN = '';
$emailBN = '';
$phoneBN = '';
//Nếu người dùng chưa đăng nhập
if (!isset($_SESSION['id']) && !isset($_SESSION['type'])) {
    $notLogin = '';
    $Login = 'style="display: none;"';
}
//Có đăng nhập và là người dùng
else if (isset($_SESSION['id'])) {
    if ($_SESSION['authority'] == 3) {
        $idBN = $_SESSION['id'];
        $notLogin = 'style="display: none;"';
        $Login = '';
        $sql_bn = "SELECT name, dob, gender, email, phone
        FROM taikhoan
        WHERE id = $idBN";
        $rs_bn = mysqli_query($conn, $sql_bn);
        $khach = mysqli_fetch_assoc($rs_bn);
        $nameBN = $khach['name'];
        $dobBN = $khach['dob'];
        $genderBN = $khach['gender'];
        $emailBN = $khach['email'];
        $phoneBN = $khach['phone'];
    } else {
        $Login = 'style="display: none;"';
    }
}

//Lấy thông tin bác sĩ
if (isset($_GET['bs_id'])) {
    $bs_id = $_GET['bs_id'];
    //Lấy thông tin của bác sĩ có id là ...
    $sql_doc = "SELECT tk.name AS HOTEN, tk.dob, tk.gender, tk.phone, tk.email, bs.address, ck.name AS TENCHUYENKHOA, 
                bs.namkinhnghiem, bs.doctor_img, bs.work_at, bs.chucdanh
                FROM bacsi bs
                JOIN chuyenkhoa ck ON bs.idCK = ck.id
                JOIN taikhoan tk ON bs.idTK = tk.id
                WHERE bs.id = $bs_id";
    $rs_doc = mysqli_query($conn, $sql_doc);
    $doctors = [];
    $doc_name = '';
    while ($row_docs = mysqli_fetch_array($rs_doc, MYSQLI_ASSOC)) {
        $doctors[] = array(
            'bs_ten'    => $row_docs['HOTEN'],
            'bs_dob'    => $row_docs['dob'],
            'bs_gender'    => $row_docs['gender'],
            'bs_phone'    => $row_docs['phone'],
            'bs_email'    => $row_docs['email'],
            'bs_address' => $row_docs['address'],
            'bs_ck'      => $row_docs['TENCHUYENKHOA'],
            'bs_namkinhnghiem' => $row_docs['namkinhnghiem'],
            'bs_img'    => $row_docs['doctor_img'],
            'bs_work_at'    => $row_docs['work_at'],
            'bs_chucdanh'    => $row_docs['chucdanh']
        );
        $doc_name = $row_docs['HOTEN'];
    }

    //Lấy lịch làm việc của bác sĩ
    $llv = [];
    $sql_llv = "SELECT llv.id, t.name AS TenThu, b.name AS TenBuoi, k.name AS TENKHUNGGIO 
                FROM lich_lam_viec llv
                JOIN thu t ON t.id = llv.id_thu
                JOIN buoi b ON b.id = llv.id_buoi
                JOIN khung_gio k ON k.id = llv.id_khung_gio
                WHERE llv.idBS = $bs_id AND llv.isAvailable=0";
    $rs_llv = mysqli_query($conn, $sql_llv);
    while ($row_llv = mysqli_fetch_array($rs_llv, MYSQLI_ASSOC)) {
        $llv[] = array(
            'llvID' => $row_llv['id'],
            'thu'   => $row_llv['TenThu'],
            'buoi'  => $row_llv['TenBuoi'],
            'khunggio' => $row_llv['TENKHUNGGIO']
        );
    }

    //Lấy buổi
    $sql_buoi = "SELECT * FROM buoi";
    $rs_buoi = mysqli_query($conn, $sql_buoi);
    $ds_buoi = [];
    while ($row_buoi = mysqli_fetch_array($rs_buoi, MYSQLI_ASSOC)) {
        $ds_buoi[] = array(
            'id'    => $row_buoi['id'],
            'name'  => $row_buoi['name']
        );
    }
} else {
    echo '<script>
        location.href = "/nln_test/frontend/pages/doctor.php";
    </script>';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grab doctor</title>

    <?php include_once(__DIR__ . './style/style_css.php'); ?>

</head>

<body>
    <div class="page-holder">
        <!-- navbar-->
        <?php include_once(__DIR__ . './layout/header.php'); ?>
        <!-- navbar end -->

        <section class="jumbotron-doctor">
            <div class="jumbotron jumbotron-fluid mb-0">
                <div class="container">
                    <h1 class="display-5">Bác sĩ <?= $doc_name ?></h1>
                    <p class="lead">Bạn có thể xem về thông tin và lịch khám của bác sĩ và đặt lịch hẹn</p>
                </div>
            </div>
        </section>

        <section class="doctor-profile">
            <div class="container py-3">
                <div class="row  ">
                    <div class="col-lg-12">
                        <?php foreach ($doctors as $doc) : ?>
                            <div class="row my-4">

                                <div class="col-md-3">
                                    <img src="/nln_test/assets/img/upload/doctor_img/<?= $doc['bs_img'] ?>" class="img-fluid" alt="From unplash - Ashkan Forouzani">
                                </div>
                                <div class="col-md-9">
                                    <table class="table table-borderless">
                                        <tbody>

                                            <tr>
                                                <th scope="row">Họ tên bác sĩ</th>
                                                <td> <?= $doc['bs_ten'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Ngày sinh</th>
                                                <td> <?= date("d-m-Y",strtotime($doc['bs_dob'])) ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Giới tính</th>
                                                <td> <?= $doc['bs_gender'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Địa chỉ phòng khám</th>
                                                <td> <?= $doc['bs_address'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Chuyên khoa</th>
                                                <td> <?= $doc['bs_ck'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Năm kinh nghiệm</th>
                                                <td> <?= $doc['bs_namkinhnghiem'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Làm việc tại</th>
                                                <td> <?= $doc['bs_work_at'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Chức danh</th>
                                                <td> <?= $doc['bs_chucdanh'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Lịch làm việc</th>
                                                <td>
                                                    <?php foreach ($llv as $l) {
                                                        $thu = $l['thu'];
                                                        $buoi = $l['buoi'];
                                                        $khunggio = $l['khunggio'];
                                                        echo $thu . ' - ' . $buoi . ' - ' . '<span class="badge badge-info">' . $khunggio . '</span><br/>';
                                                    } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="/nln_test/frontend/pages/frm_dat_lich.php?bs_id=<?= $bs_id?>" class="btn btn-primary">Đặt lịch hẹn</a>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            </div>
        </section>

        <!-- Rating system -->

        <!-- <section class="jumbotron-doctor">
            <div class="jumbotron jumbotron-fluid mb-0">
                <div class="container">
                    <h1 class="display-5">Đánh giá</h1>
                    <p class="lead">Khám bệnh thời 4.0 - đánh giá bình phẩm để phát triển hơn</p>
                </div>
            </div>
        </section>
        <section class="review" style="background: #e9ecef";  
    background-repeat: no-repeat;">
            <div class="container py-3">
                <div class="row py-2">
                    <div class="col-md-12 rounded" style="background: linear-gradient(to right bottom, rgba(255,255,255,0.8),rgba(255,255,255,0.3));">
                        <h4 class="text-left pt-2 mt-2">Điểm đánh giá</h4>
                        
                    </div>
                </div>
            </div>
        </section> -->

        <!-- Footer start -->
        <?php include_once(__DIR__ . './style/style_js.php'); ?>
        <?php include_once(__DIR__ . './layout/footer.php'); ?>
        <!-- Footer end-->
    </div>
</body>

</html>