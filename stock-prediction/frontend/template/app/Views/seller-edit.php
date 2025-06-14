<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Seller Edit")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Seller Edit")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-xxl">

                    <div class="row">
                         <div class="col-xl-3 col-md-6">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="position-relative bg-light p-2 rounded text-center">
                                             <img src="/images/seller/zara.svg" alt="" class="avatar-xxl">
                                             <div class="position-absolute top-0 end-0 m-1">
                                                  <div class="dropdown">
                                                       <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <iconify-icon icon="iconamoon:menu-kebab-vertical-circle-duotone" class="fs-20 align-middle text-muted"></iconify-icon>
                                                       </a>
                                                       <div class="dropdown-menu dropdown-menu-end">
                                                            <!-- item-->
                                                            <a href="javascript:void(0);" class="dropdown-item">Download</a>
                                                            <!-- item-->
                                                            <a href="javascript:void(0);" class="dropdown-item">Export</a>
                                                            <!-- item-->
                                                            <a href="javascript:void(0);" class="dropdown-item">Import</a>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="d-flex flex-wrap justify-content-between my-3">
                                             <div>
                                                  <h4 class="mb-1">ZARA International<span class="text-muted fs-13 ms-1">(Fashion) </span></h4>
                                                  <div>
                                                       <a href="#!" class="link-primary fs-16 fw-medium">www.zarafashion.co</a>
                                                  </div>
                                             </div>
                                             <div>
                                                  <p class="mb-0"><span class="badge bg-light text-dark fs-12 me-1"><i class="bx bxs-star align-text-top fs-14 text-warning me-1"></i> 4.5</span>3.5k</p>
                                             </div>
                                        </div>
                                        <div class="">
                                             <p class="d-flex align-items-center gap-2 mb-1"><iconify-icon icon="solar:point-on-map-bold-duotone" class="fs-18 text-primary"></iconify-icon>4604 , Philli Lane Kiowa IN 47404</p>
                                             <p class="d-flex align-items-center gap-2 mb-1"><iconify-icon icon="solar:letter-bold-duotone" class="fs-18 text-primary"></iconify-icon>zarafashionworld@dayrep.com</p>
                                             <p class="d-flex align-items-center gap-2 mb-0"><iconify-icon icon="solar:outgoing-call-rounded-bold-duotone" class="fs-20 text-primary"></iconify-icon>+243 812-801-9335</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-3 mb-1">
                                             <p class="mb-0 fs-15 fw-medium text-dark">Fashion</p>
                                             <div>
                                                  <p class="mb-0 fs-15 fw-medium text-dark">$200k <span class="ms-1"><iconify-icon icon="solar:course-up-outline" class="text-success"></iconify-icon></span></p>
                                             </div>
                                        </div>
                                        <div class="progress progress-soft progress-md">
                                             <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" role="progressbar" style="width: 80%" aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="p-2 pb-0 mx-n3 mt-2">
                                             <div class="row text-center g-2">
                                                  <div class="col-lg-4 col-4 border-end">
                                                       <h5 class="mb-1">865</h5>
                                                       <p class="text-muted mb-0">Item Stock</p>
                                                  </div>
                                                  <div class="col-lg-4 col-4 border-end">
                                                       <h5 class="mb-1">+4.5k</h5>
                                                       <p class="text-muted mb-0">Sells</p>
                                                  </div>
                                                  <div class="col-lg-4 col-4">
                                                       <h5 class="mb-1">+2k</h5>
                                                       <p class="text-muted mb-0">Happy Client</p>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <div class="col-xl-9 col-lg-8 ">
                              <div class="card">
                                   <div class="card-header">
                                        <h4 class="card-title">Add Brand Logo</h4>
                                   </div>
                                   <div class="card-body">
                                        <div class="dropzone bg-light-subtle py-5">
                                             <div class="fallback">
                                                  <input name="file" type="file" multiple="multiple">
                                             </div>
                                             <div class="dz-message needsclick">
                                                  <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                                                  <h3 class="mt-4">Drop your images here, or <span class="text-primary">click to browse</span></h3>
                                                  <span class="text-muted fs-13">
                                                       1600 x 1200 (4:3) recommended. PNG, JPG and GIF files are allowed
                                                  </span>
                                             </div>
                                        </div>

                                        <ul class="list-unstyled mb-0" id="dropzone-preview">
                                             <li class="mt-2" id="dropzone-preview-list">
                                                  <!-- This is used as the file preview template -->
                                                  <div class="border rounded">
                                                       <div class="d-flex p-2">
                                                            <div class="flex-shrink-0 me-3">
                                                                 <div class="avatar-sm bg-light rounded">
                                                                      <img data-dz-thumbnail class="img-fluid rounded d-block" src="#" alt="Dropzone-Image" />
                                                                 </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                 <div class="pt-1">
                                                                      <h5 class="fs-14 mb-1" data-dz-name>&</h5>
                                                                      <p class="fs-13 text-muted mb-0" data-dz-size></p>
                                                                      <strong class="error text-primary" data-dz-errormessage></strong>
                                                                 </div>
                                                            </div>
                                                            <div class="flex-shrink-0 ms-3">
                                                                 <button data-dz-remove class="btn btn-sm btn-primary">Delete</button>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </li>
                                        </ul>
                                        <!-- end dropzon-preview -->
                                   </div>
                              </div>
                              <div class="card">
                                   <div class="card-header">
                                        <h4 class="card-title">Seller Information</h4>
                                   </div>
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-lg-6">
                                                  <form>
                                                       <div class="mb-3">
                                                            <label for="brand-title" class="form-label">Brand Title</label>
                                                            <input type="text" id="brand-title" class="form-control" placeholder="Enter Title" value="ZARA International">
                                                       </div>
                                                  </form>
                                             </div>

                                             <div class="col-lg-6">
                                                  <form>
                                                       <label for="product-categories" class="form-label">Product Categories</label>
                                                       <select class="form-control" id="product-categories" data-choices data-choices-groups data-placeholder="Select Categories" name="choices-single-groups">
                                                            <option value="">Choose a categories</option>
                                                            <option value="Fashion" selected>Fashion</option>
                                                            <option value="Electronics">Electronics</option>
                                                            <option value="Footwear">Footwear</option>
                                                            <option value="Sportswear">Sportswear</option>
                                                            <option value="Watches">Watches</option>
                                                            <option value="Furniture">Furniture</option>
                                                            <option value="Appliances">Appliances</option>
                                                            <option value="Headphones">Headphones</option>
                                                            <option value="Other Accessories">Other Accessories</option>
                                                       </select>
                                                  </form>
                                             </div>
                                             <div class="col-lg-6">
                                                  <form>
                                                       <div class="mb-3">
                                                            <label for="brand-link" class="form-label">Brand Link</label>
                                                            <input type="text" id="brand-link" class="form-control" placeholder="www.****" value="www.zarafashion.co">
                                                       </div>

                                                  </form>
                                             </div>
                                             <div class="col-lg-6">
                                                  <form>
                                                       <label for="seller-location" class="form-label">Location</label>
                                                       <div class="input-group mb-3">
                                                            <span class="input-group-text fs-20"><iconify-icon icon="solar:point-on-map-bold-duotone" class="fs-18"></iconify-icon></span>
                                                            <input type="text" id="seller-location" class="form-control" placeholder="Add Address" value="4604 , Philli Lane Kiowa IN 47404">
                                                       </div>
                                                  </form>
                                             </div>
                                             <div class="col-lg-6">
                                                  <form>
                                                       <label for="seller-email" class="form-label">Email</label>
                                                       <div class="input-group mb-3">
                                                            <span class="input-group-text fs-20"><iconify-icon icon="solar:letter-bold-duotone" class="fs-18"></iconify-icon></span>
                                                            <input type="email" id="seller-email" class="form-control" placeholder="Add Email" value="zarafashionworld@dayrep.com">
                                                       </div>
                                                  </form>
                                             </div>
                                             <div class="col-lg-6">
                                                  <form>
                                                       <label for="seller-number" class="form-label">Phone Number</label>
                                                       <div class="input-group mb-3">
                                                            <span class="input-group-text fs-20"><iconify-icon icon="solar:outgoing-call-rounded-bold-duotone" class="fs-20"></iconify-icon></span>
                                                            <input type="text" id="seller-number" class="form-control" placeholder="Phone number" value="+243 812-801-9335">
                                                       </div>
                                                  </form>
                                             </div>
                                             <div class="col-lg-12">
                                                  <label for="customRange1" class="form-label">Yearly Revenue</label>
                                                  <div id="product-price-range" [data-slider-size="md" ] class=""></div>
                                                  <div class="formCost d-flex gap-2 align-items-center mt-2">
                                                       <input class="form-control form-control-sm text-center w-50" type="text" id="maxCost" value="$200">
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="card">
                                   <div class="card-header">
                                        <h4 class="card-title">Seller Product Information</h4>
                                   </div>
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-lg-4">
                                                  <form>
                                                       <div class="mb-3">
                                                            <label for="items-stock" class="form-label">Items Stock</label>
                                                            <input type="number" id="items-stock" class="form-control" placeholder="000" value="865">
                                                       </div>
                                                  </form>
                                             </div>
                                             <div class="col-lg-4">
                                                  <form>
                                                       <div class="mb-3">
                                                            <label for="items-sells" class="form-label">Product Sells</label>
                                                            <input type="number" id="items-sells" class="form-control" placeholder="000" value="4897">
                                                       </div>
                                                  </form>
                                             </div>
                                             <div class="col-lg-4">
                                                  <form>
                                                       <div class="mb-3">
                                                            <label for="happy-client" class="form-label">Happy Client</label>
                                                            <input type="number" id="happy-client" class="form-control" placeholder="000" value="2826">
                                                       </div>
                                                  </form>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="p-3 bg-light mb-3 rounded">
                                   <div class="row justify-content-end g-2">
                                        <div class="col-lg-2">
                                             <a href="#!" class="btn btn-outline-secondary w-100">Save Change</a>
                                        </div>
                                        <div class="col-lg-2">
                                             <a href="#!" class="btn btn-primary w-100">Cancel</a>
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

     <script src="/js/pages/app-ecommerce-seller-add.js"></script>


</body>

</html>