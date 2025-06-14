<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Apex Bar Charts")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Apex Bar Charts")) ?>
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
                                <h5 class="card-title anchor" id="overview">
                                    Overview
                                </h5>
                                <p class="mb-0"><span class="fw-medium">Find the JS file for the following chart at:</span> <code> /js/components/apexchart-bar.js</code></p>
                            </div><!-- end card-body -->
                        </div><!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="basic">
                                    Basic Bar Chart<a class="anchor-link" href="#basic">#</a>
                                </h5>

                                <div id="basic-bar" class="apex-charts"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="grouped">
                                    Grouped Bar Chart<a class="anchor-link" href="#grouped">#</a>
                                </h5>

                                <div id="grouped-bar" class="apex-charts text-white"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="stacked">
                                    Stacked Bar Chart<a class="anchor-link" href="#stacked">#</a>
                                </h5>

                                <div id="stacked-bar" class="apex-charts" dir="ltr"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="full-stacked">
                                    100% Stacked Bar Chart<a class="anchor-link" href="#full-stacked">#</a>
                                </h5>

                                <div id="full-stacked-bar" class="apex-charts"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="negative">
                                    Bar with Negative Values<a class="anchor-link" href="#negative">#</a>
                                </h5>

                                <div id="negative-bar" class="apex-charts"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="reversed">
                                    Reversed Bar Chart<a class="anchor-link" href="#reversed">#</a>
                                </h5>

                                <div id="reversed-bar" class="apex-charts"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="image-fill">
                                    Bar with Image Fill<a class="anchor-link" href="#image-fill">#</a>
                                </h5>

                                <div id="image-fill-bar" class="apex-charts"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="datalables">
                                    Custom DataLabels Bar<a class="anchor-link" href="#datalables">#</a>
                                </h5>

                                <div id="datalables-bar" class="apex-charts"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="pattern">
                                    Patterned Bar Chart<a class="anchor-link" href="#pattern">#</a>
                                </h5>

                                <div id="pattern-bar" class="apex-charts"></div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor" id="markers">
                                    Bar with Markers<a class="anchor-link" href="#pattern">#</a>
                                </h5>

                                <div id="bar-markers" class="apex-charts"></div>
                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="col-xl-3">
                        <div class="card docs-nav">
                            <ul class="nav bg-transparent flex-column">
                                <li class="nav-item">
                                    <a href="#overview" class="nav-link">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#basic" class="nav-link">Basic Bar Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#grouped" class="nav-link">Grouped Bar Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#stacked" class="nav-link">Stacked Bar Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#full-stacked" class="nav-link">100% Stacked Bar Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#negative" class="nav-link">Bar with Negative Values</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#reversed" class="nav-link">Reversed Bar Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#image-fill" class="nav-link">Bar with Image Fill</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#datalables" class="nav-link">Custom DataLabels Bar</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#pattern" class="nav-link">Patterned Bar Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#markers" class="nav-link">Bar with Markers</a>
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

    <!-- Apex Chart Bar Demo js -->
    <script src="/js/components/apexchart-bar.js"></script>

</body>

</html>