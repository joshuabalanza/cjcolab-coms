<!-- ******************** -->
<!-- ***START SESSION**** -->
<!-- ******************** -->
<?php
session_name("user_session");
session_start();
?>

<?php
// if (isset($_SESSION['uid'])) {
//     echo '<script>$("#notificationsModal").modal("show");</script>';
// }
?> 

<!-- ******************** -->
<!-- **** START HTML **** -->
<!-- ******************** -->
<?php
include('includes/header.php');
include('includes/nav.php');
?>
<style>
    .about-wrapper {
        width: 80%; /* Adjust the width as needed */
        margin: 0 auto; /* Center the wrapper horizontally */
    }

    .about-section {
    width: 100%;
    height: 100vh; /* Set height to 100% of the viewport height */
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    position: relative;
    background-color: #9b593c;
    text-align: center;
    }

    .about-section h1 {
        font-family: 'Times New Roman';
        font-size: 50px;
        padding-left: 20px;
        letter-spacing: 2px;
        margin-bottom: 20px; /* Add some space below the heading */
    }

    .about-section .par {
        padding-left: 20px;
        padding-bottom: 25px;
        font-family: Arial;
        letter-spacing: 1.2px;
        line-height: 30px;
    }

    .about-section .cn {
        width: 160px;
        height: 40px;
        background: #ff7200;
        border: none;
        margin-bottom: 10px;
        margin-left: 20px;
        font-size: 18px;
        border-radius: 10px;
        cursor: pointer;
        transition: .4s ease;
    }

    .about-section .cn a {
        text-decoration: none;
        color: #000;
        transition: .3s ease;
    }

    .cn:hover {
        background-color: #fff;
    }

    .about-section span {
        color: #ff7200;
        font-size: 65px;
    }

      .login-section {
         padding: 50px;
      padding: 50px;
      opacity: 0;
      animation: fadeIn 3s forwards;
      }

         /* Keyframes for fadeIn animation */
         @keyframes fadeIn {
            from {
               opacity: 0;
            }
            to {
               opacity: 1;
            }
         }

      .login-section h1 {
         font-family: 'Times New Roman';
         font-size: 50px;
         padding-left: 20px;
         letter-spacing: 2px;
      }
      .card {
         background-color: rgba(255, 255, 255, 0.8); /* Use rgba for opacity */
         transition: background-color 0.3s ease; /* Smooth transition for background color */
      }

      /* Add hover effect for the card */
      .card:hover {
         background-color: rgba(255, 255, 255, 1); /* Change opacity on hover */
      }
      
      .white-container {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: justify;
    width: 80%;
    }

    @media only screen and (max-width: 768px) {
        .white-container {
            width: 90%; /* Adjust the width for smaller screens */
        }
    }

    .rounded-square {
        border-radius: 8px;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }

    .black-text {
        color: black;
    }

    .text-center {
    margin: 0 auto;
    }

    .contact-us {
        margin-top: 20px; /* Adjust the margin as needed */
        cursor: pointer;
        display: inline-block;
    }

    .contact-text {
        color: #fff;
        background-color: #8B4513; /* Brown color */
        padding: 10px 20px;
        border-radius: 5px;
        display: inline-block;
        transition: background-color 0.3s ease;
    }

    .contact-text:hover {
        background-color: #6A3D15; /* Darker brown on hover */
    }

    /* Add animation keyframes */
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-20px);
        }
        60% {
            transform: translateY(-10px);
        }
    }

    /* Apply animation to the contact text */
    .contact-text:hover {
        animation: bounce 1s ease infinite;
    }
    .main-container {
        width: 80%;
        margin: 0 auto;
    }

    .about-container {
        width: 73%;
        height: 100%;
        margin: 20px auto;
        color: black;
        background-color: #fff; /* Set your desired background color */
        padding: 20px; /* Adjust padding as needed */
        border-radius: 8px; /* Add border radius for a rounded look */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow */
    }

    @media only screen and (max-width: 768px) {
        .about-container {
            width: 90%; /* Adjust the width for smaller screens */
        }
    }
