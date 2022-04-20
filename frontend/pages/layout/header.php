<header class="header bg-white">
    <div class="container px-0 px-lg-3">
        <nav class="navbar navbar-expand-lg navbar-light py-3 px-lg-0">
            <a class="navbar-brand" href="/nln_test/index.php">
                <span class="font-weight-bold text-uppercase text-dark text-center">Grab doctor</span>
            </a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <!-- Link--><a class="nav-link" href="/nln_test/index.php">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <!-- Link--><a class="nav-link" href="/nln_test/frontend/forms/QA/lienhe.php">Liên hệ</a>
                    </li>
                    <li class="nav-item">
                        <!-- Link--><a class="nav-link" href="/nln_test/frontend/pages/doctor.php">Bác sĩ</a>
                    </li>
                    <li class="nav-item">
                        <!-- Link--><a class="nav-link" href="/nln_test/frontend/forms/QA/seach_lich_hen.php">Lịch hẹn</a>
                    </li>

                    <li class="nav-item" <?= $notLogin ?>>
                        <div class="dropdown">
                            <a class="nav-link " href="/nln_test/frontend/forms/login/login.php" role="button">
                                <i class="fas fa-user-alt mr-1 text-gray"></i>
                                Đăng nhập
                            </a>
                        </div>
                    </li>
                    <li class="nav-item" <?= $notLogin ?>>
                        <!-- Link--><a class="nav-link" href="/nln_test/frontend/forms/login/signup.php">Đăng ký</a>
                    </li>


                    <li class="nav-item dropdown" <?= $Login ?>>
                            
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            
                            
                        <?php
                                
                                if( !empty($_SESSION['id'])){
                                    $id = $_SESSION['id'];
                                    $query_avatar = "SELECT avatar FROM taikhoan WHERE id=$id";
                                $rs_avatar = mysqli_query($conn, $query_avatar);
                                $avatar = mysqli_fetch_assoc($rs_avatar);
                                echo '<img src="/nln_test/assets/img/upload/avatar/'.$avatar["avatar"].'" class="rounded-circle" width="30px" height="30px" alt="avatar">';
                                }
                                
                            ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/nln_test/frontend/pages/user_profile.php">Profile</a>
                            <a class="dropdown-item" href="/nln_test/frontend/forms/login/logout.php">Đăng xuất</a>

                        </div>
                    </li>
                </ul>

            </div>
        </nav>
    </div>
</header>