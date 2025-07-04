<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Collapse")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Collapse")) ?>
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
                                <h5 class="card-title mb-1 anchor" id="default">
                                    Default Example<a class="anchor-link" href="#default">#</a>
                                </h5>
                                <p class="text-muted">Click the buttons below to show and hide another element via class changes:</p>
                                <ul>
                                    <li><code>.collapse</code> hides content</li>
                                    <li><code>.collapsing</code> is applied during transitions</li>
                                    <li><code>.collapse.show</code> shows content</li>
                                </ul>
                                <p class="text-muted">Generally, we recommend using a button with the <code>data-bs-target</code> attribute. While not recommended from a semantic point of view, you can also use a link with the <code>href</code> attribute (and a <code>role="button"</code>). In both cases, the <code>data-bs-toggle="collapse"</code> is required.</p>

                                <div class="mb-3">
                                    <div class="hstack gap-2">
                                        <a class="btn btn-primary mb-2" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Link with href
                                        </a>
                                        <button class="btn btn-primary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                            Button with data-bs-target
                                        </button>
                                    </div>
                                    <div class="collapse" id="collapseExample">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                Some placeholder content for the collapse component. This panel is hidden by default but revealed when the
                                                user activates the relevant trigger.
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
                                                    &lt;div class=&quot;hstack gap-2&quot;&gt;
                                                        &lt;a class=&quot;btn btn-primary mb-2&quot; data-bs-toggle=&quot;collapse&quot; href=&quot;#collapseExample&quot; role=&quot;button&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;collapseExample&quot;&gt;
                                                            Link with href
                                                        &lt;/a&gt;
                                                        &lt;button class=&quot;btn btn-primary mb-2&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseExample&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;collapseExample&quot;&gt;
                                                            Button with data-bs-target
                                                        &lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;collapse&quot; id=&quot;collapseExample&quot;&gt;
                                                        &lt;div class=&quot;card mb-0&quot;&gt;
                                                            &lt;div class=&quot;card-body&quot;&gt;
                                                                Some placeholder content for the collapse component. This panel is hidden by default but revealed when the
                                                                user activates the relevant trigger.
                                                            &lt;/div&gt;
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
                                <h5 class="card-title mb-1 anchor" id="horizontal">
                                    Horizontal <a class="anchor-link" href="#horizontal">#</a>
                                </h5>
                                <p class="text-muted">The collapse plugin also supports horizontal collapsing. Add the <code>.collapse-horizontal</code> modifier class to transition the <code>width</code> instead of <code>height</code> and set a <code>width</code> on the immediate child element. Feel free to write your own custom Sass, use inline styles, or use our width utilities.</p>
                                <div class="mb-3">
                                    <button class="btn btn-primary mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWidthExample" aria-expanded="false" aria-controls="collapseWidthExample">
                                        Toggle width collapse
                                    </button>
                                    <div style="min-height: 135px;">
                                        <div class="collapse collapse-horizontal" id="collapseWidthExample">
                                            <div class="card card-body mb-0" style="width: 300px;">
                                                This is some placeholder content for a horizontal collapse. It's hidden by default and shown when triggered.
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
                                                <code id="withControls">
                                                    &lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseWidthExample&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;collapseWidthExample&quot;&gt;
                                                        Toggle width collapse
                                                    &lt;/button&gt;
                                                    &lt;div style=&quot;min-height: 135px;&quot;&gt;
                                                        &lt;div class=&quot;collapse collapse-horizontal&quot; id=&quot;collapseWidthExample&quot;&gt;
                                                            &lt;div class=&quot;card card-body mb-0&quot; style=&quot;width: 300px;&quot;&gt;
                                                                This is some placeholder content for a horizontal collapse. It's hidden by default and shown when triggered.
                                                            &lt;/div&gt;
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
                                <h5 class="card-title mb-1 anchor" id="multiple-targets">
                                    Multiple Targets <a class="anchor-link" href="#multiple-targets">#</a>
                                </h5>
                                <p class="text-muted">
                                    A <code>&lt;button&gt;</code> or <code>&lt;a&gt;</code> can show and hide multiple elements by referencing them with a selector in its <code>href</code> or <code>data-bs-target</code> attribute.
                                    Multiple <code>&lt;button&gt;</code> or <code>&lt;a&gt;</code> can show and hide an element if they each reference it with their <code>href</code> or <code>data-bs-target</code> attribute</p>
                                <div class="mb-3">
                                    <div class="hstack gap-2 mb-3">
                                        <a class="btn btn-primary" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Toggle first element</a>
                                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">Toggle second element</button>
                                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Toggle both elements</button>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div class="collapse multi-collapse" id="multiCollapseExample1">
                                                <div class="card card-body">
                                                    Some placeholder content for the first collapse component of this multi-collapse example. This panel
                                                    is hidden by default but revealed when the user activates the relevant trigger.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="collapse multi-collapse" id="multiCollapseExample2">
                                                <div class="card card-body">
                                                    Some placeholder content for the second collapse component of this multi-collapse example. This
                                                    panel is hidden by default but revealed when the user activates the relevant trigger.
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
                                                    &lt;div class=&quot;hstack gap-2 mb-3&quot;&gt;
                                                        &lt;a class=&quot;btn btn-primary&quot; data-bs-toggle=&quot;collapse&quot; href=&quot;#multiCollapseExample1&quot; role=&quot;button&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;multiCollapseExample1&quot;&gt;Toggle first element&lt;/a&gt;
                                                        &lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#multiCollapseExample2&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;multiCollapseExample2&quot;&gt;Toggle second element&lt;/button&gt;
                                                        &lt;button class=&quot;btn btn-primary&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;.multi-collapse&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;multiCollapseExample1 multiCollapseExample2&quot;&gt;Toggle both elements&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;row&quot;&gt;
                                                        &lt;div class=&quot;col&quot;&gt;
                                                            &lt;div class=&quot;collapse multi-collapse&quot; id=&quot;multiCollapseExample1&quot;&gt;
                                                                &lt;div class=&quot;card card-body&quot;&gt;
                                                                    Some placeholder content for the first collapse component of this multi-collapse example. This panel
                                                                    is hidden by default but revealed when the user activates the relevant trigger.
                                                                &lt;/div&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col&quot;&gt;
                                                            &lt;div class=&quot;collapse multi-collapse&quot; id=&quot;multiCollapseExample2&quot;&gt;
                                                                &lt;div class=&quot;card card-body&quot;&gt;
                                                                    Some placeholder content for the second collapse component of this multi-collapse example. This
                                                                    panel is hidden by default but revealed when the user activates the relevant trigger.
                                                                &lt;/div&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
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
                            <div class="">
                                <div class="">
                                    <ul class="nav bg-transparent flex-column">
                                        <li class="nav-item">
                                            <a href="#default" class="nav-link">Default Example</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#horizontal" class="nav-link">Horizontal Collapse</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#multiple-targets" class="nav-link">Multiple Targets</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
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