<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Vector Maps")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Vector Maps")) ?>
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
                                        <h5 class="card-title mb-1 anchor" id="maps-world_vector_map">
                                             World Vector Map<a class="anchor-link" href="#world_vector_map">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="world-map-markers" style="height: 360px"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#homePill" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#profilePill" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="homePill">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                      <code>
                                                                           &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;div id=&quot;world-map-markers&quot; style=&quot;height: 360px&quot;&gt;&lt;/div&gt;
                                                                           &lt;/div&gt;
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="profilePill">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           initWorldMapMarker() {
                                                                                const map = new jsVectorMap({
                                                                                    map: 'world',
                                                                                    selector: '#world-map-markers',
                                                                                    zoomOnScroll: false, 
                                                                                    zoomButtons: true,
                                                                                    markersSelectable: true,
                                                                                    markers: [
                                                                                        { name: "Greenland", coords: [72, -42] },
                                                                                        { name: "Canada", coords: [56.1304, -106.3468] },
                                                                                        { name: "Brazil", coords: [-14.2350, -51.9253] },
                                                                                        { name: "Egypt", coords: [26.8206, 30.8025] },
                                                                                        { name: "Russia", coords: [61, 105] },
                                                                                        { name: "China", coords: [35.8617, 104.1954] },
                                                                                        { name: "United States", coords: [37.0902, -95.7129] },
                                                                                        { name: "Norway", coords: [60.472024, 8.468946] },
                                                                                        { name: "Ukraine", coords: [48.379433, 31.16558] },
                                                                                    ],
                                                                                    markerStyle: {
                                                                                        initial: { fill: "#5B8DEC" },
                                                                                        selected: { fill: "#ef5f5f" }
                                                                                    },
                                                                                    labels: {
                                                                                        markers: {
                                                                                            render: marker => marker.name
                                                                                        }
                                                                                    }
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
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="maps-canada_vector_map">
                                             Canada Vector Map<a class="anchor-link" href="#canada_vector_map">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="canada-vector-map" style="height: 360px"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#canada-vector-map-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#canada-vector-map-js" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="canada-vector-map-html">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                      <code>
                                                                           &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;div id=&quot;canada-vector-map&quot; style=&quot;height: 360px&quot;&gt;&lt;/div&gt;
                                                                           &lt;/div&gt;
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="canada-vector-map-js">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           initCanadaVectorMap() {
                                                                                const map = new jsVectorMap({
                                                                                    map: 'canada',
                                                                                    selector: '#canada-vector-map',
                                                                                    zoomOnScroll: false,
                                                                                    regionStyle: {
                                                                                        initial: {
                                                                                            fill: '#5B8DEC'
                                                                                        }
                                                                                    }
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
                              </div>


                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="maps-russia-vector-map">
                                             Russia Vector Map<a class="anchor-link" href="#russia-vector-map">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="russia-vector-map" style="height: 360px"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#russia-vector-map-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#russia-vector-map-js" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="russia-vector-map-html">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                      <code>
                                                                           &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;div id=&quot;russia-vector-map&quot; style=&quot;height: 360px&quot;&gt;&lt;/div&gt;
                                                                           &lt;/div&gt;
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="russia-vector-map-js">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           initRussiaVectorMap() {
                                                                                const map = new jsVectorMap({
                                                                                    map: 'russia',
                                                                                    selector: '#russia-vector-map',
                                                                                    zoomOnScroll: false,
                                                                                    regionStyle: {
                                                                                        initial: {
                                                                                            fill: '#5d7186'
                                                                                        }
                                                                                    }
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
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="maps-iraq-vector-map">
                                             Iraq Vector Map<a class="anchor-link" href="#iraq-vector-map">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="iraq-vector-map" style="height: 360px"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#iraq-vector-map-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#iraq-vector-map-js" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="iraq-vector-map-html">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                      <code>
                                                                           &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;div id=&quot;iraq-vector-map&quot; style=&quot;height: 360px&quot;&gt;&lt;/div&gt;
                                                                           &lt;/div&gt;
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="iraq-vector-map-js">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           initIraqVectorMap() {
                                                                                const map = new jsVectorMap({
                                                                                    map: 'iraq',
                                                                                    selector: '#iraq-vector-map',
                                                                                    zoomOnScroll: false,
                                                                                    regionStyle: {
                                                                                        initial: {
                                                                                            fill: '#20c8e9'
                                                                                        }
                                                                                    }
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
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="maps-spain-vector-map">
                                             Spain Vector Map<a class="anchor-link" href="#spain-vector-map">#</a>
                                        </h5>
                                        <p class="text-muted">Give textual form controls like <code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s an upgrade with custom styles, sizing, focus states, and more.</p>
                                        <div class="">
                                             <div class="mb-3">
                                                  <div id="spain-vector-map" style="height: 360px"></div>
                                             </div>

                                             <div class="highlight border rounded">
                                                  <div class="highlight-toolbar border-top-0">
                                                       <ul class="nav nav-pills">
                                                            <li class="nav-item">
                                                                 <a href="#spain-vector-map-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                      <span class="fs-12 text-uppercase">html</span>
                                                                 </a>
                                                            </li>
                                                            <li class="nav-item">
                                                                 <a href="#spain-vector-map-js" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                      <span class="fs-12 text-uppercase">javascript</span>
                                                                 </a>
                                                            </li>
                                                       </ul>
                                                       <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                                  </div>
                                                  <div class="tab-content">
                                                       <div class="tab-pane show active" id="spain-vector-map-html">
                                                            <div class="code">
                                                                 <pre class="language-html">
                                                                      <code>
                                                                           &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;div id=&quot;spain-vector-map&quot; style=&quot;height: 360px&quot;&gt;&lt;/div&gt;
                                                                           &lt;/div&gt;
                                                                      </code>
                                                                 </pre>
                                                            </div>
                                                       </div>
                                                       <div class="tab-pane" id="spain-vector-map-js">
                                                            <div class="code">
                                                                 <pre class="language-javascript">
                                                                      <code>
                                                                           initSpainVectorMap() {
                                                                                const map = new jsVectorMap({
                                                                                    map: 'spain',
                                                                                    selector: '#spain-vector-map',
                                                                                    zoomOnScroll: false,
                                                                                    regionStyle: {
                                                                                        initial: {
                                                                                            fill: '#ffe381'
                                                                                        }
                                                                                    }
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
                              </div> <!-- end card body -->
                         </div> <!-- end col -->

                         <div class="col-xl-3">
                              <div class="card docs-nav">
                                   <ul class="nav bg-transparent flex-column">
                                        <li class="nav-item">
                                             <a href="#world_vector_map" class="nav-link">World Vector Map</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#canada_vector_map" class="nav-link">Canada Vector Map</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#russia-vector-map" class="nav-link">Russia Vector Map</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#iraq-vector-map" class="nav-link">Iraq Vector Map</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#spain-vector-map" class="nav-link">Spain Vector Map</a>
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

     <!-- Vector Map Js -->
     <script src="/vendor/jsvectormap/jsvectormap.min.js"></script>
     <script src="/vendor/jsvectormap/maps/world-merc.js"></script>
     <script src="/vendor/jsvectormap/maps/world.js"></script>

     <script src="/js/components/maps-canada.js"></script>
     <script src="/js/components/maps-iraq.js"></script>
     <script src="/js/components/maps-russia.js"></script>
     <script src="/js/components/maps-spain.js"></script>
     <script src="/js/components/maps-us-aea-en.js"></script>
     <script src="/js/components/maps-us-lcc-en.js"></script>
     <script src="/js/components/maps-us-mill-en.js"></script>

     <script src="/js/components/maps-vector.js"></script>

</body>

</html>