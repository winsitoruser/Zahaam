<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Toastify")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Toastify")) ?>
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
                                <h5 class="card-title mb-1 anchor" id="overview">
                                    Overview <a class="btn btn-sm btn-outline-success rounded-2 float-end" href="https://apvarun.github.io/toastify-js/" target="_blank"> Official Website </a>
                                </h5>
                                <p class="text-muted mb-3">Rater js is the best star rater for the browser. No dependencies. Unlimited number of stars</p>

                                <h5 class="mt-2">Usage</h5>
                                <p class="mb-0">Toastify-js's Javascript files are bundled in the <code>vendor.js</code> and globally included in all pages.</p>
                            </div><!-- end card-body -->
                        </div><!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor mb-3" id="basic">
                                    Basic Toastify JS Example <a class="anchor-link" href="#basic">#</a>
                                </h5>

                                <div class="mb-3">
                                    <div class="hstack flex-wrap gap-2">
                                        <button type="button" data-toast data-toast-text="Welcome Back! This is a Toast Notification" data-toast-gravity="top" data-toast-position="right" data-toast-className="primary" data-toast-duration="3000" data-toast-close="close" data-toast-style="style" class="btn btn-light w-xs">
                                            Default
                                        </button>

                                        <button type="button" data-toast data-toast-text="Your application was successfully sent" data-toast-gravity="top" data-toast-position="center" data-toast-className="success" data-toast-duration="3000" class="btn btn-light w-xs">
                                            Success
                                        </button>

                                        <button type="button" data-toast data-toast-text="Warning ! Something went wrong try again" data-toast-gravity="top" data-toast-position="center" data-toast-className="warning" data-toast-duration="3000" class="btn btn-light w-xs">
                                            Warning
                                        </button>

                                        <button type="button" data-toast data-toast-text="Error ! An error occurred." data-toast-gravity="top" data-toast-position="center" data-toast-className="danger" data-toast-duration="3000" class="btn btn-light w-xs">
                                            Error
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
                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Welcome Back! This is a Toast Notification&quot; data-toast-gravity=&quot;top&quot; data-toast-position=&quot;right&quot; data-toast-className=&quot;primary&quot; data-toast-duration=&quot;3000&quot; data-toast-close=&quot;close&quot; data-toast-style=&quot;style&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Default
                                                    &lt;/button&gt;
                                                    
                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Your application was successfully sent&quot; data-toast-gravity=&quot;top&quot; data-toast-position=&quot;center&quot; data-toast-className=&quot;success&quot; data-toast-duration=&quot;3000&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Success
                                                    &lt;/button&gt;
                                                    
                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Warning ! Something went wrong try again&quot; data-toast-gravity=&quot;top&quot; data-toast-position=&quot;center&quot; data-toast-className=&quot;warning&quot; data-toast-duration=&quot;3000&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Warning
                                                    &lt;/button&gt;
                                                    
                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Error ! An error occurred.&quot; data-toast-gravity=&quot;top&quot; data-toast-position=&quot;center&quot; data-toast-className=&quot;danger&quot; data-toast-duration=&quot;3000&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Error
                                                    &lt;/button&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3 anchor" id="display_position">
                                    Display Position Example <a class="anchor-link" href="#display_position">#</a>
                                </h5>

                                <div class="mb-3">
                                    <div class="hstack flex-wrap gap-2">
                                        <button type="button" data-toast data-toast-text="Welcome Back ! This is a Toast Notification" data-toast-gravity="top" data-toast-position="left" data-toast-duration="3000" data-toast-close="close" class="btn btn-light w-xs">
                                            Top Left
                                        </button>

                                        <button type="button" data-toast data-toast-text="Welcome Back ! This is a Toast Notification" data-toast-gravity="top" data-toast-position="center" data-toast-duration="3000" data-toast-close="close" class="btn btn-light w-xs">
                                            Top Center
                                        </button>

                                        <button type="button" data-toast data-toast-text="Welcome Back ! This is a Toast Notification" data-toast-gravity="top" data-toast-position="right" data-toast-duration="3000" data-toast-close="close" class="btn btn-light w-xs">
                                            Top Right
                                        </button>

                                        <button type="button" data-toast data-toast-text="Welcome Back ! This is a Toast Notification" data-toast-gravity="bottom" data-toast-position="left" data-toast-duration="3000" data-toast-close="close" class="btn btn-light w-xs">
                                            Bottom Left
                                        </button>

                                        <button type="button" data-toast data-toast-text="Welcome Back ! This is a Toast Notification" data-toast-gravity="bottom" data-toast-position="center" data-toast-duration="3000" data-toast-close="close" class="btn btn-light w-xs">
                                            Bottom Center
                                        </button>

                                        <button type="button" data-toast data-toast-text="Welcome Back ! This is a Toast Notification" data-toast-gravity="bottom" data-toast-position="right" data-toast-duration="3000" data-toast-close="close" class="btn btn-light w-xs">
                                            Bottom Right
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
                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Welcome Back ! This is a Toast Notification&quot; data-toast-gravity=&quot;top&quot; data-toast-position=&quot;left&quot; data-toast-duration=&quot;3000&quot; data-toast-close=&quot;close&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Top Left
                                                    &lt;/button&gt;

                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Welcome Back ! This is a Toast Notification&quot; data-toast-gravity=&quot;top&quot; data-toast-position=&quot;center&quot; data-toast-duration=&quot;3000&quot; data-toast-close=&quot;close&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Top Center
                                                    &lt;/button&gt;

                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Welcome Back ! This is a Toast Notification&quot; data-toast-gravity=&quot;top&quot; data-toast-position=&quot;right&quot; data-toast-duration=&quot;3000&quot; data-toast-close=&quot;close&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Top Right
                                                    &lt;/button&gt;

                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Welcome Back ! This is a Toast Notification&quot; data-toast-gravity=&quot;bottom&quot; data-toast-position=&quot;left&quot; data-toast-duration=&quot;3000&quot; data-toast-close=&quot;close&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Bottom Left
                                                    &lt;/button&gt;

                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Welcome Back ! This is a Toast Notification&quot; data-toast-gravity=&quot;bottom&quot; data-toast-position=&quot;center&quot; data-toast-duration=&quot;3000&quot; data-toast-close=&quot;close&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Bottom Center
                                                    &lt;/button&gt;

                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Welcome Back ! This is a Toast Notification&quot; data-toast-gravity=&quot;bottom&quot; data-toast-position=&quot;right&quot; data-toast-duration=&quot;3000&quot; data-toast-close=&quot;close&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Bottom Right
                                                    &lt;/button&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3 anchor" id="rater">
                                    Offset, Close Button & Duration Example <a class="anchor-link" href="#rater">#</a>
                                </h5>

                                <div class="mb-3">
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <button type="button" data-toast data-toast-text="Welcome Back ! This is a Toast Notification" data-toast-gravity="top" data-toast-position="right" data-toast-duration="3000" data-toast-offset data-toast-close="close" class="btn btn-light w-xs">
                                            Offset Position
                                        </button>

                                        <button type="button" data-toast data-toast-text="Welcome Back ! This is a Toast Notification" data-toast-position="right" data-toast-duration="3000" data-toast-close="close" class="btn btn-light w-xs">
                                            Close icon Display
                                        </button>

                                        <button type="button" data-toast data-toast-text="Toast Duration 5s" data-toast-gravity="top" data-toast-position="right" data-toast-duration="5000" class="btn btn-light w-xs">
                                            Duration
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
                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Welcome Back ! This is a Toast Notification&quot; data-toast-gravity=&quot;top&quot; data-toast-position=&quot;right&quot; data-toast-duration=&quot;3000&quot; data-toast-offset data-toast-close=&quot;close&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Offset Position
                                                    &lt;/button&gt;

                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Welcome Back ! This is a Toast Notification&quot; data-toast-position=&quot;right&quot; data-toast-duration=&quot;3000&quot; data-toast-close=&quot;close&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Close icon Display
                                                    &lt;/button&gt;

                                                    &lt;button type=&quot;button&quot; data-toast data-toast-text=&quot;Toast Duration 5s&quot; data-toast-gravity=&quot;top&quot; data-toast-position=&quot;right&quot; data-toast-duration=&quot;5000&quot; class=&quot;btn btn-light w-xs&quot;&gt;
                                                        Duration
                                                    &lt;/button&gt;
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
                                    <a href="#basic" class="nav-link">Basic</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#display_position" class="nav-link">Display Position Example</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#rater" class="nav-link">Offset, Close Button & Duration Example</a>
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