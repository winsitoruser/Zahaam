<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "List Group")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "List Group")) ?>
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
                                <h5 class="card-title mb-1 anchor" id="basic">
                                    Basic<a class="anchor-link" href="#basic">#</a>
                                </h5>
                                <p class="text-muted">The most basic list group is an unordered list with list items and the proper classes. Build upon it with the options that follow, or with your own CSS as needed.</p>
                                <div class="w-50 mb-3">
                                    <ul class="list-group">
                                        <li class="list-group-item">An item</li>
                                        <li class="list-group-item">A second item</li>
                                        <li class="list-group-item">A third item</li>
                                        <li class="list-group-item">A fourth item</li>
                                        <li class="list-group-item">And a fifth one</li>
                                    </ul>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="code">
                                            <pre class="language-html">
                                                <code>
                                                    &lt;ul class=&quot;list-group&quot;&gt;
                                                        &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;
                                                        &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;
                                                        &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;
                                                        &lt;li class=&quot;list-group-item&quot;&gt;A fourth item&lt;/li&gt;
                                                        &lt;li class=&quot;list-group-item&quot;&gt;And a fifth one&lt;/li&gt;
                                                    &lt;/ul&gt;
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="active">
                                    Active items<a class="anchor-link" href="#active">#</a>
                                </h5>
                                <p class="text-muted">Add <code>.active</code> to a <code>.list-group-item</code> to indicate the current active selection.</p>
                                <div class="w-50 mb-3">
                                    <ul class="list-group">
                                        <li class="list-group-item active" aria-current="true">An active item</li>
                                        <li class="list-group-item">A second item</li>
                                        <li class="list-group-item">A third item</li>
                                        <li class="list-group-item">A fourth item</li>
                                        <li class="list-group-item">And a fifth one</li>
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
                                                &lt;ul class=&quot;list-group&quot;&gt;
                                                    &lt;li class=&quot;list-group-item active&quot; aria-current=&quot;true&quot;&gt;An active item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A fourth item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;And a fifth one&lt;/li&gt;
                                                &lt;/ul&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="disabled">
                                    Disabled items<a class="anchor-link" href="#disabled">#</a>
                                </h5>
                                <p class="text-muted">Add <code>.disabled</code> to a <code>.list-group-item</code> to make it <em>appear</em> disabled. Note that some elements with <code>.disabled</code> will also require custom JavaScript to fully disable their click events (e.g., links).</p>
                                <div class="w-50 mb-3">
                                    <ul class="list-group">
                                        <li class="list-group-item disabled" aria-disabled="true">A disabled item</li>
                                        <li class="list-group-item">A second item</li>
                                        <li class="list-group-item">A third item</li>
                                        <li class="list-group-item">A fourth item</li>
                                        <li class="list-group-item">And a fifth one</li>
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
                                                &lt;ul class=&quot;list-group&quot;&gt;
                                                    &lt;li class=&quot;list-group-item disabled&quot; aria-disabled=&quot;true&quot;&gt;A disabled item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A fourth item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;And a fifth one&lt;/li&gt;
                                                &lt;/ul&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="links-buttons">
                                    Links and buttons<a class="anchor-link" href="#links-buttons">#</a>
                                </h5>
                                <p class="taext-muted">Use <code>&lt;a&gt;</code>s or <code>&lt;button&gt;</code>s to create <em>actionable</em> list group items with hover, disabled, and active states by adding <code>.list-group-item-action</code>. We separate these pseudo-classes to ensure list groups made of non-interactive elements (like <code>&lt;li&gt;</code>s or <code>&lt;div&gt;</code>s) don’t provide a click or tap affordance.</p>
                                <div class="row p-3">
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                                The current link item
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
                                            <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                                            <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                                            <a class="list-group-item list-group-item-action disabled">A disabled link item</a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="list-group">
                                            <button type="button" class="list-group-item list-group-item-action active" aria-current="true">
                                                The current button
                                            </button>
                                            <button type="button" class="list-group-item list-group-item-action">A second button item</button>
                                            <button type="button" class="list-group-item list-group-item-action">A third button item</button>
                                            <button type="button" class="list-group-item list-group-item-action">A fourth button item</button>
                                            <button type="button" class="list-group-item list-group-item-action" disabled>A disabled button item</button>
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
                                            <code>
                                                &lt;div class=&quot;list-group&quot;&gt;
                                                    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action active&quot; aria-current=&quot;true&quot;&gt;
                                                        The current link item
                                                    &lt;/a&gt;
                                                    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;A second link item&lt;/a&gt;
                                                    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;A third link item&lt;/a&gt;
                                                    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;A fourth link item&lt;/a&gt;
                                                    &lt;a class=&quot;list-group-item list-group-item-action disabled&quot;&gt;A disabled link item&lt;/a&gt;
                                                &lt;/div&gt;

                                                &lt;div class=&quot;list-group&quot;&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;list-group-item list-group-item-action active&quot; aria-current=&quot;true&quot;&gt;
                                                        The current button
                                                    &lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;A second button item&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;A third button item&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;A fourth button item&lt;/button&gt;
                                                    &lt;button type=&quot;button&quot; class=&quot;list-group-item list-group-item-action&quot; disabled&gt;A disabled button item&lt;/button&gt;
                                                &lt;/div&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="flush">
                                    Flush<a class="anchor-link" href="#flush">#</a>
                                </h5>
                                <p class="taext-muted">Add <code>.list-group-flush</code> to remove some borders and rounded corners to render list group items edge-to-edge in a parent container (e.g., cards).</p>
                                <div class="p-3 w-50">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">An item</li>
                                        <li class="list-group-item">A second item</li>
                                        <li class="list-group-item">A third item</li>
                                        <li class="list-group-item">A fourth item</li>
                                        <li class="list-group-item">And a fifth one</li>
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
                                                &lt;ul class=&quot;list-group list-group-flush&quot;&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A fourth item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;And a fifth one&lt;/li&gt;
                                                &lt;/ul&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="numbered">
                                    Numbered<a class="anchor-link" href="#numbered">#</a>
                                </h5>
                                <p class="taext-muted">Add the <code>.list-group-numbered</code> modifier class (and optionally use an <code>&lt;ol&gt;</code> element) to opt into numbered list group items. Numbers are generated via CSS (as opposed to a <code>&lt;ol&gt;</code>s default browser styling) for better placement inside list group items and to allow for better customization.</p>
                                <p class="taext-muted">Numbers are generated by <code>counter-reset</code> on the <code>&lt;ol&gt;</code>, and then styled and placed with a <code>::before</code> pseudo-element on the <code>&lt;li&gt;</code> with <code>counter-increment</code> and <code>content</code>.</p>
                                <div class="row p-3">
                                    <div class="col-md-6">
                                        <ol class="list-group list-group-numbered">
                                            <li class="list-group-item">A list item</li>
                                            <li class="list-group-item">A list item</li>
                                            <li class="list-group-item">A list item</li>
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <ol class="list-group list-group-numbered">
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold">Subheading</div>
                                                    Content for list item
                                                </div>
                                                <span class="badge bg-primary rounded-pill">14</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold">Subheading</div>
                                                    Content for list item
                                                </div>
                                                <span class="badge bg-primary rounded-pill">14</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                                <div class="ms-2 me-auto">
                                                    <div class="fw-bold">Subheading</div>
                                                    Content for list item
                                                </div>
                                                <span class="badge bg-primary rounded-pill">14</span>
                                            </li>
                                        </ol>
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
                                                &lt;ol class=&quot;list-group list-group-numbered&quot;&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A list item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A list item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A list item&lt;/li&gt;
                                                &lt;/ol&gt;

                                                &lt;ol class=&quot;list-group list-group-numbered&quot;&gt;
                                                    &lt;li class=&quot;list-group-item d-flex justify-content-between align-items-start&quot;&gt;
                                                        &lt;div class=&quot;ms-2 me-auto&quot;&gt;
                                                            &lt;div class=&quot;fw-bold&quot;&gt;Subheading&lt;/div&gt;
                                                            Content for list item
                                                        &lt;/div&gt;
                                                        &lt;span class=&quot;badge bg-primary rounded-pill&quot;&gt;14&lt;/span&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item d-flex justify-content-between align-items-start&quot;&gt;
                                                        &lt;div class=&quot;ms-2 me-auto&quot;&gt;
                                                            &lt;div class=&quot;fw-bold&quot;&gt;Subheading&lt;/div&gt;
                                                            Content for list item
                                                        &lt;/div&gt;
                                                        &lt;span class=&quot;badge bg-primary rounded-pill&quot;&gt;14&lt;/span&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item d-flex justify-content-between align-items-start&quot;&gt;
                                                        &lt;div class=&quot;ms-2 me-auto&quot;&gt;
                                                            &lt;div class=&quot;fw-bold&quot;&gt;Subheading&lt;/div&gt;
                                                            Content for list item
                                                        &lt;/div&gt;
                                                        &lt;span class=&quot;badge bg-primary rounded-pill&quot;&gt;14&lt;/span&gt;
                                                    &lt;/li&gt;
                                                &lt;/ol&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="horizontal">
                                    Horizontal<a class="anchor-link" href="#horizontal">#</a>
                                </h5>
                                <p class="text-muted">Add <code>.list-group-horizontal</code> to change the layout of list group items from vertical to horizontal across all breakpoints. Alternatively, choose a responsive variant <code>.list-group-horizontal-{sm|md|lg|xl|xxl}</code> to make a list group horizontal starting at that breakpoint’s <code>min-width</code>. Currently <strong>horizontal list groups cannot be combined with flush list groups.</strong></p>

                                <p class="text-muted"><strong>ProTip:</strong> Want equal-width list group items when horizontal? Add <code>.flex-fill</code> to each list group item.</p>
                                <div class="w-50 mb-3 d-flex flex-column gap-2">
                                    <ul class="list-group list-group-horizontal">
                                        <li class="list-group-item">An item</li>
                                        <li class="list-group-item">A second item</li>
                                        <li class="list-group-item">A third item</li>
                                    </ul>
                                    <ul class="list-group list-group-horizontal-sm">
                                        <li class="list-group-item">An item</li>
                                        <li class="list-group-item">A second item</li>
                                        <li class="list-group-item">A third item</li>
                                    </ul>
                                    <ul class="list-group list-group-horizontal-md">
                                        <li class="list-group-item">An item</li>
                                        <li class="list-group-item">A second item</li>
                                        <li class="list-group-item">A third item</li>
                                    </ul>
                                    <ul class="list-group list-group-horizontal-lg">
                                        <li class="list-group-item">An item</li>
                                        <li class="list-group-item">A second item</li>
                                        <li class="list-group-item">A third item</li>
                                    </ul>
                                    <ul class="list-group list-group-horizontal-xl">
                                        <li class="list-group-item">An item</li>
                                        <li class="list-group-item">A second item</li>
                                        <li class="list-group-item">A third item</li>
                                    </ul>
                                    <ul class="list-group list-group-horizontal-xxl">
                                        <li class="list-group-item">An item</li>
                                        <li class="list-group-item">A second item</li>
                                        <li class="list-group-item">A third item</li>
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
                                                &lt;ul class=&quot;list-group list-group-horizontal&quot;&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;
                                                &lt;/ul&gt;

                                                &lt;ul class=&quot;list-group list-group-horizontal-sm&quot;&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;
                                                &lt;/ul&gt;

                                                &lt;ul class=&quot;list-group list-group-horizontal-md&quot;&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;
                                                &lt;/ul&gt;

                                                &lt;ul class=&quot;list-group list-group-horizontal-lg&quot;&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;
                                                &lt;/ul&gt;
                                                
                                                &lt;ul class=&quot;list-group list-group-horizontal-xl&quot;&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;
                                                &lt;/ul&gt;

                                                &lt;ul class=&quot;list-group list-group-horizontal-xxl&quot;&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;An item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A second item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A third item&lt;/li&gt;
                                                &lt;/ul&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="contextual-classes">
                                    Contextual classes<a class="anchor-link" href="#contextual-classes">#</a>
                                </h5>
                                <p class="text-muted">Use contextual classes to style list items with a stateful background and color.</p>

                                <div class="w-50 mb-3">
                                    <ul class="list-group">
                                        <li class="list-group-item">A simple default list group item</li>
                                        <li class="list-group-item list-group-item-primary">A simple primary list group item</li>
                                        <li class="list-group-item list-group-item-secondary">A simple secondary list group item</li>
                                        <li class="list-group-item list-group-item-success">A simple success list group item</li>
                                        <li class="list-group-item list-group-item-danger">A simple danger list group item</li>
                                        <li class="list-group-item list-group-item-warning">A simple warning list group item</li>
                                        <li class="list-group-item list-group-item-info">A simple info list group item</li>
                                        <li class="list-group-item list-group-item-light">A simple light list group item</li>
                                        <li class="list-group-item list-group-item-dark">A simple dark list group item</li>
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
                                                &lt;ul class=&quot;list-group&quot;&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;A simple default list group item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item list-group-item-primary&quot;&gt;A simple primary list group item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item list-group-item-secondary&quot;&gt;A simple secondary list group item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item list-group-item-success&quot;&gt;A simple success list group item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item list-group-item-danger&quot;&gt;A simple danger list group item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item list-group-item-warning&quot;&gt;A simple warning list group item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item list-group-item-info&quot;&gt;A simple info list group item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item list-group-item-light&quot;&gt;A simple light list group item&lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item list-group-item-dark&quot;&gt;A simple dark list group item&lt;/li&gt;
                                                &lt;/ul&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="custom-content">
                                    Custom content<a class="anchor-link" href="#custom-content">#</a>
                                </h5>
                                <p class="text-muted">Add nearly any HTML within, even for linked list groups like the one below, with the help of <a href="/docs/5.2/utilities/flex/">flexbox utilities</a>.</p>

                                <div class="w-50 mb-3">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1 text-reset">List group item heading</h5>
                                                <small>3 days ago</small>
                                            </div>
                                            <p class="mb-1">Some placeholder content in a paragraph.</p>
                                            <small>And some small print.</small>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">List group item heading</h5>
                                                <small class="text-muted">3 days ago</small>
                                            </div>
                                            <p class="mb-1">Some placeholder content in a paragraph.</p>
                                            <small class="text-muted">And some muted small print.</small>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">List group item heading</h5>
                                                <small class="text-muted">3 days ago</small>
                                            </div>
                                            <p class="mb-1">Some placeholder content in a paragraph.</p>
                                            <small class="text-muted">And some muted small print.</small>
                                        </a>
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
                                                &lt;div class=&quot;list-group&quot;&gt;
                                                    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action active&quot; aria-current=&quot;true&quot;&gt;
                                                        &lt;div class=&quot;d-flex w-100 justify-content-between&quot;&gt;
                                                            &lt;h5 class=&quot;mb-1&quot;&gt;List group item heading&lt;/h5&gt;
                                                            &lt;small&gt;3 days ago&lt;/small&gt;
                                                        &lt;/div&gt;
                                                        &lt;p class=&quot;mb-1&quot;&gt;Some placeholder content in a paragraph.&lt;/p&gt;
                                                        &lt;small&gt;And some small print.&lt;/small&gt;
                                                    &lt;/a&gt;
                                                    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;
                                                        &lt;div class=&quot;d-flex w-100 justify-content-between&quot;&gt;
                                                            &lt;h5 class=&quot;mb-1&quot;&gt;List group item heading&lt;/h5&gt;
                                                            &lt;small class=&quot;text-muted&quot;&gt;3 days ago&lt;/small&gt;
                                                        &lt;/div&gt;
                                                        &lt;p class=&quot;mb-1&quot;&gt;Some placeholder content in a paragraph.&lt;/p&gt;
                                                        &lt;small class=&quot;text-muted&quot;&gt;And some muted small print.&lt;/small&gt;
                                                    &lt;/a&gt;
                                                    &lt;a href=&quot;#&quot; class=&quot;list-group-item list-group-item-action&quot;&gt;
                                                        &lt;div class=&quot;d-flex w-100 justify-content-between&quot;&gt;
                                                            &lt;h5 class=&quot;mb-1&quot;&gt;List group item heading&lt;/h5&gt;
                                                            &lt;small class=&quot;text-muted&quot;&gt;3 days ago&lt;/small&gt;
                                                        &lt;/div&gt;
                                                        &lt;p class=&quot;mb-1&quot;&gt;Some placeholder content in a paragraph.&lt;/p&gt;
                                                        &lt;small class=&quot;text-muted&quot;&gt;And some muted small print.&lt;/small&gt;
                                                    &lt;/a&gt;
                                                &lt;/div&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="checkboxes-radios">
                                    Checkboxes and radios<a class="anchor-link" href="#checkboxes-radios">#</a>
                                </h5>
                                <p class="text-muted">Place Bootstrap’s checkboxes and radios within list group items and customize as needed. You can use them without <code>&lt;label&gt;</code>s, but please remember to include an <code>aria-label</code> attribute and value for accessibility.</p>

                                <div class="w-50 mb-3">
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox" value="" id="firstCheckbox">
                                            <label class="form-check-label" for="firstCheckbox">First checkbox</label>
                                        </li>
                                        <li class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox" value="" id="secondCheckbox">
                                            <label class="form-check-label" for="secondCheckbox">Second checkbox</label>
                                        </li>
                                        <li class="list-group-item">
                                            <input class="form-check-input me-1" type="checkbox" value="" id="thirdCheckbox">
                                            <label class="form-check-label" for="thirdCheckbox">Third checkbox</label>
                                        </li>
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
                                                &lt;ul class=&quot;list-group&quot;&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;
                                                        &lt;input class=&quot;form-check-input me-1&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;firstCheckbox&quot;&gt;
                                                        &lt;label class=&quot;form-check-label&quot; for=&quot;firstCheckbox&quot;&gt;First checkbox&lt;/label&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;
                                                        &lt;input class=&quot;form-check-input me-1&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;secondCheckbox&quot;&gt;
                                                        &lt;label class=&quot;form-check-label&quot; for=&quot;secondCheckbox&quot;&gt;Second checkbox&lt;/label&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;list-group-item&quot;&gt;
                                                        &lt;input class=&quot;form-check-input me-1&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;thirdCheckbox&quot;&gt;
                                                        &lt;label class=&quot;form-check-label&quot; for=&quot;thirdCheckbox&quot;&gt;Third checkbox&lt;/label&gt;
                                                    &lt;/li&gt;
                                                &lt;/ul&gt;
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
                                    <a href="#basic" class="nav-link">Basic</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#active" class="nav-link">Active items</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#disabled" class="nav-link">Disabled items</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#links-buttons" class="nav-link">Links and buttons</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#flush" class="nav-link">Flush</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#numbered" class="nav-link">Numbered</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#horizontal" class="nav-link">Horizontal</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#contextual-classes" class="nav-link">Contextual classes</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#custom-content" class="nav-link">Custom content</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#checkboxes-radios" class="nav-link">Checkboxes and Radios</a>
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