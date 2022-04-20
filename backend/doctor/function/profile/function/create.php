<?php
require_once __DIR__ . '../../../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../../frontend/forms/login/login.php');
    exit();
}

if (isset($_GET['idTK'])) {
    $idTK = $_GET['idTK'];

    //Lấy data chuyên khoa
    $sql_spec = "SELECT * FROM chuyenkhoa";
    $result_spec = mysqli_query($conn, $sql_spec);
    $specialists = [];
    while ($row_spec = mysqli_fetch_array($result_spec, MYSQLI_ASSOC)) {
        $specialists[] = array(
            'id'    => $row_spec['id'],
            'name'  => $row_spec['name']
        );
    }

    //Lấy data tỉnh thành
    $sql_prov = "SELECT * FROM tinhthanh ORDER BY name";
    $result_prov = mysqli_query($conn, $sql_prov);
    $provinces = [];
    while ($row_prov = mysqli_fetch_array($result_prov, MYSQLI_ASSOC)) {
        $provinces[] = array(
            'id'    => $row_prov['id'],
            'name'  => $row_prov['name'],
            'code'  => $row_prov['code']
        );
    }
}


$error = [];
$experience = '';
$title = '';
$workat = '';
$address = '';
if (isset($_POST['submit'])) {

    //Check dữ liệu nhập
    if (empty($_POST["experience"])) {
        $error['experience'] = "Nhập kinh nghiệm làm việc";
    } else {
        $experience = $_POST["experience"];
    }

    if (empty($_POST["work_place"])) {
        $error['work_place'] = "Nhập nơi làm việc";
    } else {
        $workat = $_POST["work_place"];
    }

    if (empty($_POST["title"])) {
        $error['title'] = "Nhập chức danh";
    } else {
        $title = $_POST["title"];
    }

    if (empty($_POST["specialist"])) {
        $error['specialist'] = "Hãy chọn chuyên khoa";
    } else {
        $specialist = $_POST["specialist"];
    }

    if (empty($_POST["address"])) {
        $error['address'] = "Nhập địa chỉ phòng khám";
    } else {
        $address = $_POST["address"];
    }

    if (empty($_POST["province"])) {
        $error['province'] = "Hãy chọn tỉnh thành";
    } else {
        $province = $_POST["province"];
    }

    if (empty($_POST["district"])) {
        $error['district'] = "Hãy chọn quận huyện";
    } else {
        $district = $_POST["district"];
    }

    if (empty($_POST["province"])) {
        $error['ward'] = "Hãy chọn phường xã";
    } else {
        $ward = $_POST["ward"];
    }

    //----------------------------------------------------------------------------------------
    //Lấy tên tỉnh thành
    $sql_province = "SELECT * FROM tinhthanh WHERE id='$province' ";
    $rs_province = mysqli_query($conn, $sql_province);
    $row_province = mysqli_fetch_array($rs_province);
    //Lấy tên quận huyện
    $sql_district = "SELECT * FROM quanhuyen WHERE id='$district' ";
    $rs_district = mysqli_query($conn, $sql_district);
    $row_district = mysqli_fetch_array($rs_district);

    //Lấy tên phường xã
    $sql_ward = "SELECT * FROM phuongxa WHERE id='$ward'";
    $rs_ward = mysqli_query($conn, $sql_ward);
    $row_ward = mysqli_fetch_array($rs_ward);

    $addr = $address . ', ' . $row_ward["prefix"] . ' ' . $row_ward["name"] . ', ' . $row_district["prefix"] . ' ' . $row_district["name"] . ', ' . $row_province["name"];
    //------------------------------------------------------------------------------------------
    //Xử lý hình avatar
    if (!isset($_FILES['profile_img']) || ($_FILES['profile_img']['error'] == UPLOAD_ERR_NO_FILE)) {
        $error['profile_img'] = "Chưa tải hình lên";
    } else {
        $file_name = $_FILES['profile_img']['name'];
        $file_size = $_FILES['profile_img']['size'];
        $file_tmp = $_FILES['profile_img']['tmp_name'];
        $file_type = $_FILES['profile_img']['type'];
        $file_error = $_FILES['profile_img']['error'];
        $file_ext = explode('.', $_FILES['profile_img']['name']);
        $fileExtension = strtolower(end($file_ext));
        $extensions = array("jpeg", "png", "jpg");

        //Check extension 
        if (in_array($fileExtension, $extensions) == false) {
            $error['type'] = "Bạn không được tải file loại này lên ngoài trừ file có đuôi .jpg, .png, .jpeg";
        }
        if ($file_error !== 0) {
            $error['error'] = "Có lỗi khi tải hình ảnh của bạn. Vui lòng thử lại!!";
        }
        if ($file_size > 4194304) {
            $error['size'] = "File tải lên có kích thước phải nhỏ hơn 4MB!!";
        }
    }
    //$error['info'] = "năm: ".$experience.", address: ".$addr.", CK: ".$specialist.", img: ".$file_name.", Work: ".$workat.", idTK: ".$idTK;
    //Nếu người dùng nhập đúng hết
    if (count($error) === 0) {
        $path = "./../../../../../assets/img/upload/doctor_img/" . $file_name;
        //Thêm dữ liệu vào database
        $sql = "INSERT INTO bacsi
        (address, idCK, namkinhnghiem, doctor_img, work_at, chucdanh, idTK)
        VALUES ('$addr', $specialist, $experience, '$file_name', '$workat', '$title', $idTK) ";
        if (mysqli_query($conn, $sql)) {
            move_uploaded_file($file_tmp, $path);
            echo "<script>location.href = '/nln_test/backend/doctor/function/profile/index.php'; </script>";
        } else {
            $error['db_failed'] = "Không thêm được. Có lỗi với database!!";
        }
    }
}

