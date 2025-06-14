<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Products List")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Products List")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->

          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-xxl">

                    <div class="row">
                         <div class="col">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="d-flex flex-wrap justify-content-between gap-3">
                                             <div class="search-bar">
                                                  <span><i class="bx bx-search-alt"></i></span>
                                                  <input type="search" class="form-control" id="search" placeholder="Search ...">
                                             </div>
                                             <div>
                                                  <a href="apps-ecommerce-product-add" class="btn btn-primary d-flex align-items-center">
                                                       <i class="bx bx-plus me-1"></i>Add Product
                                                  </a>
                                             </div>
                                        </div> <!-- end row -->
                                   </div>
                                   <div>
                                        <div class="table-responsive table-centered">
                                             <table class="table text-nowrap mb-0">
                                                  <thead class="bg-light bg-opacity-50">
                                                       <tr>
                                                            <th>Product Name</th>
                                                            <th>Category</th>
                                                            <th>Price</th>
                                                            <th>Inventory</th>
                                                            <th>Action</th>
                                                       </tr>
                                                  </thead> <!-- end thead-->
                                                  <tbody>
                                                       <tr>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <div class="flex-shrink-0 me-3">
                                                                           <a href="apps-ecommerce-product-detail"><img src="/images/products/product-1(1).png" alt="product-1(1)" class="img-fluid avatar-sm" /></a>
                                                                      </div>
                                                                      <div class="flex-grow-1">
                                                                           <h5 class="mt-0 mb-1"><a href="apps-ecommerce-product-detail" class="text-reset">G15 Gaming Laptop</a></h5>
                                                                           <span class="fs-13">Power Your Laptop with a Long-Lasting and Fast-Charging Battery.</span>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>Computer</td>
                                                            <td>$240.59</td>
                                                            <td class="text-primary"><i class="bx bxs-circle text-primary me-1"></i>Limited</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-18"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <div class="flex-shrink-0 me-3">
                                                                           <a href="apps-ecommerce-product-detail"><img src="/images/products/product-2.png" alt="product-2" class="img-fluid avatar-sm" /></a>
                                                                      </div>
                                                                      <div class="flex-grow-1">
                                                                           <h5 class="mt-0 mb-1"><a href="apps-ecommerce-product-detail" class="text-reset">Sony Alpha ILCE 6000Y 24.3 MP Mirrorless Digital SLR Camera</a></h5>
                                                                           <span class="fs-13">Capture special moments and portraits to remember and share.</span>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>Camera</td>
                                                            <td>$135.99</td>
                                                            <td class="text-primary"><i class="bx bxs-circle text-primary me-1"></i>Limited</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-18"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <div class="flex-shrink-0 me-3">
                                                                           <a href="apps-ecommerce-product-detail"><img src="/images/products/product-3.png" alt="product-3" class="img-fluid avatar-sm" /></a>
                                                                      </div>
                                                                      <div class="flex-grow-1">
                                                                           <h5 class="mt-0 mb-1"><a href="apps-ecommerce-product-detail" class="text-reset">Sony Over-Ear Wireless Headphone with Mic</a></h5>
                                                                           <span class="fs-13">Headphones are a pair of small loudspeaker drivers worn on or around the head over a user's ears.</span>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>Headphones</td>
                                                            <td>$99.49</td>
                                                            <td class="text-success"><i class="bx bxs-circle text-success me-1"></i>In Stock</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-18"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <div class="flex-shrink-0 me-3">
                                                                           <a href="apps-ecommerce-product-detail"><img src="/images/products/product-4.png" alt="product-4" class="img-fluid avatar-sm" /></a>
                                                                      </div>
                                                                      <div class="flex-grow-1">
                                                                           <h5 class="mt-0 mb-1"><a href="apps-ecommerce-product-detail" class="text-reset">Apple iPad Pro with Apple M1 chip.</a></h5>
                                                                           <span class="fs-13">The new iPad mini and iPad.</span>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>Mobile</td>
                                                            <td>$27.59</td>
                                                            <td class="text-danger"><i class="bx bxs-circle text-danger me-1"></i>Out of Stock</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-18"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <div class="flex-shrink-0 me-3">
                                                                           <a href="apps-ecommerce-product-detail"><img src="/images/products/product-5.png" alt="product-5" class="img-fluid avatar-sm" /></a>
                                                                      </div>
                                                                      <div class="flex-grow-1">
                                                                           <h5 class="mt-0 mb-1"><a href="apps-ecommerce-product-detail" class="text-reset">Adam ROMA USB-C / USB-A 3.1 (2-in-1 Flash Drive) – 128GB</a></h5>
                                                                           <span class="fs-13">A USB flash drive is a data storage device that includes flash memory with an integrated USB interface.</span>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>Pendrive</td>
                                                            <td>$350.19</td>
                                                            <td class="text-primary"><i class="bx bxs-circle text-primary me-1"></i>Limited</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-18"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <div class="flex-shrink-0 me-3">
                                                                           <a href="apps-ecommerce-product-detail"><img src="/images/products/product-6.png" alt="product-4" class="img-fluid avatar-sm" /></a>
                                                                      </div>
                                                                      <div class="flex-grow-1">
                                                                           <h5 class="mt-0 mb-1"><a href="apps-ecommerce-product-detail" class="text-reset">Apple iPHone 13.</a></h5>
                                                                           <span class="fs-13">The new iPHone 1 and iPad.</span>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>Mobile</td>
                                                            <td>$75.59</td>
                                                            <td class="text-danger"><i class="bx bxs-circle text-danger me-1"></i>Out of Stock</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-18"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <div class="flex-shrink-0 me-3">
                                                                           <a href="apps-ecommerce-product-detail"><img src="/images/products/product-1(2).png" alt="product-1(1)" class="img-fluid avatar-sm" /></a>
                                                                      </div>
                                                                      <div class="flex-grow-1">
                                                                           <h5 class="mt-0 mb-1"><a href="apps-ecommerce-product-detail" class="text-reset">Apple Mac</a></h5>
                                                                           <span class="fs-13">Power Your Laptop with a Long-Lasting and Fast-Charging Battery.</span>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>Computer</td>
                                                            <td>$350.00</td>
                                                            <td class="text-primary"><i class="bx bxs-circle text-primary me-1"></i>Limited</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-18"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></button>
                                                            </td>
                                                       </tr>
                                                  </tbody> <!-- end tbody -->
                                             </table> <!-- end table -->
                                        </div> <!-- table responsive -->
                                        <div class="align-items-center justify-content-between row g-0 text-center text-sm-start p-3 border-top">
                                             <div class="col-sm">
                                                  <div class="text-muted">
                                                       Showing <span class="fw-semibold">5</span> of <span class="fw-semibold">15</span> Results
                                                  </div>
                                             </div>
                                             <div class="col-sm-auto mt-3 mt-sm-0">
                                                  <ul class="pagination pagination-rounded m-0">
                                                       <li class="page-item">
                                                            <a href="#" class="page-link"><i class='bx bx-left-arrow-alt'></i></a>
                                                       </li>
                                                       <li class="page-item active">
                                                            <a href="#" class="page-link">1</a>
                                                       </li>
                                                       <li class="page-item">
                                                            <a href="#" class="page-link">2</a>
                                                       </li>
                                                       <li class="page-item">
                                                            <a href="#" class="page-link">3</a>
                                                       </li>
                                                       <li class="page-item">
                                                            <a href="#" class="page-link"><i class='bx bx-right-arrow-alt'></i></a>
                                                       </li>
                                                  </ul>
                                             </div>
                                        </div>
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
     <!-- END Wrapper -->

     <?= $this->include("partials/vendor-scripts") ?>

</body>

</html>