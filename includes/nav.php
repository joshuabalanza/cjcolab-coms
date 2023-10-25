<?php
?>
<!-- Rest of your HTML code -->

<!-- Start Navigation -->
<nav class="navbar navbar-expand-sm navbar-light pl-5 fixed-top">
   <a href="index.php" class="navbar-brand">COMS</a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation">
   <span class="navbar-toggler-icon"></span>
   </button>
   <div id="navigation" class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav pl-5">
         <li class="nav-item">
            <a href="index.php" class="nav-link">Home</a>
         </li>
         <li class="nav-item">
            <a href="index.php" class="nav-link">About</a>
         </li>

         <li class="nav-item">
            <a href="concourses.php" class="nav-link">Concourses</a>
         </li>

         <li class="nav-item">
            <a href="index.php" class="nav-link">Contact</a>
         </li>
         <?php
            if (isset($_SESSION['uid'])) {
                // User is logged in
                echo '<li class="nav-item">';
                echo '<a href="notifications.php" class="nav-link"><i class="fa-solid fa-bell"></i></a>';
                echo '</li>';

                // User Dropdown (Account and Logout)
                echo '<li class="nav-item dropdown ml-auto">'; // Add ml-auto to align right
                echo '<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                // Check if the user has uploaded an image
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
            } else {
                // User is not logged in
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
