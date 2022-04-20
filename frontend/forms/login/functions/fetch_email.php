<?php
include_once(__DIR__."./../../../../config/dbconnect.php");
$status = '';

$message = '';
if(isset($_POST['email'])){ 

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT * FROM taikhoan WHERE email='$email' ";

    $rs = mysqli_query($conn, $sql);

    $num_rows = mysqli_num_rows($rs);
    //Chắc chắn chỉ có 1 tài khoản
    if($num_rows == 1){
        $status = 1;

        $message = 'Email hợp lệ!';
    }
    else{
        $status = 0;

        $message = 'Email không tồn tại!';
    }

}
else{
    $status = 0;

    $message = "Lỗi xử lý";
}

$json = json_encode(array('status' => $status, 'message' => $message));

echo $json;
?>