</style>
<section class="vh-100" style="background-color: #9b593c;">
    <div class="container-fluid">
        <!-- Login Section -->
        <div class="col-md-8 mx-auto">
            <div class="login-section">
    <div class="card" style="border-radius: 1rem; width: 100%;">
        <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-flex align-items-center justify-content-center">
                <img src="assets/images/Logo-9b593c.png" alt="Login Form" class="img-fluid">
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-6 p-lg-5 text-black">
                    <form>
                        <div class="mb-4">
                            <h1 class="fw-bold mb-0 text-center" style="font-size: 2.5rem;"><br>Concessionaire Operations Monitoring System</h1>
                            <p class="fw-normal mb-0 text-center" style="font-size: 1.5rem;">Modernized Monitoring of Operations</p>
                        </div>
                    </form>
                    <!-- End Project Description -->
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <button class="btn btn-dark btn-lg btn-block" style="background-color: #9b593c;" type="button" onclick="redirectToLogin()">Login</button>
                        <button class="btn btn-dark btn-lg btn-block" style="background-color: #3C7E9B;" type="button" onclick="redirectToSignUp()">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
</section>
<!-- Start Concourses -->
<section id="concourses" class="col-md-10 about-section text-center" style="background-color: #9b593c;">
   <div class="container-fluid">
      <h3 style="margin-top: 15px; text-align: center;">Concourses</h3>
      <div id="concourse-list" class="row" style="width: 80%; margin: 0 auto;">
         <!-- This div will be populated with the fetched data -->
      </div>
   </div>
</section>
<!-- Project Description -->
<section id="about-proj" class="about-container col-md-8 about-section text-center mx-auto">                            
    <p class="fw-normal mb-4 text-justify" style="font-weight:bold;">
        The goal of the project is to provide an online system monitoring operations for business spaces.<br>
        The system helps track payments for electricity, water, and rent bills. It also automatically notifies the owner of contract dates and tenant changes.<br>
        Tenants receive notifications about due dates, especially for contracts. The Concessionaire Operations Monitoring in the Philippines is often either <br>hassle-ridden or outdated,
        according to relevant literature. Many owners are not visible to tenants and lack insights into tenant operations around the business area.
    </p>
</section>
<section>
    <!-- About Us Section -->
<div id="contact-us" class="col-md-10 about-section text-center">
    <div class="white-container" style="width: 80%;">
        <div class="d-flex justify-content-between align-items-center">
            <h1 style="color: black;">The Developers</h1>
            <div class="contact-us">
                <p class="contact-text" id="contact-text">Contact Us</p>
            </div>
        </div>
        <p class="par" style="color: black; text-align: justify;">
            We are students from Polytechnic University of the Philippines. This project is a requirement for Capstone Research. Its goal is to provide a Concessionaire Operations Monitoring System for owners wanting to monitor their tenants the modern way.
            <br>
        </p>

<!-- Example row of columns -->
<div class="row">
    <div class="col-12 col-md-3">
            <img src="assets/images/mayki.jpg" class="rounded-square mx-auto d-block" alt="Mikee" width="150" height="150">
            <p class="text-center black-text" style="color: #c19f90;"><b>Mikee Estanislao</b></p>
            <div class="text-center">
            <a class="btn btn-info" href="mailto:mikeenestanislao@iskolarngbayan.pup.edu.ph"><i class="fas fa-envelope"></i></a>
            <a class="btn btn-primary" href="https://www.facebook.com/xMaykiNerix" role="button"><i class="fab fa-facebook"></i></a>
            <a class="btn btn-secondary" href="https://github.com/maykinerii" role="button"><i class="fab fa-github"></i></a>
        </div></div>
        <div class="col-md-3">
            <img src="assets/images/ced.jpg" class="rounded-square mx-auto d-block" alt="Ced" width="150" height="150">
            <p class="text-center black-text" style="color: #c19f90;"><b>John Cedrick Garcia</b></p>
            <div class="text-center">
            <a class="btn btn-info" href="mailto:jcmgarcia@iskolarngbayan.pup.edu.ph"><i class="fas fa-envelope"></i></a>
            <a class="btn btn-primary" href="https://www.facebook.com/lcedgarcia" role="button"><i class="fab fa-facebook"></i></a>
            <a class="btn btn-secondary" href="https://github.com/cedgarcia" role="button"><i class="fab fa-github"></i></a>
        </div></div>
        <div class="col-md-3">
            <img src="assets/images/rk.png" class="rounded-square mx-auto d-block" alt="RK" width="150" height="150">
            <p class="text-center black-text" style="color: #c19f90;"><b>Rei Kristian Panelo</b></p>
            <div class="text-center">
            <a class="btn btn-info" href="mailto:reikristianapanelo@iskolarngbayan.pup.edu.ph"><i class="fas fa-envelope"></i></a>
            <a class="btn btn-primary" href="https://www.facebook.com/rkpanelo" role="button"><i class="fab fa-facebook"></i></a>
            <a class="btn btn-secondary" href="https://github.com/reikristianpanelo" role="button"><i class="fab fa-github"></i></a>
        </div></div>
        <div class="col-md-3">
            <img src="assets/images/roland.jpg" class="rounded-square mx-auto d-block" alt="Roland" width="150" height="150">
            <p class="text-center black-text"style="color: #c19f90;"><b>Roland Angelo Tugaoen</b></p>
            <div class="text-center">
            <a class="btn btn-info" href="mailto:rabtugaoen@iskolarngbayan.pup.edu.ph"><i class="fas fa-envelope"></i></a>
            <a class="btn btn-primary" href="https://www.facebook.com/rolandtugaoen2016" role="button"><i class="fab fa-facebook"></i></a>
            <a class="btn btn-secondary" href="https://github.com/TugaGelo" role="button"><i class="fab fa-github"></i></a>
        </div></div>
    </div>
