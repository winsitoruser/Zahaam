<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Scrollbar")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Scrollbar")) ?>
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
                                    Default Scroll Example <a class="anchor-link" href="#defalut">#</a>
                                </h5>
                                <p class="text-muted">Just use data attribute <code>data-simplebar</code>
                                    and add <code>max-height: **px</code> oh fix height</p>

                                <div class="border rounded py-3 mb-3">
                                    <div class="px-3" data-simplebar style="max-height: 250px;">
                                        SimpleBar does only one thing: replace the browser's default scrollbar
                                        with a custom CSS-styled one without losing performances.
                                        Unlike some popular plugins, SimpleBar doesn't mimic scroll with
                                        Javascript, causing janks and strange scrolling behaviours...
                                        You keep the awesomeness of native scrolling...with a custom scrollbar!
                                        <p>SimpleBar <strong>does NOT implement a custom scroll
                                                behaviour</strong>. It keeps the <strong>native</strong>
                                            <code>overflow: auto</code> scroll and <strong>only</strong> replace
                                            the scrollbar visual appearance.
                                        </p>
                                        <h5>Design it as you want</h5>
                                        <p>SimpleBar uses pure CSS to style the scrollbar. You can easily
                                            customize it as you want! Or even have multiple style on the same
                                            page...or just keep the default style ("Mac OS" scrollbar style).
                                        </p>
                                        <h5>Lightweight and performant</h5>
                                        <p>Only 6kb minified. SimpleBar doesn't use Javascript to handle
                                            scrolling. You keep the performances/behaviours of the native
                                            scroll.</p>
                                        <h5>Supported everywhere</h5>
                                        <p>SimpleBar has been tested on the following browsers: Chrome, Firefox,
                                            Safari, Edge, IE11.</p>
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
                                                &lt;div class=&quot;px-3&quot; data-simplebar style=&quot;max-height: 250px;&quot;&gt;
                                                    SimpleBar does only one thing: replace the browser's default scrollbar
                                                    with a custom CSS-styled one without losing performances.
                                                    Unlike some popular plugins, SimpleBar doesn't mimic scroll with
                                                    Javascript, causing janks and strange scrolling behaviours...
                                                    You keep the awesomeness of native scrolling...with a custom scrollbar!
                                                    &lt;p&gt;SimpleBar &lt;strong&gt;does NOT implement a custom scroll
                                                            behaviour&lt;/strong&gt;. It keeps the &lt;strong&gt;native&lt;/strong&gt;
                                                        &lt;code&gt;overflow: auto&lt;/code&gt; scroll and &lt;strong&gt;only&lt;/strong&gt; replace
                                                        the scrollbar visual appearance.
                                                    &lt;/p&gt;
                                                    &lt;h5&gt;Design it as you want&lt;/h5&gt;
                                                    &lt;p&gt;SimpleBar uses pure CSS to style the scrollbar. You can easily
                                                        customize it as you want! Or even have multiple style on the same
                                                        page...or just keep the default style (&quot;Mac OS&quot; scrollbar style).
                                                    &lt;/p&gt;
                                                    &lt;h5&gt;Lightweight and performant&lt;/h5&gt;
                                                    &lt;p&gt;Only 6kb minified. SimpleBar doesn't use Javascript to handle
                                                        scrolling. You keep the performances/behaviours of the native
                                                        scroll.&lt;/p&gt;
                                                    &lt;h5&gt;Supported everywhere&lt;/h5&gt;
                                                    &lt;p&gt;SimpleBar has been tested on the following browsers: Chrome, Firefox,
                                                        Safari, Edge, IE11.&lt;/p&gt;
                                                &lt;/div&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="rtl">
                                    RTL Position <a class="anchor-link" href="#rtl">#</a>
                                </h5>
                                <p class="text-muted">Just use data attribute
                                    <code>data-simplebar data-simplebar-direction='rtl'</code>
                                    and add <code>max-height: **px</code> oh fix height
                                </p>
                                <div class="border rounded py-3 mb-3">
                                    <div class="px-3" data-simplebar data-simplebar-direction='rtl' style="max-height: 250px;">
                                        SimpleBar does only one thing: replace the browser's default scrollbar
                                        with a custom CSS-styled one without losing performances.
                                        Unlike some popular plugins, SimpleBar doesn't mimic scroll with
                                        Javascript, causing janks and strange scrolling behaviours...
                                        You keep the awesomeness of native scrolling...with a custom scrollbar!
                                        <p>SimpleBar <strong>does NOT implement a custom scroll
                                                behaviour</strong>. It keeps the <strong>native</strong>
                                            <code>overflow: auto</code> scroll and <strong>only</strong> replace
                                            the scrollbar visual appearance.
                                        </p>
                                        <h5>Design it as you want</h5>
                                        <p>SimpleBar uses pure CSS to style the scrollbar. You can easily
                                            customize it as you want! Or even have multiple style on the same
                                            page...or just keep the default style ("Mac OS" scrollbar style).
                                        </p>
                                        <h5>Lightweight and performant</h5>
                                        <p>Only 6kb minified. SimpleBar doesn't use Javascript to handle
                                            scrolling. You keep the performances/behaviours of the native
                                            scroll.</p>
                                        <h5>Supported everywhere</h5>
                                        <p>SimpleBar has been tested on the following browsers: Chrome, Firefox,
                                            Safari, Edge, IE11.</p>
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
                                                &lt;div class=&quot;px-3&quot; data-simplebar data-simplebar-direction='rtl'  style=&quot;max-height: 250px;&quot;&gt;
                                                    SimpleBar does only one thing: replace the browser's default scrollbar
                                                    with a custom CSS-styled one without losing performances.
                                                    Unlike some popular plugins, SimpleBar doesn't mimic scroll with
                                                    Javascript, causing janks and strange scrolling behaviours...
                                                    You keep the awesomeness of native scrolling...with a custom scrollbar!
                                                    &lt;p&gt;SimpleBar &lt;strong&gt;does NOT implement a custom scroll
                                                            behaviour&lt;/strong&gt;. It keeps the &lt;strong&gt;native&lt;/strong&gt;
                                                        &lt;code&gt;overflow: auto&lt;/code&gt; scroll and &lt;strong&gt;only&lt;/strong&gt; replace
                                                        the scrollbar visual appearance.
                                                    &lt;/p&gt;
                                                    &lt;h5&gt;Design it as you want&lt;/h5&gt;
                                                    &lt;p&gt;SimpleBar uses pure CSS to style the scrollbar. You can easily
                                                        customize it as you want! Or even have multiple style on the same
                                                        page...or just keep the default style (&quot;Mac OS&quot; scrollbar style).
                                                    &lt;/p&gt;
                                                    &lt;h5&gt;Lightweight and performant&lt;/h5&gt;
                                                    &lt;p&gt;Only 6kb minified. SimpleBar doesn't use Javascript to handle
                                                        scrolling. You keep the performances/behaviours of the native
                                                        scroll.&lt;/p&gt;
                                                    &lt;h5&gt;Supported everywhere&lt;/h5&gt;
                                                    &lt;p&gt;SimpleBar has been tested on the following browsers: Chrome, Firefox,
                                                        Safari, Edge, IE11.&lt;/p&gt;
                                                &lt;/div&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="size">
                                    Scroll Size <a class="anchor-link" href="#size">#</a>
                                </h5>
                                <p class="text-muted">Just use data attribute <code>data-simplebar</code>, <code>data-simplebar-lg</code> and add <code>max-height: **px</code> oh fix height</p>
                                <div class="border rounded py-3 mb-3">
                                    <div class="px-3" data-simplebar data-simplebar-lg style="max-height: 250px;">
                                        SimpleBar does only one thing: replace the browser's default scrollbar
                                        with a custom CSS-styled one without losing performances.
                                        Unlike some popular plugins, SimpleBar doesn't mimic scroll with
                                        Javascript, causing janks and strange scrolling behaviours...
                                        You keep the awesomeness of native scrolling...with a custom scrollbar!
                                        <p>SimpleBar <strong>does NOT implement a custom scroll
                                                behaviour</strong>. It keeps the <strong>native</strong>
                                            <code>overflow: auto</code> scroll and <strong>only</strong> replace
                                            the scrollbar visual appearance.
                                        </p>
                                        <h5>Design it as you want</h5>
                                        <p>SimpleBar uses pure CSS to style the scrollbar. You can easily
                                            customize it as you want! Or even have multiple style on the same
                                            page...or just keep the default style ("Mac OS" scrollbar style).
                                        </p>
                                        <h5>Lightweight and performant</h5>
                                        <p>Only 6kb minified. SimpleBar doesn't use Javascript to handle
                                            scrolling. You keep the performances/behaviours of the native
                                            scroll.</p>
                                        <h5>Supported everywhere</h5>
                                        <p>SimpleBar has been tested on the following browsers: Chrome, Firefox,
                                            Safari, Edge, IE11.</p>
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
                                                &lt;div class=&quot;px-3&quot; data-simplebar data-simplebar-lg style=&quot;max-height: 250px;&quot;&gt;
                                                    SimpleBar does only one thing: replace the browser's default scrollbar
                                                    with a custom CSS-styled one without losing performances.
                                                    Unlike some popular plugins, SimpleBar doesn't mimic scroll with
                                                    Javascript, causing janks and strange scrolling behaviours...
                                                    You keep the awesomeness of native scrolling...with a custom scrollbar!
                                                    &lt;p&gt;SimpleBar &lt;strong&gt;does NOT implement a custom scroll
                                                            behaviour&lt;/strong&gt;. It keeps the &lt;strong&gt;native&lt;/strong&gt;
                                                        &lt;code&gt;overflow: auto&lt;/code&gt; scroll and &lt;strong&gt;only&lt;/strong&gt; replace
                                                        the scrollbar visual appearance.
                                                    &lt;/p&gt;
                                                    &lt;h5&gt;Design it as you want&lt;/h5&gt;
                                                    &lt;p&gt;SimpleBar uses pure CSS to style the scrollbar. You can easily
                                                        customize it as you want! Or even have multiple style on the same
                                                        page...or just keep the default style (&quot;Mac OS&quot; scrollbar style).
                                                    &lt;/p&gt;
                                                    &lt;h5&gt;Lightweight and performant&lt;/h5&gt;
                                                    &lt;p&gt;Only 6kb minified. SimpleBar doesn't use Javascript to handle
                                                        scrolling. You keep the performances/behaviours of the native
                                                        scroll.&lt;/p&gt;
                                                    &lt;h5&gt;Supported everywhere&lt;/h5&gt;
                                                    &lt;p&gt;SimpleBar has been tested on the following browsers: Chrome, Firefox,
                                                        Safari, Edge, IE11.&lt;/p&gt;
                                                &lt;/div&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="color">
                                    Scroll Color <a class="anchor-link" href="#color">#</a>
                                </h5>
                                <p class="text-muted">Just use data attribute
                                    <code>data-simplebar data-simplebar-*</code>
                                    and add <code>max-height: **px</code> oh fix height
                                </p>

                                <div class="border rounded py-3 mb-3">
                                    <div class="px-3" data-simplebar data-simplebar-primary style="max-height: 250px;">
                                        SimpleBar does only one thing: replace the browser's default scrollbar
                                        with a custom CSS-styled one without losing performances.
                                        Unlike some popular plugins, SimpleBar doesn't mimic scroll with
                                        Javascript, causing janks and strange scrolling behaviours...
                                        You keep the awesomeness of native scrolling...with a custom scrollbar!
                                        <p>SimpleBar <strong>does NOT implement a custom scroll
                                                behaviour</strong>. It keeps the <strong>native</strong>
                                            <code>overflow: auto</code> scroll and <strong>only</strong> replace
                                            the scrollbar visual appearance.
                                        </p>
                                        <h5>Design it as you want</h5>
                                        <p>SimpleBar uses pure CSS to style the scrollbar. You can easily
                                            customize it as you want! Or even have multiple style on the same
                                            page...or just keep the default style ("Mac OS" scrollbar style).
                                        </p>
                                        <h5>Lightweight and performant</h5>
                                        <p>Only 6kb minified. SimpleBar doesn't use Javascript to handle
                                            scrolling. You keep the performances/behaviours of the native
                                            scroll.</p>
                                        <h5>Supported everywhere</h5>
                                        <p>SimpleBar has been tested on the following browsers: Chrome, Firefox,
                                            Safari, Edge, IE11.</p>
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
                                                &lt;div class=&quot;px-3&quot; data-simplebar data-simplebar-primary style=&quot;max-height: 250px;&quot;&gt;
                                                    SimpleBar does only one thing: replace the browser's default scrollbar
                                                    with a custom CSS-styled one without losing performances.
                                                    Unlike some popular plugins, SimpleBar doesn't mimic scroll with
                                                    Javascript, causing janks and strange scrolling behaviours...
                                                    You keep the awesomeness of native scrolling...with a custom scrollbar!
                                                    &lt;p&gt;SimpleBar &lt;strong&gt;does NOT implement a custom scroll
                                                            behaviour&lt;/strong&gt;. It keeps the &lt;strong&gt;native&lt;/strong&gt;
                                                        &lt;code&gt;overflow: auto&lt;/code&gt; scroll and &lt;strong&gt;only&lt;/strong&gt; replace
                                                        the scrollbar visual appearance.
                                                    &lt;/p&gt;
                                                    &lt;h5&gt;Design it as you want&lt;/h5&gt;
                                                    &lt;p&gt;SimpleBar uses pure CSS to style the scrollbar. You can easily
                                                        customize it as you want! Or even have multiple style on the same
                                                        page...or just keep the default style (&quot;Mac OS&quot; scrollbar style).
                                                    &lt;/p&gt;
                                                    &lt;h5&gt;Lightweight and performant&lt;/h5&gt;
                                                    &lt;p&gt;Only 6kb minified. SimpleBar doesn't use Javascript to handle
                                                        scrolling. You keep the performances/behaviours of the native
                                                        scroll.&lt;/p&gt;
                                                    &lt;h5&gt;Supported everywhere&lt;/h5&gt;
                                                    &lt;p&gt;SimpleBar has been tested on the following browsers: Chrome, Firefox,
                                                        Safari, Edge, IE11.&lt;/p&gt;
                                                &lt;/div&gt;
                                        
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
                                    <a href="#default " class="nav-link">Default Scroll Example </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#rtl " class="nav-link">RTL Position </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#size " class="nav-link">Scroll Size </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#color " class="nav-link">Scroll Color </a>
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