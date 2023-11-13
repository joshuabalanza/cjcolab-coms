<style>
    .custom-sidebar .nav-link {
        color: white !important;
    }

    .custom-sidebar .nav-link:hover {
        color: #ffc107 !important;
    }
</style>
<nav class="col-sm-2 sidebar py-5 custom-sidebar" style="background-color: #9b593c;">
    <div class="sidebar-sticky">
        <ul class="nav nav-pills flex-column mb-auto">
            <!-- DASHBOARD -->
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link">
                    <i class="fas fa-table"></i>
                    Dashboard
                </a>
            </li>

            <!-- USERS -->
            <li class="nav-item">
                <a class="nav-link" href="users.php">
                    <i class="fas fa-users"></i>
                    Users
                </a>
            </li>

            <!-- CONCOURSE -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-concourse" aria-controls="submenu-concourse">
                    <i class="fas fa-map-marked-alt"></i>
                    Concourse
                </a>
                <div id="submenu-concourse" class="submenu collapse sidebar-collapse">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="maps.php">Maps</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="spaces.php">Spaces</a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- VERIFICATIONS -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-verifications" aria-controls="submenu-verifications">
                    <i class="fa-solid fa-file-circle-check"></i>
                    Verifications
                </a>
                <div id="submenu-verifications" class="submenu collapse sidebar-collapse">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="user_verification_transactions.php">User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="concourse_verify.php">Concourse</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>

