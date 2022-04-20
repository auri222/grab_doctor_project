<?php

include_once(__DIR__.'./../../../../../config/dbconnect.php');

$id = $_POST['bsID'];

$output = '';

$sql = "	SELECT bs.id AS IDBS, tk.name AS nameBS, tk.dob, tk.gender,
            tk.email, tk.phone ,bs.address, ck.name AS SPEC, 
            bs.namkinhnghiem AS EX
            , work_at, chucdanh
            FROM bacsi bs
            JOIN taikhoan tk ON tk.id = bs.idTK
            JOIN chuyenkhoa ck ON ck.id = bs.idCK
            WHERE bs.id=$id";
$rs = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($rs);

$output .= '
<table class="table table-striped">
<tr>
    <th scope="row">Tên bác sĩ:</th>
    <td>'.$row["nameBS"].'</td>
</tr>
<tr>
    <th scope="row">Chuyên khoa:</th>
    <td>'.$row["SPEC"].'</td>
</tr>
<tr>
    <th scope="row">Ngày sinh:</th>
    <td>'.date("d/m/Y" ,strtotime($row["dob"])).'</td>
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
    <th scope="row">Địa chỉ phòng khám:</th>
    <td>'.$row["address"].'</td>
</tr>
<tr>
    <th scope="row">Năm kinh nghiệm:</th>
    <td>'.$row["EX"].'</td>
</tr>
<tr>
    <th scope="row">Làm tại bệnh viện:</th>
    <td>'.$row["work_at"].'</td>
</tr>
<tr>
    <th scope="row">Chức danh:</th>
    <td>'.$row["chucdanh"].'</td>
</tr>

</table>
';

echo $output;

?>