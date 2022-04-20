<?php

include_once(__DIR__ . "./../../../config/dbconnect.php");

$output = "";
//Bộ chọn checkbox có chọn
if(isset($_POST['pivot'])){
    $sql_checkbox = "	SELECT tk.name AS HOTEN, tk.dob, 
                    tk.id AS TKID, bs.id AS BSID, bs.doctor_img,
                    ck.name AS SPEC, bs.namkinhnghiem AS EX, bs.address
                    FROM taikhoan tk
                    JOIN bacsi bs ON bs.idTK = tk.id
                    JOIN chuyenkhoa ck ON bs.idCK = ck.id
                    WHERE tk.idPQ=2";
    if(isset($_POST['specialist'])){
        $specialist = implode(',', $_POST['specialist']);
        $sql_checkbox .= " AND ck.name IN('".$specialist."')"; 
    }
    //Province chỉ có 1 và không phải mảng :>
    if(isset($_POST['province'])){
        $province = $_POST['province'];
        $sql_checkbox .= " AND  bs.address LIKE '%$province%' "; 
    }

    $rs_checkbox = mysqli_query($conn, $sql_checkbox);
    $num_row_checkbox = mysqli_num_rows($rs_checkbox); 
    if($num_row_checkbox>0){
        while($row_checkbox = mysqli_fetch_assoc($rs_checkbox)){
            $output .= '
            
            <div class="row my-4" style="background-color: #e9ecef;">

                <div class="col-md-3 pt-3">
                    <img src="/nln_test/assets/img/upload/doctor_img/'.$row_checkbox['doctor_img'].'" class="mx-auto d-block img-fluid">
                </div>
                <div class="col-md-9 pt-3">
                    <table class="table table-sm table-borderless">
                        <tbody>

                            <tr>
                                <th scope="row">Họ tên bác sĩ</th>
                                <td> '. $row_checkbox['HOTEN'].' </td>
                            </tr>
                            <tr>
                                <th scope="row">Ngày sinh</th>
                                <td> '. date('d/m/Y', strtotime($row_checkbox['dob'])) .'</td>
                            </tr>
                            <tr>
                                <th scope="row">Chuyên khoa</th>
                                <td> '. $row_checkbox['SPEC'] .' </td>
                            </tr>
                            <tr>
                                <th scope="row">Năm kinh nghiệm</th>
                                <td> '. $row_checkbox['EX'] .' </td>
                            </tr>
                            <tr>
                                <th scope="row">Địa chỉ phòng khám</th>
                                <td> '. $row_checkbox['address'] .' </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="/nln_test/frontend/pages/doctor_chitiet.php?bs_id='.$row_checkbox['BSID'] .'" class="btn btn-info">Xem chi tiết</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
            
            ';
        }
    }else{
        $output .= '
        <div class="row py-5 my-5" style="background-color: #e9ecef;">
            <div class="col-md-12 pt-3">
                <h5 class="text-left">Không tìm thấy bác sĩ</h5>
            </div>
        </div>
        ';
    }
}

//Bộ chọn tìm kiếm bằng thanh search
if (isset($_POST['keyword'])) {
    
    $search = trim($_POST['keyword']);
    $sql_search = "	SELECT tk.name AS HOTEN, tk.dob, 
                    tk.id AS TKID, bs.id AS BSID, bs.doctor_img,
                    ck.name AS SPEC, bs.namkinhnghiem AS EX, bs.address  
                    FROM taikhoan tk
                    JOIN bacsi bs ON bs.idTK = tk.id
                    JOIN chuyenkhoa ck ON bs.idCK = ck.id
                    WHERE tk.name LIKE '%$search%' AND idPQ=2 ";
    $rs_search = mysqli_query($conn, $sql_search);

    $num_row = mysqli_num_rows($rs_search);

    if ($num_row > 0) {
        while ($row_search = mysqli_fetch_assoc($rs_search)) {
            $output .= '
            
            <div class="row my-4" style="background-color: #e9ecef;">

                <div class="col-md-3 pt-3">
                    <img src="/nln_test/assets/img/upload/doctor_img/'.$row_search['doctor_img'].'" class="mx-auto d-block img-fluid">
                </div>
                <div class="col-md-9 pt-3">
                    <table class="table table-sm table-borderless">
                        <tbody>

                            <tr>
                                <th scope="row">Họ tên bác sĩ</th>
                                <td> '. $row_search['HOTEN'].' </td>
                            </tr>
                            <tr>
                                <th scope="row">Ngày sinh</th>
                                <td> '. date('d/m/Y', strtotime($row_search['dob'])) .'</td>
                            </tr>
                            <tr>
                                <th scope="row">Chuyên khoa</th>
                                <td> '. $row_search['SPEC'] .' </td>
                            </tr>
                            <tr>
                                <th scope="row">Năm kinh nghiệm</th>
                                <td> '. $row_search['EX'] .' </td>
                            </tr>
                            <tr>
                                <th scope="row">Địa chỉ phòng khám</th>
                                <td> '. $row_search['address'] .' </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="/nln_test/frontend/pages/doctor_chitiet.php?bs_id='.$row_search['BSID'] .'" class="btn btn-info">Xem chi tiết</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
            
            ';
        }
    } else {
        $output .= '
        <div class="row py-5 my-5" style="background-color: #e9ecef;">
            <div class="col-md-12 pt-3">
                <h5 class="text-left">Không tìm thấy bác sĩ</h5>
            </div>
        </div>
        ';
    }
}

echo $output;

?>

