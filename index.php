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
<section class="vh-100" style="background-color: #9b593c;">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-6 about-section" style="margin-top: 20em;">
            <h1>About Us</h1>
            <p class="par">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sunt neque 
               expedita atque eveniet <br> quis nesciunt. Quos nulla vero consequuntur, fugit nemo ad delectus 
            <br> a quae totam ipsa illum minus laudantium?</p>
            <button class="cn"><a href="#">Contact Us</a></button>
         </div>
         <!-- Login Section-->
         <div class="col-md-6 login-section">
            <div class="card" style="border-radius: 1rem;">
               <div class="row g-0">
                  <div class="col-md-6 col-lg-5 d-flex align-items-center justify-content-center">
                     <img src="assets/images/Logo-9b593c.png" alt="Login Form" class="img-fluid">
                  </div>
                  <div class="col-md-6 col-lg-7 d-flex align-items-center" >
                     <div class="card-body p-4 p-lg-5 text-black">
                        <form>
                           <div class="d-flex align-items-center mb-3 pb-1">
                              <span class="h1 fw-bold mb-0">Welcome to COMS</span>
                           </div>
                        </form>
                        <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px; font-style: italic;">Concessionaire Operations Monitoring System</h5>
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
   </div>
</section>
<!-- End About -->
<!-- Start Concourses -->
<section id="concourses"></section>
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
<?php include('includes/footer.php')?>
<script>
    function redirectToLogin() {
        window.location.href = "login.php";
    }

    function redirectToSignUp() {
        window.location.href = "register.php";
    }
</script>
