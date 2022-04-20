<?php
session_start();
//Kết nối database
include_once(__DIR__ . './../../config/dbconnect.php');


$notLogin = '';
$Login = '';
//Nếu người dùng chưa đăng nhập
if (!isset($_SESSION['id']) && !isset($_SESSION['type'])) {
    $notLogin = '';
    $Login = 'style="display: none;"';
}
//Có đăng nhập và là người dùng
else if (isset($_SESSION['id'])) {
    if ($_SESSION['authority'] == 3) {
        $notLogin = 'style="display: none;"';
        $Login = '';
    } else {
        $Login = 'style="display: none;"';
    }
}

//Lấy tổng số dòng để phân trang
$sql_row_acc_count = "SELECT COUNT(*) as totalRecord
                    FROM taikhoan tk
                    JOIN bacsi bs ON tk.id = bs.idTK
                    WHERE tk.idPQ = 2 
                    AND (SELECT COUNT(*) FROM lich_lam_viec llv
                    WHERE llv.idBS = bs.id) > 0";
$rs_row_acc_count = mysqli_query($conn, $sql_row_acc_count);
$row_acc_count = mysqli_fetch_assoc($rs_row_acc_count);

//Tạo biến giữ tổng số dòng
$TOTAL_COUNT_RECORD = $row_acc_count['totalRecord'];

//Số dòng muốn hiển thị
$RECORD_PER_PAGE = 3;

//Tổng số trang hiển thị
$TOTAL_PAGES = ceil($TOTAL_COUNT_RECORD / $RECORD_PER_PAGE);

//Lấy trang hiện tại
$PAGE = isset($_GET['page']) ? $_GET['page'] : 1;

//Tính offset
$OFFSET = ($PAGE - 1) * $RECORD_PER_PAGE;
//Lấy thông tin bác sĩ
$sql_doc = "SELECT bs.id ,tk.name AS HOTEN, tk.dob, ck.name AS TENCHUYENKHOA, bs.namkinhnghiem, bs.doctor_img, bs.address
            FROM bacsi bs
            JOIN chuyenkhoa ck ON bs.idCK = ck.id
            JOIN taikhoan tk ON bs.idTK = tk.id
            JOIN lich_lam_viec llv ON llv.idBS = bs.id
            GROUP BY tk.name
            ORDER BY tk.id asc
            LIMIT $OFFSET, $RECORD_PER_PAGE";
$rs_doc = mysqli_query($conn, $sql_doc);
$doctors = [];
while ($row_docs = mysqli_fetch_array($rs_doc, MYSQLI_ASSOC)) {
    $doctors[] = array(
        'bs_id'     => $row_docs['id'],
        'bs_ten'    => $row_docs['HOTEN'],
        'bs_dob'    => $row_docs['dob'],
        'bs_ck' => $row_docs['TENCHUYENKHOA'],
        'bs_namkinhnghiem' => $row_docs['namkinhnghiem'],
        'bs_img'    => $row_docs['doctor_img'],
        'address'    => $row_docs['address']
    );
}

//Lấy tỉnh cần tìm
$ds_province = array();
$sql_addr = "SELECT bs.address AS DC
            FROM bacsi bs
            JOIN chuyenkhoa ck ON bs.idCK = ck.id
            JOIN taikhoan tk ON bs.idTK = tk.id";
$rs_addr = mysqli_query($conn, $sql_addr);
$ds_addr = array();
while($row_addr = mysqli_fetch_array($rs_addr, MYSQLI_ASSOC)){
    $ds_addr[] = array(
        "addr"  => $row_addr['DC']
    );
}

foreach($ds_addr as $addr){
    $arr_tmp = explode(",", $addr['addr']);
    $last_value = end($arr_tmp);
    if(in_array($last_value, $ds_province) == false){
        array_push($ds_province, $last_value);
    }  
}

