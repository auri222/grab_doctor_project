<?php
require_once __DIR__.'./../../controllers/authController.php';

if(isset($_GET['reset'])){
  echo '<script> alert("Bạn đã đổi mật khẩu thành công. Hãy đăng nhập lại để kiểm tra!"); </script>';
}

//tạo tk thành công hoặc xác minh email thành công => user muốn đăng nhập lại
if(isset($_GET['success'])){
  //Đăng xuất
  if(isset($_SESSION['id'])){
      session_destroy();
      unset($_SESSION['id']);
      unset($_SESSION['email']);
      unset($_SESSION['username']);
      unset($_SESSION['authority']);
      unset($_SESSION['phone']);
      if(isset($_SESSION['warning'])){
          unset($_SESSION['warning']);
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
  <title>Trang đăng nhập</title>

  <!-- custom css -->
  <link rel="stylesheet" href="/nln_test/assets/frontend/css/style_login.css">

  <!-- bootstrap css -->
  <link rel="stylesheet" href="/nln_test/vendors/bootstrap/css/bootstrap.min.css">
</head>

<body>
  <div class="container-fluid" style="min-height: 100vh;">
    <div class="row" style="min-height: 100vh;">
      <div class="col-12 col-md-2 col-xl-3"></div>
      <div class="col-12 col-md-8 col-xl-6 content">
        <div class="wrapper px-3 pt-3 pb-4 ">
          <h2 class="title text-center my-4">Đăng nhập</h2>
          
          <?php if(count($error)>0): ?>
          <div class="alert alert-warning" role="alert">
            <?php foreach($error as $err): ?>
            <li><?= $err ?></li>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          
          <form action="login.php" method="POST">
            <div class="form-group">
              <label for="username">Tên đăng nhập/Email</label>
              <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="form-group">
              <label for="password">Mật khẩu</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" name="login" class="btn btn-primary btn-block mt-4 mb-3">Đăng nhập</button>
            <p class="text-center">Quên mật khẩu? <a href="confirm_email_password.php">Nhấn vào đây để sửa</a></p>
            <p class="text-center">Chưa có tài khoản? <a href="signup.php">Đăng ký</a></p>
          </form>
        </div>
      </div>
      <div class="col-12 col-md-2 col-xl-3"></div>
    </div>

    <script src="/nln_test/vendors/jquery/jquery-3.6.0.min.js"></script>
    <script src="/nln_test/vendors/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>