<div class="sideMenu">
    <div class="sidebar">
        <ul class="navbar-nav">
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/doctor/index.php" class="nav-link">
                    <i class="bi bi-clipboard-data"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/doctor/function/profile/index.php" class="nav-link">
                    <i class="bi bi-tags"></i>
                    <span class="text">Profile</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/doctor/function/lichlamviec/index.php" class="nav-link">
                    <i class="bi bi-briefcase"></i>
                    <span class="text">Lịch làm việc</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="/nln_test/backend/doctor/function/appointment/index.php" class="nav-link">
                    <i class="bi bi-clipboard-data"></i>
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
            </li>

        </ul>
    </div>
</div>