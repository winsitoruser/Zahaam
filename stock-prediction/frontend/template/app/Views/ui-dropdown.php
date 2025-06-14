<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Dropdown")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Dropdown")) ?>
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
                                <h5 class="card-title mb-1 anchor" id="single-button">
                                    Single Button Dropdowns<a class="anchor-link" href="#single-button">#</a>
                                </h5>
                                <p class="text-muted">
                                    Any single <code>.btn</code> can be turned into a dropdown toggle with some markup changes.
                                    Here&apos;s how you can put them to work with either <code>&lt;button&gt;</code> elements.
                                </p>

                                <div class="mb-3">
                                    <div class="d-flex flex-wrap gap-3">
                                        <!-- Button Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                Dropdown button
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
                                            </div>
                                        </div>

                                        <!-- Link Dropdown -->
                                        <div class="dropdown">
                                            <a href="#" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Dropdown link
                                            </a>

                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="#">Action</a>
                                                <a class="dropdown-item" href="#">Another action</a>
                                                <a class="dropdown-item" href="#">Something else here</a>
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
                                                <code id="slidesOnly">
                                                    &lt;!-- Button Dropdown --&gt;
                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; id=&quot;dropdownMenuButton1&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Dropdown button
                                                        &lt;/button&gt;
                                                        &lt;div class=&quot;dropdown-menu&quot; aria-labelledby=&quot;dropdownMenuButton1&quot;&gt;
                                                            &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;
                                                            &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;
                                                            &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;

                                                    &lt;!-- Link Dropdown --&gt;
                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;a href=&quot;#&quot; class=&quot;btn btn-secondary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Dropdown link
                                                        &lt;/a&gt;

                                                        &lt;div class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;
                                                            &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;
                                                            &lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="single-button-variant">
                                    Single Button Variant Dropdowns <a class="anchor-link" href="#single-button-variant">#</a>
                                </h5>
                                <p class="text-muted">
                                    Any single <code>.btn</code> can be turned into a dropdown toggle with some markup changes.
                                    Here&apos;s how you can put them to work with either <code>&lt;button&gt;</code> elements.
                                </p>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Primary
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="#">Separated link</a></li>
                                        </ul>
                                    </div>

                                    <div class="dropdown">
                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Secondary
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="#">Separated link</a></li>
                                        </ul>
                                    </div>

                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Success
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="#">Separated link</a></li>
                                        </ul>
                                    </div>

                                    <div class="dropdown">
                                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Info
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="#">Separated link</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code id="singleButtonDropdowns">
                                                    &lt;!-- Single Button Dropdown --&gt;
                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-primary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Primary
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                    
                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Secondary
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-success dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Success
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-info dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Info
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="split-button">
                                    Split Button Dropdowns <a class="anchor-link" href="#split-button">#</a>
                                </h5>
                                <p class="text-muted">
                                    Similarly, create split button dropdowns with virtually the same markup as single button dropdowns,
                                    but with the addition of <code>.dropdown-toggle-split</code> for proper spacing around the dropdown caret.
                                </p>
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary">Primary</button>
                                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-secondary">Secondary</button>
                                        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success">Success</button>
                                        <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info">Info</button>
                                        <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code id="splitButtonDropdowns">
                                                    &lt;div class=&quot;btn-group&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Primary&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-primary dropdown-toggle dropdown-toggle-split&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;&lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                    
                                                    &lt;div class=&quot;btn-group&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;Secondary&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle dropdown-toggle-split&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;&lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                    
                                                    &lt;div class=&quot;btn-group&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot;&gt;Success&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-success dropdown-toggle dropdown-toggle-split&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;&lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                    
                                                    &lt;div class=&quot;btn-group&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot;&gt;Info&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-info dropdown-toggle dropdown-toggle-split&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;&lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="dark-dropdown">
                                    Dark Dropdowns <a class="anchor-link" href="#dark-dropdown">#</a>
                                </h5>
                                <p class="text-muted">Opt into darker dropdowns to match a dark navbar or custom style by adding <code>.dropdown-menu-dark</code> onto an existing <code>.dropdown-menu</code>. No changes are required to the dropdown items.</p>

                                <div class="mb-3">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Dark Dropdown</button>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code id="splitButtonDropdowns">
                                                    &lt;!-- Dark Dropdown --&gt;
                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-primary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;Dark Dropdown&lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu dropdown-menu-dark&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;hr class=&quot;dropdown-divider&quot;&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="direction">
                                    Dropdown Direction <a class="anchor-link" href="#direction">#</a>
                                </h5>
                                <p class="text-muted">
                                    Trigger dropdown menus above elements by adding <code>.dropup</code>, dropdown menus at the left of the
                                    elements by adding <code>.dropstart</code> or dropdown menus at the right of the elements by adding
                                    <code>.dropend</code>.
                                </p>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Drop Down
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group dropup">
                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Drop Up
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group dropend">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Drop Right
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group dropstart">
                                        <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Drop Left
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Separated link</a></li>
                                        </ul>
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
                                                    &lt;!-- Dropdown Direction --&gt;
                                                    &lt;div class=&quot;btn-group&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-primary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Drop Down
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                    
                                                    &lt;div class=&quot;btn-group dropup&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Drop Up
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                    
                                                    &lt;div class=&quot;btn-group dropend&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-success dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Drop Right
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;btn-group dropstart&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-info dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Drop Left
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="menu-items">
                                    Dropdown Menu Items <a class="anchor-link" href="#menu-items">#</a>
                                </h5>
                                <p class="text-muted">Add <code>.active</code> to items in the dropdown to <strong>style them as active</strong>. To convey the active state to assistive technologies, use the <code>aria-current</code> attribute — using the <code>page</code> value for the current page, or <code>true</code> for the current item in a set.</p>
                                <p class="text-muted">Add <code>.disabled</code> to items in the dropdown to <strong>style them as disabled</strong>.</p>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <ul class="dropdown-menu show block position-static">
                                        <li><a class="dropdown-item" href="#">Regular link</a></li>
                                        <li><a class="dropdown-item active" href="#" aria-current="true">Active link</a></li>
                                        <li><a class="dropdown-item" href="#">Another link</a></li>
                                    </ul>

                                    <ul class="dropdown-menu show block position-static">
                                        <li><a class="dropdown-item" href="#">Regular link</a></li>
                                        <li><a class="dropdown-item disabled" href="#" aria-current="true">Active link</a></li>
                                        <li><a class="dropdown-item" href="#">Another link</a></li>
                                    </ul>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code>
                                                    &lt;ul class=&quot;dropdown-menu show block position-static&quot;&gt;
                                                        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Regular link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li&gt;&lt;a class=&quot;dropdown-item active&quot; href=&quot;#&quot; aria-current=&quot;true&quot;&gt;Active link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another link&lt;/a&gt;&lt;/li&gt;
                                                    &lt;/ul&gt;

                                                    &lt;ul class=&quot;dropdown-menu show block position-static&quot;&gt;
                                                        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Regular link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li&gt;&lt;a class=&quot;dropdown-item disabled&quot; href=&quot;#&quot; aria-current=&quot;true&quot;&gt;Active link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another link&lt;/a&gt;&lt;/li&gt;
                                                    &lt;/ul&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="dropdown-options">
                                    Dropdown options <a class="anchor-link" href="#dropdown-options">#</a>
                                </h5>
                                <p class="text-muted">Use <code>data-bs-offset</code> or <code>data-bs-reference</code> to change the location of the dropdown.</p>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="10,20">
                                            Offset
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                        </ul>
                                    </div>

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-secondary">Reference</button>
                                        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                            <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="#">Separated link</a></li>
                                        </ul>
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
                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot; data-bs-offset=&quot;10,20&quot;&gt;
                                                            Offset
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;btn-group&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;Reference&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary dropdown-toggle dropdown-toggle-split&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot; data-bs-reference=&quot;parent&quot;&gt;
                                                            &lt;span class=&quot;visually-hidden&quot;&gt;Toggle Dropdown&lt;/span&gt;
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;
                                                                &lt;hr class=&quot;dropdown-divider&quot;&gt;
                                                            &lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="auto-close-behavior">
                                    Auto close behavior <a class="anchor-link" href="#auto-close-behavior">#</a>
                                </h5>
                                <p class="text-muted">By default, the dropdown menu is closed when clicking inside or outside the dropdown menu. You can use the <code>autoClose</code> option to change this behavior of the dropdown.</p>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="btn-group">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                            Default dropdown
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                        </ul>
                                    </div>

                                    <div class="btn-group">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="inside" aria-expanded="false">
                                            Clickable outside
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                        </ul>
                                    </div>

                                    <div class="btn-group">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                            Clickable inside
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                        </ul>
                                    </div>

                                    <div class="btn-group">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                                            Manual close
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                            <li><a class="dropdown-item" href="#">Menu item</a></li>
                                        </ul>
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
                                                    &lt;div class=&quot;btn-group&quot;&gt;
                                                        &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; data-bs-toggle=&quot;dropdown&quot; data-bs-auto-close=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Default dropdown
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;btn-group&quot;&gt;
                                                        &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; data-bs-toggle=&quot;dropdown&quot; data-bs-auto-close=&quot;inside&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Clickable outside
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;btn-group&quot;&gt;
                                                        &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; data-bs-toggle=&quot;dropdown&quot; data-bs-auto-close=&quot;outside&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Clickable inside
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;btn-group&quot;&gt;
                                                        &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; data-bs-toggle=&quot;dropdown&quot; data-bs-auto-close=&quot;false&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Manual close
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Menu item&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="menu-content">
                                    Menu Content <a class="anchor-link" href="#menu-content">#</a>
                                </h5>
                                <p>Add a header to label sections of actions in any dropdown menu.</p>
                                <p>Separate groups of related menu items with a divider.</p>
                                <p>Place any freeform text within a dropdown menu with text and use spacing utilities. Note that you’ll likely need additional sizing styles to constrain the menu width.</p>
                                <p>Put a form within a dropdown menu, or make it into a dropdown menu, and use margin or padding utilities to give it the negative space you require.</p>

                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Dropdown Header
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-header">Dropdown header</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Action</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);">Another action</a></li>
                                        </ul>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Dropdown Divider
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li><a class="dropdown-item" href="#">Separated link</a></li>
                                        </ul>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Dropdown Text
                                        </button>
                                        <div class="dropdown-menu dropdown-lg p-3">
                                            <p>Some example text that's free-flowing within the dropdown menu.</p>
                                            <p class="mb-0">And this is more example text.</p>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Dropdown Menu Forms
                                        </button>
                                        <form class="dropdown-menu dropdown-lg p-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="exampleDropdownFormEmail">Email address</label>
                                                <input type="email" class="form-control" id="exampleDropdownFormEmail" placeholder="email@example.com">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="exampleDropdownFormPassword">Password</label>
                                                <input type="password" class="form-control" id="exampleDropdownFormPassword" placeholder="Password">
                                            </div>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="dropdownCheck">
                                                    <label class="form-check-label" for="dropdownCheck">Remember me</label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Sign in</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code id="otherExample">
                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button class=&quot;btn btn-primary dropdown-toggle&quot; type=&quot;button&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Dropdown Header
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-header&quot;&gt;Dropdown header&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button class=&quot;btn btn-info dropdown-toggle&quot; type=&quot;button&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Dropdown Divider
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Another action&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Something else here&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;hr class=&quot;dropdown-divider&quot;&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;#&quot;&gt;Separated link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button class=&quot;btn btn-secondary dropdown-toggle&quot; type=&quot;button&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Dropdown Text
                                                        &lt;/button&gt;
                                                        &lt;div class=&quot;dropdown-menu dropdown-lg p-3&quot;&gt;
                                                            &lt;p&gt;Some example text that's free-flowing within the dropdown menu.&lt;/p&gt;
                                                            &lt;p class=&quot;mb-0&quot;&gt;And this is more example text.&lt;/p&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                    
                                                    &lt;div class=&quot;dropdown&quot;&gt;
                                                        &lt;button class=&quot;btn btn-success dropdown-toggle&quot; type=&quot;button&quot; data-bs-toggle=&quot;dropdown&quot; aria-haspopup=&quot;true&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Dropdown Menu Forms
                                                        &lt;/button&gt;
                                                        &lt;form class=&quot;dropdown-menu dropdown-lg p-3&quot;&gt;
                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                &lt;label class=&quot;form-label&quot; for=&quot;exampleDropdownFormEmail&quot;&gt;Email address&lt;/label&gt;
                                                                &lt;input type=&quot;email&quot; class=&quot;form-control&quot; id=&quot;exampleDropdownFormEmail&quot; placeholder=&quot;email@example.com&quot;&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                &lt;label class=&quot;form-label&quot; for=&quot;exampleDropdownFormPassword&quot;&gt;Password&lt;/label&gt;
                                                                &lt;input type=&quot;password&quot; class=&quot;form-control&quot; id=&quot;exampleDropdownFormPassword&quot; placeholder=&quot;Password&quot;&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                &lt;div class=&quot;form-check&quot;&gt;
                                                                    &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;dropdownCheck&quot;&gt;
                                                                    &lt;label class=&quot;form-check-label&quot; for=&quot;dropdownCheck&quot;&gt;Remember me&lt;/label&gt;
                                                                &lt;/div&gt;
                                                            &lt;/div&gt;
                                                            &lt;button type=&quot;submit&quot; class=&quot;btn btn-primary&quot;&gt;Sign in&lt;/button&gt;
                                                        &lt;/form&gt;
                                                    &lt;/div&gt;
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
                                    <a href="#single-button" class="nav-link">Single Button Dropdowns</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#single-button-variant" class="nav-link">Single Button Variant Dropdowns </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#split-button" class="nav-link">Split Button Dropdowns </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#dark-dropdown" class="nav-link">Dark Dropdown</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#direction" class="nav-link">Dropdown Direction </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#menu-items" class="nav-link">Dropdown Menu Items </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#dropdown-options" class="nav-link">Dropdown Options </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#auto-close-behavior" class="nav-link">Auto Close Behavior </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#menu-content" class="nav-link">Menu content </a>
                                </li>
                            </ul>
                        </div> <!-- end card -->
                    </div>
                </div> <!-- end row -->
                <!-- end dropdown -->

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