</div>

</section>

<!-- End Concourses -->
<!-- Start How It Works/Process -->
<!-- <section id="processs">
   <div class="container text-center">
       <h2>How it Works</h2>
       <div class="row mt-4">
           <div class="col-sm-4 mb-4">
           <i class="fa-solid fa-tv fa-8x text-success"></i>
               <h4>Upload Map</h4>
           </div>
           <div class="col-sm-4 mb-4">
           <i class="fa-solid fa-tv fa-8x text-success"></i>
               <h4>Upload Map</h4>
           </div>
           <div class="col-sm-4 mb-4">
           <i class="fa-solid fa-tv fa-8x text-success"></i>
               <h4>Upload Map</h4>
           </div>
       </div>
   </div>
   </section> -->
<!-- End How It Works/Process -->
<!--Start View/Upload Map -->
<section id="list-view-map"></section>
<!--End View/Upload Map -->
<!-- Start Contact -->
<section id="contact"></section>
<!-- End Contacy -->
<!-- Start Create Account -->
<!-- hindi na siguro ito need sa welcome page -->
<!-- <div class="container pt-5">
   <h2 class="text-center">Create an Account</h2>
   <div class="row mt-4 mb-4" >
      <div class="col-md-6 offset-md-3">
         <form action="" class="shadow-lg p-4" method="POST">
            <div class="form-group form">
               <input type="text" class="form-control form-input" name="name"id="name" autocomplete="off" placeholder="">
               <label for="name" class="form-label">
               <i class="fa-solid fa-user"></i>    
               Name</label>
            </div>
            <div class="form-group form">
               <input type="text" class="form-control form-input" name="email"id="email" autocomplete="off" placeholder="">
               <label for="email" class="form-label">
               <i class="fa-solid fa-envelope"></i>    
               Email</label>
            </div>
            <div class="form-group form">
               <input type="password" class="form-control form-input" name="password"id="password" placeholder="">
               <label for="password" class="form-label">
               <i class="fa-solid fa-key"></i>
               Password</label>
            </div>
            <button type="submit" class="btn btn-danger mt-3 btn-block shadow-sm font-weight-bold" name="register">Sign Up</button>
         </form>
      </div>
   </div>
</div> -->
<!-- End Create Account -->
<!-- modal notificatio -->
<!-- Add JavaScript to automatically hide the modal when closed -->
<!-- In any section where you want to open the modal -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function loadConcourses(page) {
        $.ajax({
            type: 'GET',
            url: 'get_concourse.php',
            data: { page: page },
            success: function (data) {
                $('#concourse-list').html(data);
            }
        });
    }

    $(document).ready(function () {
        loadConcourses(1); // Load the first page by default

        // Pagination click event handler
        $(document).on('click', '.page-link', function (event) {
            event.preventDefault(); // Prevent the default link behavior
            var page = $(this).data('page');
            loadConcourses(page);
        });
    });
</script>
<?php include('includes/footer.php')?>
<script>
    function redirectToLogin() {
        window.location.href = "login.php";
    }

    function redirectToSignUp() {
        window.location.href = "register.php";
    }
</script>
