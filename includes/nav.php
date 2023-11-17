<style>
    .transparent-nav {
        background-color: transparent !important; /* Set the background color to transparent */
        box-shadow: none !important; /* Remove any box shadow */
    }
    .coms-text {
        color: white !important;
        opacity: 80%; /* Set the text color to white */
    }
        /* Custom class for white text color */
    .nav-link{
        color: white !important;
    }

    /* Hover effect for navigation links */
    .nav-link:hover{
        background-color: rgba(255, 255, 255, 0.3);
        color: #eeeeee !important;
        transition: background-color 0.3s ease;
    }
    .navbar-brand span {
        color: #9b593c;
        font-size: 30px;
        font-weight: bold;
    }

    /* Additional style for navigation bar when user is logged in as owner or tenant */
    <?php if (isset($_SESSION['uid']) && (($_SESSION['utype'] == 'Owner') || ($_SESSION['utype'] == 'Tenant'))) : ?>
        .navbar {
            background-color: #ffffff; /* Set your desired background color */
        }

        .navbar-nav .nav-link {
            color: #9b593c !important; /* Set your desired text color */
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.3);
            color: #9b593c !important;
        }
    <?php endif; ?>
</style>
<!-- Start Navigation -->
<nav class="navbar navbar-expand-sm navbar-light pl-5 fixed-top <?php echo isset($_SESSION['uid']) ? '' : 'transparent-nav'; ?>">
    <a href="index.php" class="navbar-brand">
            <img src="assets/images/Logo-9b593c.png" alt="Logo" width="50" height="40" class="d-inline-block align-text-top">
            <span>COMS</span>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
   <div id="navigation" class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav pl-5">
         <?php
            if (!isset($_SESSION['uid'])) {
                // Display these links when no one is logged in
                $linkClass = isset($_SESSION['uid']) ? '' : 'nav-link-white'; // Add a custom class for white color

                echo '<li class="nav-item">';
                echo '<a href="index.php" class="nav-link ' . $linkClass . '">Home</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="index.php" class="nav-link ' . $linkClass . '">About</a>';
                echo '</li>';
                echo '<li class="nav-item">';
                echo '<a href="index.php" class="nav-link ' . $linkClass . '">Contact</a>';
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

       // include('get_notification_count.php');

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
           My Profile</a>';
       echo '<a class="dropdown-item" href="transactions.php">
           <i class="fa-solid fa-file-lines"></i> Transactions</a>';
       echo '<a class="dropdown-item" href="#" onclick="event.preventDefault(); showLogoutModal();">
           <i class="fa-solid fa-power-off"></i> Logout
       </a>
       ';
       echo '</div>';
       echo '</li>';

       // *******************
       // NOTIFICATIONS
       // *******************

       include('get_notification_count.php');
       echo '<li class="nav-item">';
       echo '<a href="notifications.php" class="nav-link"><i class="fa-solid fa-bell fa-xl" id="bell-count"></i></a>';
       if ($notificationCount > 0) {
           echo '<div class="notification-circle">';

           echo '<span id="notification-indicator" class="notification-indicator">' . $notificationCount . '</span>';
       }
       echo '</div>';
       echo '</li>';


   // echo '<li class="nav-item">';
   // echo '<a href="notifications.php" class="nav-link "><i class="fa-solid fa-bell" id="bell-count"></i></a>';

   // // Check if there are unread notifications
   // if ($notificationCount > 0) {
   //     echo '<div class="notification-indicator">' . $notificationCount . '</div>'; // Display the count
   // }

   // echo '</li>';


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
      
<!-- Add the modal HTML code at the end of your page -->
<div class="modal" tabindex="-1" role="dialog" id="logoutModal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Logout Confirmation</h5>
                <button type="button" class="close" aria-label="Close" onclick="hideModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to logout?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="hideModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="logout()">Logout</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showLogoutModal() {
        // Display the modal
        document.getElementById('logoutModal').style.display = 'block';
        document.getElementById('modalOverlay').style.display = 'block';
    }

    function hideModal() {
        // Hide the modal
        document.getElementById('logoutModal').style.display = 'none';
        document.getElementById('modalOverlay').style.display = 'none';
    }

    // Add a function to handle the actual logout action
    function logout() {
        // Add your logout logic here
        // For example, you can redirect the user to the logout page
        window.location.href = 'logout.php';
    }
</script>

   </div>
</nav>
<!-- End Navigation -->

