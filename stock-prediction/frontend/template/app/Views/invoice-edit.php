<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Invoices Edit")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Invoices Edit")) ?>
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
                                        <div class="d-flex flex-wrap justify-content-between gap-3">
                                             <div class="search-bar">
                                                  <span><i class="bx bx-search-alt"></i></span>
                                                  <input type="search" class="form-control" id="search" placeholder="Search invoice...">
                                             </div>
                                             <div>
                                                  <a href="#!" class="btn btn-success">
                                                       <i class="bx bx-plus me-1"></i>Create Invoice
                                                  </a>
                                             </div>
                                        </div> <!-- end row -->
                                   </div>
                                   <div>
                                        <div class="table-responsive table-centered">
                                             <table class="table text-nowrap mb-0">
                                                  <thead class="bg-light bg-opacity-50">
                                                       <tr>
                                                            <th class="border-0 py-2">Invoice ID</th>
                                                            <th class="border-0 py-2">Customer</th>
                                                            <th class="border-0 py-2">Created Date</th>
                                                            <th class="border-0 py-2">Due Date</th>
                                                            <th class="border-0 py-2">Amount</th>
                                                            <th class="border-0 py-2">Payment Status</th>
                                                            <th class="border-0 py-2">Via</th>
                                                            <th class="border-0 py-2">Action</th>
                                                       </tr>
                                                  </thead> <!-- end thead-->
                                                  <tbody>
                                                       <tr>
                                                            <td>
                                                                 <a href="invoice-details" class="fw-medium">#RB6985</a>
                                                            </td>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <img src="s/avatar-2.jpg" alt="" class="avatar-xs rounded-circle me-2">
                                                                      <div>
                                                                           <h5 class="fs-14 m-0 fw-normal">Sean Kemper</h5>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>23 April, 2024 <small>05:09 PM</small></td>
                                                            <td>30 April, 2024</td>
                                                            <td>$852.25</td>
                                                            <td>
                                                                 <span class="badge badge-soft-warning">Unpaid</span>
                                                            </td>
                                                            <td>PayPal</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <a href="invoice-details" class="fw-medium">#RB1002</a>
                                                            </td>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <img src="s/avatar-3.jpg" alt="" class="avatar-xs rounded-circle me-2">
                                                                      <div>
                                                                           <h5 class="fs-14 m-0 fw-normal">Victoria Sullivan</h5>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>14 May, 2024 <small>10:51 AM</small></td>
                                                            <td>25 Aug, 2024</td>
                                                            <td>$953.00</td>
                                                            <td>
                                                                 <span class="badge badge-soft-primary">Send</span>
                                                            </td>
                                                            <td>PayPal</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <a href="invoice-details" class="fw-medium">#RB3652</a>
                                                            </td>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <img src="s/avatar-4.jpg" alt="" class="avatar-xs rounded-circle me-2">
                                                                      <div>
                                                                           <h5 class="fs-14 m-0 fw-normal">Liam Martinez</h5>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>12 April, 2024 <small>12:09 PM</small></td>
                                                            <td>28 April, 2024</td>
                                                            <td>$99.00</td>
                                                            <td>
                                                                 <span class="badge badge-soft-warning">Unpaid</span>
                                                            </td>
                                                            <td>Swift Transfer</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <a href="invoice-details" class="fw-medium">#RB7854</a>
                                                            </td>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <img src="s/avatar-5.jpg" alt="" class="avatar-xs rounded-circle me-2">
                                                                      <div>
                                                                           <h5 class="fs-14 m-0 fw-normal">Emma Johnson</h5>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>10 April, 2024 <small>10:09 PM</small></td>
                                                            <td>15 April, 2024</td>
                                                            <td>$1250.00</td>
                                                            <td>
                                                                 <span class="badge badge-soft-success">Paid</span>
                                                            </td>
                                                            <td>PayPal</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <a href="invoice-details" class="fw-medium">#RB9521</a>
                                                            </td>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <img src="s/avatar-1.jpg" alt="" class="avatar-xs rounded-circle me-2">
                                                                      <div>
                                                                           <h5 class="fs-14 m-0 fw-normal">Olivia Thompson</h5>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>22 May, 2024 <small>03:41 PM</small></td>
                                                            <td>05 July, 2024</td>
                                                            <td>$500.00</td>
                                                            <td>
                                                                 <span class="badge badge-soft-primary">Send</span>
                                                            </td>
                                                            <td>Payoneer</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <a href="invoice-details" class="fw-medium">#RB9634</a>
                                                            </td>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <img src="s/avatar-6.jpg" alt="" class="avatar-xs rounded-circle me-2">
                                                                      <div>
                                                                           <h5 class="fs-14 m-0 fw-normal">Noah Garcia</h5>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>18 May, 2024 <small>09:09 AM</small></td>
                                                            <td>30 May, 2024</td>
                                                            <td>$250.00</td>
                                                            <td>
                                                                 <span class="badge badge-soft-success">Paid</span>
                                                            </td>
                                                            <td>Bank</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <a href="invoice-details" class="fw-medium">#RB8520</a>
                                                            </td>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <img src="s/avatar-7.jpg" alt="" class="avatar-xs rounded-circle me-2">
                                                                      <div>
                                                                           <h5 class="fs-14 m-0 fw-normal">Sophia Davis</h5>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>05 April, 2024 <small>08:50 AM</small></td>
                                                            <td>22 April, 2024</td>
                                                            <td>$29.00</td>
                                                            <td>
                                                                 <span class="badge badge-soft-success">Paid</span>
                                                            </td>
                                                            <td>PayPal</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <a href="invoice-details" class="fw-medium">#RB3590</a>
                                                            </td>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <img src="s/avatar-8.jpg" alt="" class="avatar-xs rounded-circle me-2">
                                                                      <div>
                                                                           <h5 class="fs-14 m-0 fw-normal">Isabella Lopez</h5>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>15 Jun, 2024 <small>11:09 PM</small></td>
                                                            <td>01 Aug, 2024</td>
                                                            <td>$24.99</td>
                                                            <td>
                                                                 <span class="badge badge-soft-primary">Send</span>
                                                            </td>
                                                            <td>Swift</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <a href="invoice-details" class="fw-medium">#RB5872</a>
                                                            </td>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <img src="s/avatar-9.jpg" alt="" class="avatar-xs rounded-circle me-2">
                                                                      <div>
                                                                           <h5 class="fs-14 m-0 fw-normal">Ava Wilson</h5>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>22 April, 2024 <small>05:09 PM</small></td>
                                                            <td>30 April, 2024</td>
                                                            <td>$1000.00</td>
                                                            <td>
                                                                 <span class="badge badge-soft-warning">Unpaid</span>
                                                            </td>
                                                            <td>Payoneer</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <td>
                                                                 <a href="invoice-details" class="fw-medium">#RB1158</a>
                                                            </td>
                                                            <td>
                                                                 <div class="d-flex align-items-center">
                                                                      <img src="s/avatar-10.jpg" alt="" class="avatar-xs rounded-circle me-2">
                                                                      <div>
                                                                           <h5 class="fs-14 m-0 fw-normal">Oliver Lee</h5>
                                                                      </div>
                                                                 </div>
                                                            </td>
                                                            <td>23 April, 2024 <small>12:09 PM</small></td>
                                                            <td>30 April, 2024</td>
                                                            <td>$1999.00</td>
                                                            <td>
                                                                 <span class="badge badge-soft-warning">Unpaid</span>
                                                            </td>
                                                            <td>Wise</td>
                                                            <td>
                                                                 <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                                 <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                            </td>
                                                       </tr>

                                                  </tbody> <!-- end tbody -->
                                             </table> <!-- end table -->
                                        </div> <!-- table responsive -->
                                        <div class="align-items-center justify-content-between row g-0 text-center text-sm-start p-3 border-top">
                                             <div class="col-sm">
                                                  <div class="text-muted">
                                                       Showing <span class="fw-semibold">10</span> of <span class="fw-semibold">52</span> invoices
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