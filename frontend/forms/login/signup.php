<?php
include_once(__DIR__ . '../../../../config/dbconnect.php');
require_once __DIR__ . '../../../controllers/authController.php';


//-----------------------------------------------------
//Lấy dữ liệu phân quyền
$sql_pq = "select * from phanquyen";
$rs_pq = mysqli_query($conn, $sql_pq);
$ds_pq = [];
while ($row = mysqli_fetch_array($rs_pq, MYSQLI_ASSOC)) {
  $ds_pq[] = array(
    'id'    => $row['id'],
    'name'  => $row['name']
  );
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang đăng ký</title>

  <!-- custom css -->
  <link rel="stylesheet" href="./../../../assets/frontend/css/signup.css">

  <!-- bootstrap css -->
  <link rel="stylesheet" href="./../../../vendors/bootstrap/css/bootstrap.min.css">
</head>

<body>
  <div class="container-fluid" style="min-height: 100vh;">
    <div class="row" style="min-height: 100vh;">
      <div class="col-12 col-md-1 col-xl-2"></div>
      <div class="col-12 col-md-10 col-xl-8 content">
        <div class="wrapper px-3 pt-3 pb-4 ">
          <h2 class="title text-center my-4">Đăng ký</h2>
          <!-- Check lỗi -->
          <?php if (count($error) > 0) : ?>
            <div class="alert alert-warning" role="alert">
              <?php foreach ($error as $err) : ?>
                <li><?= $err; ?></li>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <form action="signup.php" method="POST" enctype="multipart/form-data">
            <div class="row mx-2">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name">Họ tên <span style="color: red;">*</span></label>
                  <input type="text" class="form-control " id="name" name="name" value="<?= $name?>">
                </div>
                <div class="form-group">
                  <label for="dob">Ngày sinh <span style="color: red;">*</span></label>
                  <input type="date" class="form-control " id="dob" name="dob" value="<?= $dob?>">
                  <small><span style="color: red;">*</span> Nếu chỉ có năm hãy chọn ngày 01 tháng 01</small>
                </div>
                <div class="form-group">
                  <label for="gender">Giới tính </label> <br />
                  <input type="radio" id="male" name="gender" value="Nam" checked> Nam &nbsp; &nbsp;
                  <input type="radio" id="female" name="gender" value="Nữ"> Nữ
                </div>
                <div class="form-group">
                  <label for="avatar">Hình đại diện <span style="color: red;">*</span></label><br/>
                  <input type="file" id="avatar" name="avatar">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="username">Tên đăng nhập <span style="color: red;">*</span></label>
                  <input type="text" class="form-control " id="username" name="username" value="<?= $username?>">
                </div>
                <div class="form-group">
                  <label for="password">Mật khẩu <span style="color: red;">*</span></label>
                  <input type="password" class="form-control" id="password" name="password">
                  <small>Mật khẩu phải gồm chữ cái viết hoa lẫn thường và số, phải nhiều hơn 8 ký tự</small>
                </div>
                <div class="form-group">
                  <label for="repass">Nhập lại mật khẩu <span style="color: red;">*</span></label>
                  <input type="password" class="form-control" id="repass" name="repass">
                </div>
                <div class="form-group">
                  <label for="isdoctor">Bạn là? <span style="color: red;">*</span></label> <br>
                  <?php foreach ($ds_pq as $pq) : ?>
                    <?php
                    $hidden = '';
                    $checked = '';
                    if ($pq['id'] == 1) {
                      $hidden = 'style="display:none"';
                    }
                    if ($pq['id'] == 2) {
                      $checked = 'checked';
                    }
                    ?>
                    <input type="radio" name="isdoctor" id="<?= $pq['id'] ?>" value="<?= $pq['id'] ?>" <?= $hidden ?> <?= $checked ?>> <span <?= $hidden ?>> <?= $pq['name'] ?></span> &nbsp;&nbsp;
                  <?php endforeach; ?> <br>
                  <!-- <small>Hãy chọn đúng nhé !!!</small> -->
                </div>
                <div class="form-group">
                  <label for="email">Email <span style="color: red;">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" value="<?= $email?>">
                </div>
                <div class="form-group">
                  <label for="phone">Số điện thoại <span style="color: red;">*</span></label>
                  <input type="text" class="form-control" id="phone" name="phone" value="<?= $phone?>">
                </div>
              </div>

            </div>
            <button type="submit" name="register" class="btn btn-primary btn-block my-3 btn-submit">Đăng ký</button>
            <p class="text-center">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
          </form>
        </div>
      </div>
      <div class="col-12 col-md-1 col-xl-2"></div>
    </div>

    <script src="../../../vendors/jquery/jquery-3.6.0.min.js"></script>
    <script src="../../../vendors/bootstrap/js/bootstrap.min.js"></script>

</body>

</html>
