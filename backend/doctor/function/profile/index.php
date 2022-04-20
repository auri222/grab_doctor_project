<?php
require_once __DIR__ . '../../../../../frontend/controllers/authController.php';

if (!isset($_SESSION['id'])) {
  header('location: ./../../../../frontend/forms/login/login.php');
  exit();
}
$idTK = $_SESSION['id'];
//Lấy profile account
$sql_acc = "	SELECT username, password, name, dob, gender, avatar, email, phone, idPQ, verified, token, otp, update_time
FROM taikhoan WHERE id=$idTK";
$result_acc = mysqli_query($conn, $sql_acc);
$row_acc_count = mysqli_num_rows($result_acc);
$row_acc = mysqli_fetch_assoc($result_acc);

//Lấy data profile
$sql_prof = "SELECT * FROM bacsi WHERE idTK=$idTK";
$result_prof = mysqli_query($conn, $sql_prof);
$row_count = mysqli_num_rows($result_prof);
$hide_add = '';
$hide_edit = '';
$warning = '';
$profile = [];
$specialist = [];
$idBS = '';
if ($row_count > 0) {
  $hide_add = 'style="display:none;"';
  $row_prof = mysqli_fetch_assoc($result_prof);
  $idBS = $row_prof['id'];
} else {
  $hide_edit = 'style="display:none;"';
  $warning = 'Không tìm thấy thông tin về bạn. Hãy thêm ngay';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang Doctor</title>

  <?php include_once(__DIR__ . "./../../style/style_css.php"); ?>
</head>

<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <?php include_once(__DIR__ . "./../../layout/sidebar.php"); ?>
    <!-- End Sidebar -->

    <div class="w-100">
      <!-- Navbar -->
      <?php include_once(__DIR__ . "./../../layout/header.php"); ?>
      <!-- End Navbar -->

      <!-- Content -->
      <div id="content">
        <section class="py-3 bg-grey">
          <div class="container">
            <div class="row">
              <div class="col-lg-12">
                <h1 class="font-weight-bold mb-0">Profile</h1>
                <p class="lead text-muted">Thông tin cá nhân <span <?= $hide_add ?>>(Hãy thêm thông tin làm việc của mình trước nhá!)</span></p>
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
                      <a href="/nln_test/backend/doctor/function/profile/function/create.php?idTK=<?= $idTK ?>" class="btn btn-primary text-left mb-2" <?= $hide_add ?> id="addNew">
                        Thêm mới thông tin của bạn
                      </a>
                      <!-- Thêm mới thông tin cá nhân -->
                      <div class="row">
                        <div class="col-md-12">
                          <h5 class="text-left">Thông tin tài khoản</h5>
                          <table class="table table-borderless mt-2">
                            <tbody>
                              <tr>
                                <th colspan="2" style="text-align: center;">
                                  <img src="/nln_test/assets/img/upload/avatar/<?= $row_acc['avatar'] ?>" alt="Avatar" class="rounded-circle shadow rounded" width="120px" height="120px">

                                </th>
                              </tr>
                              <tr>
                                <th>Họ tên: </th>
                                <td><?= $row_acc['name'] ?></td>
                              </tr>
                              <tr>
                                <th>Ngày sinh: </th>
                                <td><?= date('d-m-Y', strtotime($row_acc['dob'])) ?></td>
                              </tr>
                              <tr>
                                <th>Giới tính: </th>
                                <td><?= $row_acc['gender'] ?></td>
                              </tr>
                              <tr>
                                <th>Email: </th>
                                <td><?= $row_acc['email'] ?></td>
                              </tr>
                              <tr>
                                <th>Số điện thoại: </th>
                                <td><?= $row_acc['phone'] ?></td>
                              </tr>
                              <tr>
                                <th>Tên đăng nhập</th>
                                <td><?= $row_acc['username'] ?></td>
                              </tr>
                              <tr>
                                <th>Ngày tạo: </th>
                                <td><?= date('H:i:s d-m-Y', strtotime($row_acc['update_time'])) ?></td>
                              </tr>
                            </tbody>
                          </table>
                          <div class="btn mb-3">
                            <a href="/nln_test/backend/doctor/function/profile/function/edit_acc.php?idTK=<?= $idTK ?>" class="btn btn-info mb-2">Chỉnh sửa thông tin tài khoản</a>
                            <button type="button" class="btn btn-info mb-2 btn_reset" data-id-tk="<?= $idTK ?>">Thay đổi mật khẩu</a>
                          </div>
                        </div>

                      </div>
                      <hr />
                      <div class="row">
                        <div class="col-md-12">
                          <h5 class="text-left">Thông tin bác sĩ</h5>
                          <div class="alert alert-info" <?= $hide_add ?>>
                            <?= $warning ?>
                          </div>
                          <table class="table table-borderless mt-2" <?= $hide_edit ?>>
                            <tbody>
                              <tr>
                                <th colspan="2" style="text-align: center;">
                                  <img src="/nln_test/assets/img/upload/doctor_img/<?= $row_prof['doctor_img'] ?>" alt="Avatar" class="img-rounded shadow rounded" width="120px" height="120px">
                                </th>
                              </tr>
                              <tr>
                                <th>Địa chỉ phòng khám: </th>
                                <td><?= $row_prof['address'] ?></td>
                              </tr>
                              <tr>
                                <th>Chuyên khoa: </th>
                                <td>
                                  <?php
                                  $idCK = $row_prof['idCK'];
                                  $sql_ck = "SELECT * from chuyenkhoa WHERE id=$idCK";
                                  $result_ck = mysqli_query($conn, $sql_ck);
                                  $row_ck = mysqli_fetch_assoc($result_ck);
                                  echo $row_ck['name'];
                                  ?>
                                </td>
                              </tr>
                              <tr>
                                <th>Làm việc tại bệnh viện: </th>
                                <td><?= $row_prof['work_at'] ?></td>
                              </tr>
                              <tr>
                                <th>Chức danh: </th>
                                <td><?= $row_prof['chucdanh'] ?></td>
                              </tr>
                              <tr>
                                <th>Kinh nghiệm làm việc: </th>
                                <td><?= $row_prof['namkinhnghiem'] ?></td>
                              </tr>

                            </tbody>
                          </table>
                          <a href="/nln_test/backend/doctor/function/profile/function/edit_prof.php?idBS=<?= $idBS ?>" class="btn btn-info">Chỉnh sửa thông tin làm việc</a>
                        </div>
                      </div>
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

  <?php include_once(__DIR__ . "./../../style/style_js.php"); ?>
  <script>
    $(document).ready(function() {
      $('.btn_reset').on('click', function() {
        var idTK = $(this).attr("data-id-tk");

        $.ajax({
          url: "./function/sendmail.php",
          method: "POST",
          dataType: "json",
          data: {
            idTK: idTK
          },
          success: function(response) {
            if (response.status == 1) {
              location.href = "/nln_test/frontend/forms/login/reset_pd_in_acc.php?id=" + idTK;
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Có lỗi rồi',
                text: response.message
              });
            }
          }
        });
      });

    });
  </script>
</body>

</html>