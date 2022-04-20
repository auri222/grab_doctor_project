<div class="sideMenu">
    <div class="sidebar">
        <ul class="navbar-nav">
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/admin/index.php" class="nav-link">
                    <i class="bi bi-clipboard-data"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/admin/functions/profile/index.php" class="nav-link">
                    <i class="bi bi-tags"></i>
                    <span class="text">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/admin/functions/account/index.php" class="nav-link">
                    <i class="bi bi-tags"></i>
                    <span class="text">Quản lý tài khoản</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/admin/functions/doctor/index.php" class="nav-link">
                    <i class="bi bi-briefcase"></i>
                    <span class="text">Quản lý bác sĩ</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/admin/functions/appointment/index.php" class="nav-link">
                    <i class="bi bi-clipboard-data"></i>
                    <span class="text">Quản lý lịch hẹn</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/admin/functions/contact/index.php" class="nav-link">
                    <i class="bi bi-clipboard-data"></i>
                    <?php
                    $sql_contact = "SELECT COUNT(*) AS TOTAL from lien_he WHERE is_checked=0";
                    $rs_contact = mysqli_query($conn, $sql_contact);
                    $row_contact = mysqli_fetch_assoc($rs_contact);
                    ?>
                    <span class="text">Quản lý thông tin liên hệ &nbsp;
                        <?php
                        if ($row_contact['TOTAL'] > 0) : ?>
                            <span class="badge badge-danger"><?= $row_contact['TOTAL'] ?></span>
                        <?php endif; ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/admin/functions/time/index.php" class="nav-link">
                    <i class="bi bi-clipboard-data"></i>
                    <span class="text">Quản lý khung giờ</span>
                </a>
            </li>
        </ul>
    </div>
</div>