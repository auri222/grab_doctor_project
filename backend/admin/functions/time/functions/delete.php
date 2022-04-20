<?php
include_once(__DIR__."./../../../../../config/dbconnect.php");

$timeID = $_GET['timeID'];

$sql1 = "DELETE FROM lich_lam_viec WHERE id_khung_gio=$timeID";

$sql2 = "DELETE FROM khung_gio WHERE id=$timeID";

if(mysqli_query($conn, $sql1)){
    if(mysqli_query($conn, $sql2)){
        echo '<script> location.href = "/nln_test/backend/admin/functions/time/index.php"; </script>';
        exit();
    }
    echo "Lỗi 2 - xóa cái chính";
}
else{
    echo "Lỗi 1 - xóa bảng con";
}
 



?>
