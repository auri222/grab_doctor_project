<?php

include_once(__DIR__."./../../../../config/dbconnect.php");

$bsID = $_POST['bsID'];

$sql = "SELECT COUNT(*) AS TOTAL FROM lich_hen WHERE idBS=$bsID";
$rs = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($rs);

if(empty($bsID)){
    $count =0;
    $data = json_encode(array("TOTAL" => $count));
}else{
    
    $data = json_encode($row);
}


echo $data;

?>