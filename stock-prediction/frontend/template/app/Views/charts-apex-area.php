<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Apex Area Chart")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- Begin page -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Area Charts")) ?>
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
                                    Overview <a class="btn btn-sm btn-outline-success rounded-2 float-end" href="https://apexcharts.com/javascript-chart-demos/" target="_blank"> Official Website </a>
                                </h5>
                                <p class="text-muted mb-3">ApexCharts is loaded with powerful features to fulfill your data-visualization needs.</p>

                                <h5 class="mt-2">Usage</h5>
                                <p>Apexcharts's CSS and Javascript files are bundled in the <code>vender.min.css</code> and <code>vendor.js</code> and globally included in all pages.</p>

                                <p class="mb-0"><span class="fw-medium">Find the JS file for the following chart at:</span> <code> /js/components/apexchart-area.js</code></p>
                            </div><!-- end card-body -->
                        </div><!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor mb-3" id="basic">Basic Area Chart<a class="anchor-link" href="#basic">#</a></h5>
                                <div dir="ltr" class="mb-3">
                                    <div id="basic-area" class="apex-charts"></div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code" data-simplebar>
                                        <pre class="language-html">
                                                <code>
                                                    &lt;div dir=&quot;ltr&quot; class=&quot;mb-3&quot;&gt;
                                                        &lt;div id=&quot;basic-area&quot; class=&quot;apex-charts&quot;&gt;&lt;/div&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor mb-3" id="spline">Spline Area<a class="anchor-link" href="#spline">#</a></h5>

                                <div dir="ltr" class="mb-3">
                                    <div id="spline-area" class="apex-charts"></div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code" data-simplebar>
                                        <pre class="language-html">
                                                <code>
                                                    &lt;div dir=&quot;ltr&quot;&gt;
                                                        &lt;div id=&quot;spline-area&quot; class=&quot;apex-charts&quot;&gt;&lt;/div&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div><!-- end card-body -->
                        </div><!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                                    <h5 class="card-title anchor" id="datetime">
                                        Area Chart - Datetime X-axis<a class="anchor-link" href="#datetime">#</a>
                                    </h5>
                                    <div class="toolbar apex-toolbar">
                                        <button id="one_month" class="btn btn-sm btn-soft-secondary">1M</button>
                                        <button id="six_months" class="btn btn-sm btn-soft-secondary">6M</button>
                                        <button id="one_year" class="btn btn-sm btn-soft-secondary active">1Y</button>
                                        <button id="ytd" class="btn btn-sm btn-soft-secondary">YTD</button>
                                        <button id="all" class="btn btn-sm btn-soft-secondary">ALL</button>
                                    </div>
                                </div>
                                <div dir="ltr">
                                    <div id="area-chart-datetime" class="apex-charts"></div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code" data-simplebar>
                                        <pre class="language-html">
                                                <code>
                                                    &lt;div class=&quot;mb-3&quot;&gt;
                                                        &lt;div class=&quot;toolbar apex-toolbar&quot;&gt;
                                                            &lt;button id=&quot;one_month&quot; class=&quot;btn btn-sm btn-light&quot;&gt;1M&lt;/button&gt;
                                                            &lt;button id=&quot;six_months&quot; class=&quot;btn btn-sm btn-light&quot;&gt;6M&lt;/button&gt;
                                                            &lt;button id=&quot;one_year&quot; class=&quot;btn btn-sm btn-light active&quot;&gt;1Y&lt;/button&gt;
                                                            &lt;button id=&quot;ytd&quot; class=&quot;btn btn-sm btn-light&quot;&gt;YTD&lt;/button&gt;
                                                            &lt;button id=&quot;all&quot; class=&quot;btn btn-sm btn-light&quot;&gt;ALL&lt;/button&gt;
                                                        &lt;/div&gt;
                                                        &lt;div dir=&quot;ltr&quot;&gt;
                                                            &lt;div id=&quot;area-chart-datetime&quot; class=&quot;apex-charts&quot;&gt;&lt;/div&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor mb-3" id="negative">
                                    Area with Negative Values<a class="anchor-link" href="#negative">#</a>
                                </h5>
                                <div dir="ltr" class="mb-3">
                                    <div id="area-chart-negative" class="apex-charts"></div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code" data-simplebar>
                                        <pre class="language-html">
                                                <code>
                                                    &lt;div dir=&quot;ltr&quot; class=&quot;mb-3&quot;&gt;
                                                        &lt;div id=&quot;area-chart-negative&quot; class=&quot;apex-charts&quot;&gt;&lt;/div&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor mb-3" id="github">
                                    Selection - Github Style<a class="anchor-link" href="#github">#</a>
                                </h5>

                                <div class="mb-3">
                                    <div id="area-chart-github" class="apex-charts"></div>
                                    <div class="pt-2 pb-2">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <img src="/images/users/avatar-2.jpg" class="avatar-xs rounded" alt="file-image">
                                            </div>
                                            <div class="col ps-0">
                                                <a href="javascript:void(0);" class="text-muted fw-bold">Techzaa</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="area-chart-github2" class="apex-charts"></div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code" data-simplebar>
                                        <pre class="language-html">
                                                <code>
                                                    &lt;div class=&quot;mb-3&quot;&gt;
                                                        &lt;div id=&quot;area-chart-github&quot; class=&quot;apex-charts&quot;&gt;&lt;/div&gt;
                                                        &lt;div class=&quot;pt-2 pb-2&quot;&gt;
                                                            &lt;div class=&quot;row align-items-center&quot;&gt;
                                                                &lt;div class=&quot;col-auto&quot;&gt;
                                                                    &lt;img src=&quot;assets/images/users/avatar-2.jpg&quot; class=&quot;avatar-xs rounded&quot; alt=&quot;file-image&quot;&gt;
                                                                &lt;/div&gt;
                                                                &lt;div class=&quot;col ps-0&quot;&gt;
                                                                    &lt;a href=&quot;javascript:void(0);&quot; class=&quot;text-muted fw-bold&quot;&gt;Techzaa&lt;/a&gt;
                                                                &lt;/div&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div id=&quot;area-chart-github2&quot; class=&quot;apex-charts&quot;&gt;&lt;/div&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor mb-3" id="timeSeries">
                                    Irregular TimeSeries<a class="anchor-link" href="#timeSeries">#</a>
                                </h5>
                                <div dir="ltr" class="mb-3">
                                    <div id="area-timeSeries" class="apex-charts"></div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code" data-simplebar>
                                        <pre class="language-html">
                                                <code>
                                                    &lt;div dir=&quot;ltr&quot;&gt;
                                                        &lt;div id=&quot;area-timeSeries&quot; class=&quot;apex-charts&quot;&gt;&lt;/div&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
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
                                    <a href="#basic" class="nav-link">Basic Area Chart</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#spline" class="nav-link">Spline Area</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#datetime" class="nav-link">Area Chart - Datetime X-axis</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#negative" class="nav-link">Area with Negative Values</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#github" class="nav-link">Selection - Github Style</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#timeSeries" class="nav-link">Irregular TimeSeries</a>
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

    <!-- Following Apex Area Chart Demo Dummy Data Link -->
    <script src="https://apexcharts.com/samples/assets/stock-prices.js"></script>
    <script src="https://apexcharts.com/samples/assets/series1000.js"></script>
    <script src="https://apexcharts.com/samples/assets/github-data.js"></script>
    <script src="https://apexcharts.com/samples/assets/irregular-data-series.js"></script>

    <!-- Apex Chart Area Demo js -->
    <script src="/js/components/apexchart-area.js"></script>

</body>

</html>