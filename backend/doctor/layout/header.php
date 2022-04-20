<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <button class="navbar-toggler sideMenuToggler" type="button">
            <span class="navbar-toggler-icon"></span>
        </button>
        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">

                <li class="nav-item dropdown">

                    <?php
                    $idAD = $_SESSION['id'];
                    $sql_ad = "SELECT avatar FROM taikhoan WHERE id=$idAD";
                    $rs_ad = mysqli_query($conn, $sql_ad);
                    $row_ad = mysqli_fetch_assoc($rs_ad);
                    ?>
                    <img src="/nln_test/assets/img/upload/avatar/<?= $row_ad['avatar'] ?>" class="rounded-circle" width="40px" height="40px" alt="avatar">

                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#">
                        <?= $_SESSION['username']; ?>
                    </a>
                </li>
                <li class="nav-item dropdown">
                <a class="nav-link" href="/nln_test/backend/doctor/function/contact/frm_lien_he.php">
                    Góp ý<i class="bi bi-question-circle ml-1" style="font-size: 16px;"></i>
                </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link" href="/nln_test/frontend/forms/login/logout.php">
                        Đăng xuất<i class="bi bi-box-arrow-right ml-1" style="font-size: 16px;"></i>
                    </a>
                </li>

            </ul>
        </div>

    </div>
</nav>