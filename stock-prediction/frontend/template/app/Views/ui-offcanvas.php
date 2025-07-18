<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Offcanvas")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Offcanvas")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container">

                <!-- start offcanvas -->
                <div class="row">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="default">
                                    Default Buttons<a class="anchor-link" href="#default">#</a>
                                </h5>
                                <p class="text-muted">
                                    You can use a link with the href attribute, or a button with the <code>data-bs-target</code> attribute.
                                    In both cases, the <code>data-bs-toggle="offcanvas"</code> is required.
                                </p>
                                <div class="mb-3">
                                    <div class="button-list">
                                        <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                                            Link with href
                                        </a>
                                        <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                                            Button with data-bs-target
                                        </button>
                                    </div>
                                    <!-- default offcanvas -->
                                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title mt-0" id="offcanvasExampleLabel">Offcanvas</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>
                                                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                            </p>
                                            <div class="dropdown mt-3">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="defaultDropdownMenuButton" data-bs-toggle="dropdown">
                                                    Dropdown button
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="defaultDropdownMenuButton">
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                                </ul>
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
                                            <code id="defaultButtons">
                                                &lt;div class=&quot;button-list&quot;&gt;
                                                    &lt;a class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;offcanvas&quot; href=&quot;#offcanvasExample&quot; role=&quot;button&quot; aria-controls=&quot;offcanvasExample&quot;&gt;
                                                        Link with href
                                                    &lt;/a&gt;
                                                    &lt;button class=&quot;btn btn-secondary&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#offcanvasExample&quot; aria-controls=&quot;offcanvasExample&quot;&gt; 
                                                        Button with data-bs-target
                                                    &lt;/button&gt;
                                                &lt;/div&gt;


                                                &lt;!-- default offcanvas --&gt;
                                                &lt;div class=&quot;offcanvas offcanvas-start&quot; tabindex=&quot;-1&quot; id=&quot;offcanvasExample&quot; aria-labelledby=&quot;offcanvasExampleLabel&quot;&gt;
                                                    &lt;div class=&quot;offcanvas-header&quot;&gt;
                                                        &lt;h5 class=&quot;offcanvas-title mt-0&quot; id=&quot;offcanvasExampleLabel&quot;&gt;Offcanvas&lt;/h5&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn-close text-reset&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;offcanvas-body&quot;&gt;
                                                        &lt;p&gt;
                                                            Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                                        &lt;/p&gt;
                                                        &lt;div class=&quot;dropdown mt-3&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;defaultDropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot;&gt;
                                                                Dropdown button
                                                            &lt;/button&gt;
                                                            &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;defaultDropdownMenuButton&quot;&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/div&gt;
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
                                <h5 class="card-title mb-1 anchor" id="static-backdrop">
                                    Static Backdrop <a class="anchor-link" href="#static-backdrop">#</a>
                                </h5>
                                <p class="text-muted">
                                    Scrolling the <code>&lt;body&gt;</code> element is disabled when an offcanvas and its backdrop are visible.
                                    Use the <code>data-bs-scroll</code> attribute to toggle <code>&lt;body&gt;</code> scrolling and
                                    <code>data-bs-backdrop</code> to toggle the backdrop.
                                </p>
                                <div class="mb-3">
                                    <div class="button-list">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Enable Body Scrolling</button>
                                        <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBackdrop" aria-controls="offcanvasWithBackdrop">Enable Backdrop (Default)</button>
                                        <button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">Enable Both Scrolling & Backdrop</button>
                                    </div>

                                    <!-- scrolling offcanvas -->
                                    <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title mt-0" id="offcanvasScrollingLabel">Colored with scrolling</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>
                                                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                            </p>
                                            <div class="dropdown mt-3">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="scrollingDropdownMenuButton" data-bs-toggle="dropdown">
                                                    Dropdown button
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="scrollingDropdownMenuButton">
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- backdrop offcanvas -->
                                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasWithBackdrop" aria-labelledby="offcanvasWithBackdropLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title mt-0" id="offcanvasWithBackdropLabel">Offcanvas with backdrop</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>
                                                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                            </p>
                                            <div class="dropdown mt-3">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="backdropDropdownMenuButton" data-bs-toggle="dropdown">
                                                    Dropdown button
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="backdropDropdownMenuButton">
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- both scrolling & backdrop offcanvas -->
                                    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title mt-0" id="offcanvasWithBothOptionsLabel">Backdroped with scrolling</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>
                                                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                            </p>
                                            <div class="dropdown mt-3">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="scrollingBackdropDropdownMenuButton" data-bs-toggle="dropdown">
                                                    Dropdown button
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="scrollingBackdropDropdownMenuButton">
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                                </ul>
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
                                            <code id="roundedButtons">
                                                &lt;div class=&quot;button-list&quot;&gt;
                                                    &lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#offcanvasScrolling&quot; aria-controls=&quot;offcanvasScrolling&quot;&gt;Enable Body Scrolling&lt;/button&gt;
                                                    &lt;button class=&quot;btn btn-secondary&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#offcanvasWithBackdrop&quot; aria-controls=&quot;offcanvasWithBackdrop&quot;&gt;Enable Backdrop (Default)&lt;/button&gt;
                                                    &lt;button class=&quot;btn btn-success&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#offcanvasWithBothOptions&quot; aria-controls=&quot;offcanvasWithBothOptions&quot;&gt;Enable Both Scrolling &amp; Backdrop&lt;/button&gt;
                                                &lt;/div&gt;

                                                &lt;!-- scrolling offcanvas --&gt;
                                                &lt;div class=&quot;offcanvas offcanvas-start&quot; data-bs-scroll=&quot;true&quot; data-bs-backdrop=&quot;false&quot; tabindex=&quot;-1&quot; id=&quot;offcanvasScrolling&quot; aria-labelledby=&quot;offcanvasScrollingLabel&quot;&gt;
                                                    &lt;div class=&quot;offcanvas-header&quot;&gt;
                                                        &lt;h5 class=&quot;offcanvas-title mt-0&quot; id=&quot;offcanvasScrollingLabel&quot;&gt;Colored with scrolling&lt;/h5&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn-close text-reset&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;offcanvas-body&quot;&gt;
                                                        &lt;p&gt;
                                                            Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                                        &lt;/p&gt;
                                                        &lt;div class=&quot;dropdown mt-3&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;scrollingDropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot;&gt;
                                                                Dropdown button
                                                            &lt;/button&gt;
                                                            &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;scrollingDropdownMenuButton&quot;&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;

                                                &lt;!-- backdrop offcanvas --&gt;
                                                &lt;div class=&quot;offcanvas offcanvas-start&quot; tabindex=&quot;-1&quot; id=&quot;offcanvasWithBackdrop&quot; aria-labelledby=&quot;offcanvasWithBackdropLabel&quot;&gt;
                                                    &lt;div class=&quot;offcanvas-header&quot;&gt;
                                                        &lt;h5 class=&quot;offcanvas-title mt-0&quot; id=&quot;offcanvasWithBackdropLabel&quot;&gt;Offcanvas with backdrop&lt;/h5&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn-close text-reset&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;offcanvas-body&quot;&gt;
                                                        &lt;p&gt;
                                                            Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                                        &lt;/p&gt;
                                                        &lt;div class=&quot;dropdown mt-3&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;backdropDropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot;&gt;
                                                                Dropdown button
                                                            &lt;/button&gt;
                                                            &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;backdropDropdownMenuButton&quot;&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;

                                                &lt;!-- both scrolling &amp; backdrop offcanvas --&gt;
                                                &lt;div class=&quot;offcanvas offcanvas-start&quot; data-bs-scroll=&quot;true&quot; tabindex=&quot;-1&quot; id=&quot;offcanvasWithBothOptions&quot; aria-labelledby=&quot;offcanvasWithBothOptionsLabel&quot;&gt;
                                                    &lt;div class=&quot;offcanvas-header&quot;&gt;
                                                        &lt;h5 class=&quot;offcanvas-title mt-0&quot; id=&quot;offcanvasWithBothOptionsLabel&quot;&gt;Backdroped with scrolling&lt;/h5&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn-close text-reset&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;offcanvas-body&quot;&gt;
                                                        &lt;p&gt;
                                                            Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                                        &lt;/p&gt;
                                                        &lt;div class=&quot;dropdown mt-3&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;scrollingBackdropDropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot;&gt;
                                                                Dropdown button
                                                            &lt;/button&gt;
                                                            &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;scrollingBackdropDropdownMenuButton&quot;&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/div&gt;
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
                                <h5 class="card-title mb-1 anchor" id="offcanvas-position">
                                    Offcanvas Position <a class="anchor-link" href="#offcanvas-position">#</a>
                                </h5>
                                <p class="text-muted">
                                    Try the top, right, bottom and left examples out below.
                                </p>
                                <ul>
                                    <li><code>.offcanvas-top</code> places offcanvas on the top of the viewport</li>
                                    <li><code>.offcanvas-end</code> places offcanvas on the right of the viewport</li>
                                    <li><code>.offcanvas-bottom</code> places offcanvas on the bottom of the viewport</li>
                                    <li><code>.offcanvas-start</code> places offcanvas on the left of the viewport</li>
                                </ul>
                                <div class="mb-3">
                                    <div class="button-list">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#leftOffcanvas" aria-controls="leftOffcanvas">Left Offcanvas</button>
                                        <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#rightOffcanvas" aria-controls="rightOffcanvas">Right Offcanvas</button>
                                        <button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#topOffcanvas" aria-controls="topOffcanvas">Top Offcanvas</button>
                                        <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#bottomOffcanvas" aria-controls="bottomOffcanvas">Bottom Offcanvas</button>
                                    </div>

                                    <!-- left offcanvas -->
                                    <div class="offcanvas offcanvas-start" tabindex="-1" id="leftOffcanvas" aria-labelledby="leftOffcanvasLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title mt-0" id="leftOffcanvasLabel">Offcanvas</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>
                                                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                            </p>
                                            <div class="dropdown mt-3">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="leftDropdownMenuButton" data-bs-toggle="dropdown">
                                                    Dropdown button
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="leftDropdownMenuButton">
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- right offcanvas -->
                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="rightOffcanvas" aria-labelledby="rightOffcanvasLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title mt-0" id="rightOffcanvasLabel">Offcanvas</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>
                                                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                            </p>
                                            <div class="dropdown mt-3">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="rightDropdownMenuButton" data-bs-toggle="dropdown">
                                                    Dropdown button
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="rightDropdownMenuButton">
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- top offcanvas -->
                                    <div class="offcanvas offcanvas-top" tabindex="-1" id="topOffcanvas" aria-labelledby="topOffcanvasLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title mt-0" id="topOffcanvasLabel">Offcanvas</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>
                                                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                            </p>
                                            <div class="dropdown mt-3">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="topDropdownMenuButton" data-bs-toggle="dropdown">
                                                    Dropdown button
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="topDropdownMenuButton">
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- bottom offcanvas -->
                                    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="bottomOffcanvas" aria-labelledby="bottomOffcanvasLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title mt-0" id="bottomOffcanvasLabel">Offcanvas</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>
                                                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                            </p>
                                            <div class="dropdown mt-3">
                                                <button class="btn btn-primary dropdown-toggle" type="button" id="bottomDropdownMenuButton" data-bs-toggle="dropdown">
                                                    Dropdown button
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="bottomDropdownMenuButton">
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                                </ul>
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
                                            <code id="outlinetButtons">
                                                &lt;div class=&quot;button-list&quot;&gt;
                                                    &lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#leftOffcanvas&quot; aria-controls=&quot;leftOffcanvas&quot;&gt;Left Offcanvas&lt;/button&gt;
                                                    &lt;button class=&quot;btn btn-secondary&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#rightOffcanvas&quot; aria-controls=&quot;rightOffcanvas&quot;&gt;Right Offcanvas&lt;/button&gt;
                                                    &lt;button class=&quot;btn btn-success&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#topOffcanvas&quot; aria-controls=&quot;topOffcanvas&quot;&gt;Top Offcanvas&lt;/button&gt;
                                                    &lt;button class=&quot;btn btn-info&quot; type=&quot;button&quot; data-bs-toggle=&quot;offcanvas&quot; data-bs-target=&quot;#bottomOffcanvas&quot; aria-controls=&quot;bottomOffcanvas&quot;&gt;Bottom Offcanvas&lt;/button&gt;
                                                &lt;/div&gt;

                                                &lt;!-- left offcanvas --&gt;
                                                &lt;div class=&quot;offcanvas offcanvas-start&quot; tabindex=&quot;-1&quot; id=&quot;leftOffcanvas&quot; aria-labelledby=&quot;leftOffcanvasLabel&quot;&gt;
                                                    &lt;div class=&quot;offcanvas-header&quot;&gt;
                                                        &lt;h5 class=&quot;offcanvas-title mt-0&quot; id=&quot;leftOffcanvasLabel&quot;&gt;Offcanvas&lt;/h5&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn-close text-reset&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;offcanvas-body&quot;&gt;
                                                        &lt;p&gt;
                                                            Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                                        &lt;/p&gt;
                                                        &lt;div class=&quot;dropdown mt-3&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;leftDropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot;&gt;
                                                                Dropdown button
                                                            &lt;/button&gt;
                                                            &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;leftDropdownMenuButton&quot;&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;

                                                &lt;!-- right offcanvas --&gt;
                                                &lt;div class=&quot;offcanvas offcanvas-end&quot; tabindex=&quot;-1&quot; id=&quot;rightOffcanvas&quot; aria-labelledby=&quot;rightOffcanvasLabel&quot;&gt;
                                                    &lt;div class=&quot;offcanvas-header&quot;&gt;
                                                        &lt;h5 class=&quot;offcanvas-title mt-0&quot; id=&quot;rightOffcanvasLabel&quot;&gt;Offcanvas&lt;/h5&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn-close text-reset&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;offcanvas-body&quot;&gt;
                                                        &lt;p&gt;
                                                            Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                                        &lt;/p&gt;
                                                        &lt;div class=&quot;dropdown mt-3&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;rightDropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot;&gt;
                                                                Dropdown button
                                                            &lt;/button&gt;
                                                            &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;rightDropdownMenuButton&quot;&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;

                                                &lt;!-- top offcanvas --&gt;
                                                &lt;div class=&quot;offcanvas offcanvas-top&quot; tabindex=&quot;-1&quot; id=&quot;topOffcanvas&quot; aria-labelledby=&quot;topOffcanvasLabel&quot;&gt;
                                                    &lt;div class=&quot;offcanvas-header&quot;&gt;
                                                        &lt;h5 class=&quot;offcanvas-title mt-0&quot; id=&quot;topOffcanvasLabel&quot;&gt;Offcanvas&lt;/h5&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn-close text-reset&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;offcanvas-body&quot;&gt;
                                                        &lt;p&gt;
                                                            Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                                        &lt;/p&gt;
                                                        &lt;div class=&quot;dropdown mt-3&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;topDropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot;&gt;
                                                                Dropdown button
                                                            &lt;/button&gt;
                                                            &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;topDropdownMenuButton&quot;&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;

                                                &lt;!-- bottom offcanvas --&gt;
                                                &lt;div class=&quot;offcanvas offcanvas-bottom&quot; tabindex=&quot;-1&quot; id=&quot;bottomOffcanvas&quot; aria-labelledby=&quot;bottomOffcanvasLabel&quot;&gt;
                                                    &lt;div class=&quot;offcanvas-header&quot;&gt;
                                                        &lt;h5 class=&quot;offcanvas-title mt-0&quot; id=&quot;bottomOffcanvasLabel&quot;&gt;Offcanvas&lt;/h5&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn-close text-reset&quot; data-bs-dismiss=&quot;offcanvas&quot; aria-label=&quot;Close&quot;&gt;&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;offcanvas-body&quot;&gt;
                                                        &lt;p&gt;
                                                            Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                                        &lt;/p&gt;
                                                        &lt;div class=&quot;dropdown mt-3&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;bottomDropdownMenuButton&quot; data-bs-toggle=&quot;dropdown&quot;&gt;
                                                                Dropdown button
                                                            &lt;/button&gt;
                                                            &lt;ul class=&quot;dropdown-menu&quot; aria-labelledby=&quot;bottomDropdownMenuButton&quot;&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                                &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;/ul&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;                                                  
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="card docs-nav">
                            <ul class="nav bg-transparent flex-column">
                                <li class="nav-item">
                                    <a href="#default" class="nav-link">Default Buttons</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#static-backdrop" class="nav-link">Static Backdrop</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#offcanvas-position" class="nav-link">Offcanvas Position</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

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