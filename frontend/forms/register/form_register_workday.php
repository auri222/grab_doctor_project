  </html>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang đăng nhập</title>

    <!-- custom css -->
    <link rel="stylesheet" href="../../../assets/frontend/css/frwd_style.css">

    <!-- bootstrap css -->
    <link rel="stylesheet" href="../../../vendors/bootstrap/css/bootstrap.min.css">
  </head>

  <body>
    <?php
    include_once(__DIR__ . '../../../../dbconnect.php');

    //Lấy ds thứ
    $sql_thu = "select * from thu";
    $rs_thu = mysqli_query($conn, $sql_thu);
    $ds_thu = [];
    while ($row_thu = mysqli_fetch_array($rs_thu, MYSQLI_ASSOC)) {
      $ds_thu[] = array(
        'id'    => $row_thu['id'],
        'name'  => $row_thu['name']
      );
    }

    //Lấy ds buổi
    $sql_buoi = "select * from buoi";
    $rs_buoi = mysqli_query($conn, $sql_buoi);
    $ds_buoi = [];
    while ($row_buoi = mysqli_fetch_array($rs_buoi, MYSQLI_ASSOC)) {
      $ds_buoi[] = array(
        'id'    => $row_buoi['id'],
        'name'  => $row_buoi['name']
      );
    }
    ?>

    <div class="container-fluid" style="min-height: 100vh;">
      <div class="row" style="min-height: 100vh;">
        <div class="col-12 col-md-2 col-xl-3"></div>
        <div class="col-12 col-md-8 col-xl-6 content">
          <div class="wrapper px-3 pt-3 pb-4 ">
            <h2 class="title text-center my-4">Đăng ký ngày làm việc</h2>
            <form class="py-4" method="POST">

              <div class="row py-3">

                <div class="col-md-12">
                  <div class="form-group">
                    <h5>Chọn thứ</h5>
                    <?php foreach ($ds_thu as $t) : ?>
                      <div class="form-check">
                        <input type="checkbox" id="ngay_dk_kham" name="ngay_dk_kham[]" value="<?= $t['id']; ?>">
                        <label><?= $t['name']; ?></label> &nbsp;
                      </div>

                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
              <div class="row py-3">
                <div class="col-md-12">
                  <h5>Chọn buổi: </h5>
                  <?php foreach ($ds_buoi as $b) : ?>

                    <input type="checkbox" id="session" name="session[]" value="<?= $b['id']; ?>">
                    <label><?= $b['name']; ?></label> &nbsp;
                    &nbsp; &nbsp;

                  <?php endforeach; ?>
                </div>
              </div>


              <button type="submit" name="submit" class="btn btn-primary mt-3">Đăng ký</button>

            </form>
          </div>
        </div>
        <div class="col-12 col-md-2 col-xl-3"></div>
      </div>

      <script src="../../../vendors/jquery/jquery-3.6.0.min.js"></script>
      <script src="../../../vendors/bootstrap/js//bootstrap.min.js"></script>

  </html>