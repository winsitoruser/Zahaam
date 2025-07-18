<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Apex Column Charts")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>
<!-- Begin page -->
<div class="wrapper">

    <?php echo view("partials/topbar", array("title" => "Column Charts")) ?>
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

                            <p class="mb-0"><span class="fw-medium">Find the JS file for the following chart at:</span>
                                <code> /js/components/apexchart-column.js</code></p>
                        </div><!-- end card-body -->
                    </div><!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title anchor" id="basic">Basic Column Chart</h4>
                            <div dir="ltr">
                                <div id="basic-column" class="apex-charts"></div>
                            </div>
                        </div>
                        <!-- end card body-->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-3anchor" id="datalabels">Column Chart with Datalabels</h4>
                            <div dir="ltr">
                                <div id="datalabels-column" class="apex-charts"></div>
                            </div>
                        </div>
                        <!-- end card body-->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title anchor" id="stacked">Stacked Column Chart</h4>
                            <div dir="ltr">
                                <div id="stacked-column" class="apex-charts"></div>
                            </div>
                        </div>
                        <!-- end card body-->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title anchor" id="full-stacked">100% Stacked Column Chart</h4>
                            <div dir="ltr">
                                <div id="full-stacked-column" class="apex-charts"></div>
                            </div>
                        </div>
                        <!-- end card body-->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title anchor" id="markers">Column with Markers</h4>
                            <div dir="ltr">
                                <div id="column-with-markers" class="apex-charts"></div>
                            </div>
                        </div>
                        <!-- end card body-->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title anchor" id="group">Column with Group Label</h4>
                            <div dir="ltr">
                                <div id="column-with-group-label" class="apex-charts"></div>
                            </div>
                        </div>
                        <!-- end card body-->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title anchor" id="rotate-labels">Column Chart with rotated labels &
                                Annotations</h4>
                            <div dir="ltr">
                                <div id="rotate-labels-column" class="apex-charts"></div>
                            </div>
                        </div>
                        <!-- end card body-->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title anchor" id="negative-value">Column Chart with negative values</h4>
                            <div dir="ltr">
                                <div id="negative-value-column" class="apex-charts"></div>
                            </div>
                        </div>
                        <!-- end card body-->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title anchor" id="distributed">Distributed Column Chart</h4>
                            <div dir="ltr">
                                <div id="distributed-column" class="apex-charts"></div>
                            </div>
                        </div>
                        <!-- end card body-->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title anchor" id="range">Range Column Chart</h4>
                            <div dir="ltr">
                                <div id="range-column" class="apex-charts"></div>
                            </div>
                        </div>
                        <!-- end card body-->
                    </div>
                    <!-- end card -->

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <h4 class="card-title anchor flex-grow-1" id="dynamic">Dynamic Loaded Chart</h4>
                                <div class="flex-shrink-0">
                                    <select id="model" class="form-select form-select-sm">
                                        <option value="iphone5">iPhone 5</option>
                                        <option value="iphone6">iPhone 6</option>
                                        <option value="iphone7">iPhone 7</option>
                                    </select>
                                </div>
                            </div>
                            <div dir="ltr">

                                <div id="chart-year"></div>

                                <div id="chart-quarter"></div>

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
                                <a href="#basic" class="nav-link">Basic Column Chart</a>
                            </li>
                            <li class="nav-item">
                                <a href="#datalabels" class="nav-link">Column Chart with Datalabels</a>
                            </li>
                            <li class="nav-item">
                                <a href="#stacked" class="nav-link">Stacked Column Chart</a>
                            </li>
                            <li class="nav-item">
                                <a href="#full-stacked" class="nav-link">100% Stacked Column Chart</a>
                            </li>
                            <li class="nav-item">
                                <a href="#markers" class="nav-link">Column with Markers</a>
                            </li>
                            <li class="nav-item">
                                <a href="#group" class="nav-link">Column with Group Label</a>
                            </li>
                            <li class="nav-item">
                                <a href="#rotate-labels" class="nav-link">Column Chart with rotated labels &
                                    Annotations</a>
                            </li>
                            <li class="nav-item">
                                <a href="#negative-value" class="nav-link">Column Chart with negative values</a>
                            </li>
                            <li class="nav-item">
                                <a href="#distributed" class="nav-link">Distributed Column Chart</a>
                            </li>
                            <li class="nav-item">
                                <a href="#range" class="nav-link">Range Column Chart</a>
                            </li>
                            <li class="nav-item">
                                <a href="#dynamic" class="nav-link">Dynamic Loaded Chart</a>
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

<!-- Apex Chart Column Demo js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.0/dayjs.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dayjs/1.11.0/plugin/quarterOfYear.min.js"></script>
<script src="/js/components/apexchart-column.js"></script>

</body>

</html>