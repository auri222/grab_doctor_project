<?php

include_once(__DIR__."./../../../config/dbconnect.php");

$sql = "SELECT COUNT(*) AS TOTAL FROM taikhoan WHERE NOT idPQ=1";
$rs = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($rs);

$data = json_encode($row);

echo $data;

?>