<?php
session_start();
//Đăng xuất
if(isset($_SESSION['id'])){
    session_destroy();
    unset($_SESSION['id']);
    unset($_SESSION['email']);
    unset($_SESSION['username']);
    unset($_SESSION['authority']);
    unset($_SESSION['phone']);
    if(isset($_SESSION['warning'])){
        unset($_SESSION['warning']);
    }
    echo '<script> location.href = "/nln_test/index.php"; </script>';
}
else{
    echo "Lỗi rồi";
}

?>