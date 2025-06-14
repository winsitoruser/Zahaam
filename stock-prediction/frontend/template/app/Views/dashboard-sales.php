<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Sales")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Sales")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container-xxl">

                <div class="row row-cols-1 row-cols-md-3 row-cols-xl-5">
                    <div class="col">
                        <div class="card">
                            <div class="card-body overflow-hidden position-relative">
                                <iconify-icon icon="iconamoon:shopping-card-add-duotone" class="fs-36 text-info"></iconify-icon>
                                <h3 class="mb-0 fw-bold mt-3 mb-1">$59.6k</h3>
                                <p class="text-muted">Total Sales</p>
                                <span class="badge fs-12 badge-soft-success"><i class="ti ti-arrow-badge-up"></i> 8.72%</span>
                                <i class='bx bx-doughnut-chart widget-icon'></i>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                    <div class="col">
                        <div class="card">
                            <div class="card-body overflow-hidden position-relative">
                                <iconify-icon icon="iconamoon:link-external-duotone" class="fs-36 text-success"></iconify-icon>
                                <h3 class="mb-0 fw-bold mt-3 mb-1">$24.03k</h3>
                                <p class="text-muted">Total Expenses</p>
                                <span class="badge fs-12 badge-soft-danger"><i class="ti ti-arrow-badge-down"></i> 3.28%</span>
                                <i class='bx bx-bar-chart-alt-2 widget-icon'></i>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                    <div class="col">
                        <div class="card">
                            <div class="card-body overflow-hidden position-relative">
                                <iconify-icon icon="iconamoon:store-duotone" class="fs-36 text-purple"></iconify-icon>
                                <h3 class="mb-0 fw-bold mt-3 mb-1">$48.7k</h3>
                                <p class="text-muted">Investments</p>
                                <span class="badge fs-12 badge-soft-danger"><i class="ti ti-arrow-badge-down"></i> 5.69%</span>
                                <i class='bx bx-building-house widget-icon'></i>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                    <div class="col">
                        <div class="card">
                            <div class="card-body overflow-hidden position-relative">
                                <iconify-icon icon="iconamoon:gift-duotone" class="fs-36 text-orange"></iconify-icon>
                                <h3 class="mb-0 fw-bold mt-3 mb-1">$11.3k</h3>
                                <p class="text-muted">Profit</p>
                                <span class="badge fs-12 badge-soft-success"><i class="ti ti-arrow-badge-up"></i> 10.58%</span>
                                <i class='bx bx-bowl-hot widget-icon'></i>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                    <div class="col">
                        <div class="card">
                            <div class="card-body overflow-hidden position-relative">
                                <iconify-icon icon="iconamoon:certificate-badge-duotone" class="fs-36 text-warning"></iconify-icon>
                                <h3 class="mb-0 fw-bold mt-3 mb-1">$5.06k</h3>
                                <p class="text-muted">Savings</p>
                                <span class="badge fs-12 badge-soft-success"><i class="ti ti-arrow-badge-up"></i> 8.72%</span>
                                <i class='bx bx-cricket-ball widget-icon'></i>
                            </div> <!-- end card-body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->
                </div>
                <!-- end row-->

                <div class="row">
                    <div class="col-xxl-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Overview</h4>
                                <div>
                                    <button type="button" class="btn btn-sm btn-soft-secondary">ALL</button>
                                    <button type="button" class="btn btn-sm btn-soft-secondary">1M</button>
                                    <button type="button" class="btn btn-sm btn-soft-secondary">6M</button>
                                    <button type="button" class="btn btn-sm btn-soft-secondary active">1Y</button>
                                </div>
                            </div> <!-- end card-title-->

                            <div class="card-body">
                                <div dir="ltr">
                                    <div id="dash-overview-chart" class="apex-charts"></div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                    <div class="col-xxl-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Sales By Category</h4>
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
                                <div dir="ltr">
                                    <div id="sales-category-chart" class="apex-charts"></div>
                                </div>
                                <div class="table-responsive mb-n1 mt-2">
                                    <table class="table table-nowrap table-borderless table-sm table-centered mb-0">
                                        <thead class="bg-light bg-opacity-50 thead-sm">
                                            <tr>
                                                <th class="py-1">Category</th>
                                                <th class="py-1">Orders</th>
                                                <th class="py-1">Perc.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Grocery</td>
                                                <td>187,232</td>
                                                <td>48.63% <span class="badge badge-soft-success ms-1">2.5% Up</span></td>
                                            </tr>
                                            <tr>
                                                <td>Electonics</td>
                                                <td>126,874</td>
                                                <td>36.08% <span class="badge badge-soft-success ms-1">8.5% Up</span></td>
                                            </tr>
                                            <tr>
                                                <td>Other</td>
                                                <td>90,127</td>
                                                <td>23.41% <span class="badge badge-soft-danger ms-1">10.98% Down</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div> <!-- end table-responsive-->
                            </div> <!-- end card body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div class="row">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">New Accounts</h4>
                                <a href="#!" class="btn btn-sm btn-light">
                                    View All
                                </a>
                            </div> <!-- end card-header-->

                            <div class="card-body pb-1">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 table-centered">
                                        <thead>
                                            <th class="py-1">ID</th>
                                            <th class="py-1">Date</th>
                                            <th class="py-1">User</th>
                                            <th class="py-1">Account</th>
                                            <th class="py-1">Username</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#US523</td>
                                                <td>24 April, 2024</td>
                                                <td><img src="/images/users/avatar-2.jpg" alt="avatar-2" class="img-fluid avatar-xs rounded-circle"> <span class="align-middle ms-1">Dan Adrick</span></td>
                                                <td><span class="badge badge-soft-success">Verified</span></td>
                                                <td>@omions </td>
                                            </tr>
                                            <tr>
                                                <td>#US652</td>
                                                <td>24 April, 2024</td>
                                                <td><img src="/images/users/avatar-3.jpg" alt="avatar-2" class="img-fluid avatar-xs rounded-circle"> <span class="align-middle ms-1">Daniel Olsen</span></td>
                                                <td><span class="badge badge-soft-success">Verified</span></td>
                                                <td>@alliates </td>
                                            </tr>
                                            <tr>
                                                <td>#US862</td>
                                                <td>20 April, 2024</td>
                                                <td><img src="/images/users/avatar-4.jpg" alt="avatar-2" class="img-fluid avatar-xs rounded-circle"> <span class="align-middle ms-1">Jack Roldan</span></td>
                                                <td><span class="badge badge-soft-warning">Pending</span></td>
                                                <td>@griys </td>
                                            </tr>
                                            <tr>
                                                <td>#US756</td>
                                                <td>18 April, 2024</td>
                                                <td><img src="/images/users/avatar-5.jpg" alt="avatar-2" class="img-fluid avatar-xs rounded-circle"> <span class="align-middle ms-1">Betty Cox</span></td>
                                                <td><span class="badge badge-soft-success">Verified</span></td>
                                                <td>@reffon </td>
                                            </tr>
                                            <tr>
                                                <td>#US420</td>
                                                <td>18 April, 2024</td>
                                                <td><img src="/images/users/avatar-6.jpg" alt="avatar-2" class="img-fluid avatar-xs rounded-circle"> <span class="align-middle ms-1">Carlos Johnson</span></td>
                                                <td><span class="badge badge-soft-danger">Blocked</span></td>
                                                <td>@bebo </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->

                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Recent Transactions</h4>

                                <a href="#!" class="btn btn-sm btn-light">
                                    View All
                                </a>
                            </div> <!-- end card-header-->

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0 table-centered">
                                        <thead>
                                            <th class="py-1">ID</th>
                                            <th class="py-1">Date</th>
                                            <th class="py-1">Amount</th>
                                            <th class="py-1">Status</th>
                                            <th class="py-1">Description</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>#98521</td>
                                                <td>24 April, 2024</td>
                                                <td>$120.55</td>
                                                <td><span class="badge bg-success">Cr</span></td>
                                                <td>Commisions </td>
                                            </tr>
                                            <tr>
                                                <td>#20158</td>
                                                <td>24 April, 2024</td>
                                                <td>$9.68</td>
                                                <td><span class="badge bg-success">Cr</span></td>
                                                <td>Affiliates </td>
                                            </tr>
                                            <tr>
                                                <td>#36589</td>
                                                <td>20 April, 2024</td>
                                                <td>$105.22</td>
                                                <td><span class="badge bg-danger">Dr</span></td>
                                                <td>Grocery </td>
                                            </tr>
                                            <tr>
                                                <td>#95362</td>
                                                <td>18 April, 2024</td>
                                                <td>$80.59</td>
                                                <td><span class="badge bg-success">Cr</span></td>
                                                <td>Refunds </td>
                                            </tr>
                                            <tr>
                                                <td>#75214</td>
                                                <td>18 April, 2024</td>
                                                <td>$750.95</td>
                                                <td><span class="badge bg-danger">Dr</span></td>
                                                <td>Bill Payments </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card-->
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 class="card-title">Recent Orders</h4>

                                    <a href="#!" class="btn btn-sm btn-primary">
                                        <i class="bx bx-plus me-1"></i>Create Order
                                    </a>
                                </div>
                            </div> <!-- end card body -->
                            <div class="table-responsive table-centered">
                                <table class="table mb-0">
                                    <thead class="bg-light bg-opacity-50">
                                        <tr>
                                            <th class="border-0 py-2">Order ID.</th>
                                            <th class="border-0 py-2">Date</th>
                                            <th class="border-0 py-2">Product</th>
                                            <th class="border-0 py-2">Customer Name</th>
                                            <th class="border-0 py-2">Email ID</th>
                                            <th class="border-0 py-2">Phone No.</th>
                                            <th class="border-0 py-2">Address</th>
                                            <th class="border-0 py-2">Payment Type</th>
                                            <th class="border-0 py-2">Status</th>
                                        </tr>
                                    </thead> <!-- end thead-->
                                    <tbody>
                                        <tr>
                                            <td><a href="apps-ecommerce-order-detail">#RB5625</a></td>
                                            <td>29 April 2024</td>
                                            <td><img src="/images/products/product-1(1).png" alt="product-1(1)" class="img-fluid avatar-sm"></td>
                                            <td><a href="#!">Anna M. Hines</a></td>
                                            <td>anna.hines@mail.com</td>
                                            <td>(+1)-555-1564-261</td>
                                            <td>Burr Ridge/Illinois</td>
                                            <td>Credit Card</td>
                                            <td><i class="bx bxs-circle text-success me-1"></i>Completed</td>
                                        </tr>
                                        <tr>
                                            <td><a href="apps-ecommerce-order-detail">#RB9652</a></td>
                                            <td>25 April 2024</td>
                                            <td><img src="/images/products/product-4.png" alt="product-4" class="img-fluid avatar-sm"></td>
                                            <td><a href="#!">Judith H. Fritsche</a></td>
                                            <td>judith.fritsche.com</td>
                                            <td>(+57)-305-5579-759</td>
                                            <td>SULLIVAN/Kentucky</td>
                                            <td>Credit Card</td>
                                            <td><i class="bx bxs-circle text-success me-1"></i>Completed</td>
                                        </tr>
                                        <tr>
                                            <td><a href="apps-ecommerce-order-detail">#RB5984</a></td>
                                            <td>25 April 2024</td>
                                            <td><img src="/images/products/product-5.png" alt="product-5" class="img-fluid avatar-sm"></td>
                                            <td><a href="#!">Peter T. Smith</a></td>
                                            <td>peter.smith@mail.com</td>
                                            <td>(+33)-655-5187-93</td>
                                            <td>Yreka/California</td>
                                            <td>Pay Pal</td>
                                            <td><i class="bx bxs-circle text-success me-1"></i>Completed</td>
                                        </tr>
                                        <tr>
                                            <td><a href="apps-ecommerce-order-detail">#RB3625</a></td>
                                            <td>21 April 2024</td>
                                            <td><img src="/images/products/product-6.png" alt="product-6" class="img-fluid avatar-sm"></td>
                                            <td><a href="#!">Emmanuel J. Delcid</a></td>
                                            <td>emmanuel.delicid@mail.com</td>
                                            <td>(+30)-693-5553-637</td>
                                            <td>Atlanta/Georgia</td>
                                            <td>Pay Pal</td>
                                            <td><i class="bx bxs-circle text-primary me-1"></i>Processing</td>
                                        </tr>
                                        <tr>
                                            <td><a href="apps-ecommerce-order-detail">#RB8652</a></td>
                                            <td>18 April 2024</td>
                                            <td><img src="/images/products/product-1(2).png" alt="product-1(2)" class="img-fluid avatar-sm"></td>
                                            <td><a href="#!">William J. Cook</a></td>
                                            <td>william.cook@mail.com</td>
                                            <td>(+91)-855-5446-150</td>
                                            <td>Rosenberg/Texas</td>
                                            <td>Credit Card</td>
                                            <td><i class="bx bxs-circle text-primary me-1"></i>Processing</td>
                                        </tr>
                                    </tbody> <!-- end tbody -->
                                </table> <!-- end table -->
                            </div> <!-- table responsive -->
                            <div class="align-items-center justify-content-between row g-0 text-center text-sm-start p-3 border-top">
                                <div class="col-sm">
                                    <div class="text-muted">
                                        Showing <span class="fw-semibold">5</span> of <span class="fw-semibold">90,521</span> orders
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
    <script src="/js/pages/dashboard.sales.js"></script>

</body>

</html>