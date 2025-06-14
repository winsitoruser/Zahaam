<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Create Category")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Create Category")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-xxl">

                    <div class="row">
                         <div class="col-xl-3 col-lg-4">
                              <div class="card">
                                   <div class="card-body">
                                        <div class="bg-light text-center rounded bg-light">
                                             <img src="/images/product/p-1.png" alt="" class="avatar-xxl">
                                        </div>
                                        <div class="mt-3">
                                             <h4>Fashion Men , Women & Kid's</h4>
                                             <div class="row">
                                                  <div class="col-lg-4 col-4">
                                                       <p class="mb-1 mt-2">Created By :</p>
                                                       <h5 class="mb-0">Seller</h5>
                                                  </div>
                                                  <div class="col-lg-4 col-4">
                                                       <p class="mb-1 mt-2">Stock :</p>
                                                       <h5 class="mb-0">46233</h5>
                                                  </div>
                                                  <div class="col-lg-4 col-4">
                                                       <p class="mb-1 mt-2">ID :</p>
                                                       <h5 class="mb-0">FS16276</h5>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="card-footer border-top">
                                        <div class="row g-2">
                                             <div class="col-lg-6">
                                                  <a href="#!" class="btn btn-outline-secondary w-100">Create Category</a>
                                             </div>
                                             <div class="col-lg-6">
                                                  <a href="#!" class="btn btn-primary w-100">Cancel</a>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <div class="col-xl-9 col-lg-8 ">
                              <div class="card">
                                   <div class="card-header">
                                        <h4 class="card-title">Add Thumbnail Photo</h4>
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
                                        <h4 class="card-title">General Information</h4>
                                   </div>
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-lg-6">
                                                  <form>
                                                       <div class="mb-3">
                                                            <label for="category-title" class="form-label">Category Title</label>
                                                            <input type="text" id="category-title" class="form-control" placeholder="Enter Title">
                                                       </div>
                                                  </form>
                                             </div>

                                             <div class="col-lg-6">
                                                  <form>
                                                       <label for="crater" class="form-label">Created By</label>
                                                       <select class="form-control" id="crater" data-choices data-choices-groups data-placeholder="Select Crater">
                                                            <option value="">Select Crater</option>
                                                            <option value="Seller">Seller</option>
                                                            <option value="Admin">Admin</option>
                                                            <option value="Other">Other</option>
                                                       </select>
                                                  </form>
                                             </div>
                                             <div class="col-lg-6">
                                                  <form>
                                                       <div class="mb-3">
                                                            <label for="product-stock" class="form-label">Stock</label>
                                                            <input type="number" id="product-stock" class="form-control" placeholder="Quantity">
                                                       </div>

                                                  </form>
                                             </div>
                                             <div class="col-lg-6">
                                                  <form>
                                                       <div class="mb-3">
                                                            <label for="product-id" class="form-label">Tag ID</label>
                                                            <input type="number" id="product-id" class="form-control" placeholder="#******">
                                                       </div>

                                                  </form>
                                             </div>
                                             <div class="col-lg-12">
                                                  <div class="mb-0">
                                                       <label for="description" class="form-label">Description</label>
                                                       <textarea class="form-control bg-light-subtle" id="description" rows="7" placeholder="Type description"></textarea>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="card">
                                   <div class="card-header">
                                        <h4 class="card-title">Meta Options</h4>
                                   </div>
                                   <div class="card-body">
                                        <div class="row">
                                             <div class="col-lg-6">
                                                  <form>
                                                       <div class="mb-3">
                                                            <label for="meta-title" class="form-label">Meta Title</label>
                                                            <input type="text" id="meta-title" class="form-control" placeholder="Enter Title">
                                                       </div>
                                                  </form>
                                             </div>
                                             <div class="col-lg-6">
                                                  <form>
                                                       <div class="mb-3">
                                                            <label for="meta-tag" class="form-label">Meta Tag Keyword</label>
                                                            <input type="text" id="meta-tag" class="form-control" placeholder="Enter word">
                                                       </div>
                                                  </form>
                                             </div>
                                             <div class="col-lg-12">
                                                  <div class="mb-0">
                                                       <label for="description" class="form-label">Description</label>
                                                       <textarea class="form-control bg-light-subtle" id="description" rows="4" placeholder="Type description"></textarea>
                                                  </div>
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

     <!-- Page Js -->
     <script src="/js/pages/ecommerce-product-details.js"></script>

</body>

</html>