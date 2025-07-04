<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Placeholder")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Placeholder")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div>

            <div class="page-content">

                <!-- Start Container Fluid -->
                <div class="container">

                    <!-- start progress -->
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="default">
                                        Default<a class="anchor-link" href="#default">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        A progress bar can be used to show a user how far along he/she is in a process.
                                    </p>

                                    <div class="mb-3">
                                        <div class="row g-4">
                                            <div class="col-md-4">
                                                <div class="card">
                                                    <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                                                        <title>Placeholder</title>
                                                        <rect width="100%" height="100%" fill="#20c997"></rect>
                                                    </svg>

                                                    <div class="card-body">
                                                        <h5 class="card-title mb-2">Card title</h5>
                                                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                                                        <a href="#" class="btn btn-primary">Go somewhere</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card" aria-hidden="true">
                                                    <svg class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                                                        <title>Placeholder</title>
                                                        <rect width="100%" height="100%" fill="#868e96"></rect>
                                                    </svg>

                                                    <div class="card-body">
                                                        <div class="h5 card-title placeholder-glow">
                                                            <span class="placeholder col-6"></span>
                                                        </div>
                                                        <p class="card-text placeholder-glow">
                                                            <span class="placeholder col-7"></span>
                                                            <span class="placeholder col-4"></span>
                                                            <span class="placeholder col-4"></span>
                                                            <span class="placeholder col-6"></span>
                                                            <span class="placeholder col-8"></span>
                                                        </p>
                                                        <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-6"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code id="backgroundsProgress">
                                                    &lt;div class=&quot;card&quot;&gt;
                                                        &lt;svg class=&quot;bd-placeholder-img card-img-top&quot; width=&quot;100%&quot; height=&quot;180&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot; role=&quot;img&quot; aria-label=&quot;Placeholder&quot; preserveAspectRatio=&quot;xMidYMid slice&quot; focusable=&quot;false&quot;&gt;
                                                            &lt;title&gt;Placeholder&lt;/title&gt;
                                                            &lt;rect width=&quot;100%&quot; height=&quot;100%&quot; fill=&quot;#20c997&quot;&gt;&lt;/rect&gt;
                                                        &lt;/svg&gt;

                                                        &lt;div class=&quot;card-body&quot;&gt;
                                                            &lt;h5 class=&quot;card-title&quot;&gt;Card title&lt;/h5&gt;
                                                            &lt;p class=&quot;card-text&quot;&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;
                                                            &lt;a href=&quot;#&quot; class=&quot;btn btn-primary&quot;&gt;Go somewhere&lt;/a&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;card&quot; aria-hidden=&quot;true&quot;&gt;
                                                        &lt;svg class=&quot;bd-placeholder-img card-img-top&quot; width=&quot;100%&quot; height=&quot;180&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot; role=&quot;img&quot; aria-label=&quot;Placeholder&quot; preserveAspectRatio=&quot;xMidYMid slice&quot; focusable=&quot;false&quot;&gt;
                                                            &lt;title&gt;Placeholder&lt;/title&gt;
                                                            &lt;rect width=&quot;100%&quot; height=&quot;100%&quot; fill=&quot;#868e96&quot;&gt;&lt;/rect&gt;
                                                        &lt;/svg&gt;

                                                        &lt;div class=&quot;card-body&quot;&gt;
                                                            &lt;div class=&quot;h5 card-title placeholder-glow&quot;&gt;
                                                                &lt;span class=&quot;placeholder col-6&quot;&gt;&lt;/span&gt;
                                                            &lt;/div&gt;
                                                            &lt;p class=&quot;card-text placeholder-glow&quot;&gt;
                                                                &lt;span class=&quot;placeholder col-7&quot;&gt;&lt;/span&gt;
                                                                &lt;span class=&quot;placeholder col-4&quot;&gt;&lt;/span&gt;
                                                                &lt;span class=&quot;placeholder col-4&quot;&gt;&lt;/span&gt;
                                                                &lt;span class=&quot;placeholder col-6&quot;&gt;&lt;/span&gt;
                                                                &lt;span class=&quot;placeholder col-8&quot;&gt;&lt;/span&gt;
                                                            &lt;/p&gt;
                                                            &lt;a href=&quot;#&quot; tabindex=&quot;-1&quot; class=&quot;btn btn-primary disabled placeholder col-6&quot;&gt;&lt;/a&gt;
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
                                    <h5 class="card-title mb-1 anchor" id="how-works">
                                        How it works<a class="anchor-link" href="#how-works">#</a>
                                    </h5>
                                    <p class="text-muted">Create placeholders with the <code>.placeholder</code> class and a grid column class (e.g., <code>.col-6</code>) to set the <code>width</code>. They can replace the text inside an element or be added as a modifier class to an existing component.</p>

                                    <div class="mb-3">
                                        <p aria-hidden="true">
                                            <span class="placeholder col-6"></span>
                                        </p>

                                        <a href="#" tabindex="-1" class="btn btn-primary disabled placeholder col-4" aria-hidden="true"></a>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code>
                                                    &lt;p aria-hidden=&quot;true&quot;&gt;
                                                        &lt;span class=&quot;placeholder col-6&quot;&gt;&lt;/span&gt;
                                                    &lt;/p&gt;

                                                    &lt;a href=&quot;#&quot; tabindex=&quot;-1&quot; class=&quot;btn btn-primary disabled placeholder col-4&quot; aria-hidden=&quot;true&quot;&gt;&lt;/a&gt;
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="width">
                                        Width<a class="anchor-link" href="#">#</a>
                                    </h5>
                                    <p class="text-muted">You can change the <code>width</code> through grid column classes, width utilities, or inline styles.</p>

                                    <div class="mb-3">
                                        <span class="placeholder col-6"></span>
                                        <span class="placeholder w-75"></span>
                                        <span class="placeholder" style="width: 25%;"></span>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code>
                                                    &lt;span class=&quot;placeholder col-6&quot;&gt;&lt;/span&gt;
                                                    &lt;span class=&quot;placeholder w-75&quot;&gt;&lt;/span&gt;
                                                    &lt;span class=&quot;placeholder&quot; style=&quot;width: 25%;&quot;&gt;&lt;/span&gt;
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="color">
                                        Color<a class="anchor-link" href="#color">#</a>
                                    </h5>
                                    <p class="text-muted">By default, the <code>placeholder</code> uses <code>currentColor</code>. This can be overridden with a custom color or utility class.</p>

                                    <div class="mb-3">
                                        <span class="placeholder col-12"></span>
                                        <span class="placeholder col-12 bg-primary"></span>
                                        <span class="placeholder col-12 bg-secondary"></span>
                                        <span class="placeholder col-12 bg-success"></span>
                                        <span class="placeholder col-12 bg-danger"></span>
                                        <span class="placeholder col-12 bg-warning"></span>
                                        <span class="placeholder col-12 bg-info"></span>
                                        <span class="placeholder col-12 bg-light"></span>
                                        <span class="placeholder col-12 bg-dark"></span>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code id="backgroundsProgress">
                                                    &lt;span class=&quot;placeholder col-12&quot;&gt;&lt;/span&gt;
                                                    &lt;span class=&quot;placeholder col-12 bg-primary&quot;&gt;&lt;/span&gt;
                                                    &lt;span class=&quot;placeholder col-12 bg-secondary&quot;&gt;&lt;/span&gt;
                                                    &lt;span class=&quot;placeholder col-12 bg-success&quot;&gt;&lt;/span&gt;
                                                    &lt;span class=&quot;placeholder col-12 bg-danger&quot;&gt;&lt;/span&gt;
                                                    &lt;span class=&quot;placeholder col-12 bg-warning&quot;&gt;&lt;/span&gt;
                                                    &lt;span class=&quot;placeholder col-12 bg-info&quot;&gt;&lt;/span&gt;
                                                    &lt;span class=&quot;placeholder col-12 bg-light&quot;&gt;&lt;/span&gt;
                                                    &lt;span class=&quot;placeholder col-12 bg-dark&quot;&gt;&lt;/span&gt;
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
                                        <a href="#default" class="nav-link">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#how-works" class="nav-link">How it works</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#width" class="nav-link">Width</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#color" class="nav-link">Color</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div> <!-- end row -->
                    <!-- end progress -->

                    <?= $this->include("partials/footer") ?>

                </div>
                <!-- End Container Fluid -->


            </div>
            <!-- ==================================================== -->
            <!-- End Page Content -->
            <!-- ==================================================== -->
        </div>

    </div>
    <!-- END Wrapper -->

    <?= $this->include("partials/vendor-scripts") ?>

</body>

</html>