<?php

include_once(__DIR__.'./../../../../../config/dbconnect.php');

$id = $_POST['lhID'];

$output = '';

$sql = "	SELECT lh.id as LHID, tk.name as nameBS, tk.phone as phoneBS, tk.email as emailBS ,
            nameBN, genderBN, ageBN, phoneBN, emailBN, symtom, start_time, end_time, date, lh.update_time, is_checked
            FROM lich_hen lh
            JOIN bacsi bs ON bs.id = lh.idBS
            JOIN chuyenkhoa ck ON ck.id = bs.idCK
            JOIN taikhoan tk ON bs.idTK = tk.id
            WHERE lh.id = $id";
$rs = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($rs);

$output .= '
<table class="table table-striped">
<tr>
    <th scope="row">Tên bác sĩ:</th>
    <td>'
    .$row["nameBS"].
    '<br/>('.$row["phoneBS"].')<br/>('.$row["emailBS"].')
    
    </td>
</tr>
<tr>
    <th scope="row">Tên bệnh nhân:</th>
    <td>'
    .$row["nameBN"].
    '<br/>('.$row["phoneBN"].')<br/>('.$row["emailBN"].')
    
    </td>
</tr>
<tr>
    <th scope="row">Tuổi:</th>
    <td>'.$row["ageBN"].'</td>
</tr>
<tr>
    <th scope="row">Giới tính:</th>
    <td>'.$row["genderBN"].'</td>
</tr>
<tr>
    <th scope="row">Ngày đăng ký khám:</th>
    <td>'.date("d/m/Y", strtotime($row["date"])).'</td>
</tr>
<tr>
    <th scope="row">Giờ: </th>
    <td>'.$row['start_time'].' - '.$row['end_time'].'</td>
</tr>
<tr>
    <th scope="row">Triệu chứng</th>
    <td>'.$row["symtom"].'</td>
</tr>
<tr>
    <th scope="row">Ngày tạo:</th>
    <td>'.date("H:i:s d/m/Y", strtotime($row["update_time"])).'</td>
</tr>
<tr>
    <th scope="row">Trạng thái:</th>
    <td>'.(($row["is_checked"]==0)?"Chưa duyệt":"Đã duyệt").'</td>
</tr>
</table>
';

echo $output;

?>