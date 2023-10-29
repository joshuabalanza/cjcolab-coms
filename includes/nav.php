

<!-- Start Navigation -->
<nav class="navbar navbar-expand-sm navbar-light pl-5 fixed-top">
   <a href="index.php" class="navbar-brand">COMS</a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation">
   <span class="navbar-toggler-icon"></span>
   </button>
   <div id="navigation" class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav pl-5">
         <?php
            if (!isset($_SESSION['uid'])) {
                // Display these links when no one is logged in
                echo '<li class="nav-item">';
                echo '<a href="index.php" class="nav-link">Home</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="index.php" class="nav-link">About</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="index.php" class="nav-link">Contact</a>';
                echo '</li>';
            } elseif (isset($_SESSION['utype']) && $_SESSION['utype'] == 'Owner') {
                // Display these links for Owner type users
                echo '<li class="nav-item">';
                echo '<a href="index.php" class="nav-link">Home</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="dashboard.php" class="nav-link">Dashboard</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="concourses.php" class="nav-link">Concourses</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="tenants.php" class="nav-link">Tenants</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="reservations.php" class="nav-link">Reservations</a>';
                echo '</li>';

            } elseif (isset($_SESSION['utype']) && $_SESSION['utype'] == 'Tenant') {
                // Display these links for Tenant type users
                echo '<li class="nav-item">';
                echo '<a href="index.php" class="nav-link">Home</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="concourses.php" class="nav-link">Concourses</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="bills.php" class="nav-link">Bills</a>';
                echo '</li>';
            }
         ?>
         
         <?php
            if (isset($_SESSION['uid'])) {
                echo '<li class="nav-item dropdown ml-auto">';
                echo '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                if (isset($_SESSION['uimage']) && !empty($_SESSION['uimage'])) {
                    echo '<img src="' . $_SESSION['uimage'] . '" class="user-image" alt="' . $_SESSION['uname'] . '">';
                } else {
                    echo 'Hi, ' . $_SESSION['uname'] . ' (' . $_SESSION['utype'] . ')';
                }
                echo '</a>';
                echo '<div class="dropdown-menu" aria-labelledby="userDropdown">';
                echo '<a class="dropdown-item" href="profile.php">
                            <i class="fa-regular fa-user"></i>
                            Account</a>';
                echo '<a class="dropdown-item" href="logout.php">
                            <i class="fa-solid fa-power-off"></i>Logout</a>';
                echo '</div>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="notifications.php" class="nav-link"><i class="fa-solid fa-bell"></i></a>';
                echo '</li>';
            } else {
                echo '<li class="nav-item">';
                echo '<a href="login.php" class="nav-link">Login</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="register.php" class="nav-link">Register</a>';
                echo '</li>';
            }
         ?>
      </ul>
   </div>
</nav>
<!-- End Navigation -->
