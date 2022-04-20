<?php

include_once(__DIR__ . "./../../../../config/dbconnect.php");

if (isset($_POST['keyword'])) {
    $output = "";
    $search = trim($_POST['keyword']);
    $sql_search = "	SELECT lh.id AS LHID, tk.name AS TENBS, ck.name AS SPEC,lh.nameBN AS TENBN, 
                    lh.symtom, lh.date AS NGAYHEN, lh.start_time, lh.end_time,
                    lh.update_time AS UPTIME, lh.is_checked 
                    FROM lich_hen lh
                    JOIN bacsi bs ON lh.idBS = bs.id
                    JOIN chuyenkhoa ck ON bs.idCK = ck.id
                    JOIN taikhoan tk ON bs.idTK = tk.id
                    WHERE lh.id=$search ";
    $rs_search = mysqli_query($conn, $sql_search);

    $num_row = mysqli_num_rows($rs_search);

    if ($num_row > 0) {
        while ($row_search = mysqli_fetch_assoc($rs_search)) {
            $output .= '
            
            <table class="table table-striped">
                            <tbody>

                                <tr>
                                    <th scope="row">Mã lịch hẹn</th>
                                    <td> '.$row_search["LHID"].' </td>
                                </tr>
                                <tr>
                                    <th scope="row">Họ tên bác sĩ</th>
                                    <td> 
                                        '. $row_search["TENBS"] .'<br/>
                                        ('.$row_search["SPEC"].')
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Tên người hẹn</th>
                                    <td> '.$row_search["TENBN"].' </td>
                                </tr>
                                <tr>
                                    <th scope="row">Triệu chứng</th>
                                    <td>'.$row_search["symtom"] .'</td>
                                </tr>
                                <tr>
                                    <th scope="row">Ngày hẹn:</th>
                                    <td>'.date("d/m/Y", strtotime($row_search["NGAYHEN"])).' <br/>
                                        ('.date("H:i", strtotime($row_search["start_time"])).' - '.date("H:i", strtotime($row_search["end_time"])).')
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Ngày tạo:</th>
                                    <td> '.date("H:i:s d/m/Y", strtotime($row_search["UPTIME"])).' </td>
                                </tr>
                                <tr>
                                    <th scope="row">Trạng thái:</th>
                                    <td> '.(($row_search["is_checked"]==0)?"Bác sĩ chưa duyệt":"Bác sĩ đã duyệt").' </td>
                                </tr>

                            </tbody>
                        </table>
            
            ';
        }
    } else {
        $output .= '

                <h5 class="text-left">Không tìm thấy lịch hẹn</h5>

        ';
    }
}

echo $output;


?>

