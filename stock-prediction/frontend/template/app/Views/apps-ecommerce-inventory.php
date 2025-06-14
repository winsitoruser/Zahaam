<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Inventory")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Inventory")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container-xxl">

                <div class="row">
                    <div class="col-xl-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Filter Products</h5>
                                <div class="search-bar mb-3">
                                    <span><i class="bx bx-search-alt"></i></span>
                                    <input type="email" class="form-control" id="search" placeholder="Search by name .......">
                                </div>
                                <div class="mb-3">
                                    <label for="productId" class="form-label">Product Id</label>
                                    <input type="email" class="form-control" id="productId" placeholder="Filter by Product Id">
                                </div>
                                <div class="mb-3">
                                    <label for="condition" class="form-label">Condition</label>
                                    <select class="form-select" id="condition">
                                        <option selected>All Conditions</option>
                                        <option value="1">New</option>
                                        <option value="2">Return</option>
                                        <option value="3">Damaged</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category">
                                        <option selected>All Categories</option>
                                        <option value="1">Electronics & Accesories</option>
                                        <option value="2">Home & Kitchen</option>
                                        <option value="3">Cloth</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <select class="form-select" id="location">
                                        <option selected>All Locations</option>
                                        <option value="1">WareHouse 1</option>
                                        <option value="2">WareHouse 2</option>
                                        <option value="3">WareHouse 3</option>
                                        <option value="3">WareHouse 4</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <a href="javascript:void(0);" class="btn btn-outline-primary w-100">Clear</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" class="btn btn-primary w-100">Apply Filters</a>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex flex-wrap align-items-center gap-2">
                                            <a href="javascript:void(0);" class="btn btn-secondary"><i class="bx bx-export me-1"></i>Export</a>
                                            <a href="javascript:void(0);" class="btn btn-secondary"><i class="bx bx-import me-1"></i>Import</a>

                                            <a href="apps-ecommerce-product-add" class="btn btn-primary d-inline-flex align-items-center ms-md-auto"><i class="bx bx-plus me-1"></i>Add Product</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive table-centered mt-3">
                                    <table class="table text-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Product</th>
                                                <th>Condition</th>
                                                <th>Location</th>
                                                <th>Available</th>
                                                <th>Reserved</th>
                                                <th>On hand</th>
                                                <th>Modified</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#a113</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <a href="apps-ecommerce-product-detail"><img src="/images/products/product-1(1).png" alt="product-1(1)" class="img-fluid avatar-sm" /></a>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mt-0 mb-1">G15 Gaming Laptop</h5>
                                                            <span class="fs-13">Added: 12 April 2018</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-soft-success">New</span></td>
                                                <td>WareHouse 1</td>
                                                <td>3521</td>
                                                <td>6532</td>
                                                <td>1236</td>
                                                <td>12/03/2021</td>
                                            </tr>
                                            <tr>
                                                <td>#a123</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <a href="apps-ecommerce-product-detail"><img src="/images/products/product-2.png" alt="product-2" class="img-fluid avatar-sm" /></a>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mt-0 mb-1">Sony Alpha ILCE 6000Y</h5>
                                                            <span class="fs-13">Added: 10 April 2018</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-soft-success">New</span></td>
                                                <td>WareHouse 2</td>
                                                <td>4562</td>
                                                <td>256</td>
                                                <td>214</td>
                                                <td>06/04/2021</td>
                                            </tr>
                                            <tr>
                                                <td>#a133</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <a href="apps-ecommerce-product-detail"><img src="/images/products/product-3.png" alt="product-3" class="img-fluid avatar-sm" /></a>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mt-0 mb-1">Sony Wireless Headphone</h5>
                                                            <span class="fs-13">Added: 25 December 2017</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-soft-warning">Return</span></td>
                                                <td>WareHouse 3</td>
                                                <td>125</td>
                                                <td>4512</td>
                                                <td>412</td>
                                                <td>21/05/2020</td>
                                            </tr>
                                            <tr>
                                                <td>#a143</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <a href="apps-ecommerce-product-detail"><img src="/images/products/product-4.png" alt="product-4" class="img-fluid avatar-sm" /></a>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mt-0 mb-1">Apple iPad Pro</h5>
                                                            <span class="fs-13">Added: 05 May 2018</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-soft-danger">Damaged</span></td>
                                                <td>WareHouse 1</td>
                                                <td>4523</td>
                                                <td>1241</td>
                                                <td>852</td>
                                                <td>15/03/2021</td>
                                            </tr>
                                            <tr>
                                                <td>#a153</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <a href="apps-ecommerce-product-detail"><img src="/images/products/product-5.png" alt="product-5" class="img-fluid avatar-sm" /></a>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h5 class="mt-0 mb-1">Adam ROMA USB-C</h5>
                                                            <span class="fs-13">Added: 31 March 2018</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge badge-soft-success">New</span></td>
                                                <td>WareHouse 2</td>
                                                <td>1475</td>
                                                <td>2345</td>
                                                <td>1256</td>
                                                <td>15/10/2020</td>
                                            </tr>
                                        </tbody>
                                    </table> <!-- end table-->
                                </div> <!-- end table responsive -->
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