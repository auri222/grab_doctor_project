<?php
include_once(__DIR__.'../../../../dbconnect.php');
if(isset($_POST['submit'])){
    $adds = '';
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $provinceID = $_POST['city'];
    $districtID = $_POST['district'];
    $wardID = $_POST['ward'];
    $specialistID = $_POST['specialist'];

    //Lấy tên tỉnh
    $sql_tt = "select * from tinhthanh where id='$provinceID'";
    $rs_tt = mysqli_query($conn, $sql_tt);
    $row_tt = mysqli_fetch_array($rs_tt, MYSQLI_ASSOC);
    
    //Lấy tên quận huyện
    $sql_qh = "select * from quanhuyen where id='$districtID'";
    $rs_qh = mysqli_query($conn, $sql_qh);
    $row_qh = mysqli_fetch_array($rs_qh, MYSQLI_ASSOC);

    //Lấy tên phường xã
    $sql_px = "select * from xa where id='$wardID'";
    $rs_px = mysqli_query($conn, $sql_px);
    $row_px = mysqli_fetch_array($rs_px, MYSQLI_ASSOC);
    
    //Ghép chuỗi địa chỉ
    $tt = $row_tt['tentinhthanh'];
    $qh = $row_qh['name'];
    $px = $row_px['name'];
    $adds .= $address.', '.$px.', '.$qh.', '.$tt;

    $sql = "INSERT INTO `profile_doctor`(`name`, `dob`,`gender`, `address`, `id_specialist`) VALUES ('$name','$dob','$gender','$adds','$specialistID')";
    if(mysqli_query($conn,$sql)){
        echo 'Lưu thành công';
    }
    else echo "Lưu thất bại";

}

?>