<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Đăng ký</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../../vendors/bootstrap/css/bootstrap.min.css" >

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../../assets/frontend/css/frid_style.css">

</head>

<body style="background-color: #aeeff0;">
    <div class="container-fluid" style="min-height: 100vh;">
        <div class="row" style="min-height: 100vh;">
            <div class="col-12 col-md-2 col-xl-3"></div>
            <div class="col-12 col-md-8 col-xl-6 content">
                <div class="wrapper px-3 pb-4 pt-3 ">
                    <h2 class="title text-center my-4">Form đăng ký thông tin</h2>
                    <form class="fregister" action="frid.php" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Họ và tên</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dob">Ngày sinh</label>
                                <input type="date" class="form-control" name="dob" id="dob">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Giới tính</label> <br>
                            <input type="radio" name="gender" id="male" value="Nam"> Nam
                            <input type="radio" name="gender" id="female" value="Nữ"> Nữ
                        </div>
                        <div class="form-group">
                            <label for="address">Địa chỉ phòng khám</label>
                            <input type="text" class="form-control" name="address" id="address" placeholder="Số nhà, tên đường ...">
                        </div>

                        <?php
                        // Tạo kết nối tới database
                        include_once(__DIR__ . '../../../../dbconnect.php');

                        // Lấy ds tỉnh thành
                        $sql_tinhthanh = "Select * from tinhthanh";
                        $rs_tinhthanh = mysqli_query($conn, $sql_tinhthanh);
                        $ds_tinhthanh = [];
                        while($row_tinhthanh = mysqli_fetch_array($rs_tinhthanh,MYSQLI_ASSOC)){
                            $ds_tinhthanh[] = array (
                                'id'           => $row_tinhthanh['id'],
                                'tentinhthanh' => $row_tinhthanh['tentinhthanh'],
                                'viettat'      => $row_tinhthanh['viettat']
                            );
                        }
                        
                        // Lấy ds chuyên khoa
                        $sql_chuyenkhoa = "select * from chuyenkhoa";
                        $rs_chuyenkhoa = mysqli_query($conn, $sql_chuyenkhoa);
                        $ds_chuyenkhoa = [];
                        while($row_chuyenkhoa = mysqli_fetch_array($rs_chuyenkhoa, MYSQLI_ASSOC)){
                            $ds_chuyenkhoa[] = array (
                                'id'        => $row_chuyenkhoa['id'],
                                'name'      => $row_chuyenkhoa['name']
                            );
                        }

                        ?>

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="city">Tỉnh/Thành phố</label>
                                <select id="city" name="city" class="form-control">
                                    <option selected>Chọn tỉnh/thành phố</option>
                                    <?php foreach($ds_tinhthanh as $tt): ?>
                                        <option value="<?= $tt['id']?>"><?= $tt['tentinhthanh'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Quận/ huyện sẽ hiện theo tỉnh/thành -->
                            <div class="form-group col-md-4">
                                <label for="district">Quận/Huyện</label>
                                <select id="district" name="district" class="form-control">
                                    <option disabled selected>-- Chọn quận/huyện --</option>
                                </select>
                            </div>
                            <!-- Phường/ xã sẽ hiện theo Quận/ huyện -->
                            <div class="form-group col-md-4">
                                <label for="ward">Phường/Xã</label>
                                <select id="ward" name="ward" class="form-control">
                                    <option disabled selected>-- Chọn phường/xã --</option>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="specialist">Chuyên khoa</label>
                            <select id="specialist" name="specialist" class="form-control">
                                <option disabled selected>-- Chọn chuyên khoa --</option>
                                <?php foreach($ds_chuyenkhoa as $ck): ?>
                                    <option value="<?= $ck['id'] ?>"><?= $ck['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <button type="submit" name="submit" class="btn btn-primary form-control my-2">Đăng ký và đi tới bước tiếp theo</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-2 col-xl-3"></div>
        </div>

    </div>
        

    <script src="../../../vendors/jquery/jquery-3.6.0.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
    <script src="../../../vendors/bootstrap/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#city').change(function(){
                var provinceID = $(this).val();
                
                console.log(provinceID);
                $.ajax({
                    url: "district.php",
                    type: "POST",
                    data: { province_id: provinceID},
                    success: function(data){
                        $('#district').html(data);
                    }
                });
            });

            $('#district').change(function(){
                var districtID = $(this).val();
                var provinceID = $('#city').val()
                console.log(districtID);
                console.log(provinceID);
                $.ajax({
                    url: "ward.php",
                    type: "POST",
                    data: {district_id: districtID,
                            province_id: provinceID},
                    success: function(data){
                        $('#ward').html(data);
                    }
                });
            })
        });
    </script>

</body>

</html>