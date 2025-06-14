<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Help Center")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Help Center")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-xxl">
                    <div class="row justify-content-center">
                         <div class="col-lg-12">
                              <div class="card overflow-hidden" style="background: url('/images/small/img-2.jpg'); ">
                                   <div class="position-absolute top-0 end-0 bottom-0 start-0 bg-dark opacity-75"></div>
                                   <div class="card-body">
                                        <div class="row justify-content-center">
                                             <div class="col-lg-7 text-center">
                                                  <h3 class="text-white">Help Center</h3>

                                                  <p class="text-white-50">How can we help you ?</p>

                                                  <div class="search-bar">
                                                       <span><i class="bx bx-search-alt"></i></span>
                                                       <input type="search" class="form-control rounded-pill bg-white border-0" id="search" placeholder="Search ...">
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="row">
                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="avatar bg-primary-subtle d-flex align-items-center justify-content-center rounded mb-2">
                                             <iconify-icon icon="solar:round-arrow-right-bold" class="fs-24 text-primary"></iconify-icon>
                                        </div>
                                        <h4 class="mt-3">Getting Started with Larkon</h4>
                                        <p>Welcome to Larkon Dive into basic for a swift on boarding experience</p>

                                        <div class="d-flex align-items-center gap-2">
                                             <img src="/images/users/avatar-1.jpg" alt="user photo" class="avatar-sm rounded-circle">
                                             <p class="mb-0">by Aston Martin</p>

                                             <div class="vr bg-dark-subtle h-75 my-auto"></div>

                                             <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="link-primary">19 Video</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="avatar bg-primary-subtle d-flex align-items-center justify-content-center rounded mb-2">
                                             <iconify-icon icon="solar:user-circle-bold" class="fs-24 text-primary"></iconify-icon>
                                        </div>

                                        <h4 class="mt-3">Admin Settings</h4>
                                        <p>Learn how to manage your current workspace or your enterprise space</p>

                                        <div class="d-flex align-items-center gap-2">
                                             <img src="/images/users/avatar-2.jpg" alt="user photo" class="avatar-sm rounded-circle">
                                             <p class="mb-0">by Michael A. Miner</p>

                                             <div class="vr bg-dark-subtle h-75 my-auto"></div>

                                             <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="link-primary">10 Video</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="avatar bg-primary-subtle d-flex align-items-center justify-content-center rounded mb-2">
                                             <iconify-icon icon="solar:display-bold" class="fs-24 text-primary"></iconify-icon>
                                        </div>

                                        <h4 class="mt-3">Server Setup</h4>
                                        <p>Connect, simplify, and automate. Discover the power of apps and tools.</p>

                                        <div class="d-flex align-items-center gap-2">
                                             <img src="/images/users/avatar-3.jpg" alt="user photo" class="avatar-sm rounded-circle">
                                             <p class="mb-0">by Theresa T. Brose</p>

                                             <div class="vr bg-dark-subtle h-75 my-auto"></div>

                                             <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="link-primary">07 Video</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="avatar bg-primary-subtle d-flex align-items-center justify-content-center rounded mb-2">
                                             <iconify-icon icon="solar:login-3-bold" class="fs-24 text-primary"></iconify-icon>
                                        </div>

                                        <h4 class="mt-3">Login And Verification</h4>
                                        <p>Read on to learn how to sign in with your email address, or your Apple or Google.</p>

                                        <div class="d-flex align-items-center gap-2">
                                             <img src="/images/users/avatar-4.jpg" alt="user photo" class="avatar-sm rounded-circle">
                                             <p class="mb-0">by James L. Erickson</p>

                                             <div class="vr bg-dark-subtle h-75 my-auto"></div>

                                             <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="link-primary">03 Video</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="avatar bg-primary-subtle d-flex align-items-center justify-content-center rounded mb-2">
                                             <iconify-icon icon="solar:shield-user-bold" class="fs-24 text-primary"></iconify-icon>
                                        </div>

                                        <h4 class="mt-3">Account Setup</h4>
                                        <p>Adjust your profile and preferences to make ChatCloud work just for you</p>

                                        <div class="d-flex align-items-center gap-2">
                                             <img src="/images/users/avatar-5.jpg" alt="user photo" class="avatar-sm rounded-circle">
                                             <p class="mb-0">by Lily Wilson</p>

                                             <div class="vr bg-dark-subtle h-75 my-auto"></div>

                                             <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="link-primary">11 Video</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="avatar bg-primary-subtle d-flex align-items-center justify-content-center rounded mb-2">
                                             <iconify-icon icon="solar:hand-shake-bold" class="fs-24 text-primary"></iconify-icon>
                                        </div>

                                        <h4 class="mt-3">Trust & Safety</h4>
                                        <p>Trust on our current database and learn how we distribute your data.</p>

                                        <div class="d-flex align-items-center gap-2">
                                             <img src="/images/users/avatar-6.jpg" alt="user photo" class="avatar-sm rounded-circle">
                                             <p class="mb-0">by Sarah Brooks</p>

                                             <div class="vr bg-dark-subtle h-75 my-auto"></div>

                                             <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="link-primary">09 Video</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="avatar bg-primary-subtle d-flex align-items-center justify-content-center rounded mb-2">
                                             <iconify-icon icon="solar:settings-bold" class="fs-24 text-primary"></iconify-icon>
                                        </div>

                                        <h4 class="mt-3">Channel Setup</h4>
                                        <p>From channels to search, learn how ChatCloud works from top to bottom.</p>

                                        <div class="d-flex align-items-center gap-2">
                                             <img src="/images/users/avatar-7.jpg" alt="user photo" class="avatar-sm rounded-circle">
                                             <p class="mb-0">by Joe K. Hall</p>

                                             <div class="vr bg-dark-subtle h-75 my-auto"></div>

                                             <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="link-primary">14 Video</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="avatar bg-primary-subtle d-flex align-items-center justify-content-center rounded mb-2">
                                             <iconify-icon icon="solar:lock-keyhole-minimalistic-bold" class="fs-24 text-primary"></iconify-icon>
                                        </div>

                                        <h4 class="mt-3">Permissions</h4>
                                        <p>Permission for you and others to join and work within a workspace</p>

                                        <div class="d-flex align-items-center gap-2">
                                             <img src="/images/users/avatar-8.jpg" alt="user photo" class="avatar-sm rounded-circle">
                                             <p class="mb-0">by Robert Leavitt</p>

                                             <div class="vr bg-dark-subtle h-75 my-auto"></div>

                                             <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="link-primary">17 Video</a>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="avatar bg-primary-subtle d-flex align-items-center justify-content-center rounded mb-2">
                                             <iconify-icon icon="solar:lock-keyhole-minimalistic-bold" class="fs-24 text-primary"></iconify-icon>
                                        </div>

                                        <h4 class="mt-3">Billing Help</h4>
                                        <p>That feel when you look at your bank account and billing works.</p>

                                        <div class="d-flex align-items-center gap-2">
                                             <img src="/images/users/avatar-9.jpg" alt="user photo" class="avatar-sm rounded-circle">
                                             <p class="mb-0">by Lydia Anderson</p>

                                             <div class="vr bg-dark-subtle h-75 my-auto"></div>

                                             <a href="#!" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class="link-primary">12 Video</a>
                                        </div>
                                   </div>
                              </div>
                         </div>
                    </div>

                    <div class="modal fade" id="staticBackdrop" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                         <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                              <div class="modal-content">
                                   <div class="modal-header border-bottom">
                                        <h1 class="modal-title fs-18" id="staticBackdropLabel">Show Video</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                   </div>
                                   <div class="modal-body">
                                        <h4 class="card-title">Getting Started with Larkon</h4>
                                        <div class="row g-2 mt-2">
                                             <div class="col-lg-6">
                                                  <div class="ratio ratio-16x9">
                                                       <iframe src="https://www.youtube.com/embed/PrUxWZiQfy4?autohide=0&amp;showinfo=0&amp;controls=0" class="rounded"></iframe>
                                                  </div>
                                             </div>
                                             <div class="col-lg-6">
                                                  <div class="ratio ratio-16x9">
                                                       <iframe width="560" height="315" src="https://www.youtube.com/embed/D89Dgg32yLk?si=hxvuTzNEzCyfuBN1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" class="rounded" allowfullscreen=""></iframe>
                                                  </div>
                                             </div>
                                             <div class="col-lg-6">
                                                  <div class="ratio ratio-16x9">
                                                       <iframe width="560" height="315" src="https://www.youtube.com/embed/qBpY4MJt6lc?si=LXHNQxR1XHEt_5VT" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen="" class="rounded"></iframe>
                                                  </div>
                                             </div>
                                             <div class="col-lg-6">
                                                  <div class="ratio ratio-16x9">
                                                       <iframe width="560" height="315" src="https://www.youtube.com/embed/wEw4A7CcSWU?si=BWA7J4IpWkiYvypk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen="" class="rounded"></iframe>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="modal-footer p-2">
                                        <a href="#!" class="btn btn-primary">More Video</a>
                                   </div>

                              </div>
                         </div>
                    </div>

               </div>
               <!-- End Container xxl -->

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