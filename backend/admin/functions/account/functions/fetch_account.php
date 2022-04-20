<?php

include_once(__DIR__.'./../../../../../config/dbconnect.php');

$id = $_POST['tkID'];

$output = '';

$sql = "SELECT tk.id ,username, tk.name as TKNAME, dob, gender, avatar, email, phone, p.name as PQNAME, verified, update_time
        FROM taikhoan tk
        JOIN phanquyen p ON tk.idPQ = p.id
        WHERE tk.id = $id";
$rs = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($rs);

$output .= '
<table class="table table-striped">
<tr>
    <th scope="row">ID:</th>
    <td>'.$row["id"].'</td>
</tr>
<tr>
    <th scope="row">Tên đăng nhập:</th>
    <td>'.$row["username"].'</td>
</tr>
<tr>
    <th scope="row">Họ tên: </th>
    <td>'.$row["TKNAME"].'</td>
</tr>
<tr>
    <th scope="row">Ngày sinh:</th>
    <td>'.date("d/m/Y", strtotime($row["dob"])).'</td>
</tr>
<tr>
    <th scope="row">Giới tính:</th>
    <td>'.$row["gender"].'</td>
</tr>
<tr>
    <th scope="row">Số điện thoại:</th>
    <td>'.$row["phone"].'</td>
</tr>
<tr>
    <th scope="row">Email:</th>
    <td>'.$row["email"].'</td>
</tr>
<tr>
    <th scope="row">Loại tài khoản:</th>
    <td>'.$row["PQNAME"].'</td>
</tr>
<tr>
    <th scope="row">Ngày tạo:</th>
    <td>'.date("H:i:s d/m/Y", strtotime($row["update_time"])).'</td>
</tr>
</table>
';

echo $output;

?>