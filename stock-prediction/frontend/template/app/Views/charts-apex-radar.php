<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Apex Radar Charts")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Radar Charts")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start Page Content here -->
        <!-- ==================================================== -->
        <div class="page-content">

            <!-- Start Content-->
            <div class="container">

                <div class="row">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor mb-1" id="overview">
                                    Overview
                                </h5>

                                <p class="mb-0"><span class="fw-medium">Find the JS file for the following chart at:</span> <code> /js/components/apexchart-radar.js</code></p>
                            </div><!-- end card-body -->
                        </div><!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-3 anchor" id="basic">Basic Radar Chart</h4>
                                <div dir="ltr">
                                    <div id="basic-radar" class="apex-charts"></div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-3 anchor" id="polygon">Radar with Polygon-fill</h4>
                                <div dir="ltr">
                                    <div id="radar-polygon" class="apex-charts"></div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title mb-3 anchor" id="multiple-series">Radar – Multiple Series</h4>
                                <div dir="ltr">
                                    <div id="radar-multiple-series" class="apex-charts"></div>
                                    <div class="text-center mt-2">
                                        <button onclick="update()" class="btn btn-sm btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                            <!-- end card body-->
                        </div>
                        <!-- end card -->
                    </div> <!-- end col -->

                    <div class="col-xl-3">
                        <div class="card docs-nav">
                            <ul class="nav bg-transparent flex-column">
                                <li class="nav-item">
                                    <a href="#overview" class="nav-link">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#basic" class="nav-link">Basic Radar Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#polygon" class="nav-link">Radar with Polygon-fill</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#multiple-series" class="nav-link">Radar – Multiple Series</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> <!-- end row -->
            </div> <!-- container -->

            <?= $this->include("partials/footer") ?>

        </div>

        <!-- ==================================================== -->
        <!-- End Page content -->
        <!-- ==================================================== -->

    </div>
    <!-- END wrapper -->

    <?= $this->include("partials/vendor-scripts") ?>

    <!-- Apex Chart Radar Demo js -->
    <script src="/js/components/apexchart-radar.js"></script>

</body>

</html>