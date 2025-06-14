<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Product Details")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Product Details")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div>

               <div class="page-content">

                    <!-- Start Container Fluid -->
                    <div class="container-xxl">

                         <div class="row">
                              <div class="col">
                                   <div class="card">
                                        <div class="card-body">
                                             <div class="row">
                                                  <div class="col-lg-4">
                                                       <div class="tab-content">
                                                            <div class="tab-pane active show" id="product-1-item">
                                                                 <img src="/images/products/product-1(2).png" alt="product-1(2)" class="img-fluid mx-auto d-block rounded">
                                                            </div>
                                                            <div class="tab-pane" id="product-2-item">
                                                                 <img src="/images/products/product-1(1).png" alt="product-1(1)" class="img-fluid mx-auto d-block rounded">
                                                            </div>
                                                            <div class="tab-pane" id="product-3-item">
                                                                 <img src="/images/products/product-1(3).png" alt="product-1(3)" class="img-fluid mx-auto d-block rounded">
                                                            </div>
                                                            <div class="tab-pane" id="product-4-item">
                                                                 <img src="/images/products/product-1(4).png" alt="product-1(4)" class="img-fluid mx-auto d-block rounded">
                                                            </div>
                                                       </div>
                                                       <ul class="nav nav-pills nav-justified">
                                                            <li class="nav-item">
                                                                 <a href="#product-1-item" data-bs-toggle="tab" aria-expanded="false" class="nav-link product-thumb active show">
                                                                      <img src="/images/products/product-1(2).png" alt="product-1(2)" class="img-fluid mx-auto rounded">
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#product-2-item" data-bs-toggle="tab" aria-expanded="false" class="nav-link product-thumb">
                                                                      <img src="/images/products/product-1(1).png" alt="product-1(1)" class="img-fluid mx-auto rounded">
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#product-3-item" data-bs-toggle="tab" aria-expanded="false" class="nav-link product-thumb">
                                                                      <img src="/images/products/product-1(3).png" alt="product-1(3)" class="img-fluid mx-auto rounded">
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#product-4-item" data-bs-toggle="tab" aria-expanded="false" class="nav-link product-thumb">
                                                                      <img src="/images/products/product-1(4).png" alt="product-1(4)" class="img-fluid mx-auto rounded">
                                                                 </a>
                                                            </li>
                                                       </ul>

                                                  </div> <!-- end col -->
                                                  <div class="col-lg-8">
                                                       <div class="ps-xl-3 mt-3 mt-xl-0">
                                                            <a href="javascript:void(0);" class="text-primary mb-2 d-inline-block">Apple MacBook Air M1</a>
                                                            <h4 class="mb-3">(8 GB/256 GB SSD/Mac OS Big Sur) MGN63HN/A (13.3 inch, Space Grey, 1.29 kg)</h4>
                                                            <p class="text-muted float-start me-3">
                                                                 <span class="bx bxs-star text-warning"></span>
                                                                 <span class="bx bxs-star text-warning"></span>
                                                                 <span class="bx bxs-star text-warning"></span>
                                                                 <span class="bx bxs-star text-warning"></span>
                                                                 <span class="bx bxs-star text-warning"></span>
                                                            </p>
                                                            <p class="mb-3"><a href="javascript:void(0);" class="text-muted">( 36 Customer Reviews )</a></p>
                                                            <h6 class="text-danger text-uppercase">20 % Off</h6>
                                                            <h4 class="mb-3">Price : <span class="text-muted me-2"><del>$999 USD</del></span> <b>$899 USD</b></h4>
                                                            <h4><span class="badge badge-soft-success mb-3">Instock</span></h4>

                                                            <form class="d-flex flex-wrap align-items-center mb-3">
                                                                 <label class="my-1 me-2" for="color">Color:</label>
                                                                 <div class="me-3">
                                                                      <select class="form-select form-select-sm my-1" id="color">
                                                                           <option value="1">Black</option>
                                                                           <option value="2">Blue</option>
                                                                           <option value="3">Midnight</option>
                                                                      </select>
                                                                 </div>

                                                                 <label class="my-1 me-2" for="sizeinput">Size:</label>
                                                                 <div class="me-sm-3">
                                                                      <select class="form-select form-select-sm my-1" id="sizeinput">
                                                                           <option selected>256 GB</option>
                                                                           <option value="1">512 GB</option>
                                                                      </select>
                                                                 </div>
                                                            </form>

                                                            <div class="mb-3 pb-3 border-bottom">
                                                                 <h5>Processor Brand : <span class="text-muted me-2"></span> <b>Apple</b></h5>
                                                                 <h5>Processor Name : <span class="text-muted me-2"></span> <b>M1</b></h5>
                                                                 <h5>SSD : <span class="text-muted me-2"></span> <b>Yes</b></h5>
                                                                 <h5>SSD Capacity : <span class="text-muted me-2"></span> <b>256 GB</b></h5>
                                                                 <h5>RAM : <span class="text-muted me-2"></span> <b>8 GB</b></h5>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <h5>About this item:</h5>
                                                                 <p class="text-muted mb-1"><i class="bx bx-check-circle text-primary me-2"></i>Quad LED Backlit IPS Display (227 PPI, 400 nits Brightness, Wide Colour (P3), True Tone Technology)</p>
                                                                 <p class="text-muted mb-1"><i class="bx bx-check-circle text-primary me-2"></i>Built-in Speakers</p>
                                                                 <p class="text-muted mb-1"><i class="bx bx-check-circle text-primary me-2"></i>Three-mic Array with Directional Beamforming</p>
                                                                 <p class="text-muted mb-1"><i class="bx bx-check-circle text-primary me-2"></i>Stereo Speakers, Wide Stereo Sound, Support for Dolby Atmos Playback</p>
                                                                 <p class="text-muted mb-1"><i class="bx bx-check-circle text-primary me-2"></i>49.9 WHr Li-polymer Battery</p>
                                                                 <p class="text-muted mb-1"><i class="bx bx-check-circle text-primary me-2"></i>Backlit Magic Keyboard</p>
                                                            </div>

                                                            <div>
                                                                 <button type="button" class="btn btn-danger me-2"><i class="bx bx-heart fs-18"></i></button>
                                                                 <button type="button" class="btn btn-primary"><i class="bx bx-cart fs-18 me-2"></i>Add to cart</button>
                                                            </div>
                                                       </div>
                                                  </div> <!-- end col -->
                                             </div> <!-- end row -->
                                        </div> <!-- end card body -->
                                   </div> <!-- end card -->
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

     </div>
     <!-- END Wrapper -->

     <?= $this->include("partials/vendor-scripts") ?>

</body>

</html>