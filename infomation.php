<?php
include_once(__DIR__ . '/dbconnect.php');
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $dob = date('d-m-Y', strtotime($_POST["dob"]));
    $gender = $_POST["gender"];
    $address = $_POST["address"];
    $province = $_POST["city"];
    $district = $_POST["district"];
    $ward = $_POST["ward"];
    $specialist = $_POST["specialist"];
    $ngay_dk_kham = implode(', ', $_POST["ngay_dk_kham"]);
    $session = implode(', ', $_POST["session"]);
    
    //Lấy tên tỉnh thành
    $sql_tinhthanh = "select tentinhthanh from tinhthanh where id='$province' ";
    $rs_tinhthanh = mysqli_query($conn, $sql_tinhthanh);
    $row_tinhthanh = mysqli_fetch_array($rs_tinhthanh);
    //Lấy tên quận huyện
    $sql_quanhuyen = "select name, prefix from quanhuyen where id='$district' ";
    $rs_quanhuyen = mysqli_query($conn, $sql_quanhuyen);
    $row_quanhuyen = mysqli_fetch_array($rs_quanhuyen);

    //Lấy tên phường xã
    $sql_phuongxa = "select name, prefix from xa where id='$ward'";
    $rs_phuongxa = mysqli_query($conn, $sql_phuongxa);
    $row_phuongxa = mysqli_fetch_array($rs_phuongxa);

    $addr = $address.', '.$row_phuongxa["prefix"].' '.$row_phuongxa["name"].', '.$row_quanhuyen["prefix"].' '.$row_quanhuyen["name"].', '.$row_tinhthanh["tentinhthanh"];
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trang kết quả đăng ký</title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <style>
            * {
                margin: 0;
                padding: 0;
            }
        </style>
    </head>

    <body style="background-color: #aeeff0;">
        <div class="container-fluid" style="min-height: 100vh;">
           <div class="row">
               <div class="col-lg-3"></div>
               <div class="col-lg-6">
                <div class="wrapper mt-3 px-3 pt-3 pb-4" >
                    <h2 class="text-center my-4">Kết quả đăng ký</h2>
                    <table class="table table-striped table-dark">
                        <tbody>
                            <tr>
                                <th scope="row">Họ và tên</th>
                                <td><?= $name?></td>
                            </tr>
                            <tr>
                                <th scope="row">Ngày sinh</th>
                                <td><?= $dob?></td>
                            </tr>
                            <tr>
                                <th scope="row">Giới tính</th>
                                <td><?= $gender?></td>
                            </tr>
                            <tr>
                                <th scope="row">Địa chỉ phòng khám</th>
                                <td><?= $addr ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Chuyên khoa</th>
                                <td><?= $specialist?></td>
                            </tr>
                            <tr>
                                <th scope="row">Ngày đăng ký khám</th>
                                <td><?= $ngay_dk_kham?></td>
                            </tr>
                            <tr>
                                <th scope="row">Buổi</th>
                                <td><?= $session ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
               <div class="col-lg-3"></div>
           </div>
        </div>


    </body>

    </html>

<?php }; ?>