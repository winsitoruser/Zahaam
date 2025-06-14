<!DOCTYPE html>
<html lang="en">

<head>
<?php echo view("partials/title-meta", array("title" => "Lock Screen")) ?>

<?= $this->include("partials/head-css") ?>
</head>

<body class="vh-100">

     <div class="d-flex flex-column h-100 p-3">
          <div class="d-flex flex-column flex-grow-1">
               <div class="row h-100">
                    <div class="col-xxl-7">
                         <div class="row justify-content-center h-100">
                              <div class="col-lg-6 py-lg-5">
                                   <div class="d-flex flex-column h-100 justify-content-center">
                                        <div class="auth-logo mb-4">
                                             <a href="/" class="logo-dark">
                                                  <img src="/images/logo-dark.png" height="24" alt="logo dark">
                                             </a>

                                             <a href="/" class="logo-light">
                                                  <img src="/images/logo-light.png" height="24" alt="logo light">
                                             </a>
                                        </div>

                                        <h2 class="fw-bold fs-24">Hi ! Gaston</h2>

                                        <p class="text-muted mt-1 mb-4">Enter your password to access the admin.</p>

                                        <div class="mb-5">
                                             <form  class="authentication-form">
                                                  <div class="mb-3">
                                                       <label class="form-label visually-hidden" for="example-password">Password</label>
                                                       <input type="text" id="example-password" class="form-control" placeholder="Enter your password">
                                                  </div>
                                                  <div class="mb-1 text-center d-grid">
                                                       <button class="btn btn-primary" type="submit">Sign In</button>
                                                  </div>
                                             </form>
                                        </div>

                                        <p class="text-danger text-center">Not you? return<a href="auth-signup" class="text-dark fw-bold ms-1">Sign Up</a></p>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="col-xxl-5 d-none d-xxl-flex">
                         <div class="card h-100 mb-0 overflow-hidden">
                              <div class="d-flex flex-column h-100">
                                   <img src="/images/small/img-10.jpg" alt="" class="w-100 h-100">
                              </div>
                         </div> <!-- end card -->
                    </div>
               </div>
          </div>
     </div>

     <?= $this->include("partials/vendor-scripts") ?>


</body>

</html>