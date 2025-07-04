<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Pagination")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Pagination")) ?>
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
                                <h5 class="card-title mb-1 anchor" id="default-buttons">
                                    Default Buttons<a class="anchor-link" href="#default-buttons">#</a>
                                </h5>
                                <p class="text-muted">
                                    Use the button classes on an <code>&lt;a&gt;</code>, <code>&lt;button&gt;</code> or <code>&lt;input&gt;</code> element.
                                </p>
                                <div class="mb-3">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">Previous</a></li>
                                            <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">Next</a></li>
                                        </ul>
                                    </nav>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination mb-0">
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0);" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0);" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>

                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="defaultButtons">
                                                &lt;nav aria-label=&quot;Page navigation example&quot;&gt;
                                                    &lt;ul class=&quot;pagination&quot;&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;Previous&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item active&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;Next&lt;/a&gt;&lt;/li&gt;
                                                    &lt;/ul&gt;
                                                &lt;/nav&gt;

                                                &lt;nav aria-label=&quot;Page navigation example&quot;&gt;
                                                    &lt;ul class=&quot;pagination mb-0&quot;&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;
                                                            &lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot; aria-label=&quot;Previous&quot;&gt;
                                                                &lt;span aria-hidden=&quot;true&quot;&gt;&amp;laquo;&lt;/span&gt;
                                                            &lt;/a&gt;
                                                        &lt;/li&gt;
                                                        &lt;li class=&quot;page-item active&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;
                                                            &lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot; aria-label=&quot;Next&quot;&gt;
                                                                &lt;span aria-hidden=&quot;true&quot;&gt;&amp;raquo;&lt;/span&gt;
                                                            &lt;/a&gt;
                                                        &lt;/li&gt;
                                                    &lt;/ul&gt;
                                                &lt;/nav&gt;                                                      
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="rounded-pagination">
                                    Rounded Pagination <a class="anchor-link" href="#rounded-pagination">#</a>
                                </h5>
                                <p class="text-muted">
                                    Simple pagination inspired by Rdio, great for apps and search results.
                                </p>
                                <div class="mb-3">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination pagination-rounded">
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">Previous</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">1</a></li>
                                            <li class="page-item active"><a class="page-link" href="javascript:void(0);">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">Next</a></li>
                                        </ul>
                                    </nav>
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination pagination-rounded mb-0">
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0);" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">1</a></li>
                                            <li class="page-item active"><a class="page-link" href="javascript:void(0);">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0);" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>

                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="roundedButtons">
                                                &lt;nav aria-label=&quot;Page navigation example&quot;&gt;
                                                    &lt;ul class=&quot;pagination pagination-rounded&quot;&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;Previous&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item active&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;Next&lt;/a&gt;&lt;/li&gt;
                                                    &lt;/ul&gt;
                                                &lt;/nav&gt;

                                                &lt;nav aria-label=&quot;Page navigation example&quot;&gt;
                                                    &lt;ul class=&quot;pagination pagination-rounded mb-0&quot;&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;
                                                            &lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot; aria-label=&quot;Previous&quot;&gt;
                                                                &lt;span aria-hidden=&quot;true&quot;&gt;&amp;laquo;&lt;/span&gt;
                                                            &lt;/a&gt;
                                                        &lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item active&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;
                                                            &lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot; aria-label=&quot;Next&quot;&gt;
                                                                &lt;span aria-hidden=&quot;true&quot;&gt;&amp;raquo;&lt;/span&gt;
                                                            &lt;/a&gt;
                                                        &lt;/li&gt;
                                                    &lt;/ul&gt;
                                                &lt;/nav&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="alignment">
                                    Alignment <a class="anchor-link" href="#alignment">#</a>
                                </h5>
                                <p class="text-muted">
                                    Change the alignment of pagination components with flexbox utilitie
                                </p>
                                <div class="mb-3">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">Previous</a></li>
                                            <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">Next</a></li>
                                        </ul>
                                    </nav>

                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end mb-0">
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">Previous</a></li>
                                            <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">Next</a></li>
                                        </ul>
                                    </nav>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>

                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="outlinetButtons">
                                                &lt;nav aria-label=&quot;Page navigation example&quot;&gt;
                                                    &lt;ul class=&quot;pagination justify-content-center&quot;&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;Previous&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item active&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;Next&lt;/a&gt;&lt;/li&gt;
                                                    &lt;/ul&gt;
                                                &lt;/nav&gt;
                                                
                                                &lt;nav aria-label=&quot;Page navigation example&quot;&gt;
                                                    &lt;ul class=&quot;pagination justify-content-end mb-0&quot;&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;Previous&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item active&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;1&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;Next&lt;/a&gt;&lt;/li&gt;
                                                    &lt;/ul&gt;
                                                &lt;/nav&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="sizing">
                                    Sizing <a class="anchor-link" href="#sizing">#</a>
                                </h5>
                                <p class="text-muted">
                                    Add <code>.pagination-lg</code> or <code>.pagination-sm</code> for additional sizes.
                                </p>

                                <div class="mb-3">
                                    <nav aria-label="...">
                                        <ul class="pagination pagination-lg">
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">1</span>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                        </ul>
                                    </nav>
                                    <nav aria-label="...">
                                        <ul class="pagination">
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">1</span>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                        </ul>
                                    </nav>
                                    <nav aria-label="...">
                                        <ul class="pagination pagination-sm mb-0">
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">1</span>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                            <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                        </ul>
                                    </nav>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>

                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="outlineRoundedButtons">
                                                &lt;nav aria-label=&quot;...&quot;&gt;
                                                    &lt;ul class=&quot;pagination pagination-lg&quot;&gt;
                                                        &lt;li class=&quot;page-item active&quot; aria-current=&quot;page&quot;&gt;
                                                            &lt;span class=&quot;page-link&quot;&gt;1&lt;/span&gt;
                                                        &lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                    &lt;/ul&gt;
                                                &lt;/nav&gt;
                                                
                                                &lt;nav aria-label=&quot;...&quot;&gt;
                                                    &lt;ul class=&quot;pagination&quot;&gt;
                                                        &lt;li class=&quot;page-item active&quot; aria-current=&quot;page&quot;&gt;
                                                            &lt;span class=&quot;page-link&quot;&gt;1&lt;/span&gt;
                                                        &lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                    &lt;/ul&gt;
                                                &lt;/nav&gt;
                                                
                                                &lt;nav aria-label=&quot;...&quot;&gt;
                                                    &lt;ul class=&quot;pagination pagination-sm mb-0&quot;&gt;
                                                        &lt;li class=&quot;page-item active&quot; aria-current=&quot;page&quot;&gt;
                                                            &lt;span class=&quot;page-link&quot;&gt;1&lt;/span&gt;
                                                        &lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;2&lt;/a&gt;&lt;/li&gt;
                                                        &lt;li class=&quot;page-item&quot;&gt;&lt;a class=&quot;page-link&quot; href=&quot;javascript:void(0);&quot;&gt;3&lt;/a&gt;&lt;/li&gt;
                                                    &lt;/ul&gt;
                                                &lt;/nav&gt;                                                    
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end card body -->
                    </div> <!-- end card -->

                    <div class="col-xl-3">
                        <div class="card docs-nav">
                            <ul class="nav bg-transparent flex-column">
                                <li class="nav-item">
                                    <a href="#default-buttons" class="nav-link">Default Buttons</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#rounded-pagination" class="nav-link">Rounded Pagination</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#alignment" class="nav-link">Alignment</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#sizing" class="nav-link">Sizing</a>
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