//Lấy ds chuyên khoa
$sql_spec = "SELECT * FROM chuyenkhoa";
$rs_spec = mysqli_query($conn, $sql_spec);
$ds_spec = [];
while($row_spec = mysqli_fetch_array($rs_spec, MYSQLI_ASSOC)){
    $ds_spec[] = array(
        "id"    => $row_spec['id'],
        "name"    => $row_spec['name'],
    );
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

        <!-- jumbotron -->
        <section class="jumbotron-profile">
            <div class="jumbotron jumbotron-fluid mb-0">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1 class="display-4">Grab Doctor</h1>
                            <p class="lead">Bạn có thể tìm kiếm thông tin về các bác sĩ ở đây</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- jumbotron end -->


        <section class="doctor-profile">
            <div class="container py-2">
                <div class="row ">
                    <div class="col-lg-3 mt-4">
                        <p class="text-left">TÌM KIẾM THEO TÊN VÀ ĐỊA CHỈ PHÒNG KHÁM</p>
                        <input type="text" class="form-control mb-4" id="search" autocomplete="off">
                        <hr/>
                        <p class="text-left">TÌM KIẾM THEO ĐỊA CHỈ PHÒNG KHÁM</p>
                        <?php 
                        $i = 1;
                        foreach($ds_province as $province): ?>
                            <div class="form-check">
                                <input class="form-check-input province check-filter" type="radio" name="province" id="province<?= $i?>" value="<?= $province?>">
                                <label class="form-check-label">
                                    <?= $province?>
                                </label>
                            </div>
                        <?php 
                        $i++;
                        endforeach?>
                        <hr/>
                        <p class="text-left">TÌM KIẾM THEO TÊN CHUYÊN KHOA</p>
                        <?php
                        foreach($ds_spec as $spec): ?>
                            <div class="form-check">
                                <input class="form-check-input specialist check-filter" type="checkbox" value="<?= $spec['name']?>" >
                                <label class="form-check-label">
                                    <?= $spec['name']?>
                                </label>
                            </div>
                        <?php 
                        endforeach; ?>
                        <hr/>
                        <button type="button" class="btn btn-warning btn-del-filter btn-block">Xóa bộ lọc</button>
                        
                    </div>
                    <div class="col-lg-9" id="search_result">
                    <?php foreach ($doctors as $doc) : ?>
                        <div class="row my-4" style="background-color: #e9ecef;">
                            
                                <div class="col-md-3 pt-3">
                                    <img src="/nln_test/assets/img/upload/doctor_img/<?= $doc['bs_img']?>" class="mx-auto d-block img-fluid" >
                                </div>
                                <div class="col-md-9 pt-3">
                                    <table class="table table-sm table-borderless">
                                        <tbody>

                                            <tr>
                                                <th scope="row">Họ tên bác sĩ</th>
                                                <td> <?= $doc['bs_ten'] ?> </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Ngày sinh</th>
                                                <td> <?= date('d/m/Y',strtotime($doc['bs_dob'])) ?> </td>
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
                                                <th scope="row">Địa chỉ phòng khám</th>
                                                <td> <?= $doc['address'] ?> </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <a href="/nln_test/frontend/pages/doctor_chitiet.php?bs_id=<?= $doc['bs_id']?>" class="btn btn-info">Xem chi tiết</a>    
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            
                        </div>
                        <?php endforeach; ?>
                        <!-- Thanh phân trang -->
                        <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <?php
                                    $first_page = ($PAGE == 1) ? 'disabled' : '';
                                    $last_page = ($PAGE == $TOTAL_PAGES) ? 'disabled' : '';
                                    $next_page = $PAGE + 1;
                                    $previous_page = $PAGE - 1;
                                    ?>
                                    <li class="page-item <?= $first_page ?>">
                                        <a class="page-link" href="?page=<?= $previous_page ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    <?php for ($i = 1; $i <= $TOTAL_PAGES; $i++) : ?>
                                        <?php $this_page = ($PAGE == $i) ? 'active' : ''; ?>
                                        <li class="page-item <?= $this_page ?>"><a class="page-link " href="?page=<?= $i ?>"><?= $i ?></a></li>

                                    <?php endfor; ?>
                                    <li class="page-item <?= $last_page ?>">
                                        <a class="page-link" href="?page=<?= $next_page ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>

                                </ul>
                            </nav>
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
        $(document).ready(function(){

            //Hàm lấy dữ liệu các checkbox bằng class name
            function checkbox_filter(classname){
                var arr = [];
                $('.'+classname+':checked').each(function(){
                    arr.push($(this).val());
                });
                return arr;
            }
            function radio_filter(classname){
                var arr = "";
                $('.'+classname+':checked').each(function(){
                    arr = $(this).val();
                });
                return arr;
            }

            //Hàm lọc dựa trên checkbox
            function search_checkbox(){
                var pivot = "search";
                var specialist = checkbox_filter('specialist');
                var province = radio_filter('province');
                $.ajax({
                    url: "./functions/search_doctor.php",
                    method: "POST",
                    data:{
                        pivot: pivot,
                        specialist: specialist,
                        province: province
                    },
                    success: function(data){
                        $('#search_result').html(data);
                    }
                });
            }

            $('.check-filter').on('click', function(){
                
                //alert(specialist);
                search_checkbox();
            })

            $('.btn-del-filter').on('click', function(){
                $('.check-filter').each(function(){
                    $(this).prop('checked', false);
                })
                location.reload();
            });

            $('#search').keyup(function(){
                var keyword = $(this).val();
                console.log("key: "+keyword);

                if(keyword !== ""){
                    $.ajax({
                        url: "./functions/search_doctor.php",
                        method: "POST",
                        data: {
                            keyword: keyword
                        },
                        success:function(data){
                            $('#search_result').html(data);
                        }
                    });
                }else{
                    location.reload();
                }
            });
        })
    </script>
</body>

</html>