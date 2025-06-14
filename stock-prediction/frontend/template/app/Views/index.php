<!DOCTYPE html>
<html lang="en">

<head>
      <?php echo view("partials/title-meta", array("title" => "Dashboard")) ?>

      <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Welcome!")) ?>
          <?= $this->include("partials/main-nav") ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-fluid">

                    <!-- Start here.... -->
                    <div class="row">
                         <div class="col-xxl-5">
                              <div class="row">
                                   <div class="col-12">
                                        <div class="alert alert-primary text-truncate mb-3" role="alert">
                                             We regret to inform you that our server is currently experiencing technical difficulties.
                                        </div>
                                   </div>

                                   <div class="col-md-6">
                                        <div class="card overflow-hidden">
                                             <div class="card-body">
                                                  <div class="row">
                                                       <div class="col-6">
                                                            <div class="avatar-md bg-soft-primary rounded">
                                                                 <iconify-icon icon="solar:cart-5-bold-duotone" class="avatar-title fs-32 text-primary"></iconify-icon>
                                                            </div>
                                                       </div> <!-- end col -->
                                                       <div class="col-6 text-end">
                                                            <p class="text-muted mb-0 text-truncate">Total Orders</p>
                                                            <h3 class="text-dark mt-1 mb-0">13, 647</h3>
                                                       </div> <!-- end col -->
                                                  </div> <!-- end row-->
                                             </div> <!-- end card body -->
                                             <div class="card-footer py-2 bg-light bg-opacity-50">
                                                  <div class="d-flex align-items-center justify-content-between">
                                                       <div>
                                                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 2.3%</span>
                                                            <span class="text-muted ms-1 fs-12">Last Week</span>
                                                       </div>
                                                       <a href="#!" class="text-reset fw-semibold fs-12">View More</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col-md-6">
                                        <div class="card overflow-hidden">
                                             <div class="card-body">
                                                  <div class="row">
                                                       <div class="col-6">
                                                            <div class="avatar-md bg-soft-primary rounded">
                                                                 <i class="bx bx-award avatar-title fs-24 text-primary"></i>
                                                            </div>
                                                       </div> <!-- end col -->
                                                       <div class="col-6 text-end">
                                                            <p class="text-muted mb-0 text-truncate">New Leads</p>
                                                            <h3 class="text-dark mt-1 mb-0">9, 526</h3>
                                                       </div> <!-- end col -->
                                                  </div> <!-- end row-->
                                             </div> <!-- end card body -->
                                             <div class="card-footer py-2 bg-light bg-opacity-50">
                                                  <div class="d-flex align-items-center justify-content-between">
                                                       <div>
                                                            <span class="text-success"> <i class="bx bxs-up-arrow fs-12"></i> 8.1%</span>
                                                            <span class="text-muted ms-1 fs-12">Last Month</span>
                                                       </div>
                                                       <a href="#!" class="text-reset fw-semibold fs-12">View More</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col-md-6">
                                        <div class="card overflow-hidden">
                                             <div class="card-body">
                                                  <div class="row">
                                                       <div class="col-6">
                                                            <div class="avatar-md bg-soft-primary rounded">
                                                                 <i class="bx bxs-backpack avatar-title fs-24 text-primary"></i>
                                                            </div>
                                                       </div> <!-- end col -->
                                                       <div class="col-6 text-end">
                                                            <p class="text-muted mb-0 text-truncate">Deals</p>
                                                            <h3 class="text-dark mt-1 mb-0">976</h3>
                                                       </div> <!-- end col -->
                                                  </div> <!-- end row-->
                                             </div> <!-- end card body -->
                                             <div class="card-footer py-2 bg-light bg-opacity-50">
                                                  <div class="d-flex align-items-center justify-content-between">
                                                       <div>
                                                            <span class="text-danger"> <i class="bx bxs-down-arrow fs-12"></i> 0.3%</span>
                                                            <span class="text-muted ms-1 fs-12">Last Month</span>
                                                       </div>
                                                       <a href="#!" class="text-reset fw-semibold fs-12">View More</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col-md-6">
                                        <div class="card overflow-hidden">
                                             <div class="card-body">
                                                  <div class="row">
                                                       <div class="col-6">
                                                            <div class="avatar-md bg-soft-primary rounded">
                                                                 <i class="bx bx-dollar-circle avatar-title text-primary fs-24"></i>
                                                            </div>
                                                       </div> <!-- end col -->
                                                       <div class="col-6 text-end">
                                                            <p class="text-muted mb-0 text-truncate">Booked Revenue</p>
                                                            <h3 class="text-dark mt-1 mb-0">$123.6k</h3>
                                                       </div> <!-- end col -->
                                                  </div> <!-- end row-->
                                             </div> <!-- end card body -->
                                             <div class="card-footer py-2 bg-light bg-opacity-50">
                                                  <div class="d-flex align-items-center justify-content-between">
                                                       <div>
                                                            <span class="text-danger"> <i class="bx bxs-down-arrow fs-12"></i> 10.6%</span>
                                                            <span class="text-muted ms-1 fs-12">Last Month</span>
                                                       </div>
                                                       <a href="#!" class="text-reset fw-semibold fs-12">View More</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                              </div> <!-- end row -->
                         </div> <!-- end col -->

                         <div class="col-xxl-7">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                             <h4 class="card-title">Performance</h4>
                                             <div>
                                                  <button type="button" class="btn btn-sm btn-outline-light">ALL</button>
                                                  <button type="button" class="btn btn-sm btn-outline-light">1M</button>
                                                  <button type="button" class="btn btn-sm btn-outline-light">6M</button>
                                                  <button type="button" class="btn btn-sm btn-outline-light active">1Y</button>
                                             </div>
                                        </div> <!-- end card-title-->

                                        <div dir="ltr">
                                             <div id="dash-performance-chart" class="apex-charts"></div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->
                    </div> <!-- end row -->

                    <div class="row">
                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title">Conversions</h5>
                                        <div id="conversions" class="apex-charts mb-2 mt-n2"></div>
                                        <div class="row text-center">
                                             <div class="col-6">
                                                  <p class="text-muted mb-2">This Week</p>
                                                  <h3 class="text-dark mb-3">23.5k</h3>
                                             </div> <!-- end col -->
                                             <div class="col-6">
                                                  <p class="text-muted mb-2">Last Week</p>
                                                  <h3 class="text-dark mb-3">41.05k</h3>
                                             </div> <!-- end col -->
                                        </div> <!-- end row -->
                                        <div class="text-center">
                                             <button type="button" class="btn btn-light shadow-none w-100">View Details</button>
                                        </div> <!-- end row -->
                                   </div>
                              </div>
                         </div> <!-- end left chart card -->

                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title">Sessions by Country</h5>
                                        <div id="world-map-markers" style="height: 316px">
                                        </div>
                                        <div class="row text-center">
                                             <div class="col-6">
                                                  <p class="text-muted mb-2">This Week</p>
                                                  <h3 class="text-dark mb-3">23.5k</h3>
                                             </div> <!-- end col -->
                                             <div class="col-6">
                                                  <p class="text-muted mb-2">Last Week</p>
                                                  <h3 class="text-dark mb-3">41.05k</h3>
                                             </div> <!-- end col -->
                                        </div> <!-- end row -->
                                   </div>
                              </div> <!-- end card-->
                         </div> <!-- end col -->

                         <div class="col-lg-4">
                              <div class="card card-height-100">
                                   <div class="card-header d-flex align-items-center justify-content-between gap-2">
                                        <h4 class="card-title flex-grow-1">Top Pages</h4>

                                        <a href="#" class="btn btn-sm btn-soft-primary">View All</a>
                                   </div>
                                   <div class="table-responsive">
                                        <table class="table table-hover table-nowrap table-centered m-0">
                                             <thead class="bg-light bg-opacity-50">
                                                  <tr>
                                                       <th class="text-muted ps-3">Page Path</th>
                                                       <th class="text-muted">Page Views</th>
                                                       <th class="text-muted">Exit Rate</th>
                                                  </tr>
                                             </thead>
                                             <tbody>
                                                  <tr>
                                                       <td class="ps-3"><a href="#" class="text-muted">larkon/ecommerce</a></td>
                                                       <td>465 </td>
                                                       <td><span class="badge badge-soft-success">4.4%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-3"><a href="#" class="text-muted">larkon/dashboard</a></td>
                                                       <td> 426</td>
                                                       <td><span class="badge badge-soft-danger">20.4%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-3"><a href="#" class="text-muted">larkon/chat</a></td>
                                                       <td>254 </td>
                                                       <td><span class="badge badge-soft-warning">12.25%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-3"><a href="#" class="text-muted">larkon/auth-login</a></td>
                                                       <td> 3369</td>
                                                       <td><span class="badge badge-soft-success">5.2%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-3"><a href="#" class="text-muted">larkon/email</a></td>
                                                       <td>985 </td>
                                                       <td><span class="badge badge-soft-danger">64.2%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-3"><a href="#" class="text-muted">larkon/social</a></td>
                                                       <td>653 </td>
                                                       <td><span class="badge badge-soft-success">2.4%</span></td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-3"><a href="#" class="text-muted">larkon/blog</a></td>
                                                       <td>478 </td>
                                                       <td><span class="badge badge-soft-danger">1.4%</span></td>
                                                  </tr>
                                             </tbody>
                                        </table>
                                   </div>
                              </div>
                         </div> <!-- end col -->

                         <div class="col-xl-4 d-none">
                              <div class="card">
                                   <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Recent Transactions</h4>
                                        <div>
                                             <a href="#!" class="btn btn-sm btn-primary">
                                                  <i class="bx bx-plus me-1"></i>Add
                                             </a>
                                        </div>
                                   </div> <!-- end card-header-->
                                   <div class="card-body p-0">
                                        <div class="px-3" data-simplebar style="max-height: 398px;">
                                             <table class="table table-hover mb-0 table-centered">
                                                  <tbody>
                                                       <tr>
                                                            <td>24 April, 2024</td>
                                                            <td>$120.55</td>
                                                            <td><span class="badge bg-success">Cr</span></td>
                                                            <td>Commisions </td>
                                                       </tr>
                                                       <tr>
                                                            <td>24 April, 2024</td>
                                                            <td>$9.68</td>
                                                            <td><span class="badge bg-success">Cr</span></td>
                                                            <td>Affiliates </td>
                                                       </tr>
                                                       <tr>
                                                            <td>20 April, 2024</td>
                                                            <td>$105.22</td>
                                                            <td><span class="badge bg-danger">Dr</span></td>
                                                            <td>Grocery </td>
                                                       </tr>
                                                       <tr>
                                                            <td>18 April, 2024</td>
                                                            <td>$80.59</td>
                                                            <td><span class="badge bg-success">Cr</span></td>
                                                            <td>Refunds </td>
                                                       </tr>
                                                       <tr>
                                                            <td>18 April, 2024</td>
                                                            <td>$750.95</td>
                                                            <td><span class="badge bg-danger">Dr</span></td>
                                                            <td>Bill Payments </td>
                                                       </tr>
                                                       <tr>
                                                            <td>17 April, 2024</td>
                                                            <td>$455.62</td>
                                                            <td><span class="badge bg-danger">Dr</span></td>
                                                            <td>Electricity </td>
                                                       </tr>
                                                       <tr>
                                                            <td>17 April, 2024</td>
                                                            <td>$102.77</td>
                                                            <td><span class="badge bg-success">Cr</span></td>
                                                            <td>Interest </td>
                                                       </tr>
                                                       <tr>
                                                            <td>16 April, 2024</td>
                                                            <td>$79.49</td>
                                                            <td><span class="badge bg-success">Cr</span></td>
                                                            <td>Refunds </td>
                                                       </tr>
                                                       <tr>
                                                            <td>05 April, 2024</td>
                                                            <td>$980.00</td>
                                                            <td><span class="badge bg-danger">Dr</span></td>
                                                            <td>Shopping</td>
                                                       </tr>
                                                  </tbody>
                                             </table>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card-->
                         </div> <!-- end col-->
                    </div> <!-- end row -->

                    <div class="row">
                         <div class="col">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <h4 class="card-title">
                                                  Recent Orders
                                             </h4>

                                             <a href="#!" class="btn btn-sm btn-soft-primary">
                                                  <i class="bx bx-plus me-1"></i>Create Order
                                             </a>
                                        </div>
                                   </div>
                                   <!-- end card body -->
                                   <div class="table-responsive table-centered">
                                        <table class="table mb-0">
                                             <thead class="bg-light bg-opacity-50">
                                                  <tr>
                                                       <th class="ps-3">
                                                            Order ID.
                                                       </th>
                                                       <th>
                                                            Date
                                                       </th>
                                                       <th>
                                                            Product
                                                       </th>
                                                       <th>
                                                            Customer Name
                                                       </th>
                                                       <th>
                                                            Email ID
                                                       </th>
                                                       <th>
                                                            Phone No.
                                                       </th>
                                                       <th>
                                                            Address
                                                       </th>
                                                       <th>
                                                            Payment Type
                                                       </th>
                                                       <th>
                                                            Status
                                                       </th>
                                                  </tr>
                                             </thead>
                                             <!-- end thead-->
                                             <tbody>
                                                  <tr>
                                                       <td class="ps-3">
                                                            <a href="apps-ecommerce-order-detail">#RB5625</a>
                                                       </td>
                                                       <td>29 April 2024</td>
                                                       <td>
                                                            <img src="/images/products/product-1(1).png" alt="product-1(1)" class="img-fluid avatar-sm">
                                                       </td>
                                                       <td>
                                                            <a href="#!">Anna M. Hines</a>
                                                       </td>
                                                       <td>anna.hines@mail.com</td>
                                                       <td>(+1)-555-1564-261</td>
                                                       <td>Burr Ridge/Illinois</td>
                                                       <td>Credit Card</td>
                                                       <td>
                                                            <i class="bx bxs-circle text-success me-1"></i>Completed
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-3">
                                                            <a href="apps-ecommerce-order-detail">#RB9652</a>
                                                       </td>
                                                       <td>25 April 2024</td>
                                                       <td>
                                                            <img src="/images/products/product-4.png" alt="product-4" class="img-fluid avatar-sm">
                                                       </td>
                                                       <td>
                                                            <a href="#!">Judith H. Fritsche</a>
                                                       </td>
                                                       <td>judith.fritsche.com</td>
                                                       <td>(+57)-305-5579-759</td>
                                                       <td>SULLIVAN/Kentucky</td>
                                                       <td>Credit Card</td>
                                                       <td>
                                                            <i class="bx bxs-circle text-success me-1"></i>Completed
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-3">
                                                            <a href="apps-ecommerce-order-detail">#RB5984</a>
                                                       </td>
                                                       <td>25 April 2024</td>
                                                       <td>
                                                            <img src="/images/products/product-5.png" alt="product-5" class="img-fluid avatar-sm">
                                                       </td>
                                                       <td>
                                                            <a href="#!">Peter T. Smith</a>
                                                       </td>
                                                       <td>peter.smith@mail.com</td>
                                                       <td>(+33)-655-5187-93</td>
                                                       <td>Yreka/California</td>
                                                       <td>Pay Pal</td>
                                                       <td>
                                                            <i class="bx bxs-circle text-success me-1"></i>Completed
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-3">
                                                            <a href="apps-ecommerce-order-detail">#RB3625</a>
                                                       </td>
                                                       <td>21 April 2024</td>
                                                       <td>
                                                            <img src="/images/products/product-6.png" alt="product-6" class="img-fluid avatar-sm">
                                                       </td>
                                                       <td>
                                                            <a href="#!">Emmanuel J. Delcid</a>
                                                       </td>
                                                       <td>
                                                            emmanuel.delicid@mail.com
                                                       </td>
                                                       <td>(+30)-693-5553-637</td>
                                                       <td>Atlanta/Georgia</td>
                                                       <td>Pay Pal</td>
                                                       <td>
                                                            <i class="bx bxs-circle text-primary me-1"></i>Processing
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td class="ps-3">
                                                            <a href="apps-ecommerce-order-detail">#RB8652</a>
                                                       </td>
                                                       <td>18 April 2024</td>
                                                       <td>
                                                            <img src="/images/products/product-1(2).png" alt="product-1(2)" class="img-fluid avatar-sm">
                                                       </td>
                                                       <td>
                                                            <a href="#!">William J. Cook</a>
                                                       </td>
                                                       <td>william.cook@mail.com</td>
                                                       <td>(+91)-855-5446-150</td>
                                                       <td>Rosenberg/Texas</td>
                                                       <td>Credit Card</td>
                                                       <td>
                                                            <i class="bx bxs-circle text-primary me-1"></i>Processing
                                                       </td>
                                                  </tr>
                                             </tbody>
                                             <!-- end tbody -->
                                        </table>
                                        <!-- end table -->
                                   </div>
                                   <!-- table responsive -->

                                   <div class="card-footer border-top">
                                        <div class="row g-3">
                                             <div class="col-sm">
                                                  <div class="text-muted">
                                                       Showing
                                                       <span class="fw-semibold">5</span>
                                                       of
                                                       <span class="fw-semibold">90,521</span>
                                                       orders
                                                  </div>
                                             </div>

                                             <div class="col-sm-auto">
                                                  <ul class="pagination m-0">
                                                       <li class="page-item">
                                                            <a href="#" class="page-link"><i class="bx bx-left-arrow-alt"></i></a>
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
                                                            <a href="#" class="page-link"><i class="bx bx-right-arrow-alt"></i></a>
                                                       </li>
                                                  </ul>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <!-- end card -->
                         </div>
                         <!-- end col -->
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

     <!-- Vector Map Js -->
     <script src="/vendor/jsvectormap/jsvectormap.min.js"></script>
     <script src="/vendor/jsvectormap/maps/world-merc.js"></script>
     <script src="/vendor/jsvectormap/maps/world.js"></script>

     <!-- Dashboard Js -->
     <script src="/js/pages/dashboard.js"></script>

</body>

</html>