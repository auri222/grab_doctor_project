<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <button class="navbar-toggler sideMenuToggler" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="#">Grab Doctor</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown mx-2">
                <?php
                $id = $_SESSION['id'];
                $query_avatar = "SELECT avatar FROM taikhoan WHERE id=$id";
                $rs_avatar = mysqli_query($conn, $query_avatar);
                $avatar = mysqli_fetch_assoc($rs_avatar);
                if(!empty($avatar['avatar'])){
                ?>
                <img src="/nln_test/assets/img/upload/avatar/<?= $avatar['avatar'] ?>" class="rounded-circle" width="40px" height="40px" alt="avatar">
                <?php }?>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="#">
                    <?= $_SESSION['username']; ?>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="/nln_test/backend/doctor/function/contact/frm_lien_he.php">
                    Góp ý<i class="bi bi-question-circle" style="font-size: 16px;"></i>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" href="/nln_test/frontend/forms/login/logout.php">
                    Đăng xuất<i class="bi bi-box-arrow-right " style="font-size: 16px;"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>