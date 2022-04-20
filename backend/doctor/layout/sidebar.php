<div id="sidebar-container" class="bg-main">
    <div class="logo">
        <h4 class="text-light font-weight-bold">Grab Doctor</h4>
    </div>
    <div class="menu">
        <a href="/nln_test/backend/doctor/index.php" class="d-block text-light p-3">
            <i class="bi bi-clipboard-data mr-2"></i>
            Dashboard</a>
        <a href="/nln_test/backend/doctor/function/profile/index.php" class="d-block text-light p-3">
            <i class="icon ion-md-contact mr-2"></i>
            Profile</a>
        <a href="/nln_test/backend/doctor/function/appointment/index.php" class="d-block text-light p-3">
            <i class="bi bi-clipboard-data mr-2"></i>
            <?php
                    $id = $_SESSION['id'];
                    $sql_appointment_total = "  SELECT COUNT(*) AS Total  
                                                FROM lich_hen lh
                                                JOIN bacsi bs ON bs.id = lh.idBS
                                                JOIN taikhoan tk ON bs.idTK = tk.id
                                                WHERE tk.id = $id AND lh.is_checked = 0";
                    $rs_appointment_total = mysqli_query($conn, $sql_appointment_total);
                    $row_appointment_total = mysqli_fetch_assoc($rs_appointment_total);
                    ?>
                    <span class="text">Lịch hẹn 
                        <?php 
                        if($row_appointment_total['Total'] > 0):

                        ?>
                        <span class="badge badge-danger"><?= $row_appointment_total['Total'] ?></span>
                        <?php endif; ?>
                    </span>
            </a>
        
        <a href="/nln_test/backend/doctor/function/lichlamviec/index.php" class="d-block text-light p-3">
            <i class="bi bi-clipboard-data mr-2"></i>
            Lịch làm việc</a>
    </div>
</div>