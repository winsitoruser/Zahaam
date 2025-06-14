<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Range Slider")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Range Slider")) ?>
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
                                             Overview <a class="btn btn-sm btn-outline-success rounded-2 float-end" href="https://github.com/leongersen/noUiSlider#readme" target="_blank"> Official Website </a>
                                        </h5>
                                        <p class="text-muted mb-3">noUiSlider is a lightweight JavaScript range slider.</p>

                                        <h5 class="mt-2">Usage</h5>
                                        <p class="mb-0">noUiSlider's CSS and Javascript files are bundled in the <code>vender.min.css</code> and <code>vendor.js</code> and globally included in all pages.</p>
                                   </div><!-- end card-body -->
                              </div><!-- end card -->

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-3 anchor" id="basic-range">
                                             Basic Range Slider<a class="anchor-link" href="#basic-range">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div id="rangeslider_basic"></div>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#basic-range-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#basic-range-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="basic-range-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;div id=&quot;rangeslider_basic&quot;&gt;&lt;/label&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="basic-range-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      var sliderColorScheme = document.querySelectorAll('#rangeslider_basic');
                                                                      if (sliderColorScheme)
                                                                           sliderColorScheme.forEach(function (slider) {
                                                                                noUiSlider.create(slider, {
                                                                                     start: 127,
                                                                                     connect: 'lower',
                                                                                     range: {
                                                                                          'min': 0,
                                                                                          'max': 255
                                                                                     },
                                                                                });
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
                                        <h5 class="card-title mb-3 anchor" id="vertical-range">
                                             Vertical Range Slider<a class="anchor-link" href="#vertical-range">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div id="rangeslider_vertical" style="height: 150px;"></div>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#vertical-range-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#vertical-range-js" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="vertical-range-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;div class=&quot;mb-3&quot;&gt;
                                                                           &lt;div id=&quot;rangeslider_vertical&quot; style=&quot;height: 150px;&quot;&gt;&lt;/div&gt;
                                                                      &lt;/div&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="vertical-range-js">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      var rangesliderVertical = document.querySelectorAll('#rangeslider_vertical');
                                                                      if (rangesliderVertical)
                                                                      rangesliderVertical.forEach(function (slider) {
                                                                           noUiSlider.create(slider, {
                                                                                start: [60, 160],
                                                                                connect: true,
                                                                                orientation: "vertical",
                                                                                range: {
                                                                                     "min": 0,
                                                                                     "max": 200
                                                                                }
                                                                           });
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
                                        <h5 class="card-title mb-3 anchor" id="multi-range">
                                             Multi Elements Range<a class="anchor-link" href="#multi-range">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div id="rangeslider_multielement"></div>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#multielement-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#multielement-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="multielement-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;div class=&quot;mb-3&quot;&gt;
                                                                           &lt;div id=&quot;rangeslider_multielement&quot;&gt;&lt;/div&gt;
                                                                      &lt;/div&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="multielement-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      var multielementslider = document.querySelectorAll('#rangeslider_multielement');
                                                                      if (multielementslider)
                                                                      multielementslider.forEach(function (slider) {
                                                                           noUiSlider.create(slider, {
                                                                                start: [20, 80],
                                                                                connect: true,
                                                                                range: {
                                                                                     'min': 0,
                                                                                     'max': 100
                                                                                }
                                                                           });
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
                                        <h5 class="card-title mb-3 anchor" id="colorpicker-range">
                                             Colorpicker<a class="anchor-link" href="#colorpicker-range">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div class="sliders" id="red"></div>
                                             <div class="sliders" id="green"></div>
                                             <div class="sliders" id="blue"></div>

                                             <div id="result"></div>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#colorpicker-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#colorpicker-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="colorpicker-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;div class=&quot;mb-3&quot;&gt;
                                                                      &lt;div class=&quot;sliders&quot; id=&quot;red&quot;&gt;&lt;/div&gt;
                                                                      &lt;div class=&quot;sliders&quot; id=&quot;green&quot;&gt;&lt;/div&gt;
                                                                      &lt;div class=&quot;sliders&quot; id=&quot;blue&quot;&gt;&lt;/div&gt;
                         
                                                                      &lt;div id=&quot;result&quot;&gt;&lt;/div&gt;
                                                                 &lt;/div&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="colorpicker-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      var resultElement = document.getElementById('result');
                                                                      var sliders = document.getElementsByClassName('sliders');
                                                                      var colors = [0, 0, 0];
                                                                      if (sliders)
                                                                      [].slice.call(sliders).forEach(function (slider, index) {

                                                                           noUiSlider.create(slider, {
                                                                                start: 127,
                                                                                connect: [true, false],
                                                                                orientation: "vertical",
                                                                                range: {
                                                                                     'min': 0,
                                                                                     'max': 255
                                                                                },
                                                                                format: wNumb({
                                                                                     decimals: 0
                                                                                })
                                                                           });

                                                                           // Bind the color changing function to the update event.
                                                                           slider.noUiSlider.on('update', function () {

                                                                                colors[index] = slider.noUiSlider.get();

                                                                                var color = 'rgb(' + colors.join(',') + ')';

                                                                                resultElement.style.background = color;
                                                                                resultElement.style.color = color;
                                                                           });
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
                                        <h5 class="card-title mb-3 anchor" id="value-range">
                                             Value Range Slider<a class="anchor-link" href="#value-range">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div id="nonlinear"></div>
                                             <div class="d-flex justify-content-between">
                                                  <div class="example-val" id="lower-value"></div>
                                                  <div class="example-val" id="upper-value"></div>
                                             </div>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#value-range-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#value-range-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="value-range-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;div class=&quot;mb-3&quot;&gt;
                                                                      &lt;div id=&quot;nonlinear&quot;&gt;&lt;/div&gt;
                                                                      &lt;div class=&quot;d-flex justify-content-between&quot;&gt;
                                                                           &lt;div class=&quot;example-val&quot; id=&quot;lower-value&quot;&gt;&lt;/div&gt;
                                                                           &lt;div class=&quot;example-val&quot; id=&quot;upper-value&quot;&gt;&lt;/div&gt;
                                                                      &lt;/div&gt;
                                                                 &lt;/div&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="value-range-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      var nonLinearSlider = document.getElementById('nonlinear');
                                                                      if (nonLinearSlider)
                                                                           noUiSlider.create(nonLinearSlider, {
                                                                                connect: true,
                                                                                behaviour: 'tap',
                                                                                start: [500, 4000],
                                                                                range: {
                                                                                     // Starting at 500, step the value by 500,
                                                                                     // until 4000 is reached. From there, step by 1000.
                                                                                     'min': [0],
                                                                                     '10%': [500, 500],
                                                                                     '50%': [4000, 1000],
                                                                                     'max': [10000]
                                                                                }
                                                                           });
                                                                      
                                                                      var nodes = [
                                                                           document.getElementById('lower-value'), // 0
                                                                           document.getElementById('upper-value') // 1
                                                                      ];
                                                                      
                                                                      // Display the slider value and how far the handle moved
                                                                      // from the left edge of the slider.
                                                                      nonLinearSlider.noUiSlider.on('update', function (values, handle, unencoded, isTap, positions) {
                                                                           nodes[handle].innerHTML = values[handle] + ', ' + positions[handle].toFixed(2) + '%';
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
                                        <h5 class="card-title mb-3 anchor" id="locking-together">
                                             Locking Sliders Together<a class="anchor-link" href="#locking-together">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div class="slider" id="slider1"></div>
                                             <span class="example-val mt-2" id="slider1-span"></span>

                                             <div class="slider" id="slider2"></div>
                                             <span class="example-val mt-2" id="slider2-span"></span>

                                             <button id="lockbutton" class="btn btn-primary">Lock</button>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#locking-together-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#locking-together-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="locking-together-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;div class=&quot;mb-3&quot;&gt;
                                                                           &lt;div class=&quot;slider&quot; id=&quot;slider1&quot;&gt;&lt;/div&gt;
                                                                           &lt;span class=&quot;example-val mt-2&quot; id=&quot;slider1-span&quot;&gt;&lt;/span&gt;

                                                                           &lt;div class=&quot;slider&quot; id=&quot;slider2&quot;&gt;&lt;/div&gt;
                                                                           &lt;span class=&quot;example-val mt-2&quot; id=&quot;slider2-span&quot;&gt;&lt;/span&gt;

                                                                           &lt;button id=&quot;lockbutton&quot; class=&quot;btn btn-primary&quot;&gt;Lock&lt;/button&gt;
                                                                      &lt;/div&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="locking-together-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      var lockedState = false;
                                                                      var lockedSlider = false;
                                                                      var lockedValues = [60, 80];

                                                                      var slider1 = document.getElementById('slider1');
                                                                      var slider2 = document.getElementById('slider2');

                                                                      var lockButton = document.getElementById('lockbutton');
                                                                      var slider1Value = document.getElementById('slider1-span');
                                                                      var slider2Value = document.getElementById('slider2-span');

                                                                      // When the button is clicked, the locked state is inverted.
                                                                      if (lockButton)
                                                                      lockButton.addEventListener('click', function () {
                                                                           lockedState = !lockedState;
                                                                           this.textContent = lockedState ? 'unlock' : 'lock';
                                                                      });

                                                                      function crossUpdate(value, slider) {

                                                                      // If the sliders aren't interlocked, don't
                                                                      // cross-update.
                                                                      if (!lockedState) return;

                                                                      // Select whether to increase or decrease
                                                                      // the other slider value.
                                                                      var a = slider1 === slider ? 0 : 1;

                                                                      // Invert a
                                                                      var b = a ? 0 : 1;

                                                                      // Offset the slider value.
                                                                      value -= lockedValues[b] - lockedValues[a];

                                                                      // Set the value
                                                                      slider.noUiSlider.set(value);
                                                                      }

                                                                      noUiSlider.create(slider1, {
                                                                      start: 60,

                                                                      // Disable animation on value-setting,
                                                                      // so the sliders respond immediately.
                                                                      animate: false,
                                                                      range: {
                                                                           min: 50,
                                                                           max: 100
                                                                      }
                                                                      });

                                                                      noUiSlider.create(slider2, {
                                                                      start: 80,
                                                                      animate: false,
                                                                      range: {
                                                                           min: 50,
                                                                           max: 100
                                                                      }
                                                                      });

                                                                      if (slider1 && slider2) {
                                                                      slider1.noUiSlider.on('update', function (values, handle) {
                                                                           slider1Value.innerHTML = values[handle];
                                                                      });
                                                                      slider2.noUiSlider.on('update', function (values, handle) {
                                                                           slider2Value.innerHTML = values[handle];
                                                                      });


                                                                      function setLockedValues() {
                                                                           lockedValues = [
                                                                                Number(slider1.noUiSlider.get()),
                                                                                Number(slider2.noUiSlider.get())
                                                                           ];
                                                                      }

                                                                      slider1.noUiSlider.on('change', setLockedValues);
                                                                      slider2.noUiSlider.on('change', setLockedValues);

                                                                      slider1.noUiSlider.on('slide', function (values, handle) {
                                                                           crossUpdate(values[handle], slider2);
                                                                      });

                                                                      slider2.noUiSlider.on('slide', function (values, handle) {
                                                                           crossUpdate(values[handle], slider1);
                                                                      });
                                                                      }

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
                                        <h5 class="card-title mb-3 anchor" id="tooltip">
                                             Tooltip<a class="anchor-link" href="#tooltip">#</a>
                                        </h5>

                                        <div class="p-3 pt-5">
                                             <div class="slider" id="slider-merging-tooltips"></div>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#tooltip-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#tooltip-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="tooltip-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;div class=&quot;slider&quot; id=&quot;slider-merging-tooltips&quot;&gt;&lt;/div&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="tooltip-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      var mergingTooltipSlider = document.getElementById('slider-merging-tooltips');
                                                                      if (mergingTooltipSlider) {
                                                                      noUiSlider.create(mergingTooltipSlider, {
                                                                           start: [20, 75],
                                                                           connect: true,
                                                                           tooltips: [true, true],
                                                                           range: {
                                                                                'min': 0,
                                                                                'max': 100
                                                                           }
                                                                      });

                                                                      mergeTooltips(mergingTooltipSlider, 5, ' - ');
                                                                      }

                                                                      /**
                                                                      * @param slider HtmlElement with an initialized slider
                                                                      * @param threshold Minimum proximity (in percentages) to merge tooltips
                                                                      * @param separator String joining tooltips
                                                                      */
                                                                      function mergeTooltips(slider, threshold, separator) {

                                                                      var textIsRtl = getComputedStyle(slider).direction === 'rtl';
                                                                      var isRtl = slider.noUiSlider.options.direction === 'rtl';
                                                                      var isVertical = slider.noUiSlider.options.orientation === 'vertical';
                                                                      var tooltips = slider.noUiSlider.getTooltips();
                                                                      var origins = slider.noUiSlider.getOrigins();

                                                                      // Move tooltips into the origin element. The default stylesheet handles this.
                                                                      tooltips.forEach(function (tooltip, index) {
                                                                           if (tooltip) {
                                                                                origins[index].appendChild(tooltip);
                                                                           }
                                                                      });
                                                                      if (slider)
                                                                           slider.noUiSlider.on('update', function (values, handle, unencoded, tap, positions) {

                                                                                var pools = [
                                                                                     []
                                                                                ];
                                                                                var poolPositions = [
                                                                                     []
                                                                                ];
                                                                                var poolValues = [
                                                                                     []
                                                                                ];
                                                                                var atPool = 0;

                                                                                // Assign the first tooltip to the first pool, if the tooltip is configured
                                                                                if (tooltips[0]) {
                                                                                     pools[0][0] = 0;
                                                                                     poolPositions[0][0] = positions[0];
                                                                                     poolValues[0][0] = values[0];
                                                                                }

                                                                                for (var i = 1; i < positions.length; i++) {
                                                                                     if (!tooltips[i] || (positions[i] - positions[i - 1]) > threshold) {
                                                                                          atPool++;
                                                                                          pools[atPool] = [];
                                                                                          poolValues[atPool] = [];
                                                                                          poolPositions[atPool] = [];
                                                                                     }

                                                                                     if (tooltips[i]) {
                                                                                          pools[atPool].push(i);
                                                                                          poolValues[atPool].push(values[i]);
                                                                                          poolPositions[atPool].push(positions[i]);
                                                                                     }
                                                                                }

                                                                                pools.forEach(function (pool, poolIndex) {
                                                                                     var handlesInPool = pool.length;

                                                                                     for (var j = 0; j < handlesInPool; j++) {
                                                                                          var handleNumber = pool[j];

                                                                                          if (j === handlesInPool - 1) {
                                                                                          var offset = 0;

                                                                                          poolPositions[poolIndex].forEach(function (value) {
                                                                                               offset += 1000 - value;
                                                                                          });

                                                                                          var direction = isVertical ? 'bottom' : 'right';
                                                                                          var last = isRtl ? 0 : handlesInPool - 1;
                                                                                          var lastOffset = 1000 - poolPositions[poolIndex][last];
                                                                                          offset = (textIsRtl && !isVertical ? 100 : 0) + (offset / handlesInPool) - lastOffset;

                                                                                          // Center this tooltip over the affected handles
                                                                                          tooltips[handleNumber].innerHTML = poolValues[poolIndex].join(separator);
                                                                                          tooltips[handleNumber].style.display = 'block';
                                                                                          tooltips[handleNumber].style[direction] = offset + '%';
                                                                                          } else {
                                                                                          // Hide this tooltip
                                                                                          tooltips[handleNumber].style.display = 'none';
                                                                                          }
                                                                                     }
                                                                                });
                                                                           });
                                                                      }

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
                                        <h5 class="card-title mb-3 anchor" id="soft-limits">
                                             Soft Limits <a class="anchor-link" href="#soft-limits">#</a>
                                        </h5>

                                        <div class="mb-3 pb-5">
                                             <div class="slider" id="soft"></div>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#soft-limits-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#soft-limits-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="soft-limits-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;div  class=&quot;slider&quot; id=&quot;soft&quot;&gt;&lt;/label&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="soft-limits-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      var softSlider = document.getElementById('soft');
                                                                      if (softSlider) {
                                                                      noUiSlider.create(softSlider, {
                                                                           start: 50,
                                                                           range: {
                                                                                min: 0,
                                                                                max: 100
                                                                           },
                                                                           pips: {
                                                                                mode: 'values',
                                                                                values: [20, 80],
                                                                                density: 4
                                                                           }
                                                                      });

                                                                      softSlider.noUiSlider.on('change', function (values, handle) {
                                                                           if (values[handle] < 20) {
                                                                                softSlider.noUiSlider.set(20);
                                                                           } else if (values[handle] > 80) {
                                                                                softSlider.noUiSlider.set(80);
                                                                           }
                                                                      });
                                                                      }
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
                                             <a href="#basic-range" class="nav-link">Basic Range Slider</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#vertical-range" class="nav-link">Vertical Range Slider</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#multi-range" class="nav-link">Multi Elements Range</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#colorpicker-range" class="nav-link">Colorpicker</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#value-range" class="nav-link">Value Range SLider</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#locking-together" class="nav-link">Locking Sliders Together</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#tooltip" class="nav-link">Tooltip</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#soft-limits " class="nav-link">Soft Limits </a>
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

     <!-- slider js -->
     <script src="/js/components/form-slider.js"></script>

</body>

</html>