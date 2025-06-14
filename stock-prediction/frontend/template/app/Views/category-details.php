<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Category List")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Category List")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-xxl">

                    <div class="row">
                         <div class="col-12">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                             <div>
                                                  <form class="d-flex flex-wrap align-items-center gap-2">
                                                       <label for="inputPassword2" class="visually-hidden">Search</label>
                                                       <div class="search-bar me-3">
                                                            <span><i class="bx bx-search-alt"></i></span>
                                                            <input type="search" class="form-control" id="search" placeholder="Search ...">
                                                       </div>

                                                       <label for="status-select" class="me-2">Sort By</label>
                                                       <div class="me-sm-3">
                                                            <select class="form-select my-1 my-md-0" id="status-select">
                                                                 <option selected="">All</option>
                                                                 <option value="1">Name</option>
                                                                 <option value="2">Joining Date</option>
                                                                 <option value="3">Phone</option>
                                                                 <option value="4">Orders</option>
                                                            </select>
                                                       </div>
                                                  </form>
                                             </div>
                                             <div>
                                                  <div class="d-flex flex-wrap gap-2 justify-content-md-end align-items-center">
                                                       <ul class="nav nav-pills bg-transparent  gap-1 p-0">
                                                            <li class="nav-item">
                                                                 <a href="#team-grid" class="nav-link" data-bs-toggle="tab" aria-expanded="false" data-bs-toggle="tooltip" data-bs-placement="top" title="Grid">
                                                                      <i class="bx bx-grid-alt"></i>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#team-list" class="nav-link active" data-bs-toggle="tab" aria-expanded="false" data-bs-toggle="tooltip" data-bs-placement="top" title="List">
                                                                      <i class="bx bx-list-ul"></i>
                                                                 </a>
                                                            </li>
                                                       </ul>

                                                       <div class="dropdown">
                                                            <a href="javascript:void(0);" class="dropdown-toggle btn btn-soft-success arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                 <i class="bx bx-sort me-1"></i>Filter
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                 <a href="javascript:void(0);" class="dropdown-item">By Date</a>
                                                                 <a href="javascript:void(0);" class="dropdown-item">By Order ID</a>
                                                                 <a href="javascript:void(0);" class="dropdown-item">By City</a>
                                                            </div>
                                                       </div>

                                                       <a href="#!" class="btn btn-danger">
                                                            <i class="bi bi-plus-circle me-1"></i>Add Customer
                                                       </a>
                                                  </div>
                                             </div><!-- end col-->
                                        </div> <!-- end row -->
                                   </div>
                              </div> <!-- end card -->
                         </div><!-- end col-->
                    </div>


                    <div class="tab-content pt-0">
                         <div class="tab-pane show active" id="team-list">

                              <div class="card overflow-hidden">
                                   <div class="table-responsive table-centered">
                                        <table class="table text-nowrap mb-0">
                                             <thead class="teble-light">
                                                  <tr>
                                                       <th>Customer Name</th>
                                                       <th>Date</th>
                                                       <th>Email ID</th>
                                                       <th>Phone No.</th>
                                                       <th>Location</th>
                                                       <th>Orders</th>
                                                       <th>Action</th>
                                                  </tr>
                                             </thead> <!-- end thead-->
                                             <tbody>
                                                  <tr>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                 <img src="/images/users/avatar-1.jpg" alt="avatar-1" class="img-fluid avatar-xs rounded-circle avatar-border me-1" />
                                                                 <a href="#!">Anna M. Hines</a>
                                                            </div>
                                                       </td>
                                                       <td>23 April 2024</td>
                                                       <td>anna.hines@mail.com</td>
                                                       <td>(+1)-555-1564-261</td>
                                                       <td>Burr Ridge/Illinois</td>
                                                       <td>15</td>
                                                       <td>
                                                            <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                            <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                 <img src="/images/users/avatar-2.jpg" alt="avatar-2" class="img-fluid avatar-xs rounded-circle avatar-border me-1" />
                                                                 <a href="#!">Candice F. Gilmore</a>
                                                            </div>
                                                       </td>
                                                       <td>12 April 2024</td>
                                                       <td>candice.gilmore@mail.com</td>
                                                       <td>(+257)-755-5532-588</td>
                                                       <td>Roselle/Illinois</td>
                                                       <td>215</td>
                                                       <td>
                                                            <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                            <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                 <img src="/images/users/avatar-3.jpg" alt="avatar-3" class="img-fluid avatar-xs rounded-circle avatar-border me-1" />
                                                                 <a href="#!">Vanessa R. Davis</a>
                                                            </div>
                                                       </td>
                                                       <td>15 March 2024</td>
                                                       <td>vanessa.davis@mail.com</td>
                                                       <td>(+1)-441-5558-183</td>
                                                       <td>Wann/Oklahoma</td>
                                                       <td>125</td>
                                                       <td>
                                                            <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                            <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                 <img src="/images/users/avatar-4.jpg" alt="avatar-4" class="img-fluid avatar-xs rounded-circle avatar-border me-1" />
                                                                 <a href="#!">Judith H. Fritsche</a>
                                                            </div>
                                                       </td>
                                                       <td>11 January 2024</td>
                                                       <td>judith.fritsche.com</td>
                                                       <td>(+57)-305-5579-759</td>
                                                       <td>SULLIVAN/Kentucky</td>
                                                       <td>05</td>
                                                       <td>
                                                            <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                            <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                 <img src="/images/users/avatar-5.jpg" alt="avatar-5" class="img-fluid avatar-xs rounded-circle avatar-border me-1" />
                                                                 <a href="#!">Peter T. Smith</a>
                                                            </div>
                                                       </td>
                                                       <td>03 December 2023</td>
                                                       <td>peter.smith@mail.com</td>
                                                       <td>(+33)-655-5187-93</td>
                                                       <td>Yreka/California</td>
                                                       <td>15</td>
                                                       <td>
                                                            <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                            <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                 <img src="/images/users/avatar-6.jpg" alt="avatar-6" class="img-fluid avatar-xs rounded-circle avatar-border me-1" />
                                                                 <a href="#!">Emmanuel J. Delcid</a>
                                                            </div>
                                                       </td>
                                                       <td>12 April 2024</td>
                                                       <td>emmanuel.delicid@mail.com</td>
                                                       <td>(+30)-693-5553-637</td>
                                                       <td>Atlanta/Georgia</td>
                                                       <td>10</td>
                                                       <td>
                                                            <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                            <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                 <img src="/images/users/avatar-7.jpg" alt="avatar-7" class="img-fluid avatar-xs rounded-circle avatar-border me-1" />
                                                                 <a href="#!">William J. Cook</a>
                                                            </div>
                                                       </td>
                                                       <td>13 November 2023</td>
                                                       <td>william.cook@mail.com</td>
                                                       <td>(+91)-855-5446-150</td>
                                                       <td>Rosenberg/Texas</td>
                                                       <td>85</td>
                                                       <td>
                                                            <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                            <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                 <img src="/images/users/avatar-8.jpg" alt="avatar-8" class="img-fluid avatar-xs rounded-circle avatar-border me-1" />
                                                                 <a href="#!">Martin R. Peters</a>
                                                            </div>
                                                       </td>
                                                       <td>25 August 2023</td>
                                                       <td>martin.peters@mail.com</td>
                                                       <td>(+61)-455-5943-13</td>
                                                       <td>Youngstown/Ohio</td>
                                                       <td>03</td>
                                                       <td>
                                                            <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                            <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                 <img src="/images/users/avatar-9.jpg" alt="avatar-9" class="img-fluid avatar-xs rounded-circle avatar-border me-1" />
                                                                 <a href="#!">Paul M. Schubert</a>
                                                            </div>
                                                       </td>
                                                       <td>28 April 2024</td>
                                                       <td>paul.schubert@mail.com</td>
                                                       <td>(+61)-035-5531-64</td>
                                                       <td>Austin/Texas</td>
                                                       <td>181</td>
                                                       <td>
                                                            <button type="button" class="btn btn-sm btn-soft-secondary me-1"><i class="bx bx-edit fs-16"></i></button>
                                                            <button type="button" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-16"></i></button>
                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <td>
                                                            <div class="d-flex align-items-center gap-1">
                                                                 <img src="/images/users/avatar-10.jpg" alt="avatar-10" class="img-fluid avatar-xs rounded-circle avatar-border me-1" />
                                                                 <a href="#!">Janet J. Champine</a>
                                                            </div>
                                                       </td>
                                                       <td>06 May 2023</td>
                                                       <td>janet.champine@mail.com</td>
                                                       <td>(+880)-115-5592-916</td>
                                                       <td>Nashville/Tennessee</td>
                                                       <td>521</td>
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
                                                  Showing <span class="fw-semibold">10</span> of <span class="fw-semibold">2,852</span> Results
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
                              </div> <!-- end card -->
                         </div>

                         <div class="tab-pane" id="team-grid">
                              <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5 gx-3">
                                   <div class="col">
                                        <div class="card">
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <img src="/images/users/avatar-1.jpg" alt="avatar-1" class="img-fluid avatar-xxl img-thumbnail rounded-circle avatar-border" />
                                                       <h4 class="mt-3"><a href="javascript:void(0);">Anna M. Hines</a></h4>
                                                       <a href="javascript:void(0);" class="mb-1 d-inline-block"><i class="bx bx-location-plus text-danger fs-14 me-1"></i>Burr Ridge/Illinois</a> <br>
                                                       <a href="javascript:void(0);"><i class="bx bx-phone-call text-success fs-14 me-1"></i>555-1564-261</a> <br>
                                                       <a href="#!" class="btn btn-sm btn-outline-primary mt-2">View All Details</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col">
                                        <div class="card">
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <img src="/images/users/avatar-2.jpg" alt="avatar-2" class="img-fluid avatar-xxl img-thumbnail rounded-circle avatar-border" />
                                                       <h4 class="mt-3"><a href="javascript:void(0);">Candice F. Gilmore</a></h4>
                                                       <a href="javascript:void(0);" class="mb-1 d-inline-block"><i class="bx bx-location-plus text-danger fs-14 me-1"></i>Roselle/Illinois</a> <br>
                                                       <a href="javascript:void(0);"><i class="bx bx-phone-call text-success fs-14 me-1"></i>755-5532-588</a> <br>
                                                       <a href="#!" class="btn btn-sm btn-outline-primary mt-2">View All Details</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col">
                                        <div class="card">
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <img src="/images/users/avatar-3.jpg" alt="avatar-3" class="img-fluid avatar-xxl img-thumbnail rounded-circle avatar-border" />
                                                       <h4 class="mt-3"><a href="javascript:void(0);">Vanessa R. Davis</a></h4>
                                                       <a href="javascript:void(0);" class="mb-1 d-inline-block"><i class="bx bx-location-plus text-danger fs-14 me-1"></i>Wann/Oklahoma</a> <br>
                                                       <a href="javascript:void(0);"><i class="bx bx-phone-call text-success fs-14 me-1"></i>441-5558-183</a> <br>
                                                       <a href="#!" class="btn btn-sm btn-outline-primary mt-2">View All Details</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col">
                                        <div class="card">
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <img src="/images/users/avatar-4.jpg" alt="avatar-4" class="img-fluid avatar-xxl img-thumbnail rounded-circle avatar-border" />
                                                       <h4 class="mt-3"><a href="javascript:void(0);">Judith H. Fritsche</a></h4>
                                                       <a href="javascript:void(0);" class="mb-1 d-inline-block"><i class="bx bx-location-plus text-danger fs-14 me-1"></i>SULLIVAN/Kentucky</a> <br>
                                                       <a href="javascript:void(0);"><i class="bx bx-phone-call text-success fs-14 me-1"></i>305-5579-759</a> <br>
                                                       <a href="#!" class="btn btn-sm btn-outline-primary mt-2">View All Details</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col">
                                        <div class="card">
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <img src="/images/users/avatar-5.jpg" alt="avatar-5" class="img-fluid avatar-xxl img-thumbnail rounded-circle avatar-border" />
                                                       <h4 class="mt-3"><a href="javascript:void(0);">Peter T. Smith</a></h4>
                                                       <a href="javascript:void(0);" class="mb-1 d-inline-block"><i class="bx bx-location-plus text-danger fs-14 me-1"></i>Yreka/California</a> <br>
                                                       <a href="javascript:void(0);"><i class="bx bx-phone-call text-success fs-14 me-1"></i>655-5187-93</a> <br>
                                                       <a href="#!" class="btn btn-sm btn-outline-primary mt-2">View All Details</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col">
                                        <div class="card">
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <img src="/images/users/avatar-6.jpg" alt="avatar-6" class="img-fluid avatar-xxl img-thumbnail rounded-circle avatar-border" />
                                                       <h4 class="mt-3"><a href="javascript:void(0);">Emmanuel J. Delcid</a></h4>
                                                       <a href="javascript:void(0);" class="mb-1 d-inline-block"><i class="bx bx-location-plus text-danger fs-14 me-1"></i>Atlanta/Georgia</a> <br>
                                                       <a href="javascript:void(0);"><i class="bx bx-phone-call text-success fs-14 me-1"></i>693-5553-637</a> <br>
                                                       <a href="#!" class="btn btn-sm btn-outline-primary mt-2">View All Details</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col">
                                        <div class="card">
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <img src="/images/users/avatar-7.jpg" alt="avatar-7" class="img-fluid avatar-xxl img-thumbnail rounded-circle avatar-border" />
                                                       <h4 class="mt-3"><a href="javascript:void(0);">William J. Cook</a></h4>
                                                       <a href="javascript:void(0);" class="mb-1 d-inline-block"><i class="bx bx-location-plus text-danger fs-14 me-1"></i>Rosenberg/Texas</a> <br>
                                                       <a href="javascript:void(0);"><i class="bx bx-phone-call text-success fs-14 me-1"></i>855-5446-150</a> <br>
                                                       <a href="#!" class="btn btn-sm btn-outline-primary mt-2">View All Details</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col">
                                        <div class="card">
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <img src="/images/users/avatar-8.jpg" alt="avatar-8" class="img-fluid avatar-xxl img-thumbnail rounded-circle avatar-border" />
                                                       <h4 class="mt-3"><a href="javascript:void(0);">Martin R. Peters</a></h4>
                                                       <a href="javascript:void(0);" class="mb-1 d-inline-block"><i class="bx bx-location-plus text-danger fs-14 me-1"></i>Youngstown/Ohio</a> <br>
                                                       <a href="javascript:void(0);"><i class="bx bx-phone-call text-success fs-14 me-1"></i>455-5943-13</a> <br>
                                                       <a href="#!" class="btn btn-sm btn-outline-primary mt-2">View All Details</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col">
                                        <div class="card">
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <img src="/images/users/avatar-9.jpg" alt="avatar-9" class="img-fluid avatar-xxl img-thumbnail rounded-circle avatar-border" />
                                                       <h4 class="mt-3"><a href="javascript:void(0);">Paul M. Schubert</a></h4>
                                                       <a href="javascript:void(0);" class="mb-1 d-inline-block"><i class="bx bx-location-plus text-danger fs-14 me-1"></i>Austin/Texas</a> <br>
                                                       <a href="javascript:void(0);"><i class="bx bx-phone-call text-success fs-14 me-1"></i>035-5531-64</a> <br>
                                                       <a href="#!" class="btn btn-sm btn-outline-primary mt-2">View All Details</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                                   <div class="col">
                                        <div class="card">
                                             <div class="card-body">
                                                  <div class="text-center">
                                                       <img src="/images/users/avatar-10.jpg" alt="avatar-10" class="img-fluid avatar-xxl img-thumbnail rounded-circle avatar-border" />
                                                       <h4 class="mt-3"><a href="javascript:void(0);">Janet J. Champine</a></h4>
                                                       <a href="javascript:void(0);" class="mb-1 d-inline-block"><i class="bx bx-location-plus text-danger fs-14 me-1"></i>Nashville/Tennessee</a> <br>
                                                       <a href="javascript:void(0);"><i class="bx bx-phone-call text-success fs-14 me-1"></i>115-5592-916</a> <br>
                                                       <a href="#!" class="btn btn-sm btn-outline-primary mt-2">View All Details</a>
                                                  </div>
                                             </div> <!-- end card body -->
                                        </div> <!-- end card -->
                                   </div> <!-- end col -->
                              </div> <!-- end row -->
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