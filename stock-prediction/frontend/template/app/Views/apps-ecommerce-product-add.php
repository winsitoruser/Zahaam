<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Create Product")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Create Product")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container-xxl">

                    <div class="row">
                         <div class="col">
                              <div class="card" id="horizontalwizard">
                                   <div class="card-header">
                                        <ul class="nav nav-tabs card-header-tabs border-0">
                                             <li class="nav-item" data-target-form="#generalDetailForm">
                                                  <a href="#generalDetail" data-bs-toggle="tab" data-toggle="tab" class="nav-link active pb-3">
                                                       <i class="bx bxs-contact me-1"></i>
                                                       <span class="d-none d-sm-inline">General Detail</span>
                                                  </a>
                                             </li> <!-- end nav item -->
                                             <li class="nav-item" data-target-form="#productImagesForm">
                                                  <a href="#productImages" data-bs-toggle="tab" data-toggle="tab" class="nav-link pb-3">
                                                       <i class="bx bx-images me-1"></i>
                                                       <span class="d-none d-sm-inline">Product Images</span>
                                                  </a>
                                             </li> <!-- end nav item -->
                                             <li class="nav-item" data-target-form="#metaDataForm">
                                                  <a href="#metaData" data-bs-toggle="tab" data-toggle="tab" class="nav-link pb-3">
                                                       <i class="bx bxs-book me-1"></i>
                                                       <span class="d-none d-sm-inline">Meta Data</span>
                                                  </a>
                                             </li> <!-- end nav item -->
                                             <li class="nav-item">
                                                  <a href="#finish" data-bs-toggle="tab" data-toggle="tab" class="nav-link pb-3">
                                                       <i class="bx bxs-check-circle me-1"></i>
                                                       <span class="d-none d-sm-inline">Finish</span>
                                                  </a>
                                             </li> <!-- end nav item -->
                                        </ul> <!-- nav pills -->
                                   </div>
                                   <div class="card-body">

                                        <div class="tab-content pt-0">
                                             <div class="tab-pane show active" id="generalDetail">
                                                  <form id="generalDetailForm" method="post" action="#">
                                                       <div class="row">
                                                            <div class="col-lg-6">
                                                                 <div class="mb-3">
                                                                      <label class="form-label" for="productName">Product Name</label>
                                                                      <input type="text" class="form-control" id="productName" placeholder="Enter product name">
                                                                 </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                 <div class="mb-3">
                                                                      <label class="form-label" for="referenceName">Reference</label>
                                                                      <input type="text" class="form-control" id="referenceName" placeholder="Enter reference name">
                                                                 </div>
                                                            </div>
                                                       </div>
                                                       <div class="row">
                                                            <div class="col-lg-12">
                                                                 <div class="mb-3">
                                                                      <label class="form-label">Product Description</label>
                                                                      <div id="snow-editor" style="height: 195px;">
                                                                           <h5><span class="ql-size-large">Describe Your Product Description...</span></h5>
                                                                      </div>
                                                                 </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                 <div class="mb-3">
                                                                      <label for="productSummary" class="form-label">Product Summary</label>
                                                                      <textarea class="form-control" id="productSummary" rows="5"></textarea>
                                                                 </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                 <div class="mb-3">
                                                                      <label for="productSummary" class="form-label">Categories</label>
                                                                      <select class="form-control select2" data-toggle="select2">
                                                                           <optgroup label="">
                                                                                <option value="select">Select</option>
                                                                           </optgroup>
                                                                           <optgroup label="Shopping">
                                                                                <option value="shopping1">Shopping 1</option>
                                                                                <option value="shopping2">Shopping 2</option>
                                                                                <option value="shopping3">Shopping 3</option>
                                                                                <option value="shopping4">Shopping 4</option>
                                                                           </optgroup>
                                                                           <optgroup label="CRM">
                                                                                <option value="CRM1">CRM 1</option>
                                                                                <option value="CRM2">CRM 2</option>
                                                                           </optgroup>
                                                                      </select>
                                                                 </div>
                                                                 <div class="mb-3">
                                                                      <label class="form-label" for="price">Price</label>
                                                                      <input type="text" class="form-control" id="price" placeholder="Enter amount">
                                                                 </div>
                                                            </div>
                                                       </div>
                                                       <div class="mb-3">
                                                            <label class="form-label">Status</label><br>
                                                            <div class="form-check form-check-inline">
                                                                 <input class="form-check-input" name="radio" type="radio" id="onlineStatus" value="Online" checked>
                                                                 <label class="form-check-label" for="onlineStatus">Online</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                 <input class="form-check-input" name="radio" type="radio" id="offlineStatus" value="Offline">
                                                                 <label class="form-check-label" for="offlineStatus">Offline</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                 <input class="form-check-input" name="radio" type="radio" id="draftStatus" value="Draft">
                                                                 <label class="form-check-label" for="draftStatus">Draft</label>
                                                            </div>
                                                       </div>
                                                       <div class="mb-3">
                                                            <label for="comment" class="form-label">Comment</label>
                                                            <textarea class="form-control" id="comment" rows="3"></textarea>
                                                       </div>
                                                  </form>
                                             </div> <!-- end contact detail tab pane -->
                                             <div class="tab-pane" id="productImages">
                                                  <h5 class="fs-14 mb-1">Product Gallery</h5>
                                                  <p class="text-muted fs-13">Add Product Gallery Images.</p>
                                                  <form action="/" method="post" class="dropzone" id="productImagesForm" data-plugin="dropzone" data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                                                       <div class="fallback">
                                                            <input name="file" type="file" multiple />
                                                       </div>

                                                       <div class="dz-message needsclick">
                                                            <i class="h1 bx bx-cloud-upload"></i>
                                                            <h3>Drop files here or click to upload.</h3>
                                                            <span class="text-muted fs-13">
                                                                 (This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)
                                                            </span>
                                                       </div>
                                                  </form>
                                             </div> <!-- end job detail tab pane -->
                                             <div class="tab-pane" id="metaData">
                                                  <form id="metaDataForm" method="post" action="#">
                                                       <h5 class="mb-3 mt-0">Fill all information below</h5>
                                                       <div class="row">
                                                            <div class="col-md-6">
                                                                 <div class="mb-3">
                                                                      <label class="form-label" for="metaTitle">Meta Title</label>
                                                                      <input type="text" class="form-control" id="metaTitle" placeholder="Enter Meta Title">
                                                                 </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                 <div class="mb-3">
                                                                      <label class="form-label" for="metaKeywords">Meta Keywords</label>
                                                                      <input type="text" class="form-control" id="metaKeywords" placeholder="Enter Meta Keywords">
                                                                 </div>
                                                            </div>
                                                       </div>
                                                       <div class="mb-3">
                                                            <label for="metaDescription" class="form-label">Meta Description</label>
                                                            <textarea class="form-control" id="metaDescription" rows="3"></textarea>
                                                       </div>
                                                  </form>
                                             </div> <!-- end education detail tab pane -->
                                             <div class="tab-pane" id="finish">
                                                  <div class="row d-flex justify-content-center">
                                                       <div class="col-lg-6">
                                                            <div class="text-center">
                                                                 <i class="bx bx-check-double text-success h2"></i>
                                                                 <h3 class="mt-0">Congratulations !</h3>

                                                                 <h5 class="w-75 mb-2 mt-3 mx-auto text-muted">
                                                                      Your Product has been successfully added!
                                                                 </h5>
                                                            </div>
                                                       </div> <!-- end col -->
                                                  </div> <!-- end row -->
                                             </div>
                                             <div class="d-flex flex-wrap gap-2 wizard justify-content-between mt-3">
                                                  <div class="first d-none">
                                                       <a href="javascript:void(0);" class="btn btn-primary">
                                                            First
                                                       </a>
                                                  </div>
                                                  <div class="previous me-2">
                                                       <a href="javascript:void(0);" class="btn btn-primary">
                                                            <i class="bx bx-left-arrow-alt me-2"></i>Back To Previous
                                                       </a>
                                                  </div>
                                                  <div class="next">
                                                       <a href="javascript:void(0);" class="btn btn-primary">
                                                            Next Step<i class="bx bx-right-arrow-alt ms-2"></i>
                                                       </a>
                                                  </div>
                                                  <div class="last d-none">
                                                       <a href="javascript:void(0);" class="btn btn-primary">
                                                            Finish
                                                       </a>
                                                  </div>
                                             </div>
                                        </div> <!-- end tab content-->

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

     <!-- Page Js -->
     <script src="/js/pages/app-ecommerce-product.js"></script>

</body>

</html>