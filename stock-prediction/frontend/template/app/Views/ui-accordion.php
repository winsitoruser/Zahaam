<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Accordion")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("sub-title" => "Accordion")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">

            <!-- Start Container -->
            <div class="container">

                <div class="row">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="default">
                                    Basic Example <a class="anchor-link" href="#default">#</a>
                                </h5>
                                <p class="text-muted">
                                    Using the card component, you can extend the default collapse behavior to create an accordion.
                                    To properly achieve the accordion style, be sure to use <code>.accordion</code> as a wrapper.
                                </p>

                                <div class="mb-3">
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    Accordion Item #1
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <strong>This is the first item's accordion body.</strong> It is shown by default, until the
                                                    collapse
                                                    plugin adds the appropriate classes that we use to style each element. These classes control
                                                    the overall
                                                    appearance, as well as the showing and hiding via CSS transitions. You can modify any of
                                                    this with
                                                    custom CSS or overriding our default variables. It's also worth noting that just about any
                                                    HTML can go
                                                    within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingTwo">
                                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                    Accordion Item #2
                                                </button>
                                            </h2>
                                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <strong>This is the second item's accordion body.</strong> It is hidden by default, until
                                                    the collapse
                                                    plugin adds the appropriate classes that we use to style each element. These classes control
                                                    the overall
                                                    appearance, as well as the showing and hiding via CSS transitions. You can modify any of
                                                    this with
                                                    custom CSS or overriding our default variables. It's also worth noting that just about any
                                                    HTML can go
                                                    within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingThree">
                                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                    Accordion Item #3
                                                </button>
                                            </h2>
                                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <strong>This is the third item's accordion body.</strong> It is hidden by default, until the
                                                    collapse
                                                    plugin adds the appropriate classes that we use to style each element. These classes control
                                                    the overall
                                                    appearance, as well as the showing and hiding via CSS transitions. You can modify any of
                                                    this with
                                                    custom CSS or overriding our default variables. It's also worth noting that just about any
                                                    HTML can go
                                                    within the <code>.accordion-body</code>, though the transition does limit overflow.
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
                                            <code>
                                                &lt;div class=&quot;accordion&quot; id=&quot;accordionExample&quot;&gt;
                                                    &lt;div class=&quot;accordion-item&quot;&gt;
                                                        &lt;h2 class=&quot;accordion-header&quot; id=&quot;headingOne&quot;&gt;
                                                            &lt;button class=&quot;accordion-button fw-medium&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseOne&quot; aria-expanded=&quot;true&quot; aria-controls=&quot;collapseOne&quot;&gt;
                                                                Accordion Item #1
                                                            &lt;/button&gt;
                                                        &lt;/h2&gt;
                                                        &lt;div id=&quot;collapseOne&quot; class=&quot;accordion-collapse collapse show&quot; aria-labelledby=&quot;headingOne&quot; data-bs-parent=&quot;#accordionExample&quot;&gt;
                                                            &lt;div class=&quot;accordion-body&quot;&gt;
                                                                &lt;strong&gt;This is the first item's accordion body.&lt;/strong&gt; It is shown by default, until the
                                                                collapse
                                                                plugin adds the appropriate classes that we use to style each element. These classes control
                                                                the overall
                                                                appearance, as well as the showing and hiding via CSS transitions. You can modify any of
                                                                this with
                                                                custom CSS or overriding our default variables. It's also worth noting that just about any
                                                                HTML can go
                                                                within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;accordion-item&quot;&gt;
                                                        &lt;h2 class=&quot;accordion-header&quot; id=&quot;headingTwo&quot;&gt;
                                                            &lt;button class=&quot;accordion-button fw-medium collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseTwo&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;collapseTwo&quot;&gt;
                                                                Accordion Item #2
                                                            &lt;/button&gt;
                                                        &lt;/h2&gt;
                                                        &lt;div id=&quot;collapseTwo&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;headingTwo&quot; data-bs-parent=&quot;#accordionExample&quot;&gt;
                                                            &lt;div class=&quot;accordion-body&quot;&gt;
                                                                &lt;strong&gt;This is the second item's accordion body.&lt;/strong&gt; It is hidden by default, until
                                                                the collapse
                                                                plugin adds the appropriate classes that we use to style each element. These classes control
                                                                the overall
                                                                appearance, as well as the showing and hiding via CSS transitions. You can modify any of
                                                                this with
                                                                custom CSS or overriding our default variables. It's also worth noting that just about any
                                                                HTML can go
                                                                within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;accordion-item&quot;&gt;
                                                        &lt;h2 class=&quot;accordion-header&quot; id=&quot;headingThree&quot;&gt;
                                                            &lt;button class=&quot;accordion-button fw-medium collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#collapseThree&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;collapseThree&quot;&gt;
                                                                Accordion Item #3
                                                            &lt;/button&gt;
                                                        &lt;/h2&gt;
                                                        &lt;div id=&quot;collapseThree&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;headingThree&quot; data-bs-parent=&quot;#accordionExample&quot;&gt;
                                                            &lt;div class=&quot;accordion-body&quot;&gt;
                                                                &lt;strong&gt;This is the third item's accordion body.&lt;/strong&gt; It is hidden by default, until the
                                                                collapse
                                                                plugin adds the appropriate classes that we use to style each element. These classes control
                                                                the overall
                                                                appearance, as well as the showing and hiding via CSS transitions. You can modify any of
                                                                this with
                                                                custom CSS or overriding our default variables. It's also worth noting that just about any
                                                                HTML can go
                                                                within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.
                                                            &lt;/div&gt;
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
                                <h5 class="card-title mb-1 anchor" id="flush">
                                    Flush Accordion <a class="anchor-link" href="#flush">#</a>
                                </h5>
                                <p class="text-muted">Add <code>.accordion-flush</code> to remove the default <code>background-color</code>, some borders, and some rounded corners to render accordions edge-to-edge with their parent container.</p>

                                <div class="mb-3">
                                    <div class="accordion accordion-flush" id="accordionFlushExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                                    Accordion Item #1
                                                </button>
                                            </h2>
                                            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the
                                                    <code>.accordion-flush</code> class. This is the first item's accordion body.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingTwo">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                                    Accordion Item #2
                                                </button>
                                            </h2>
                                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the
                                                    <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this
                                                    being
                                                    filled with some actual content.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingThree">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                                    Accordion Item #3
                                                </button>
                                            </h2>
                                            <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the
                                                    <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting
                                                    happening here in terms of content, but just filling up the space to make it look, at least at first
                                                    glance, a bit more representative of how this would look in a real-world application.
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
                                            <code>
                                                &lt;div class=&quot;accordion accordion-flush&quot; id=&quot;accordionFlushExample&quot;&gt;
                                                    &lt;div class=&quot;accordion-item&quot;&gt;
                                                        &lt;h2 class=&quot;accordion-header&quot; id=&quot;flush-headingOne&quot;&gt;
                                                            &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#flush-collapseOne&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;flush-collapseOne&quot;&gt;
                                                                Accordion Item #1
                                                            &lt;/button&gt;
                                                        &lt;/h2&gt;
                                                        &lt;div id=&quot;flush-collapseOne&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;flush-headingOne&quot; data-bs-parent=&quot;#accordionFlushExample&quot;&gt;
                                                            &lt;div class=&quot;accordion-body&quot;&gt;Placeholder content for this accordion, which is intended to demonstrate the
                                                                &lt;code&gt;.accordion-flush&lt;/code&gt; class. This is the first item's accordion body.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;accordion-item&quot;&gt;
                                                        &lt;h2 class=&quot;accordion-header&quot; id=&quot;flush-headingTwo&quot;&gt;
                                                            &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#flush-collapseTwo&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;flush-collapseTwo&quot;&gt;
                                                                Accordion Item #2
                                                            &lt;/button&gt;
                                                        &lt;/h2&gt;
                                                        &lt;div id=&quot;flush-collapseTwo&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;flush-headingTwo&quot; data-bs-parent=&quot;#accordionFlushExample&quot;&gt;
                                                            &lt;div class=&quot;accordion-body&quot;&gt;Placeholder content for this accordion, which is intended to demonstrate the
                                                                &lt;code&gt;.accordion-flush&lt;/code&gt; class. This is the second item's accordion body. Let's imagine this
                                                                being
                                                                filled with some actual content.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;accordion-item&quot;&gt;
                                                        &lt;h2 class=&quot;accordion-header&quot; id=&quot;flush-headingThree&quot;&gt;
                                                            &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#flush-collapseThree&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;flush-collapseThree&quot;&gt;
                                                                Accordion Item #3
                                                            &lt;/button&gt;
                                                        &lt;/h2&gt;
                                                        &lt;div id=&quot;flush-collapseThree&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;flush-headingThree&quot; data-bs-parent=&quot;#accordionFlushExample&quot;&gt;
                                                            &lt;div class=&quot;accordion-body&quot;&gt;Placeholder content for this accordion, which is intended to demonstrate the
                                                                &lt;code&gt;.accordion-flush&lt;/code&gt; class. This is the third item's accordion body. Nothing more exciting
                                                                happening here in terms of content, but just filling up the space to make it look, at least at first
                                                                glance, a bit more representative of how this would look in a real-world application.
                                                            &lt;/div&gt;
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
                                <h5 class="card-title mb-1 anchor" id="always-open">
                                    Always Open Accordion <a class="anchor-link" href="#always-open">#</a>
                                </h5>
                                <p class="text-muted">Omit the <code>data-bs-parent</code> attribute on each <code>.accordion-collapse</code> to make accordion items stay open when another item is opened.</p>

                                <div class="mb-3">
                                    <div class="accordion" id="accordionPanelsStayOpenExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                                    Accordion Item #1
                                                </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                                                <div class="accordion-body">
                                                    <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse
                                                    plugin adds the appropriate classes that we use to style each element. These classes control the overall
                                                    appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with
                                                    custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go
                                                    within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                                    Accordion Item #2
                                                </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                                                <div class="accordion-body">
                                                    <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse
                                                    plugin adds the appropriate classes that we use to style each element. These classes control the overall
                                                    appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with
                                                    custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go
                                                    within the <code>.accordion-body</code>, though the transition does limit overflow.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                                    Accordion Item #3
                                                </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                                                <div class="accordion-body">
                                                    <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse
                                                    plugin adds the appropriate classes that we use to style each element. These classes control the overall
                                                    appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with
                                                    custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go
                                                    within the <code>.accordion-body</code>, though the transition does limit overflow.
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
                                            <code>
                                                &lt;div class=&quot;accordion&quot; id=&quot;accordionPanelsStayOpenExample&quot;&gt;
                                                    &lt;div class=&quot;accordion-item&quot;&gt;
                                                        &lt;h2 class=&quot;accordion-header&quot; id=&quot;panelsStayOpen-headingOne&quot;&gt;
                                                            &lt;button class=&quot;accordion-button&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#panelsStayOpen-collapseOne&quot; aria-expanded=&quot;true&quot; aria-controls=&quot;panelsStayOpen-collapseOne&quot;&gt;
                                                                Accordion Item #1
                                                            &lt;/button&gt;
                                                        &lt;/h2&gt;
                                                        &lt;div id=&quot;panelsStayOpen-collapseOne&quot; class=&quot;accordion-collapse collapse show&quot; aria-labelledby=&quot;panelsStayOpen-headingOne&quot;&gt;
                                                            &lt;div class=&quot;accordion-body&quot;&gt;
                                                                &lt;strong&gt;This is the first item's accordion body.&lt;/strong&gt; It is shown by default, until the collapse
                                                                plugin adds the appropriate classes that we use to style each element. These classes control the overall
                                                                appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with
                                                                custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go
                                                                within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;accordion-item&quot;&gt;
                                                        &lt;h2 class=&quot;accordion-header&quot; id=&quot;panelsStayOpen-headingTwo&quot;&gt;
                                                            &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#panelsStayOpen-collapseTwo&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;panelsStayOpen-collapseTwo&quot;&gt;
                                                                Accordion Item #2
                                                            &lt;/button&gt;
                                                        &lt;/h2&gt;
                                                        &lt;div id=&quot;panelsStayOpen-collapseTwo&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;panelsStayOpen-headingTwo&quot;&gt;
                                                            &lt;div class=&quot;accordion-body&quot;&gt;
                                                                &lt;strong&gt;This is the second item's accordion body.&lt;/strong&gt; It is hidden by default, until the collapse
                                                                plugin adds the appropriate classes that we use to style each element. These classes control the overall
                                                                appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with
                                                                custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go
                                                                within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;accordion-item&quot;&gt;
                                                        &lt;h2 class=&quot;accordion-header&quot; id=&quot;panelsStayOpen-headingThree&quot;&gt;
                                                            &lt;button class=&quot;accordion-button collapsed&quot; type=&quot;button&quot; data-bs-toggle=&quot;collapse&quot; data-bs-target=&quot;#panelsStayOpen-collapseThree&quot; aria-expanded=&quot;false&quot; aria-controls=&quot;panelsStayOpen-collapseThree&quot;&gt;
                                                                Accordion Item #3
                                                            &lt;/button&gt;
                                                        &lt;/h2&gt;
                                                        &lt;div id=&quot;panelsStayOpen-collapseThree&quot; class=&quot;accordion-collapse collapse&quot; aria-labelledby=&quot;panelsStayOpen-headingThree&quot;&gt;
                                                            &lt;div class=&quot;accordion-body&quot;&gt;
                                                                &lt;strong&gt;This is the third item's accordion body.&lt;/strong&gt; It is hidden by default, until the collapse
                                                                plugin adds the appropriate classes that we use to style each element. These classes control the overall
                                                                appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with
                                                                custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go
                                                                within the &lt;code&gt;.accordion-body&lt;/code&gt;, though the transition does limit overflow.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
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
                                    <a href="#default" class="nav-link">Default Example</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#flush" class="nav-link">Flush Accordian</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#always-open" class="nav-link">Always Open</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> <!-- end row -->
            </div>
            <!-- End Container -->

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