<!DOCTYPE html>
<html lang="en">

<head>

    <?php echo view("partials/title-meta", array("title" => "Buttons")) ?>

    <?= $this->include("partials/head-css") ?>


</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Buttons")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div>

            <div class="page-content">

                <!-- Start Container Fluid -->
                <div class="container">

                    <!-- start button -->
                    <div class="row">
                        <div class="col-xl-9">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="default">
                                        Default Buttons<a class="anchor-link" href="#default">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Use the button classes on an <code>&lt;a&gt;</code>, <code>&lt;button&gt;</code> or <code>&lt;input&gt;</code> element.
                                    </p>
                                    <div class="mb-3">
                                        <div class="button-list">
                                            <button type="button" class="btn btn-primary">Primary</button>
                                            <button type="button" class="btn btn-secondary">Secondary</button>
                                            <button type="button" class="btn btn-success">Success</button>
                                            <button type="button" class="btn btn-info">Info</button>
                                            <button type="button" class="btn btn-warning">Warning</button>
                                            <button type="button" class="btn btn-danger">Danger</button>
                                            <button type="button" class="btn btn-dark">Dark</button>
                                            <button type="button" class="btn btn-purple">Purple</button>
                                            <button type="button" class="btn btn-pink">Pink</button>
                                            <button type="button" class="btn btn-orange">Orange</button>
                                            <button type="button" class="btn btn-light">Light</button>
                                            <button type="button" class="btn btn-link">Link</button>
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
                                                    &lt;!-- Default Buttons --&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;Primary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;Secondary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot;&gt;Success&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot;&gt;Info&lt;/button&gt; 
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-warning&quot;&gt;Warning&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-danger&quot;&gt;Danger&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-dark&quot;&gt;Dark&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-purple&quot;&gt;Purple&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-pink&quot;&gt;Pink&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-orange&quot;&gt;Orange&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Light&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-link&quot;&gt;Link&lt;/button&gt;
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="rounded">
                                        Rounded Buttons <a class="anchor-link" href="#rounded">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Add <code>.rounded-pill</code> to default button to get rounded corners.
                                    </p>
                                    <div class="mb-3">
                                        <div class="button-list">
                                            <button type="button" class="btn btn-primary rounded-pill">Primary</button>
                                            <button type="button" class="btn btn-secondary rounded-pill">Secondary</button>
                                            <button type="button" class="btn btn-success rounded-pill">Success</button>
                                            <button type="button" class="btn btn-info rounded-pill">Info</button>
                                            <button type="button" class="btn btn-warning rounded-pill">Warning</button>
                                            <button type="button" class="btn btn-danger rounded-pill">Danger</button>
                                            <button type="button" class="btn btn-dark rounded-pill">Dark</button>
                                            <button type="button" class="btn btn-purple rounded-pill">Purple</button>
                                            <button type="button" class="btn btn-pink rounded-pill">Pink</button>
                                            <button type="button" class="btn btn-orange rounded-pill">Orange</button>
                                            <button type="button" class="btn btn-light rounded-pill">Light</button>
                                            <button type="button" class="btn btn-link rounded-pill">Link</button>
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
                                                    &lt;!-- Rounded Buttons --&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary rounded-pill&quot;&gt;Primary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary rounded-pill&quot;&gt;Secondary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-success rounded-pill&quot;&gt;Success&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-info rounded-pill&quot;&gt;Info&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-warning rounded-pill&quot;&gt;Warning&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-danger rounded-pill&quot;&gt;Danger&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-dark rounded-pill&quot;&gt;Dark&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-purple rounded-pill&quot;&gt;Purple&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-pink rounded-pill&quot;&gt;Pink&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-orange rounded-pill&quot;&gt;Orange&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-light rounded-pill&quot;&gt;Light&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-link rounded-pill&quot;&gt;Link&lt;/button&gt;
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="outline">
                                        Outline Buttons <a class="anchor-link" href="#outline">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Use a classes <code>.btn-outline-**</code> to quickly create a bordered buttons.
                                    </p>
                                    <div class="mb-3">
                                        <div class="button-list">
                                            <button type="button" class="btn btn-outline-primary">Primary</button>
                                            <button type="button" class="btn btn-outline-secondary">Secondary</button>
                                            <button type="button" class="btn btn-outline-success">Success</button>
                                            <button type="button" class="btn btn-outline-info">Info</button>
                                            <button type="button" class="btn btn-outline-warning">Warning</button>
                                            <button type="button" class="btn btn-outline-purple">Purple</button>
                                            <button type="button" class="btn btn-outline-pink">Pink</button>
                                            <button type="button" class="btn btn-outline-orange">Orange</button>
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
                                                    &lt;!-- Outline Buttons --&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary&quot;&gt;Primary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-secondary&quot;&gt;Secondary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-success&quot;&gt;Success&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-info&quot;&gt;Info&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-warning&quot;&gt;Warning&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-purple&quot;&gt;Purple&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-pink&quot;&gt;Pink&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-orange&quot;&gt;Orange&lt;/button&gt;                                                        
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="outline-rounded">
                                        Outline Rounded Buttons <a class="anchor-link" href="#outline-rounded">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Use a classes <code>.btn-outline-**</code> to quickly create a bordered buttons.
                                    </p>
                                    <div class="mb-3">
                                        <div class="button-list">
                                            <button type="button" class="btn btn-outline-primary rounded-pill">Primary</button>
                                            <button type="button" class="btn btn-outline-secondary rounded-pill">Secondary</button>
                                            <button type="button" class="btn btn-outline-success rounded-pill">Success</button>
                                            <button type="button" class="btn btn-outline-info rounded-pill">Info</button>
                                            <button type="button" class="btn btn-outline-warning rounded-pill">Warning</button>
                                        </div>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code id="outlineRoundedButtons">
                                                    &lt;!-- Outline Buttons --&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-primary rounded-pill&quot;&gt;Primary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-secondary rounded-pill&quot;&gt;Secondary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-success rounded-pill&quot;&gt;Success&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-info rounded-pill&quot;&gt;Info&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-outline-warning rounded-pill&quot;&gt;Warning&lt;/button&gt;                                                           
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="soft">
                                        Soft Buttons <a class="anchor-link" href="#soft">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Use a classes <code>.btn-soft-**</code> to quickly create buttons with soft background.
                                    </p>
                                    <div class="mb-3">
                                        <div class="button-list">
                                            <button type="button" class="btn btn-soft-primary">Primary</button>
                                            <button type="button" class="btn btn-soft-secondary">Secondary</button>
                                            <button type="button" class="btn btn-soft-success">Success</button>
                                            <button type="button" class="btn btn-soft-info">Info</button>
                                            <button type="button" class="btn btn-soft-warning">Warning</button>
                                        </div>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code id="softButtons">
                                                    &lt;!-- Soft Buttons --&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-soft-primary&quot;&gt;Primary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-soft-secondary&quot;&gt;Secondary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-soft-success&quot;&gt;Success&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-soft-info&quot;&gt;Info&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-soft-warning&quot;&gt;Warning&lt;/button&gt;
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="soft-rounded">
                                        Soft Rounded Buttons <a class="anchor-link" href="#soft-rounded">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Use a classes <code>.rounded-pill**</code> with <code>.btn-soft-**</code> to quickly create a Outline Soft buttons.
                                    </p>
                                    <div class="mb-3">
                                        <div class="button-list">
                                            <button type="button" class="btn btn-soft-primary rounded-pill">Primary</button>
                                            <button type="button" class="btn btn-soft-secondary rounded-pill">Secondary</button>
                                            <button type="button" class="btn btn-soft-success rounded-pill">Success</button>
                                            <button type="button" class="btn btn-soft-info rounded-pill">Info</button>
                                            <button type="button" class="btn btn-soft-warning rounded-pill">Warning</button>
                                        </div>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code id="softRoundedButtons">
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-soft-primary rounded-pill&quot;&gt;Primary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-soft-secondary rounded-pill&quot;&gt;Secondary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-soft-success rounded-pill&quot;&gt;Success&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-soft-info rounded-pill&quot;&gt;Info&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-soft-warning rounded-pill&quot;&gt;Warning&lt;/button&gt;                                                         
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="width">
                                        Button Width <a class="anchor-link" href="#width">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Create buttons with minimum width by adding add <code>.width-xs</code>, <code>.width-sm</code>,
                                        <code>.width-md</code>, <code>.width-lg</code> or <code>.width-xl</code>.
                                    </p>
                                    <div class="mb-3">
                                        <div class="button-list">
                                            <button type="button" class="btn btn-primary width-xl">Extra Large</button>
                                            <button type="button" class="btn btn-secondary width-lg">Large</button>
                                            <button type="button" class="btn btn-success width-md">Middle</button>
                                            <button type="button" class="btn btn-info width-sm">Small</button>
                                            <button type="button" class="btn btn-warning width-xs">Xs</button>
                                        </div>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code id="buttonWidth">
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary width-xl&quot;&gt;Extra Large&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary width-lg&quot;&gt;Large&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-success width-md&quot;&gt;Middle&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-info width-sm&quot;&gt;Small&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-warning width-xs&quot;&gt;Xs&lt;/button&gt;                                                          
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="sizes">
                                        Button Sizes <a class="anchor-link" href="#sizes">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Add <code>.btn-lg</code>, <code>.btn-sm</code> for additional sizes.
                                    </p>
                                    <div class="mb-3">
                                        <div class="button-list">
                                            <button type="button" class="btn btn-primary btn-lg">Large</button>
                                            <button type="button" class="btn btn-secondary">Normal</button>
                                            <button type="button" class="btn btn-success btn-sm">Small</button>
                                        </div>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code id="buttonSizes">
                                                    &lt;!-- Button Sizes --&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary btn-lg&quot;&gt;Large&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;Normal&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-success btn-sm&quot;&gt;Small&lt;/button&gt;
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="disabled">
                                        Disabled Button <a class="anchor-link" href="#disabled">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Add <code>disabled</code> attribute to buttons.
                                    </p>
                                    <div class="mb-3">
                                        <div class="button-list">
                                            <button type="button" class="btn btn-primary" disabled>Primary</button>
                                            <button type="button" class="btn btn-secondary" disabled>Secondary</button>
                                            <button type="button" class="btn btn-success" disabled>Success</button>
                                            <button type="button" class="btn btn-info" disabled>Info</button>
                                            <button type="button" class="btn btn-warning" disabled>Warning</button>
                                        </div>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code id="disabledButton">
                                                    &lt;!-- Button Disabled --&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; disabled&gt;Primary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot; disabled&gt;Secondary&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot; disabled&gt;Success&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot; disabled&gt;info&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-warning&quot; disabled&gt;Warning&lt;/button&gt;
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="icon">
                                        Icon Button <a class="anchor-link" href="#icon">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Icon only Button.
                                    </p>
                                    <div class="mb-3">
                                        <div class="button-list">
                                            <button type="button" class="btn btn-primary"><i class="bx bx-heart"></i></button>
                                            <button type="button" class="btn btn-secondary"><i class="bx bx-user-voice"></i></button>
                                            <button type="button" class="btn btn-success"><i class="bx bx-check-double"></i></button>
                                            <button type="button" class="btn btn-info"><i class="bx bx-cloud me-1"></i>Cloude Hosting</button>
                                            <button type="button" class="btn btn-warning"><i class="bx bx-info-circle me-1"></i>Warning</button>
                                        </div>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code id="iconButton">
                                                    &lt;!-- Icon Button --&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot;&gt;
                                                        &lt;i class=&quot;bx bx-heart&quot;&gt;&lt;/i&gt;
                                                    &lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;
                                                        &lt;i class=&quot;bx bx-user-voice&quot;&gt;&lt;/i&gt;
                                                    &lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot;&gt;
                                                        &lt;i class=&quot;bx bx-check-double&quot;&gt;&lt;/i&gt;
                                                    &lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot;&gt;
                                                        &lt;i class=&quot;bx bx-cloud me-1&quot;&gt;&lt;/i&gt;Cloude Hosting
                                                    &lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;btn btn-warning&quot;&gt;
                                                        &lt;i class=&quot;bx bx-info-circle me-1&quot;&gt;&lt;/i&gt;Warning
                                                    &lt;/button&gt;
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div> <!-- end card body -->
                            </div> <!-- end card -->

                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-1 anchor" id="group">
                                        Button Group <a class="anchor-link" href="#group">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Wrap a series of buttons with <code>.btn</code> in <code>.btn-group</code>.
                                    </p>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- btn-group horizontal -->
                                                <div class="btn-group mb-1 me-1">
                                                    <button type="button" class="btn btn-light">Left</button>
                                                    <button type="button" class="btn btn-light">Middle</button>
                                                    <button type="button" class="btn btn-light">Right</button>
                                                </div>
                                                <div class="btn-group mb-1 me-1">
                                                    <button type="button" class="btn btn-light">1</button>
                                                    <button type="button" class="btn btn-light">2</button>
                                                    <button type="button" class="btn btn-secondary">3</button>
                                                    <button type="button" class="btn btn-light">4</button>
                                                </div>
                                                <div class="btn-group mb-1 me-1">
                                                    <button type="button" class="btn btn-light">5</button>
                                                    <button type="button" class="btn btn-secondary">6</button>
                                                    <button type="button" class="btn btn-light">7</button>
                                                    <button id="dropdown" type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Dropdown
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown">
                                                        <li><a class="dropdown-item" href="javascript:void(0);">Dropdown link</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0);">Dropdown link</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- btn-group vertical -->
                                                <div class="btn-group-vertical me-4">
                                                    <button type="button" class="btn btn-light">Top</button>
                                                    <button type="button" class="btn btn-light">Middle</button>
                                                    <button type="button" class="btn btn-light">Bottom</button>
                                                </div>
                                                <div class="btn-group-vertical">
                                                    <button type="button" class="btn btn-light">Button 1</button>
                                                    <button type="button" class="btn btn-light">Button 2</button>
                                                    <button id="verticalDropdown" type="button" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                        Button 3
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="verticalDropdown">
                                                        <li><a class="dropdown-item" href="javascript:void(0);">Dropdown link</a></li>
                                                        <li><a class="dropdown-item" href="javascript:void(0);">Dropdown link</a></li>
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
                                                <code id="buttonGroup">
                                                    &lt;!-- btn-group horizontal --&gt;
                                                    &lt;div class=&quot;btn-group mb-1 me-1&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Left&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Middle&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Right&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;btn-group mb-1 me-1&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;1&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;2&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;3&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;4&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;btn-group mb-1 me-1&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;5&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;6&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;7&lt;/button&gt;
                                                        &lt;button id=&quot;dropdown&quot; type=&quot;button&quot; class=&quot;btn btn-light dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Dropdown
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu dropdown-menu-end&quot; aria-labelledby=&quot;dropdown&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
                                                        &lt;/ul&gt;
                                                    &lt;/div&gt;

                                                    &lt;!-- btn-group vertical --&gt;
                                                    &lt;div class=&quot;btn-group-vertical me-4&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Top&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Middle&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Bottom&lt;/button&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;btn-group-vertical&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Button 1&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light&quot;&gt;Button 2&lt;/button&gt;
                                                        &lt;button id=&quot;verticalDropdown&quot; type=&quot;button&quot; class=&quot;btn btn-light dropdown-toggle&quot; data-bs-toggle=&quot;dropdown&quot; aria-expanded=&quot;false&quot;&gt;
                                                            Button 3
                                                        &lt;/button&gt;
                                                        &lt;ul class=&quot;dropdown-menu dropdown-menu-end&quot; aria-labelledby=&quot;verticalDropdown&quot;&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
                                                            &lt;li&gt;&lt;a class=&quot;dropdown-item&quot; href=&quot;javascript:void(0);&quot;&gt;Dropdown link&lt;/a&gt;&lt;/li&gt;
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
                                    <h5 class="card-title mb-1 anchor" id="block">
                                        Block Button <a class="anchor-link" href="#block">#</a>
                                    </h5>
                                    <p class="text-muted">
                                        Create block level buttons by adding class <code>.d-grid</code> to parent div.
                                    </p>
                                    <div class="mb-3">
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-primary btn-lg">Block Button</button>
                                            <button type="button" class="btn btn-secondary">Block Button</button>
                                            <button type="button" class="btn btn-light btn-sm">Block Button</button>
                                        </div>
                                    </div>

                                    <div class="highlight">
                                        <div class="highlight-toolbar">
                                            <small class="text-uppercase">html</small>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="code">
                                            <pre class="language-html">
                                                <code id="blockButton">
                                                    &lt;!-- Block Button --&gt;
                                                    &lt;div class=&quot;d-grid gap-2&quot;&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-primary btn-lg&quot;&gt;Block Button&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-secondary&quot;&gt;Block Button&lt;/button&gt;
                                                        &lt;button type=&quot;button&quot; class=&quot;btn btn-light btn-sm&quot;&gt;Block Button&lt;/button&gt;
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
                                                <a href="#rounded " class="nav-link">Rounded Buttons </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#outline" class="nav-link">Outline Buttons </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#outline-rounded" class="nav-link">Outline Rounded Buttons </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#soft" class="nav-link">Soft Buttons </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#soft-rounded " class="nav-link">Soft Rounded Buttons </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#width " class="nav-link">Button Width </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#sizes " class="nav-link">Button Sizes </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#disabled " class="nav-link">Disabled Button </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#icon " class="nav-link">Icon Button </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#group " class="nav-link">Button Group </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#block" class="nav-link">Block Button </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end row -->
                    <!-- end button -->

                </div>
                <!-- End Container Fluid -->

                <?= $this->include("partials/footer") ?>

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