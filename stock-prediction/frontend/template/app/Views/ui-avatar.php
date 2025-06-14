<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Alerts")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Avatars")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->

        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container">

                <div class="row">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="basic-example">
                                    Basic Example <a class="anchor-link" href="#basic-example">#</a>
                                </h5>
                                <p class="text-muted font-14 mb-3">
                                    Create and group avatars of different sizes and shapes with the css classes.
                                    Using Bootstrap's naming convention, you can control size of avatar including standard avatar, or scale it up to different sizes.
                                </p>

                                <div class="row">
                                    <div class="col-md-3">
                                        <img src="/images/users/avatar-2.jpg" alt="image" class="img-fluid avatar-xs rounded">
                                        <p>
                                            <code>.avatar-xs</code>
                                        </p>
                                        <img src="/images/users/avatar-3.jpg" alt="image" class="img-fluid avatar-sm rounded mt-2">
                                        <p class="mb-2 mb-sm-0">
                                            <code>.avatar-sm</code>
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <img src="/images/users/avatar-4.jpg" alt="image" class="img-fluid avatar-md rounded" />
                                        <p>
                                            <code>.avatar-md</code>
                                        </p>
                                    </div>

                                    <div class="col-md-3">
                                        <img src="/images/users/avatar-5.jpg" alt="image" class="img-fluid avatar-lg rounded" />
                                        <p>
                                            <code>.avatar-lg</code>
                                        </p>
                                    </div>

                                    <div class="col-md-3">
                                        <img src="/images/users/avatar-6.jpg" alt="image" class="img-fluid avatar-xl rounded" />
                                        <p class="mb-0">
                                            <code>.avatar-xl</code>
                                        </p>
                                    </div>
                                </div> <!-- end row-->

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                            <code>
                                                &lt;img src=&quot;/images/users/avatar-2.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-xs rounded&quot;&gt;
                                                &lt;img src=&quot;/images/users/avatar-3.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-sm rounded mt-2&quot;&gt;
                                                &lt;img src=&quot;/images/users/avatar-4.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-md rounded&quot; /&gt;
                                                &lt;img src=&quot;/images/users/avatar-5.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-lg rounded&quot; /&gt;
                                                &lt;img src=&quot;/images/users/avatar-6.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-xl rounded&quot; /&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="rounded-circle">
                                    Rounded Circle<a class="anchor-link" href="#rounded-circle">#</a>
                                </h5>
                                <p class="text-muted font-14 mb-4">
                                    Using an additional class <code>.rounded-circle</code> in <code>&lt;img&gt;</code> element creates the rounded avatar.
                                </p>

                                <div class="row">
                                    <div class="col-md-4">
                                        <img src="/images/users/avatar-7.jpg" alt="image" class="img-fluid avatar-md rounded-circle" />
                                        <p class="mt-1">
                                            <code>.avatar-md .rounded-circle</code>
                                        </p>
                                    </div>

                                    <div class="col-md-4">
                                        <img src="/images/users/avatar-8.jpg" alt="image" class="img-fluid avatar-lg rounded-circle" />
                                        <p>
                                            <code>.avatar-lg .rounded-circle</code>
                                        </p>
                                    </div>

                                    <div class="col-md-4">
                                        <img src="/images/users/avatar-9.jpg" alt="image" class="img-fluid avatar-xl rounded-circle" />
                                        <p class="mb-0">
                                            <code>.avatar-xl .rounded-circle</code>
                                        </p>
                                    </div>
                                </div> <!-- end row-->

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                            <code>
                                                &lt;img src=&quot;/images/users/avatar-7.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-md rounded-circle&quot; /&gt;
                                                &lt;img src=&quot;/images/users/avatar-8.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-lg rounded-circle&quot; /&gt;
                                                &lt;img src=&quot;/images/users/avatar-9.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid avatar-xl rounded-circle&quot; /&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="images-shapes">
                                    Images Shapes<a class="anchor-link" href="#">#</a>
                                </h5>
                                <p class="text-muted font-14 mb-3">
                                    Avatars with different sizes and shapes.
                                </p>

                                <div class="d-flex flex-wrap gap-3 align-items-end">
                                    <div>
                                        <img src="/images/small/img-2.jpg" alt="image" class="img-fluid rounded" width="200" />
                                        <p class="mb-0">
                                            <code>.rounded</code>
                                        </p>
                                    </div>

                                    <div>
                                        <img src="/images/users/avatar-5.jpg" alt="image" class="img-fluid rounded" width="120" />
                                        <p class="mb-0">
                                            <code>.rounded</code>
                                        </p>
                                    </div>

                                    <div>
                                        <img src="/images/users/avatar-7.jpg" alt="image" class="img-fluid rounded-circle" width="120" />
                                        <p class="mb-0">
                                            <code>.rounded-circle</code>
                                        </p>
                                    </div>

                                    <div>
                                        <img src="/images/small/img-3.jpg" alt="image" class="img-fluid img-thumbnail" width="200" />
                                        <p class="mb-0">
                                            <code>.img-thumbnail</code>
                                        </p>
                                    </div>

                                    <div>
                                        <img src="/images/users/avatar-8.jpg" alt="image" class="img-fluid rounded-circle img-thumbnail" width="120" />
                                        <p class="mb-0">
                                            <code>.rounded-circle .img-thumbnail</code>
                                        </p>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="alertsLink">
                                                &lt;img src=&quot;/images/small/img-2.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid rounded&quot; width=&quot;200&quot; /&gt;
                                                &lt;img src=&quot;/images/users/avatar-5.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid rounded&quot; width=&quot;120&quot; /&gt;
                                                &lt;img src=&quot;/images/users/avatar-7.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid rounded-circle&quot; width=&quot;120&quot; /&gt;
                                                &lt;img src=&quot;/images/small/img-3.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid img-thumbnail&quot; width=&quot;200&quot; /&gt;
                                                &lt;img src=&quot;/images/users/avatar-8.jpg&quot; alt=&quot;image&quot; class=&quot;img-fluid rounded-circle img-thumbnail&quot; width=&quot;120&quot; /&gt;
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
                                    <a href="#basic-example" class="nav-link">Basic Example</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#rounded-circle" class="nav-link">Rounded Circle</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#images-shapes" class="nav-link">Images Shapes</a>
                                </li>
                            </ul>
                        </div>
                    </div>
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