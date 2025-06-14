<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Tooltips")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Tooltips")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container">

                <!-- start tooltips-->
                <div class="row">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">

                                <h5 class="card-title mb-1 anchor" id="direction">
                                    Tooltip Direction<a class="anchor-link" href="#direction">#</a>
                                </h5>
                                <p class="text-muted">
                                    Four options are available: top, right, bottom, and left aligned.
                                </p>
                                <div class="mb-3">
                                    <div class="d-flex flex-wrap gap-3">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top">
                                            Tooltip on top
                                        </button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Tooltip on right">
                                            Tooltip on right
                                        </button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom">
                                            Tooltip on bottom
                                        </button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Tooltip on left">
                                            Tooltip on left
                                        </button>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                            <code>
                                                &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;tooltip&quot; data-bs-placement=&quot;top&quot; data-bs-title=&quot;Tooltip on top&quot;&gt;
                                                    Tooltip on top
                                                &lt;/button&gt;
                                                &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;tooltip&quot; data-bs-placement=&quot;right&quot; data-bs-title=&quot;Tooltip on right&quot;&gt;
                                                    Tooltip on right
                                                &lt;/button&gt;
                                                &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;tooltip&quot; data-bs-placement=&quot;bottom&quot; data-bs-title=&quot;Tooltip on bottom&quot;&gt;
                                                    Tooltip on bottom
                                                &lt;/button&gt;
                                                &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;tooltip&quot; data-bs-placement=&quot;left&quot; data-bs-title=&quot;Tooltip on left&quot;&gt;
                                                    Tooltip on left
                                                &lt;/button&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="color_tooltip">
                                    Color Tooltip <a class="anchor-link" href="#color_tooltip">#</a>
                                </h5>
                                <p class="text-muted">We set a custom class with ex. <code>data-bs-custom-class="primary-tooltip"</code> to scope our background-color primary appearance and use it to override a local CSS variable.</p>
                                <div class="mb-3">
                                    <div class="d-flex flex-wrap gap-3">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="primary-tooltip" data-bs-title="This top tooltip is themed via CSS variables.">
                                            Primary tooltip
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="danger-tooltip" data-bs-title="This top tooltip is themed via CSS variables.">
                                            Danger tooltip
                                        </button>
                                        <button type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="info-tooltip" data-bs-title="This top tooltip is themed via CSS variables.">
                                            Info tooltip
                                        </button>
                                        <button type="button" class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="success-tooltip" data-bs-title="This top tooltip is themed via CSS variables.">
                                            Success tooltip
                                        </button>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                            <code>
                                                &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;tooltip&quot; data-bs-placement=&quot;top&quot; data-bs-custom-class=&quot;primary-tooltip&quot; data-bs-title=&quot;This top tooltip is themed via CSS variables.&quot;&gt;
                                                    Primary tooltip
                                                &lt;/button&gt;
                                                &lt;button type=&quot;button&quot; class=&quot;btn btn-danger&quot; data-bs-toggle=&quot;tooltip&quot; data-bs-placement=&quot;top&quot; data-bs-custom-class=&quot;danger-tooltip&quot; data-bs-title=&quot;This top tooltip is themed via CSS variables.&quot;&gt;
                                                    Danger tooltip
                                                &lt;/button&gt;
                                                &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot; data-bs-toggle=&quot;tooltip&quot; data-bs-placement=&quot;top&quot; data-bs-custom-class=&quot;info-tooltip&quot; data-bs-title=&quot;This top tooltip is themed via CSS variables.&quot;&gt;
                                                    Info tooltip
                                                &lt;/button&gt;
                                                &lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot; data-bs-toggle=&quot;tooltip&quot; data-bs-placement=&quot;top&quot; data-bs-custom-class=&quot;success-tooltip&quot; data-bs-title=&quot;This top tooltip is themed via CSS variables.&quot;&gt;
                                                    Success tooltip
                                                &lt;/button&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
                    </div> <!-- end col -->

                    <div class="col-xl-3">
                        <div class="card docs-nav">
                            <ul class="nav bg-transparent flex-column">
                                <li class="nav-item">
                                    <a href="#direction" class="nav-link">Direction</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#color_tooltip" class="nav-link">Color Tooltip</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> <!-- end row -->
                <!-- start tooltips-->

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