<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Breadcrumb")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Breadcrumb")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->

        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container">

                <!-- start breadcrumbs -->
                <div class="row">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="default">
                                    Default Example<a class="anchor-link" href="#default">#</a>
                                </h5>
                                <p class="text-muted">
                                    Use an ordered or unordered list with linked list items to create a minimally styled breadcrumb.
                                    Use our utilities to add additional styles as desired.
                                </p>

                                <div class="mb-3">
                                    <!-- Default Breadcrumb -->
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb py-0">
                                            <li class="breadcrumb-item active" aria-current="page">Home</li>
                                        </ol>
                                    </nav>

                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb py-0">
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Library</li>
                                        </ol>
                                    </nav>

                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 py-0">
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">Library</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Data</li>
                                        </ol>
                                    </nav>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code id="defaultBreadcrumb">
                                                    &lt;!-- Default Breadcrumb --&gt;
                                                    &lt;nav aria-label=&quot;breadcrumb&quot;&gt;
                                                        &lt;ol class=&quot;breadcrumb py-0&quot;&gt;
                                                            &lt;li class=&quot;breadcrumb-item active&quot; aria-current=&quot;page&quot;&gt;Home&lt;/li&gt;
                                                        &lt;/ol&gt;
                                                    &lt;/nav&gt;
            
                                                    &lt;nav aria-label=&quot;breadcrumb&quot;&gt;
                                                        &lt;ol class=&quot;breadcrumb py-0&quot;&gt;
                                                            &lt;li class=&quot;breadcrumb-item&quot;&gt;&lt;a href=&quot;javascript:void(0);&quot;&gt;Home&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li class=&quot;breadcrumb-item active&quot; aria-current=&quot;page&quot;&gt;Library&lt;/li&gt;
                                                        &lt;/ol&gt;
                                                    &lt;/nav&gt;
            
                                                    &lt;nav aria-label=&quot;breadcrumb&quot;&gt;
                                                        &lt;ol class=&quot;breadcrumb mb-0 py-0&quot;&gt;
                                                            &lt;li class=&quot;breadcrumb-item&quot;&gt;&lt;a href=&quot;javascript:void(0);&quot;&gt;Home&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li class=&quot;breadcrumb-item&quot;&gt;&lt;a href=&quot;javascript:void(0);&quot;&gt;Library&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li class=&quot;breadcrumb-item active&quot; aria-current=&quot;page&quot;&gt;Data&lt;/li&gt;
                                                        &lt;/ol&gt;
                                                    &lt;/nav&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="dividers_breadcrumb">
                                    Dividers Breadcrumb <a class="anchor-link" href="#dividers_breadcrumb">#</a>
                                </h5>
                                <p class="text-muted">
                                    Optionally you can also specify the icon with your breadcrumb item.
                                </p>
                                <div class="mb-3">
                                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                        <ol class="breadcrumb py-0">
                                            <li class="breadcrumb-item active" aria-current="page">Home</li>
                                        </ol>
                                    </nav>

                                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                        <ol class="breadcrumb py-0">
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Library</li>
                                        </ol>
                                    </nav>

                                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                        <ol class="breadcrumb mb-0 py-0">
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">Library</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Data</li>
                                        </ol>
                                    </nav>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code id="dividerBreadcrumb">
                                                    &lt;nav style=&quot;--bs-breadcrumb-divider: '&gt;';&quot; aria-label=&quot;breadcrumb&quot;&gt;
                                                        &lt;ol class=&quot;breadcrumb py-0&quot;&gt;
                                                            &lt;li class=&quot;breadcrumb-item active&quot; aria-current=&quot;page&quot;&gt;Home&lt;/li&gt;
                                                        &lt;/ol&gt;
                                                    &lt;/nav&gt;

                                                    &lt;nav style=&quot;--bs-breadcrumb-divider: '&gt;';&quot; aria-label=&quot;breadcrumb&quot;&gt;
                                                        &lt;ol class=&quot;breadcrumb py-0&quot;&gt;
                                                            &lt;li class=&quot;breadcrumb-item&quot;&gt;&lt;a href=&quot;javascript:void(0);&quot;&gt;Home&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li class=&quot;breadcrumb-item active&quot; aria-current=&quot;page&quot;&gt;Library&lt;/li&gt;
                                                        &lt;/ol&gt;
                                                    &lt;/nav&gt;
                                                    
                                                    &lt;nav style=&quot;--bs-breadcrumb-divider: '&gt;';&quot; aria-label=&quot;breadcrumb&quot;&gt;
                                                        &lt;ol class=&quot;breadcrumb mb-0 py-0&quot;&gt;
                                                            &lt;li class=&quot;breadcrumb-item&quot;&gt;&lt;a href=&quot;javascript:void(0);&quot;&gt;Home&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li class=&quot;breadcrumb-item&quot;&gt;&lt;a href=&quot;javascript:void(0);&quot;&gt;Library&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li class=&quot;breadcrumb-item active&quot; aria-current=&quot;page&quot;&gt;Data&lt;/li&gt;
                                                        &lt;/ol&gt;
                                                    &lt;/nav&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end col -->

                    <div class="col-xl-3">
                        <div class="card docs-nav">
                            <ul class="nav bg-transparent flex-column">
                                <li class="nav-item">
                                    <a href="#default" class="nav-link">Default Example</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#dividers_breadcrumb" class="nav-link">Dividers Breadcrumb</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> <!-- end row -->
                <!-- end breadcrumbs -->

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