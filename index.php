<?php
session_name("user_session");

session_start();?>

<?php include('includes/header.php')?>
<?php include('includes/nav.php')?>
<?php
if (isset($_SESSION['uid'])) {
    echo '<script>$("#notificationsModal").modal("show");</script>';
}
?>
<!-- Start HOME -->
<section id="home" class="jumbotron back-image" style="background-image:url(assets/images/hero-bg.png);">
<div class="main-heading">
    <h1 class="text-uppercase font-weight-bold">
Welcome to COMS
    </h1>
    <p class="font-italic">Concessionaire Operations Monitoring System</p>
    <a href="login.php" class="btn btn-success mr-4">Login</a>
    <a href="register.php" class="btn btn-danger mr-4">Sign Up</a>
</div>
</section>
<!-- End HOME -->
<!-- Start About -->
<section id="about">
    <div class="container">
        <div class="jumbotron">
            <h2 class="text-center">About COMS</h2>
            <p class="text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi unde, accusamus praesentium maxime molestiae earum explicabo atque obcaecati tenetur amet quas itaque, nostrum nulla sequi dolor fugiat deserunt placeat mollitia! <br> Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit vel neque ipsam culpa nesciunt aliquam quae at earum sunt tempore amet, quam tenetur nostrum. Fugit quae obcaecati voluptates ipsa perspiciatis.</p>
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
<div class="container pt-5">
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
</div>
<!-- End Create Account -->





<!-- modal notificatio -->

<!-- Add JavaScript to automatically hide the modal when closed -->

<!-- In any section where you want to open the modal -->


<?php include('includes/footer.php')?>
