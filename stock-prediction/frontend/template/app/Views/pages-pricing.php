<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Pricing")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Pricing")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container xxl -->
               <div class="container-xxl">

                    <div class="row">
                         <div class="col-12">
                              <div class="text-center my-4">
                                   <h3>Simple Pricing Plans</h3>
                                   <p class="text-muted text-center">
                                        Get the power and control you need to manage your organization's technical documentation
                                   </p>
                              </div>
                              <div class="row justify-content-center">
                                   <div class="col-lg-3">
                                        <div class="card card-pricing">
                                             <div class="card-body">
                                                  <h5 class="mt-0 mb-3 fs-14 text-uppercase fw-semibold">Free Pack</h5>
                                                  <h2 class="mt-0 mb-3 fw-bold">$0 <span class="fs-14 fw-medium text-muted">/ Month</span></h2>

                                                  <ul class="card-pricing-features text-muted border-top pt-2 mt-2 ps-0 list-unstyled">
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>5 GB Storage</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>100 GB Bandwidth</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>1 Domain</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>No Support</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>24x7 Support</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>1 User</li>
                                                  </ul>

                                                  <div class="mt-4 text-center">
                                                       <button class="btn btn-primary px-sm-4 w-100">Get Started</button>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col-lg-3">
                                        <div class="card card-pricing">
                                             <div class="card-body">
                                                  <div class="pricing-ribbon pricing-ribbon-primary float-end">Popular</div>
                                                  <h5 class="mt-0 mb-3 fs-14 text-uppercase fw-semibold">Professional Pack</h5>
                                                  <h2 class="mt-0 mb-3 fw-bold">$19 <span class="fs-14 fw-medium text-muted">/ Month</span></h2>

                                                  <ul class="card-pricing-features text-muted border-top pt-2 mt-2 ps-0 list-unstyled">
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>50 GB Storage</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>900 GB Bandwidth</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>2 Domains</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>Email Support</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>24x7 Support</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>5 Users</li>
                                                  </ul>

                                                  <div class="mt-4 text-center">
                                                       <button class="btn btn-primary px-sm-4 disabled w-100">Current Plan</button>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col-lg-3">
                                        <div class="card card-pricing">
                                             <div class="card-body">
                                                  <h5 class="mt-0 mb-3 fs-14 text-uppercase fw-semibold">Business Pack</h5>
                                                  <h2 class="mt-0 mb-3 fw-bold">$29 <span class="fs-14 fw-medium text-muted">/ Month</span></h2>

                                                  <ul class="card-pricing-features text-muted border-top pt-2 mt-2 ps-0 list-unstyled">
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>500 GB Storage</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>2.5 TB Bandwidth</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>5 Domains</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>Email Support</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>24x7 Support</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>10 Users</li>
                                                  </ul>

                                                  <div class="mt-4 text-center">
                                                       <button class="btn btn-primary px-sm-4 w-100">Get Started</button>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col-lg-3">
                                        <div class="card card-pricing">
                                             <div class="card-body">
                                                  <h5 class="mt-0 mb-3 fs-14 text-uppercase fw-semibold">Enterprise Pack</h5>
                                                  <h2 class="mt-0 mb-3 fw-bold">$49 <span class="fs-14 fw-medium text-muted">/ Month</span></h2>

                                                  <ul class="card-pricing-features text-muted border-top pt-2 mt-2 ps-0 list-unstyled">
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>2 TB Storage</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>Unlimited Bandwidth</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>50 Domains</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>Email Support</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>24x7 Support</li>
                                                       <li class="text-dark"><i class='bx bx-check-circle text-primary fs-15 me-1'></i>Unlimited Users</li>
                                                  </ul>

                                                  <div class="mt-4 text-center">
                                                       <button class="btn btn-primary px-sm-4 w-100">Get Started</button>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                              </div> <!-- end row -->
                         </div> <!-- end col -->
                    </div> <!-- end row -->

               </div>
               <!-- End Container Fluid -->

               <?= $this->include("partials/footer") ?>

          </div>
          <!-- ==================================================== -->
          <!-- End Page Content -->
          <!-- ==================================================== -->

     </div>
     <!-- END Wrapper -->

     <?= $this->include("partials/vendor-scripts") ?>

</body>

</html>