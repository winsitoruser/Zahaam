<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Finance")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Finance")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-xxl">

                    <!-- Start here.... -->
                    <div class="row">
                         <div class="col">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <h3 class="mb-0 fw-bold mb-2">$55.6k</h3>
                                                  <p class="text-muted">Wallet Balance</p>
                                                  <span class="badge fs-12 badge-soft-success"><i class="ti ti-arrow-badge-up"></i> 8.72%</span>
                                             </div>
                                             <div>
                                                  <div class="avatar-lg d-inline-block me-1">
                                                       <span class="avatar-title bg-info-subtle text-info rounded-circle">
                                                            <iconify-icon icon="iconamoon:credit-card-duotone" class="fs-32"></iconify-icon>
                                                       </span>
                                                  </div>
                                             </div>
                                        </div>
                                   </div> <!-- end card-body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->
                         <div class="col">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <h3 class="mb-0 fw-bold mb-2">$75.09k</h3>
                                                  <p class="text-muted">Total Income</p>
                                                  <span class="badge fs-12 badge-soft-success"><i class="ti ti-arrow-badge-up"></i> 7.36%</span>
                                             </div>
                                             <div>
                                                  <div class="avatar-lg d-inline-block me-1">
                                                       <span class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                            <iconify-icon icon="iconamoon:store-duotone" class="fs-32"></iconify-icon>
                                                       </span>
                                                  </div>
                                             </div>
                                        </div>
                                   </div> <!-- end card-body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->
                         <div class="col">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <h3 class="mb-0 fw-bold mb-2">$62.8k</h3>
                                                  <p class="text-muted">Total Expenses</p>
                                                  <span class="badge fs-12 badge-soft-danger"><i class="ti ti-arrow-badge-up"></i> 5.62%</span>
                                             </div>
                                             <div>
                                                  <div class="avatar-lg d-inline-block me-1">
                                                       <span class="avatar-title bg-success-subtle text-success rounded-circle">
                                                            <iconify-icon icon="iconamoon:3d-duotone" class="fs-32"></iconify-icon>
                                                       </span>
                                                  </div>
                                             </div>
                                        </div>
                                   </div> <!-- end card-body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->

                         <div class="col">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                             <div>
                                                  <h3 class="mb-0 fw-bold mb-2">$6.4k</h3>
                                                  <p class="text-muted">Investments</p>
                                                  <span class="badge fs-12 badge-soft-success"><i class="ti ti-arrow-badge-up"></i> 2.53%</span>
                                             </div>
                                             <div>
                                                  <div class="avatar-lg d-inline-block me-1">
                                                       <span class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                                            <iconify-icon icon="iconamoon:3d-duotone" class="fs-32"></iconify-icon>
                                                       </span>
                                                  </div>
                                             </div>
                                        </div>
                                   </div> <!-- end card-body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                         <div class="col-xxl-6">
                              <div class="card">
                                   <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Revenue</h4>
                                        <div>
                                             <button type="button" class="btn btn-sm btn-outline-light">ALL</button>
                                             <button type="button" class="btn btn-sm btn-outline-light">1M</button>
                                             <button type="button" class="btn btn-sm btn-outline-light">6M</button>
                                             <button type="button" class="btn btn-sm btn-outline-light active">1Y</button>
                                        </div>
                                   </div> <!-- end card-title-->

                                   <div class="card-body">
                                        <div dir="ltr">
                                             <div id="dash-revenue-chart" class="apex-charts"></div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->

                         <div class="col-xxl-6">
                              <div class="card">
                                   <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Expenses</h4>
                                        <div>
                                             <button type="button" class="btn btn-sm btn-outline-light">ALL</button>
                                             <button type="button" class="btn btn-sm btn-outline-light">1M</button>
                                             <button type="button" class="btn btn-sm btn-outline-light">6M</button>
                                             <button type="button" class="btn btn-sm btn-outline-light active">1Y</button>
                                        </div>
                                   </div> <!-- end card-header-->
                                   <div class="card-body">
                                        <div dir="ltr">
                                             <div id="daily-expenses" class="apex-charts"></div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card-->
                         </div> <!-- end col -->
                    </div> <!-- end row -->

                    <div class="row">
                         <div class="col-xxl-8">
                              <div class="card">
                                   <div class="d-flex card-header justify-content-between align-items-center">
                                        <h4 class="card-title">Transactions</h4>
                                        <div class="flex-shrink-0">
                                             <div class="d-flex gap-2">
                                                  <select class="form-select form-select-sm">
                                                       <option selected="">All</option>
                                                       <option value="0">Paid</option>
                                                       <option value="1">Cancelled</option>
                                                       <option value="2">Failed</option>
                                                       <option value="2">Onhold</option>
                                                  </select>

                                             </div>
                                        </div>
                                   </div>
                                   <div class="card-body p-0">
                                        <div class="table-responsive table-card">
                                             <table class="table table-borderless table-hover table-nowrap align-middle mb-0">
                                                  <thead class="bg-light bg-opacity-50 thead-sm">
                                                       <tr>
                                                            <th scope="col">Name</th>
                                                            <th scope="col">Description</th>
                                                            <th scope="col">Amount</th>
                                                            <th scope="col">Timestamp</th>
                                                            <th scope="col">Status</th>
                                                       </tr>
                                                  </thead>

                                                  <tbody>
                                                       <tr>
                                                            <td>
                                                                 <img src="/images/users/avatar-6.jpg" alt="" class="avatar-xs rounded-circle me-1">
                                                                 <a href="#!" class="text-reset">Adam M</a>
                                                            </td>
                                                            <td>Licensing Revenue</td>
                                                            <td>USD $750.00</td>
                                                            <td>20 Apr,24 <small class="text-muted">10:31:23 am</small></td>
                                                            <td><span class="badge bg-success-subtle text-success p-1">Success</span></td>
                                                       </tr>

                                                       <tr>
                                                            <td><img src="/images/users/avatar-2.jpg" alt="" class="avatar-xs rounded-circle me-1">
                                                                 <a href="#javascript: void(0);" class="text-reset align-middle">Alexa Newsome</a>
                                                            </td>
                                                            <td>Invoice #1001</td>
                                                            <td class="text-danger">-AUD $90.99</td>
                                                            <td>18 Apr,24 <small class="text-muted">06:22:09 pm</small></td>
                                                            <td><span class="badge bg-info-subtle text-info p-1">Cancelled</span></td>
                                                       </tr>

                                                       <tr>
                                                            <td>
                                                                 <img src="/images/users/avatar-10.jpg" alt="" class="avatar-xs rounded-circle me-1">
                                                                 <a href="#!" class="text-reset">Shelly Dorey</a>
                                                            </td>
                                                            <td>Custom Software Development</td>
                                                            <td>USD $500.00</td>
                                                            <td>16 Apr,24 <small class="text-muted">05:09:58 pm</small></td>
                                                            <td><span class="badge bg-success-subtle text-success p-1">Success</span></td>
                                                       </tr>

                                                       <tr>
                                                            <td><img src="/images/users/avatar-5.jpg" alt="" class="avatar-xs rounded-circle me-1">
                                                                 <a href="#javascript: void(0);" class="text-reset align-middle">Fredrick Arnett</a>
                                                            </td>
                                                            <td>Envato Payout - Collaboration</td>
                                                            <td>USD $1250.95</td>
                                                            <td>16 Apr,24 <small class="text-muted">10:21:25 am</small></td>
                                                            <td><span class="badge bg-warning-subtle text-warning p-1">Onhold</span></td>
                                                       </tr>

                                                       <tr>
                                                            <td><img src="/images/users/avatar-4.jpg" alt="" class="avatar-xs rounded-circle me-1">
                                                                 <a href="#javascript: void(0);" class="text-reset align-middle">Barbara Frink</a>
                                                            </td>
                                                            <td>Personal Payment</td>
                                                            <td class="text-danger">-AUD $90.99</td>
                                                            <td>12 Apr,24 <small class="text-muted">06:22:09 pm</small></td>
                                                            <td><span class="badge bg-danger-subtle text-danger p-1">Failed</span></td>
                                                       </tr>

                                                  </tbody><!-- end tbody -->
                                             </table><!-- end table -->
                                        </div><!-- end table responsive -->
                                   </div> <!-- End Card-body -->
                                   <div class="card-footer border-top border-light">
                                        <div class="align-items-center justify-content-between row text-center text-sm-start">
                                             <div class="col-sm">
                                                  <div class="text-muted">
                                                       Showing <span class="fw-semibold text-body"> 5 </span> of <span class="fw-semibold"> 15 </span> Transactions
                                                  </div>
                                             </div>
                                             <div class="col-sm-auto mt-3 mt-sm-0">
                                                  <ul class="pagination pagination-boxed pagination-sm mb-0 justify-content-center">
                                                       <li class="page-item disabled">
                                                            <a href="#" class="page-link"><i class='bx bxs-chevron-left'></i></a>
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
                                                            <a href="#" class="page-link"><i class='bx bxs-chevron-right'></i></a>
                                                       </li>
                                                  </ul>
                                             </div>
                                        </div>
                                   </div>
                              </div> <!-- end card-->
                         </div> <!-- end col -->

                         <div class="col-xxl-4">
                              <div class="card">
                                   <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Revenue Sources</h4>
                                        <div class="dropdown">
                                             <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                                  <iconify-icon icon="iconamoon:menu-kebab-vertical-circle-duotone" class="fs-20 align-middle text-muted"></iconify-icon>
                                             </a>
                                             <div class="dropdown-menu dropdown-menu-end">
                                                  <!-- item-->
                                                  <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                                  <!-- item-->
                                                  <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                                  <!-- item-->
                                                  <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                                  <!-- item-->
                                                  <a href="javascript:void(0);" class="dropdown-item">Action</a>
                                             </div>
                                        </div>
                                   </div> <!-- end card-header-->
                                   <div class="card-body">
                                        <div id="revenue-sources" class="apex-charts mb-2"></div>

                                        <div class="table-responsive mb-n1">
                                             <table class="table table-nowrap table-borderless table-sm table-centered mb-0">
                                                  <thead class="bg-light bg-opacity-50 thead-sm">
                                                       <tr>
                                                            <th class="py-1">Sources</th>
                                                            <th class="py-1">Revenue</th>
                                                            <th class="py-1">Perc.</th>
                                                       </tr>
                                                  </thead>
                                                  <tbody>
                                                       <tr>
                                                            <td>Online</td>
                                                            <td>$187,232</td>
                                                            <td>48.63% <span class="badge badge-soft-success ms-1">2.5% Up</span></td>
                                                       </tr>
                                                       <tr>
                                                            <td>Offline</td>
                                                            <td>$126,874</td>
                                                            <td>36.08% <span class="badge badge-soft-success ms-1">8.5% Up</span></td>
                                                       </tr>
                                                       <tr>
                                                            <td>Direct</td>
                                                            <td>$90,127</td>
                                                            <td>23.41% <span class="badge badge-soft-danger ms-1">10.98% Down</span></td>
                                                       </tr>
                                                  </tbody>
                                             </table>
                                        </div> <!-- end table-responsive-->
                                   </div>
                              </div> <!-- end card-->
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

     <!-- Dashboard Js -->
     <script src="/js/pages/dashboard.finance.js"></script>

</body>

</html>