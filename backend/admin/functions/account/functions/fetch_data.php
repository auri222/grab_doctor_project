<?php

include_once (__DIR__.'./../../../../../config/dbconnect.php');

$limit = 3;

$page = 1;

if($_POST['page'] > 1){
    $start = (($_POST['page'] - 1) * $limit);
    $page = $_POST['page'];
}
else{
    $start = 0;
}

$query = ' 	SELECT tk.id, tk.username, tk.avatar, p.name, tk.verified
            FROM taikhoan tk
            JOIN phanquyen p ON p.id = tk.idPQ
';

if($_POST['query'] != ''){
    $query .= 'WHERE tk.username LIKE "%'.str_replace(' ','%', $_POST['query']).'%" ';
}

$query .= 'ORDER BY tk.id ASC';

$filter_query = $query . ' LIMIT '.$start.', '.$limit.'';

$result = mysqli_query($conn, $query);

$total_row = mysqli_num_rows($result); 

$rs = mysqli_query($conn, $filter_query);

$output = '
    <label>Tổng số: '.$total_row.'</label>
    <table class="table table-bordered table-hover">
    <thead class="thead-dark">
        <tr>
            <th>Avatar</th>
            <th>Tên đăng nhập</th>
            <th>Loại tài khoản</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
';

if($total_row>0){
    while($row = mysqli_fetch_assoc($rs)){
        if ($row['verified'] == 1) {
            $verify = '<span class="badge badge-success">Đã xác minh</span>';
        } else {
            $verify = '<span class="badge badge-secondary">Chưa xác minh</span>';
        }
        $output .= '
            <tr>
                <td>
                    <img src="/nln_test/assets/img/upload/avatar/'.$row['avatar'].'" class="mx-auto d-block img-rounded shadow rounded" width="100px" height="100px">
                </td>
                <td class="text-center">
                    '.$row['username'].'
                </td>
                <td class="text-center">'.$row['name'].'</td>
                <td class="text-center">'.$verify.'</td>
                <td class="text-center">
                    <button type="button" class="btn btn-info btn-view" data-tk-id="'.$row['id'].'">Xem</button>
                    <button type="button" class="btn btn-danger btn-del" data-tk-id="'.$row['id'].'">Xóa</button>
                </td>
             </tr>
        ';
    }
    
$output .= '
<tbody>
</table>
<br/>
<div align="center">
    <ul class="pagination justify-content-center">
';

// 7/5 = 2 trang
$total_page = ceil($total_row/$limit);

$previous_page ='';

$next_page = '';

$current_page = '';


if($total_page > 8){ //TP = 10 
if($page < 5){ // trang hiện tại = 4: 1 2 3 4 5 ... 10
    // TP = 5 => 1 2 3 4 5
    for($count = 1; $count<=5; $count++){
        $page_arr[] = $count;
    }
    $page_arr[] = '...';
    $page_arr[] = $total_page;
}
else{ //trang hiện tại = 6 
    $end_litmit = $total_page - 5;
    if($page > $end_litmit){ //EL = 10 - 5 = 5: 1 ... 5 6 7 8 9 10
        //TP = 5 -> EL = 0 
        // CP = 5 
        $page_arr[] = 1;
        $page_arr[] = '...';
        for($count = $end_litmit; $count <= $total_page; $count++){
            $page_arr[] = $count;
        }
    }
    else{ 
        //TP = 60
        //EL = 60 - 5 = 55
        //CP = 6 < EL: 1 ... 5 6 7 ... 60
        $page_arr[] = 1;
        $page_arr[] = '...';
        for($count = $page -1; $count <= $page +1; $count++){
            $page_arr[] = $count;
        }
        $page_arr[] = '...';
        $page_arr[] = $total_page;
    }
}
}
else{ //TP < 4 = 3: 1 2 3
for($count = 1; $count<=$total_page; $count++){
    $page_arr[] = $count;
}
}

for($count = 0; $count < count($page_arr); $count++){
if($page == $page_arr[$count]){
    $current_page .='
        <li class="page-item active">
            <a class="page-link" href="#">
                '.$page_arr[$count].'<span class="sr-only">(current)</span>
            </a>
        </li>
    ';

    $previous_nb = $page_arr[$count] - 1;
    if($previous_nb > 0){
        $previous_page = '
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_nb.'"><i class="bi bi-arrow-left"></i></a>
            </li>
        ';
    }else{
        $previous_page = '
            <li class="page-item disabled">
                <a class="page-link" href="#"><i class="bi bi-arrow-left"></i></a>
            </li>
        ';
    }

    $next_nb = $page_arr[$count] + 1;
    if($next_nb >= $total_page){
        $next_page = '
        <li class="page-item disabled">
            <a class="page-link" href="#"><i class="bi bi-arrow-right"></i></a>
        </li>
        ';
    }else{
        $next_page = '
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page_number="'.$next_nb.'"><i class="bi bi-arrow-right"></i></a>
            </li>
        ';
    }
}
else{
    if($page_arr[$count] == '...'){
        $current_page .= '
        <li class="page-item disabled">
            <a class="page-link" href="#">...</a>
        </li>
        ';
    }
    else{
        $current_page .= '
        <li class="page-ite">
            <a class="page-link" href="javascript:void(0)" data-page_number="'.$page_arr[$count].'">'.$page_arr[$count].'</a>
        </li>
        ';
    }
}
}

$output .= $previous_page.$current_page.$next_page;
}
else{
    $output .= '
    <tr>
        <td colspan="5" align="center">Không tìm thấy tài khoản</td>
    </tr>
    ';
}


echo $output;
