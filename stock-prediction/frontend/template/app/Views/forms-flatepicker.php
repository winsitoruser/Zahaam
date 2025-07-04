<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Flatpicker")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Flatpicker")) ?>
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
                                        <h5 class="card-title mb-1 anchor" id="overview">
                                             Overview <a class="btn btn-sm btn-outline-success rounded-2 float-end" href="https://flatpickr.js.org/#introduction" target="_blank"> Official Website </a>
                                        </h5>
                                        <p class="text-muted mb-3">flatpickr is a lightweight and powerful datetime picker.</p>

                                        <h5 class="mt-2">Usage</h5>
                                        <p class="mb-0">Flatpickr's CSS and Javascript files are bundled in the <code>vender.min.css</code> and <code>vendor.js</code> and globally included in all pages.</p>
                                   </div><!-- end card-body -->
                              </div><!-- end card -->


                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-3 anchor" id="basic">
                                             Basic<a class="anchor-link" href="#basic">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <div class="mb-3">
                                                  <input type="text" id="basic-datepicker" class="form-control" placeholder="Basic datepicker">
                                             </div>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#basic-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#basic-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="basic-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;basic-datepicker&quot; class=&quot;form-control&quot; placeholder=&quot;Basic datepicker&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="basic-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('basic-datepicker').flatpickr();
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
                                        <h5 class="card-title mb-3 anchor" id="datetime">
                                             DateTime<a class="anchor-link" href="#datetime">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <div class="mb-3">
                                                  <input type="text" id="datetime-datepicker" class="form-control" placeholder="Date and Time">
                                             </div>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#datetime-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#datetime-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="datetime-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;datetime-datepicker&quot; class=&quot;form-control&quot; placeholder=&quot;Date and Time&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="datetime-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('datetime-datepicker').flatpickr({
                                                                           enableTime: true,
                                                                           dateFormat: "Y-m-d H:i"
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
                                        <h5 class="card-title mb-3 anchor" id="human-friendly-dates">
                                             Human-friendly Dates<a class="anchor-link" href="#human-friendly-dates">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="humanfd-datepicker" class="form-control" placeholder="October 9, 2018">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#human-friendly-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#human-friendly-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="human-friendly-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;humanfd-datepicker&quot; class=&quot;form-control&quot; placeholder=&quot;October 9, 2018&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="human-friendly-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('humanfd-datepicker').flatpickr({
                                                                           altInput: true,
                                                                           altFormat: "F j, Y",
                                                                           dateFormat: "Y-m-d",
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
                                        <h5 class="card-title mb-3 anchor" id="minmax">
                                             MinDate and MaxDate<a class="anchor-link" href="#minmax">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="minmax-datepicker" class="form-control" placeholder="mindate - maxdate">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#minmax-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#minmax-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="minmax-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;minmax-datepicker&quot; class=&quot;form-control&quot; placeholder=&quot;mindate - maxdate&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="minmax-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code> 
                                                                      document.getElementById('minmax-datepicker').flatpickr({
                                                                           minDate: "2020-01",
                                                                           maxDate: "2020-03"
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
                                        <h5 class="card-title mb-3 anchor" id="multiple">
                                             Disabling dates<a class="anchor-link" href="#disable">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="disable-datepicker" class="form-control" placeholder="Disabling dates">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#disable-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#disable-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="disable-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;disable-datepicker&quot; class=&quot;form-control&quot; placeholder=&quot;disable datepicker&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="disable-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('disable-datepicker').flatpickr({
                                                                           onReady: function () {
                                                                               this.jumpToDate("2025-01")
                                                                           },
                                                                           disable: ["2025-01-10", "2025-01-21", "2025-01-30", new Date(2025, 4, 9) ],
                                                                           dateFormat: "Y-m-d",
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
                                        <h5 class="card-title mb-3 anchor" id="multiple">
                                             Selecting multiple dates<a class="anchor-link" href="#multiple">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="multiple-datepicker" class="form-control" placeholder="Multiple dates">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#multiple-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#multiple-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="multiple-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;multiple-datepicker&quot; class=&quot;form-control&quot; placeholder=&quot;Multiple dates&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="multiple-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('multiple-datepicker').flatpickr({
                                                                           mode: "multiple",
                                                                           dateFormat: "Y-m-d"
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
                                        <h5 class="card-title mb-3 anchor" id="conjunction">
                                             Selecting multiple dates - Conjunction<a class="anchor-link" href="#conjunction">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="conjunction-datepicker" class="form-control" placeholder="2018-10-10 :: 2018-10-11">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#conjunction-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#conjunction-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="conjunction-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;conjunction-datepicker&quot; class=&quot;form-control&quot; placeholder=&quot;2018-10-10 :: 2018-10-11&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="conjunction-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('conjunction-datepicker').flatpickr({
                                                                           mode: "multiple",
                                                                           dateFormat: "Y-m-d",
                                                                           conjunction: " :: "
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
                                        <h5 class="card-title mb-3 anchor" id="range-calendar">
                                             Range Calendar<a class="anchor-link" href="#range-calendar">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="range-datepicker" class="form-control" placeholder="2018-10-03 to 2018-10-10">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#range-calendar-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#range-calendar-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="range-calendar-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;range-datepicker&quot; class=&quot;form-control&quot; placeholder=&quot;2018-10-03 to 2018-10-10&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="range-calendar-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('range-datepicker').flatpickr({
                                                                           mode: "range"
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
                                        <h5 class="card-title mb-3 anchor" id="inline-calendar">
                                             Inline Calendar<a class="anchor-link" href="#inline-calendar">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="inline-datepicker" class="form-control" placeholder="Inline calendar">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#inline-calendar-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#inline-calendar-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="inline-calendar-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;inline-datepicker&quot; class=&quot;form-control&quot; placeholder=&quot;Inline calendar&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="inline-calendar-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('inline-datepicker').flatpickr({
                                                                           inline: true
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
                                        <h5 class="card-title mb-3 anchor" id="timepicker-basic">
                                             Basic Timepicker<a class="anchor-link" href="#timepicker-basic">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="basic-timepicker" class="form-control" placeholder="Basic timepicker">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#timepicker-basic-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#timepicker-basic-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="timepicker-basic-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;basic-timepicker&quot; class=&quot;form-control&quot; placeholder=&quot;Basic timepicker&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="timepicker-basic-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('basic-timepicker').flatpickr({
                                                                           enableTime: true,
                                                                           noCalendar: true,
                                                                           dateFormat: "H:i"
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
                                        <h5 class="card-title mb-3 anchor" id="24hours">
                                             24-hour Time Picker<a class="anchor-link" href="#24hours">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="24hours-timepicker" class="form-control" placeholder="16:21">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#fullhours-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#fullhours-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="fullhours-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;24hours-timepicker&quot; class=&quot;form-control&quot; placeholder=&quot;16:21&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="fullhours-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('24hours-timepicker').flatpickr({
                                                                           enableTime: true,
                                                                           noCalendar: true,
                                                                           dateFormat: "H:i",
                                                                           time_24hr: true
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
                                        <h5 class="card-title mb-3 anchor" id="timepicker-minmax">
                                             Time Picker w/ Limits<a class="anchor-link" href="#timepicker-minmax">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="minmax-timepicker" class="form-control" placeholder="Limits">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#timepicker-minmax-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#timepicker-minmax-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="timepicker-minmax-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;minmax-timepicker&quot; class=&quot;form-control&quot; placeholder=&quot;Limits&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="timepicker-minmax-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('minmax-timepicker').flatpickr({
                                                                           enableTime: true,
                                                                           noCalendar: true,
                                                                           dateFormat: "H:i",
                                                                           minDate: "16:00",
                                                                           maxDate: "22:30",
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
                                        <h5 class="card-title mb-3 anchor" id="preloading-time">
                                             Preloading Time<a class="anchor-link" href="#preloading-time">#</a>
                                        </h5>
                                        <div class="w-50 mb-3">
                                             <input type="text" id="preloading-timepicker" class="form-control" placeholder="Pick a time">
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#preloading-time-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#preloading-time-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="preloading-time-html">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-html">
                                                                 <code>
                                                                      &lt;input type=&quot;text&quot; id=&quot;preloading-timepicker&quot; class=&quot;form-control&quot; placeholder=&quot;Pick a time&quot;&gt;
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="preloading-time-javascript">
                                                       <div class="code" data-simplebar>
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      document.getElementById('preloading-timepicker').flatpickr({
                                                                           enableTime: true,
                                                                           noCalendar: true,
                                                                           dateFormat: "H:i",
                                                                           defaultDate: "01:45"
                                                                      });
                                                                 </code>
                                                            </pre>
                                                       </div>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div> <!-- end col -->

                         <div class="col-xl-3">
                              <div class="card docs-nav">
                                   <ul class="nav bg-transparent flex-column">
                                        <li class="nav-item">
                                             <a href="#overview" class="nav-link">Overview</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#basic" class="nav-link">Basic</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#datetime" class="nav-link">DateTime</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#human-friendly-dates" class="nav-link">Human-friendly Dates</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#minmax" class="nav-link">MinDate and MaxDate</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#disable" class="nav-link">Disabling dates</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#multiple" class="nav-link">Selecting multiple dates</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#conjunction" class="nav-link">Selecting multiple dates - Conjunction</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#range-calendar" class="nav-link">Range Calendar</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#inline-calendar" class="nav-link">Inline Calendar</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#timepicker-basic" class="nav-link">Basic Timepicker</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#24hours" class="nav-link">24-hour Time Picker</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#timepicker-minmax" class="nav-link">Time Picker w/ Limits</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#preloading-time" class="nav-link">Preloading Time</a>
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

     <!-- Flatepicker Demo Js -->
     <script src="/js/components/form-flatepicker.js"></script>

</body>

</html>