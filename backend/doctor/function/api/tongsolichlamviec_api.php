<?php

include_once(__DIR__."./../../../../config/dbconnect.php");

$bsID = $_POST['bsID'];

$sql = "SELECT COUNT(*) AS TOTAL FROM lich_lam_viec WHERE idBS=$bsID";
$rs = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($rs);

$data = json_encode($row);

echo $data;

?>