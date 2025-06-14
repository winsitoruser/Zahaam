<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Roles List")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Roles List")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-xxl">

                    <div class="card overflow-hiddenCoupons">
                         <div class="card-body p-0">
                              <div class="table-responsive">
                                   <table class="table align-middle mb-0 table-hover table-centered">
                                        <thead class="bg-light-subtle">
                                             <tr>
                                                  <th>Role</th>
                                                  <th>Workspace</th>
                                                  <th>Tags</th>
                                                  <th>Users</th>
                                                  <th>Status</th>
                                                  <th>Action</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             <tr>
                                                  <td>Workspace Manager</td>
                                                  <td>
                                                       <img src="/images/app-calendar/facebook.png" class="avatar-xs rounded-circle me-1" alt="..."> Facebook
                                                  </td>
                                                  <td> <span class="badge bg-light-subtle text-muted border py-1 px-2">Manager</span> <span class="badge bg-light-subtle text-muted border py-1 px-2">Product</span></td>
                                                  <td>
                                                       <div class="avatar-group">
                                                            <div class="avatar">
                                                                 <img src="/images/users/avatar-4.jpg" alt="" class="rounded-circle avatar-sm">
                                                            </div>
                                                            <div class="avatar">
                                                                 <span class=" avatar-sm d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle fw-bold shadow">
                                                                      P
                                                                 </span>
                                                            </div>
                                                            <div class="avatar">
                                                                 <img src="/images/users/avatar-3.jpg" alt="" class="rounded-circle avatar-sm">
                                                            </div>
                                                            <div class="avatar">
                                                                 <img src="/images/users/avatar-6.jpg" alt="" class="rounded-circle avatar-sm">
                                                            </div>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked1" checked>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="d-flex gap-2">
                                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="role-edit" class="btn btn-soft-primary btn-sm"><iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                       </div>
                                                  </td>
                                             </tr>

                                             <tr>
                                                  <td>Product Owner</td>
                                                  <td>
                                                       <img src="/images/app-calendar/slack.png" class="avatar-xs rounded-circle me-1" alt="..."> Slack
                                                  </td>
                                                  <td> <span class="badge bg-light-subtle text-muted border py-1 px-2">Manager</span> <span class="badge bg-light-subtle text-muted border py-1 px-2">Product</span> <span class="badge bg-light-subtle text-muted border py-1 px-2">Data</span></td>
                                                  <td>
                                                       <div class="avatar-group">
                                                            <div class="avatar">
                                                                 <img src="/images/users/avatar-6.jpg" alt="" class="rounded-circle avatar-sm">
                                                            </div>
                                                            <div class="avatar">
                                                                 <img src="/images/users/avatar-7.jpg" alt="" class="rounded-circle avatar-sm">
                                                            </div>
                                                            <div class="avatar">
                                                                 <span class=" avatar-sm d-flex align-items-center justify-content-center bg-info-subtle text-info rounded-circle fw-bold shadow">
                                                                      +12
                                                                 </span>
                                                            </div>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked4">
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="d-flex gap-2">
                                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="role-edit" class="btn btn-soft-primary btn-sm"><iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                       </div>
                                                  </td>
                                             </tr>

                                             <tr>
                                                  <td>Product Designer</td>
                                                  <td>
                                                       <img src="/images/app-calendar/zoom.png" class="avatar-xs rounded-circle me-1" alt="..."> Zoom
                                                  </td>
                                                  <td><span class="badge bg-light-subtle text-muted border py-1 px-2">Designer</span> <span class="badge bg-light-subtle text-muted border py-1 px-2">Data</span></td>
                                                  <td>
                                                       <div class="avatar">
                                                            <img src="/images/users/avatar-10.jpg" alt="" class="rounded-circle avatar-sm">
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked2" checked>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="d-flex gap-2">
                                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="role-edit" class="btn btn-soft-primary btn-sm"><iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                       </div>
                                                  </td>
                                             </tr>

                                             <tr>
                                                  <td>Data Analyst</td>
                                                  <td>
                                                       <img src="/images/app-calendar/google-analytics.png" class="avatar-xs rounded-circle me-1" alt="..."> Analytics
                                                  </td>
                                                  <td> <span class="badge bg-light-subtle text-muted border py-1 px-2">Data</span></td>
                                                  <td>
                                                       <div class="avatar-group">
                                                            <div class="avatar">
                                                                 <img src="/images/users/avatar-10.jpg" alt="" class="rounded-circle avatar-sm">
                                                            </div>
                                                            <div class="avatar">
                                                                 <img src="/images/users/avatar-11.jpg" alt="" class="rounded-circle avatar-sm">
                                                            </div>
                                                            <div class="avatar">
                                                                 <img src="/images/users/avatar-12.jpg" alt="" class="rounded-circle avatar-sm">
                                                            </div>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked4">
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="d-flex gap-2">
                                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="role-edit" class="btn btn-soft-primary btn-sm"><iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                       </div>
                                                  </td>
                                             </tr>

                                             <tr>
                                                  <td>Tech Lead</td>
                                                  <td>
                                                       <a href="#!" class="link-primary">+ Add Workspace</a>
                                                  </td>
                                                  <td> <span class="badge bg-light-subtle text-muted border py-1 px-2">Product</span> <span class="badge bg-light-subtle text-muted border py-1 px-2">Data</span> <span class="badge bg-light-subtle text-muted border py-1 px-2">Supporter</span></td>
                                                  <td> <a href="#!" class="link-primary">+ Add User</a></td>
                                                  <td>
                                                       <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked4">
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="d-flex gap-2">
                                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="role-edit" class="btn btn-soft-primary btn-sm"><iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                       </div>
                                                  </td>
                                             </tr>

                                             <tr>
                                                  <td>Product Manager</td>
                                                  <td>
                                                       <img src="/images/app-calendar/google-meet.png" class="avatar-xs rounded-circle me-1" alt="..."> Meet
                                                  </td>
                                                  <td> <span class="badge bg-light-subtle text-muted border py-1 px-2">System Design</span></td>
                                                  <td>
                                                       <div class="avatar-group">
                                                            <div class="avatar">
                                                                 <span class=" avatar-sm d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle fw-bold shadow">
                                                                      A
                                                                 </span>
                                                            </div>
                                                            <div class="avatar">
                                                                 <span class=" avatar-sm d-flex align-items-center justify-content-center bg-info-subtle text-info rounded-circle fw-bold shadow">
                                                                      J
                                                                 </span>
                                                            </div>
                                                            <div class="avatar">
                                                                 <span class=" avatar-sm d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle fw-bold shadow">
                                                                      K
                                                                 </span>
                                                            </div>
                                                            <div class="avatar">
                                                                 <span class=" avatar-sm d-flex align-items-center justify-content-center bg-dark text-white rounded-circle fw-bold shadow">
                                                                      +17
                                                                 </span>
                                                            </div>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked3" checked>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="d-flex gap-2">
                                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="role-edit" class="btn btn-soft-primary btn-sm"><iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                       </div>
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td>Chief Product Owner</td>
                                                  <td>
                                                       <img src="/images/app-calendar/google-mail.png" class="avatar-xs rounded-circle me-1" alt="..."> Mail
                                                  </td>
                                                  <td> <span class="badge bg-light-subtle text-muted border py-1 px-2">Manager</span> <span class="badge bg-light-subtle text-muted border py-1 px-2">Product</span></td>
                                                  <td>
                                                       <div class="avatar-group">
                                                            <div class="avatar">
                                                                 <img src="/images/users/avatar-2.jpg" alt="" class="rounded-circle avatar-sm">
                                                            </div>
                                                            <div class="avatar">
                                                                 <img src="/images/users/avatar-12.jpg" alt="" class="rounded-circle avatar-sm">
                                                            </div>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked4" checked>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="d-flex gap-2">
                                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="role-edit" class="btn btn-soft-primary btn-sm"><iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                       </div>
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td>Support Team Head</td>
                                                  <td>
                                                       <img src="/images/app-calendar/stripe.png" class="avatar-xs rounded-circle me-1" alt="..."> Strip
                                                  </td>
                                                  <td> <span class="badge bg-light-subtle text-muted border py-1 px-2">QA</span></td>
                                                  <td>
                                                       <div class="avatar">
                                                            <span class=" avatar-sm d-flex align-items-center justify-content-center bg-warning-subtle text-warning rounded-circle fw-bold shadow">
                                                                 K
                                                            </span>
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked5">
                                                       </div>
                                                  </td>
                                                  <td>
                                                       <div class="d-flex gap-2">
                                                            <a href="#!" class="btn btn-light btn-sm"><iconify-icon icon="solar:eye-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="role-edit" class="btn btn-soft-primary btn-sm"><iconify-icon icon="solar:pen-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                            <a href="#!" class="btn btn-soft-danger btn-sm"><iconify-icon icon="solar:trash-bin-minimalistic-2-broken" class="align-middle fs-18"></iconify-icon></a>
                                                       </div>
                                                  </td>
                                             </tr>
                                        </tbody>
                                   </table>
                              </div>
                              <!-- end table-responsive -->
                         </div>
                         <div class="row g-0 align-items-center justify-content-between text-center text-sm-start p-3 border-top">
                              <div class="col-sm">
                                   <div class="text-muted">
                                        Showing <span class="fw-semibold">10</span> of <span class="fw-semibold">59</span> Results
                                   </div>
                              </div>
                              <div class="col-sm-auto mt-3 mt-sm-0">
                                   <ul class="pagination  m-0">
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