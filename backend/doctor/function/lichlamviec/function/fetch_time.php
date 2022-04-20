<?php
include_once(__DIR__.'./../../../../../config/dbconnect.php');

if(isset($_POST['sessionID'])){
    $output = '';
    $sessionID = $_POST['sessionID'];
    $sql = "SELECT * FROM khung_gio WHERE id_buoi='$sessionID' ";
    $rs = mysqli_query($conn, $sql);
    $output = '<option selected disabled>-- Chọn giờ --</option>';
    while($row = mysqli_fetch_assoc($rs)){
        $output .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }
    echo $output;
}

?>