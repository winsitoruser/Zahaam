<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Reviews List")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Reviews List")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-xxl">
                    <div class="row">
                         <div class="col-xl-3 col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <p class="mb-2 text-dark fw-semibold fs-15">Reviewed in U.S.A on 21 December 2023</p>
                                        <p class="mb-0">" I recently purchased a t-shirt that I was quite excited about, and I must say, there are several aspects that I really appreciate about it. Firstly, the material is absolutely wonderful."</p>
                                        <div class="d-flex align-items-center gap-2 mt-2 mb-1">
                                             <ul class="d-flex text-warning m-0 fs-20 list-unstyled">
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star-half"></i>
                                                  </li>
                                             </ul>
                                             <p class="fw-medium mb-0 text-dark fs-15">Excellent Quality</p>
                                        </div>
                                   </div>
                                   <div class="card-footer bg-primary position-relative mt-3">
                                        <div class="position-absolute top-0 start-0 translate-middle-y ms-3">
                                             <img src="/images/users/avatar-2.jpg" alt="" class="avatar-lg border border-light border-3 rounded-circle">
                                        </div>
                                        <div class="position-absolute top-0 end-0 translate-middle-y me-3">
                                             <img src="/images/double.png" alt="" class="avatar-md">
                                        </div>
                                        <div class="mt-4">
                                             <h4 class="text-white mb-1">Michael B. Coch</h4>
                                             <p class="text-white mb-0">Kaika Hill, CEO / Hill & CO</p>
                                        </div>

                                   </div>
                              </div>
                         </div>
                         <div class="col-xl-3 col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <p class="mb-2 text-dark fw-semibold fs-15">Reviewed in Canada on 16 March 2023</p>
                                        <p class="mb-0">"I purchased a pair of jeans Firstly, the fabric is fantastic—it's both durable and comfortable. The denim is soft yet sturdy, making it perfect for everyday wear."</p>
                                        <div class="d-flex align-items-center gap-2 mt-2 mb-1">
                                             <ul class="d-flex text-warning m-0 fs-20 list-unstyled">
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star-half"></i>
                                                  </li>
                                             </ul>
                                             <p class="fw-medium mb-0 text-dark fs-15">Best Quality</p>
                                        </div>
                                   </div>
                                   <div class="card-footer bg-primary position-relative mt-3">
                                        <div class="position-absolute top-0 start-0 translate-middle-y ms-3">
                                             <img src="/images/users/avatar-3.jpg" alt="" class="avatar-lg border border-light border-3 rounded-circle">
                                        </div>
                                        <div class="position-absolute top-0 end-0 translate-middle-y me-3">
                                             <img src="/images/double.png" alt="" class="avatar-md">
                                        </div>
                                        <div class="mt-4">
                                             <h4 class="text-white mb-1">Theresa T. Brose</h4>
                                             <p class="text-white mb-0">Millenia Life, / General internist</p>
                                        </div>

                                   </div>
                              </div>
                         </div>
                         <div class="col-xl-3 col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <p class="mb-2 text-dark fw-semibold fs-15">Reviewed in Germany on 23 October 2023</p>
                                        <p class="mb-0">"The fit is perfect, hugging in all the right places while allowing for ease of movement. Overall, this dress exceeded my expectations and has quickly become a favorite in my wardrobe."</p>
                                        <div class="d-flex align-items-center gap-2 mt-2 mb-1">
                                             <ul class="d-flex text-warning m-0 fs-20 list-unstyled">
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bx-star"></i>
                                                  </li>
                                             </ul>
                                             <p class="fw-medium mb-0 text-dark fs-15">Good Quality</p>
                                        </div>
                                   </div>
                                   <div class="card-footer bg-primary position-relative mt-3">
                                        <div class="position-absolute top-0 start-0 translate-middle-y ms-3">
                                             <img src="/images/users/avatar-4.jpg" alt="" class="avatar-lg border border-light border-3 rounded-circle">
                                        </div>
                                        <div class="position-absolute top-0 end-0 translate-middle-y me-3">
                                             <img src="/images/double.png" alt="" class="avatar-md">
                                        </div>
                                        <div class="mt-4">
                                             <h4 class="text-white mb-1"> James L. Erickson</h4>
                                             <p class="text-white mb-0">Omni Tech Solutions / Founder</p>
                                        </div>

                                   </div>
                              </div>
                         </div>
                         <div class="col-xl-3 col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <p class="mb-2 text-dark fw-semibold fs-15">Reviewed in Germany on 23 October 2023</p>
                                        <p class="mb-0">"The fit is perfect, hugging in all the right places while allowing for ease of movement. Overall, this dress exceeded my expectations and has quickly become a favorite in my wardrobe."</p>
                                        <div class="d-flex align-items-center gap-2 mt-2 mb-1">
                                             <ul class="d-flex text-warning m-0 fs-20 list-unstyled">
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star-half"></i>
                                                  </li>
                                             </ul>
                                             <p class="fw-medium mb-0 text-dark fs-15">Good Quality</p>
                                        </div>
                                   </div>
                                   <div class="card-footer bg-primary position-relative mt-3">
                                        <div class="position-absolute top-0 start-0 translate-middle-y ms-3">
                                             <img src="/images/users/avatar-5.jpg" alt="" class="avatar-lg border border-light border-3 rounded-circle">
                                        </div>
                                        <div class="position-absolute top-0 end-0 translate-middle-y me-3">
                                             <img src="/images/double.png" alt="" class="avatar-md">
                                        </div>
                                        <div class="mt-4">
                                             <h4 class="text-white mb-1"> Lily W. Wilson</h4>
                                             <p class="text-white mb-0">Grade A Investment / Manager </p>
                                        </div>

                                   </div>
                              </div>
                         </div>
                         <div class="col-xl-3 col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <p class="mb-2 text-dark fw-semibold fs-15">Reviewed in Canada on 29 May 2023</p>
                                        <p class="mb-0">"Additionally, the fit is perfect, providing great support and comfort for all-day wear. These boots have quickly become a staple in my wardrobe, and I couldn't be happier with my purchase."</p>
                                        <div class="d-flex align-items-center gap-2 mt-2 mb-1">
                                             <ul class="d-flex text-warning m-0 fs-20 list-unstyled">
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bx-star"></i>
                                                  </li>
                                             </ul>
                                             <p class="fw-medium mb-0 text-dark fs-15">Excellent Quality</p>
                                        </div>
                                   </div>
                                   <div class="card-footer bg-primary position-relative mt-3">
                                        <div class="position-absolute top-0 start-0 translate-middle-y ms-3">
                                             <img src="/images/users/avatar-6.jpg" alt="" class="avatar-lg border border-light border-3 rounded-circle">
                                        </div>
                                        <div class="position-absolute top-0 end-0 translate-middle-y me-3">
                                             <img src="/images/double.png" alt="" class="avatar-md">
                                        </div>
                                        <div class="mt-4">
                                             <h4 class="text-white mb-1"> Sarah M. Brooks</h4>
                                             <p class="text-white mb-0">Metro / Counseling </p>
                                        </div>

                                   </div>
                              </div>
                         </div>
                         <div class="col-xl-3 col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <p class="mb-2 text-dark fw-semibold fs-15">Reviewed in U.S.A on 18 August 2023</p>
                                        <p class="mb-0">"The color is rich and vibrant, making it a standout piece in my wardrobe. Overall, this sweater has exceeded my expectations and has quickly become one of my favorite pieces to wear."</p>
                                        <div class="d-flex align-items-center gap-2 mt-2 mb-1">
                                             <ul class="d-flex text-warning m-0 fs-20 list-unstyled">
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star-half"></i>
                                                  </li>
                                             </ul>
                                             <p class="fw-medium mb-0 text-dark fs-15">Best Quality</p>
                                        </div>
                                   </div>
                                   <div class="card-footer bg-primary position-relative mt-3">
                                        <div class="position-absolute top-0 start-0 translate-middle-y ms-3">
                                             <img src="/images/users/avatar-7.jpg" alt="" class="avatar-lg border border-light border-3 rounded-circle">
                                        </div>
                                        <div class="position-absolute top-0 end-0 translate-middle-y me-3">
                                             <img src="/images/double.png" alt="" class="avatar-md">
                                        </div>
                                        <div class="mt-4">
                                             <h4 class="text-white mb-1"> Joe K. Hall</h4>
                                             <p class="text-white mb-0">Atlas Realty / Media specialist </p>
                                        </div>

                                   </div>
                              </div>
                         </div>
                         <div class="col-xl-3 col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <p class="mb-2 text-dark fw-semibold fs-15">Reviewed in Iceland on 12 May 2023</p>
                                        <p class="mb-0">"I ordered my usual size, but the shoes are either too small or too big, making them uncomfortable to wear. I would not recommend them to others not buy product, I couldn't be happier with my purchase"</p>
                                        <div class="d-flex align-items-center gap-2 mt-2 mb-1">
                                             <ul class="d-flex text-warning m-0 fs-20 list-unstyled">
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bx-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bx-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bx-star"></i>
                                                  </li>
                                             </ul>
                                             <p class="fw-medium mb-0 text-dark fs-15">Bad Quality</p>
                                        </div>
                                   </div>
                                   <div class="card-footer bg-primary position-relative mt-3">
                                        <div class="position-absolute top-0 start-0 translate-middle-y ms-3">
                                             <img src="/images/users/avatar-9.jpg" alt="" class="avatar-lg border border-light border-3 rounded-circle">
                                        </div>
                                        <div class="position-absolute top-0 end-0 translate-middle-y me-3">
                                             <img src="/images/double.png" alt="" class="avatar-md">
                                        </div>
                                        <div class="mt-4">
                                             <h4 class="text-white mb-1">Jennifer Schafer</h4>
                                             <p class="text-white mb-0">Red Bears Tavern / Director </p>
                                        </div>

                                   </div>
                              </div>
                         </div>
                         <div class="col-xl-3 col-md-6">
                              <div class="card overflow-hidden">
                                   <div class="card-body">
                                        <p class="mb-2 text-dark fw-semibold fs-15">Reviewed in Arabic on 18 September 2023</p>
                                        <p class="mb-0">"irstly, the quality of the fabric is exceptional. It's soft, luxurious, and drapes beautifully, giving the dress an elegant and sophisticated look. The design is simply stunning I couldn't be happier with my purchase."</p>
                                        <div class="d-flex align-items-center gap-2 mt-2 mb-1">
                                             <ul class="d-flex text-warning m-0 fs-20 list-unstyled">
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bxs-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bx-star"></i>
                                                  </li>
                                                  <li>
                                                       <i class="bx bx-star"></i>
                                                  </li>
                                             </ul>
                                             <p class="fw-medium mb-0 text-dark fs-15">Best Quality</p>
                                        </div>
                                   </div>
                                   <div class="card-footer bg-primary position-relative mt-3">
                                        <div class="position-absolute top-0 start-0 translate-middle-y ms-3">
                                             <img src="/images/users/avatar-10.jpg" alt="" class="avatar-lg border border-light border-3 rounded-circle">
                                        </div>
                                        <div class="position-absolute top-0 end-0 translate-middle-y me-3">
                                             <img src="/images/double.png" alt="" class="avatar-md">
                                        </div>
                                        <div class="mt-4">
                                             <h4 class="text-white mb-1">Nashida Ulfah </h4>
                                             <p class="text-white mb-0">Platinum Interior / Manager </p>
                                        </div>

                                   </div>
                              </div>
                         </div>
                    </div>
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