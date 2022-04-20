<?php

include_once(__DIR__."./../../../../config/dbconnect.php");

$idBS = $_GET['bsID'];

$sql = "SELECT id, idBS, nameBN, symtom, start_time, end_time, date FROM lich_hen WHERE idBS=$idBS AND is_checked=1";

$rs = mysqli_query($conn, $sql);

$ds_lh = array();

while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
    $ds_lh[] = array(
        "id"            =>  $row["id"],
        "title"        => $row["start_time"].' - '.$row["end_time"].' : '.$row["nameBN"].' - '.$row['symtom'],
        "start_time"    =>  $row["date"].' '.$row["start_time"],
        "end_time"      =>  $row["date"].' '.$row["end_time"],
        "date"      => $row["date"]
    );
}

echo json_encode($ds_lh);

?>