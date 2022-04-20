<?php
include_once(__DIR__.'./../../../config/dbconnect.php');

$idBuoi = $_POST['idBuoi'];
$idBS = $_POST['idBS'];
$idThu = $_POST['idthu'];
$sql = "	SELECT k.id, k.name AS TENKHUNGGIO
            FROM lich_lam_viec llv
            JOIN khung_gio k ON k.id = llv.id_khung_gio
            WHERE llv.idBS = $idBS AND llv.id_buoi = $idBuoi AND llv.isAvailable=0 AND llv.id_thu = $idThu";
$rs = mysqli_query($conn, $sql);
$output = '<option disabled selected>-- Chọn khung giờ --</option>';
while($row = mysqli_fetch_assoc($rs)){
    $output .= '<option value="'.$row['id'].'"> '.$row['TENKHUNGGIO'].' </option>';
}
echo $output;

?>