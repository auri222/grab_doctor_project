<?php
require_once __DIR__ . '../../../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
    header('location: ./../../../../../frontend/forms/login/login.php');
    exit();
}

if (isset($_GET['idBS'])) {
    $idBS = $_GET['idBS'];

    //Lấy thông tin bác sĩ
    $sql_doc = "SELECT * FROM bacsi WHERE id = $idBS";
    $rs_doc = mysqli_query($conn, $sql_doc);
    $row_doc = mysqli_fetch_assoc($rs_doc);

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
    }else{
        $addr = $_POST["address"];
    }


    //------------------------------------------------------------------------------------------
    //Xử lý hình avatar
    if (!isset($_FILES['profile_img']) || ($_FILES['profile_img']['error'] == UPLOAD_ERR_NO_FILE)) {
        //-------------------------------------------------------------------------------
        //Nếu người dùng không muốn cập nhật ảnh
        if (count($error) === 0) {
            //Thêm dữ liệu vào database
            $sql = "UPDATE bacsi
                    SET
                        address='$addr',
                        idCK=$specialist,
                        namkinhnghiem=$experience,
                        work_at='$workat',
                        chucdanh='$title'
                    WHERE id = $idBS";
            if (mysqli_query($conn, $sql)) {
                echo "<script>location.href = '/nln_test/backend/doctor/function/profile/index.php'; </script>";
            } else {
                $error['db_failed'] = "Không thêm được. Có lỗi với database!!";
            }
        }
    }
    //---------------------------------------------------------------------------------
    //Nếu người dùng có nhập hình
    else {
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
        if (empty($file_name)) {
            $error['upload_image'] = "Hãy chọn hình";
        }
        //$error['info'] = "năm: ".$experience.", address: ".$addr.", CK: ".$specialist.", img: ".$file_name.", Work: ".$workat.", idTK: ".$idTK;
        //Nếu người dùng nhập đúng hết
        if (count($error) === 0) {
            $path = "./../../../../../assets/img/upload/doctor_img/" . $file_name;
            //Thêm dữ liệu vào database
            $sql = "UPDATE bacsi
                SET
                    address='$addr',
                    idCK=$specialist,
                    namkinhnghiem=$experience,
                    doctor_img='$file_name',
                    work_at='$workat',
                    chucdanh='$title'
                WHERE id=$idBS";
            if (mysqli_query($conn, $sql)) {
                move_uploaded_file($file_tmp, $path);
                echo "<script>location.href = '/nln_test/backend/doctor/function/profile/index.php'; </script>";
            } else {
                $error['db_failed'] = "Không thêm được. Có lỗi với database!!";
            }
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
                                <h1 class="font-weight-bold mb-0">Profile Bác sĩ</h1>
                                <p class="lead text-muted">Sửa thông tin bác sĩ</p>
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
                                                        <input type="text" class="form-control" name="work_place" id="work_place" value="<?= $row_doc['work_at'] ?>">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="title">Chức danh</label>
                                                        <input type="text" class="form-control" name="title" id="title" value="<?= $row_doc['chucdanh'] ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="specialist">Chuyên khoa</label>
                                                    <select id="specialist" name="specialist" class="form-control">
                                                        <option selected>-- Chọn chuyên khoa --</option>
                                                        <?php foreach ($specialists as $spec) : ?>
                                                            <?php
                                                            $ck = $row_doc['idCK'];
                                                            $selected = '';
                                                            if ($spec['id'] == $ck) {
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
                                                        <input type="text" class="form-control" name="experience" value="<?= $row_doc['namkinhnghiem'] ?>" id="experience">
                                                        <small><span style="color: red;">*</span>Chỉ ghi số</small>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <img src="/nln_test/assets/img/upload/doctor_img/<?= $row_doc['doctor_img'] ?>" alt="Hình profile" class="img-thumbnail shadow rounded" width="160px" height="160px">
                                                            </div>
                                                            <div class="form-group col-md-8">
                                                                <label for="profile_img">Ảnh profile</label> <br />
                                                                <input type="file" name="profile_img" id="profile_img">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="address">Địa chỉ phòng khám</label>
                                                    <input type="text" class="form-control" name="address" id="address" value="<?= $row_doc['address'] ?>">
                                                    <small><span style="color: red;">*</span> Nếu bạn không sửa địa chỉ thì hãy giữ nguyên thông tin này</small>
                                                </div>

                                                <button type="submit" name="submit" class="btn btn-primary form-control">Cập nhật thông tin</button>
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

</body>

</html>