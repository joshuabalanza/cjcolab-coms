<style>
    .navbar-brand span {
        color: #9b593c;
        font-size: 30px;
        font-weight: bold;
    }
</style>
<!-- Start Navigation -->
<nav class="navbar navbar-expand-sm navbar-light pl-5 fixed-top" style="background-color: #ffffff;">
    <a href="index.php" class="navbar-brand">
        <img src="assets/images/Logo-9b593c.png" alt="Logo" width="50" height="40" class="d-inline-block align-text-top">
        <span>COMS</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div id="navigation" class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav pl-5">
            <!-- <li class="nav-item">
                <a href="index.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
                <a href="index.php" class="nav-link">About</a>
            </li>
            <li class="nav-item">
                <a href="index.php" class="nav-link">Concourses</a>
            </li>
            <li class="nav-item">
                <a href="index.php" class="nav-link">Contact</a>
            </li> -->

            <?php
            if (isset($_SESSION['aid'])) {
                // User is logged in
                echo '<li class="nav-item dropdown">';
                echo '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                // Check if the user has uploaded an image
                if (isset($_SESSION['aimage']) && !empty($_SESSION['aimage'])) {
                    echo '<img src="' . $_SESSION['aimage'] . '" class="user-image" alt="' . $_SESSION['aname'] . '">';
                } else {
                    echo 'Hi, ' . $_SESSION['aname'] . '';
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
            } else {
                // User is not logged in
                echo '<li class="nav-item">';
                echo '<a href="index.php" class="nav-link">Login</a>';
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
