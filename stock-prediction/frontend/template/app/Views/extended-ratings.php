<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Ratings")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Ratings")) ?>
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
                                    Overview <a class="btn btn-sm btn-outline-success rounded-2 float-end" href="https://fredolss.github.io/rater-js/" target="_blank"> Official Website </a>
                                </h5>
                                <p class="text-muted mb-3">Rater js is the best star rater for the browser. No dependencies. Unlimited number of stars</p>

                                <h5 class="mt-2">Usage</h5>
                                <p class="mb-0">Rater js's CSS and Javascript files are bundled in the <code>vender.min.css</code> and <code>vendor.js</code> and globally included in all pages.</p>
                            </div><!-- end card-body -->
                        </div><!-- end card -->

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3 anchor" id="basic">
                                    Basic Rater Example <a class="anchor-link" href="#basic">#</a>
                                </h5>

                                <div class="mb-3">
                                    <div id="basic-rater" dir="ltr"></div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#rating-basic-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#rating-basic-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="rating-basic-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div id=&quot;basic-rater&quot; dir=&quot;ltr&quot;&gt;&lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="rating-basic-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // basic-rater
                                                        if (document.querySelector('#basic-rater'))
                                                            var basicRating = raterJs({
                                                                starSize: 22,
                                                                rating: 3,
                                                                element: document.querySelector("#basic-rater"),
                                                                rateCallback: function rateCallback(rating, done) {
                                                                    this.setRating(rating);
                                                                    done();
                                                                }
                                                            });
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3 anchor" id="step">
                                    Rater with Step Example <a class="anchor-link" href="#step">#</a>
                                </h5>

                                <div class="mb-3">
                                    <div id="rater-step" dir="ltr"></div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#step-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#step-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="step-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div id=&quot;rater-step&quot; dir=&quot;ltr&quot;&gt;&lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="step-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // rater-step
                                                        if (document.querySelector('#rater-step'))
                                                            var starRatingStep = raterJs({
                                                                starSize: 22,
                                                                rating: 1.5,
                                                                element: document.querySelector("#rater-step"),
                                                                rateCallback: function rateCallback(rating, done) {
                                                                    this.setRating(rating);
                                                                    done();
                                                                }
                                                            });
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3 anchor" id="custom-message">
                                    Custom Messages Example <a class="anchor-link" href="#custom-message">#</a>
                                </h5>

                                <div class="mb-3">
                                    <div id="rater-message" dir="ltr"></div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#custom-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#custom-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="custom-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div id=&quot;rater-message&quot; dir=&quot;ltr&quot;&gt;&lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="custom-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // rater-message
                                                        var messageDataService = {
                                                            rate: function (rating) {
                                                                return {
                                                                    then: function (callback) {
                                                                        setTimeout(function () {
                                                                            callback((Math.random() * 5));
                                                                        }, 1000);
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        
                                                        if (document.querySelector('#rater-message'))
                                                            var starRatingmessage = raterJs({
                                                                isBusyText: "Rating in progress. Please wait...",
                                                                starSize: 22,
                                                                element: document.querySelector("#rater-message"),
                                                                rateCallback: function rateCallback(rating, done) {
                                                                    starRatingmessage.setRating(rating);
                                                                    messageDataService.rate().then(function (avgRating) {
                                                                        starRatingmessage.setRating(avgRating);
                                                                        done();
                                                                    });
                                                                }
                                                            });
                                                        
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3 anchor" id="readOnly">
                                    ReadOnly Example <a class="anchor-link" href="#readOnly">#</a>
                                </h5>

                                <div class="mb-3">
                                    <div id="rater-unlimitedstar" dir="ltr"></div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#readOnly-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#readOnly-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="readOnly-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div id=&quot;rater-unlimitedstar&quot; dir=&quot;ltr&quot;&gt;&lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="readOnly-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // rater-unlimitedstar
                                                        if (document.querySelector("#rater-unlimitedstar"))
                                                          var starRatingunlimited = raterJs({
                                                            starSize: 22,
                                                            max: 5,
                                                            readOnly: true,
                                                            rating: 3.5,
                                                            element: document.querySelector("#rater-unlimitedstar"),
                                                          });
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3 anchor" id="onhover">
                                    On Hover Event Example <a class="anchor-link" href="#onhover">#</a>
                                </h5>

                                <div class="mb-3">
                                    <div dir="ltr">
                                        <div id="rater-onhover" class="align-middle"></div>
                                        <span class="ratingnum badge bg-info align-middle ms-2"></span>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#onhover-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#onhover-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="onhover-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div dir=&quot;ltr&quot;&gt;
                                                            &lt;div id=&quot;rater-onhover&quot; class=&quot;align-middle&quot;&gt;&lt;/div&gt;
                                                            &lt;span class=&quot;ratingnum badge bg-info align-middle ms-2&quot;&gt;&lt;/span&gt;
                                                        &lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="onhover-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // rater-onhover
                                                        if (document.querySelector('#rater-onhover'))
                                                            var starRatinghover = raterJs({
                                                                starSize: 22,
                                                                rating: 1,
                                                                element: document.querySelector("#rater-onhover"),
                                                                rateCallback: function rateCallback(rating, done) {
                                                                    this.setRating(rating);
                                                                    done();
                                                                },
                                                                onHover: function (currentIndex, currentRating) {
                                                                    document.querySelector('.ratingnum').textContent = currentIndex;
                                                                },
                                                                onLeave: function (currentIndex, currentRating) {
                                                                    document.querySelector('.ratingnum').textContent = currentRating;
                                                                }
                                                            });
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3 anchor" id="reset">
                                    Clear/Reset Rater Example <a class="anchor-link link-offset-2" href="#reset">#</a>
                                </h5>

                                <div class="mb-3">
                                    <div dir="ltr">
                                        <div id="raterreset" class="align-middle"></div>
                                        <span class="clear-rating"></span>
                                        <button id="raterreset-button" class="btn btn-light btn-sm ms-2">Reset</button>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#reset-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#reset-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="reset-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div dir=&quot;ltr&quot;&gt;
                                                            &lt;div id=&quot;raterreset&quot; class=&quot;align-middle&quot;&gt;&lt;/div&gt;
                                                            &lt;span class=&quot;clear-rating&quot;&gt;&lt;/span&gt;
                                                            &lt;button id=&quot;raterreset-button&quot; class=&quot;btn btn-light btn-sm ms-2&quot;&gt;Reset&lt;/button&gt;
                                                        &lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="reset-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // rater-reset
                                                        if (document.querySelector('#raterreset'))
                                                            var starRatingreset = raterJs({
                                                                starSize: 22,
                                                                rating: 2,
                                                                element: document.querySelector("#raterreset"),
                                                                rateCallback: function rateCallback(rating, done) {
                                                                    this.setRating(rating);
                                                                    done();
                                                                }
                                                            });
                                                        
                                                            if (document.querySelector("#raterreset-button"))
                                                            document.querySelector("#raterreset-button").addEventListener(
                                                              "click",
                                                              function () {
                                                                starRatingreset.clear();
                                                              },
                                                              false
                                                            );
                                                          
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                    </div> <!-- end col -->

                    <div class="col-xl-3">
                        <div class="card docs-nav">
                            <ul class="nav bg-transparent flex-column">
                                <li class="nav-item">
                                    <a href="#overview" class="nav-link">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#basic" class="nav-link">Basic Example</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#step" class="nav-link">Rater with Step Example</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#custom-message" class="nav-link">Custom Messages Example</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#readOnly" class="nav-link">ReadOnly Example</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#onhover" class="nav-link">On Hover Event Example</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#reset" class="nav-link">Clear/Reset Rater Example</a>
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

    <!-- Rating Demo js -->
    <script src="/js/components/extended-rating.js"></script>

</body>

</html>