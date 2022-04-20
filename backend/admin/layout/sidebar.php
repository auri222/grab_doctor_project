<div id="sidebar-container" class="bg-main">
    <div class="logo">
        <h4 class="text-light font-weight-bold">Grab Doctor</h4>
    </div>
    <div class="menu">
        <a href="/nln_test/backend/admin/index.php" class="d-block text-light p-3">
            <i class="bi bi-clipboard-data mr-2"></i>
            Dashboard</a>
        <a href="/nln_test/backend/admin/functions/profile/index.php" class="d-block text-light p-3">
            <i class="icon ion-md-contact mr-2"></i>
            Profile</a>
        <a href="/nln_test/backend/admin/functions/account/index.php" class="d-block text-light p-3">
            <i class="icon ion-md-list-box mr-2"></i>
            Quản lý tài khoản</a>
        <a href="/nln_test/backend/admin/functions/doctor/index.php" class="d-block text-light p-3">
            <i class="bi bi-briefcase mr-2"></i>
            Quản lý bác sĩ</a>
        <a href="/nln_test/backend/admin/functions/appointment/index.php" class="d-block text-light p-3">
            <i class="bi bi-clipboard-data mr-2"></i>
            Quản lý lịch hẹn</a>
        <a href="/nln_test/backend/admin/functions/contact/index.php" class="d-block text-light p-3">
            <i class="icon ion-md-text mr-2"></i>
            <?php
            $sql_contact = "SELECT COUNT(*) AS TOTAL from lien_he WHERE is_checked=0";
            $rs_contact = mysqli_query($conn, $sql_contact);
            $row_contact = mysqli_fetch_assoc($rs_contact);
            ?>
            Quản lý thông tin liên hệ &nbsp;
            <?php
            if ($row_contact['TOTAL'] > 0) : ?>
                <span class="badge badge-danger"><?= $row_contact['TOTAL'] ?></span>
            <?php endif; ?></a>
            <a href="/nln_test/backend/admin/functions/time/index.php" class="d-block text-light p-3">
            <i class="bi bi-clipboard-data mr-2"></i>
            Quản lý lịch làm việc</a>
    </div>
</div>