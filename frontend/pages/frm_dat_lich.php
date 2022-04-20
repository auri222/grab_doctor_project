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
                WHERE llv.idBS = $bs_id";
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

        <section class="register" style="background: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url('/nln_test/assets/img/frontend/national-cancer-institute-L8tWZT4CcVQ-unsplash.jpg');  
    background-repeat: no-repeat;">
            <div class="container py-3">
                <!-- Form đặt lịch khám -->
                <div class="row py-2">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-9 rounded" style="background: linear-gradient(to right bottom, rgba(255,255,255,0.7),rgba(255,255,255,0.3));">
                        <h3 class="text-left pt-2 mt-2">Form đặt lịch khám</h3>
                        <form action="" method="POST" class="py-4">
                            <div class="form-group ">
                                <label for="name">Họ và tên</label>
                                <input type="text" class="form-control" name="name" value="<?= $nameBN ?>" require id="name">
                                <input type="hidden" id="bnID" value="<?= $idBN ?>">
                                <input type="hidden" id="bsID" value="<?= $bs_id ?>">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Giới tính</label> <br>
                                    <?php
                                    $male = "";
                                    $female = "";
                                    if ($genderBN == "") {
                                        $male = "checked";
                                    } else if ($genderBN == "Nam") {
                                        $male = "checked";
                                    } else {
                                        $female = "checked";
                                    }
                                    ?>
                                    <input type="radio" name="gender" id="male" value="Nam" <?= $male ?>> Nam
                                    <input type="radio" name="gender" id="female" <?= $female ?> value="Nữ"> Nữ
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="dob">Ngày sinh</label>
                                    <input type="date" class="form-control" min="1940-01-01" require name="dob" id="dob" value="<?= date('Y-m-d', strtotime($dobBN)) ?>">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="email">Email <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="email" id="email" require value="<?= $emailBN ?>" placeholder="example@gmail.com">
                            </div>
                            <div class="form-group ">
                                <label for="phone">Số điện thoại <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" name="phone" id="phone" value="<?= $phoneBN ?>" require placeholder="0284729xxx (10 số)">
                            </div>
                            <div class="form-group ">
                                <label for="symtom">Triệu chứng <span style="color: red;">*</span></label>
                                <textarea class="form-control" name="symtom" rows="3" id="symtom" require placeholder="Ghi triệu chứng vào ô này"></textarea>
                            </div>
                            <div class="form-group">
                                <div class="alert alert-info">
                                    <li>Hãy chọn ngày trước</li>
                                    <li>Sau đó chọn buổi rồi tới khung giờ</li>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="date">Chọn ngày</label> <br />
                                <input type="date" name="date" id="date">
                                <input type="hidden" name="bsID" id="bsID" value="<?= $bs_id ?>">
                                <div id="idThu"></div>

                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="session">Chọn buổi</label>
                                    <select name="session" id="session" class="form-control">
                                        <option disabled selected>-- Chọn buổi --</option>
                                        <?php foreach ($ds_buoi as $b) : ?>
                                            <option value="<?= $b['id'] ?>"><?= $b['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="time">Chọn giờ</label>
                                    <select name="time" id="time" class="form-control">
                                        <option disabled selected>-- Chọn khung giờ --</option>

                                    </select>
                                </div>
                                <input type="hidden" id="Login" value="<?= $Login ?>">
                            </div>


                            <button type="button" name="submit" class="btn btn-primary form-control mt-2 btn-submit ">Đăng ký</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer start -->
        <?php include_once(__DIR__ . './style/style_js.php'); ?>
        <?php include_once(__DIR__ . './layout/footer.php'); ?>
        <!-- Footer end-->
    </div>
    <script>
        $(document).ready(function() {
            var idThu;
            var ngay;
            var bsID;
            var idBuoi;
            $('#session').attr('disabled', true);
            $('#time').attr('disabled', true);
            $('#date').change(function() {
                ngay = $(this).val();
                bsID = $('#bsID').val();
                console.log("Ngày: " + ngay);
                console.log("ID bác sĩ: " + bsID);
                $.ajax({
                    url: "./functions/check_day.php",
                    type: "POST",
                    data: {
                        ngay: ngay,
                        bsID: bsID
                    },
                    success: function(response) {
                        var obj = JSON.parse(response);
                        if (obj.status == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Có gì sai rồi',
                                text: ' ' + obj.error
                            });
                            $('#session').attr('disabled', true);
                            $('#time').attr('disabled', true);
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Ngày hợp lệ',
                                showConfirmButton: false,
                                timer: 800
                            });
                            $('#session').attr('disabled', false);
                            idThu = obj.wDID;
                        }
                    }
                });
            });

            $('#session').change(function() {
                var buoi = $(this).val();
                console.log("Buổi được chọn: " + buoi);
                console.log("Thứ được chọn: " + idThu);
                console.log("IDBS: " + bsID);
                $.ajax({
                    url: "./functions/check_session.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        idBuoi: buoi,
                        idThu: idThu,
                        bsID: bsID
                    },
                    success: function(output) {
                        if (output.status == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Có gì sai rồi',
                                text: ' ' + output.response
                            });
                            $('#time').attr('disabled', true);
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Buổi hợp lệ',
                                showConfirmButton: false,
                                timer: 800
                            });
                            console.log(output.response);
                            idBuoi = output.buoi;
                            idThu = output.thu;
                            $.ajax({
                                url: "./functions/load_time.php",
                                type: "POST",
                                data: {
                                    idBuoi: idBuoi,
                                    idBS: bsID,
                                    idthu: idThu
                                },
                                success: function(data) {
                                    $('#time').html(data);
                                    $('#time').attr('disabled', false);
                                }
                            });
                        }
                    }
                });
            });

            // Check khung giờ người dùng CHỌN
            var check_time = false;
            $('#time').on("change", function() {
                var idTime = $(this).val();
                console.log("Thời gian chọn là: " + idTime);
                $.ajax({
                    url: "./functions/check_time.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        idBS: bsID,
                        idTime: idTime,
                        idBuoi: $('#session').val(),
                        bookdate: $('#date').val()
                    },
                    success: function(response) {
                        // console.log("Time checked: "+response.start_time+" - "+response.end_time+" - count = "+response.total_row);
                        if (response.status == 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Có gì sai rồi',
                                text: ' ' + response.output
                            });
                            check_time = false;
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: '' + response.output,
                                showConfirmButton: false,
                                timer: 800
                            });
                            check_time = true;
                        }
                    }
                });
            });

            function check_input() {
                var re = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                var date = $('#date').val();
                var session = $('#session').val();
                var time = $('#time').val();
                var name = $('#name').val();
                var dob = $('#dob').val();
                var email = $('#email').val();
                var phone = $('#phone').val();
                var symtom = $('#symtom').val();
                var check = true;
                //Triệu chứng
                if (symtom === "") {
                    Swal.fire({
                        position: 'center',
                        icon: 'question',
                        title: 'Cảnh báo',
                        text: 'Hãy ghi triệu chứng!'
                    });
                    check = false;
                }

                if (name === "") {
                    Swal.fire({
                        position: 'center',
                        icon: 'question',
                        title: 'Cảnh báo',
                        text: 'Hãy ghi họ tên!'
                    });
                    check = false;
                }

                if (phone === "") {
                    Swal.fire({
                        position: 'center',
                        icon: 'question',
                        title: 'Cảnh báo',
                        text: 'Hãy ghi số điện thoại!'
                    });
                    check = false;
                } else if (phone.length > 11 || phone.length < 10) {
                    Swal.fire({
                        position: 'center',
                        icon: 'question',
                        title: 'Cảnh báo',
                        text: 'Hãy ghi số điện thoại hợp lệ!'
                    });
                    check = false;
                }

                if (date === "") {
                    Swal.fire({
                        position: 'center',
                        icon: 'question',
                        title: 'Cảnh báo',
                        text: 'Hãy chọn ngày!'
                    });
                    check = false;
                }

                if (session === "") {
                    Swal.fire({
                        position: 'center',
                        icon: 'question',
                        title: 'Cảnh báo',
                        text: 'Hãy chọn buổi!'
                    });
                    check = false;
                }

                if (time === "") {
                    Swal.fire({
                        position: 'center',
                        icon: 'question',
                        title: 'Cảnh báo',
                        text: 'Hãy chọn giờ!'
                    });
                    check = false;
                }

                //Check email hợp lệ
                if (email === "") {
                    Swal.fire({
                        position: 'center',
                        icon: 'question',
                        title: 'Cảnh báo',
                        text: 'Hãy nhập địa chỉ email của bạn!'
                    });
                    check = false;
                } else if ($('#email').val().match(re)) {
                    email = $('#email').val();
                } else {
                    Swal.fire({
                        position: 'center',
                        icon: 'question',
                        title: 'Cảnh báo',
                        text: 'Địa chỉ email không hợp lệ!'
                    });
                    check = false;
                }

                return check;
            }

            $('.btn-submit').on("click", function() {
                var login = $('#Login').val();
                console.log("Biến login: " + login);
                //console.log(idBS + " - " + date + " - " + session + " - " + time + " - " + name + " - " + gender + " - " + dob + " - " + email + " - " + phone + " - " + idBN);
                if (login !== "") {
                    Swal.fire({
                        position: 'center',
                        icon: 'question',
                        title: 'Cảnh báo',
                        text: 'Bạn cần đăng nhập để thực hiện đăng ký khám!',
                        footer: '<a href="/nln_test/frontend/forms/login/signup.php">Bạn có thể đăng ký tài khoản ở đây?</a>'
                    })
                } else {
                    var flag = check_input();
                    var gender = $('input[name="gender"]:checked').val();
                    var idBS = $('#bsID').val();
                    var idBN = $('#bnID').val();
                    console.log("gender: " + gender);
                    if (check_time == true) {
                        if (flag == true) {
                            console.log("Check flag rồi!");
                            //console.log("Check val: "+name+" - "+gender+" - "+email+" - "+phone+" - "+symtom+" - "+date+" - "+session+" - "+time)
                            $.ajax({
                                url: "./functions/input_frm_book.php",
                                method: "POST",
                                dataType: "json",
                                data: {
                                    name: $('#name').val(),
                                    gender: gender,
                                    dob: $('#dob').val(),
                                    email: $('#email').val(),
                                    phone: $('#phone').val(),
                                    symtom: $('#symtom').val(),
                                    idBS: idBS,
                                    idBN: idBN,
                                    date: $('#date').val(),
                                    session: $('#session').val(),
                                    time: $('#time').val()
                                },
                                //     success: function(response){
                                //         console.log("input ajax check: "+response.name+" - "+response.emailBN+" - "+response.phone+" - "+response.symtom);
                                //     }
                                // });

                                success: function(response) {
                                    if (response.status == 0) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Lỗi rồi!',
                                            text: ' ' + response.output
                                        });
                                    } else {
                                        var appointmentID = response.appointmentID;
                                        var book_date = response.date;
                                        var bs_name = response.name;
                                        var bs_phone = response.phone;
                                        var emailBN = response.emailBN;
                                        var nameBN = response.nameBN;
                                        console.log("EMAIL BN: " + emailBN + ", id: " + appointmentID + ", date: " + book_date);
                                        Swal.fire({
                                            position: 'center',
                                            icon: 'success',
                                            title: 'Đặt lịch thành công! Mã lịch hẹn là ' + appointmentID + '. Chúng tôi đang gửi mail về lịch hẹn! Xin đợi trong vài giây!',
                                            showCancelButton: false,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'OK',
                                            timer: 2000
                                        });
                                        $.ajax({
                                            url: "./functions/sendmail_book.php",
                                            method: "POST",
                                            dataType: "json",
                                            data: {
                                                appointmentID: appointmentID,
                                                book_date: book_date,
                                                bs_name: bs_name,
                                                bs_phone: bs_phone,
                                                email: emailBN,
                                                nameBN: nameBN
                                            },
                                            success: function(data) {
                                                if (data.status == 0) {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Lỗi rồi!',
                                                        text: ' ' + data.message + 'Bạn có thể check thông tin lịch hẹn tại trang Lịch hẹn với mã lịch mà chúng tôi đã cung cấp - '+appointmentID+' <a href="/nln_test/frontend/forms/QA/seach_lich_hen.php">tại đây</a> ! '
                                                    });
                                                } else {
                                                    Swal.fire({
                                                        title: 'Đặt lịch thành công',
                                                        text: "Thông tin lịch khám đã được gửi tới email của bạn. Bác sĩ sẽ liên hệ với bạn sau!!",
                                                        icon: 'success',
                                                        showCancelButton: false,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'OK',
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            location.href = "/nln_test/index.php";
                                                        }
                                                    })
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi rồi!',
                                text: 'Hãy kiểm tra lại các thông tin đã nhập!'
                            });
                        }

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi rồi!',
                            text: 'Thời gian chọn bị trùng hoặc không hợp lệ!'
                        });
                    }
                }
            });

        });
    </script>
</body>

</html>