?>

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
                                <h1 class="font-weight-bold mb-0">Profile bác sĩ</h1>
                                <p class="lead text-muted">Thêm thông tin bác sĩ</p>
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
                                        </div>
                                        <?php if (count($error) > 0) : ?>
                                            <div class="alert alert-info" role="alert">
                                                <?php foreach ($error as $err) : ?>
                                                    <li><?= $err ?></li>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="work_place">Làm việc tại</label>
                                                    <input type="text" class="form-control" name="work_place" id="work_place" value="<?= $workat ?>" placeholder="Bệnh viện Đa khoa Trung ương ...">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="title">Chức danh</label>
                                                    <input type="text" class="form-control" name="title" id="title" value="<?= $title ?>" placeholder="Bác sĩ / Dược sĩ...">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="specialist">Chuyên khoa</label>
                                                <select id="specialist" name="specialist" class="form-control">
                                                    <option selected>-- Chọn chuyên khoa --</option>
                                                    <?php foreach ($specialists as $spec) : ?>
                                                        <?php
                                                        $selected = '';
                                                        if ($spec['id'] == $specialist) {
                                                            $selected = 'selected';
                                                        }
                                                        ?>
                                                        <option value="<?= $spec['id'] ?>" <?= $selected ?>><?= $spec['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label for="experience">Kinh nghiệm làm việc</label>
                                                    <input type="text" class="form-control" name="experience" value="<?= $experience ?>" id="experience" placeholder="6 (năm)">
                                                    <small><span style="color: red;">*</span>Chỉ ghi số</small>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="profile_img">Ảnh profile</label> <br />
                                                    <input type="file" name="profile_img" id="profile_img">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="address">Địa chỉ phòng khám</label>
                                                <input type="text" class="form-control" name="address" id="address" value="<?= $address ?>" placeholder="Số nhà, tên đường ...">
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-4">
                                                    <label for="province">Tỉnh/Thành phố</label>
                                                    <select id="province" name="province" class="form-control">
                                                        <option selected>-- Chọn tỉnh thành --</option>
                                                        <?php foreach ($provinces as $prov) : ?>
                                                            <option value="<?= $prov['id'] ?>"><?= $prov['name'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <!-- Quận/ huyện sẽ hiện theo tỉnh/thành -->
                                                <div class="form-group col-md-4">
                                                    <label for="district">Quận/Huyện</label>
                                                    <select id="district" name="district" class="form-control">
                                                        <option disabled selected>-- Chọn quận huyện --</option>
                                                    </select>
                                                </div>
                                                <!-- Phường/ xã sẽ hiện theo Quận/ huyện -->
                                                <div class="form-group col-md-4">
                                                    <label for="ward">Phường/Xã</label>
                                                    <select id="ward" name="ward" class="form-control">
                                                        <option disabled selected>-- Chọn phường xã --</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <button type="submit" name="submit" class="btn btn-primary form-control">Thêm thông tin</button>
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


    <!-- Ajax cho dữ liệu tỉnh thành quận huyện phường xã -->
    <script>
        $(document).ready(function() {
            //Lấy dữ liệu quận huyện theo tỉnh thành
            $("#province").change(function() {
                var provinceID = $(this).val();
                console.log("ID province: " + provinceID);
                $.ajax({
                    url: "fetch_district.php",
                    method: "POST",
                    data: {
                        provinceID: provinceID
                    },
                    success: function(data) {
                        $("#district").html(data);
                    }
                });
            });
            //Lấy dữ liệu phường xã theo quận huyện
            $("#district").change(function() {
                var districtID = $(this).val();
                console.log("ID district: " + districtID);
                $.ajax({
                    url: "fetch_ward.php",
                    method: "POST",
                    data: {
                        districtID: districtID
                    },
                    success: function(data) {
                        $("#ward").html(data);
                    }
                });
            });

        });
    </script>

</body>

</html>