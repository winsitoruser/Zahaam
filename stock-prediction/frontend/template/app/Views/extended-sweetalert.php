<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Sweet Alert")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Sweet Alert")) ?>
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
                                             Overview <a class="btn btn-sm btn-outline-success rounded-2 float-end" href="https://sweetalert2.github.io/" target="_blank"> Official Website </a>
                                        </h5>
                                        <p class="text-muted mb-3">A beautiful, responsive, customizable, accessible (WAI-ARIA) replacement for JavaScript's popup boxes</p>

                                        <h5 class="mt-2">Usage</h5>
                                        <p class="mb-0">Sweetalert2's CSS and Javascript files are bundled in the <code>vender.min.css</code> and <code>vendor.js</code> and globally included in all pages.</p>
                                   </div><!-- end card-body -->
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-3 anchor" id="basic">
                                             Basic<a class="anchor-link" href="#basic">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <button type="button" class="btn btn-primary" id="sweetalert-basic">Click me</button>
                                        </div>

                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#basic-sweetalert-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#basic-sweetalert-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="basic-sweetalert-html">
                                                       <div class="code">
                                                            <pre class="language-html">
                                                                      <code>
                                                                           &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; id=&quot;sweetalert-basic&quot;&gt;Click me&lt;/button&gt;
                                                                      </code>
                                                                 </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="basic-sweetalert-javascript">
                                                       <div class="code">
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      if (document.getElementById("sweetalert-basic"))
                                                                           document.getElementById("sweetalert-basic").addEventListener("click", function () {
                                                                                Swal.fire({
                                                                                     title: 'Any fool can use a computer',
                                                                                     confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                                                                     buttonsStyling: false,
                                                                                     showCloseButton: true
                                                                                })
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
                                        <h5 class="card-title mb-3 anchor" id="title">
                                             A Title with a Text Under<a class="anchor-link" href="#title">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <button type="button" class="btn btn-primary" id="sweetalert-title">Click me</button>
                                        </div>

                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#title-sweetalert-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#title-sweetalert-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="title-sweetalert-html">
                                                       <div class="code">
                                                            <pre class="language-html">
                                                                      <code>
                                                                           &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; id=&quot;sweetalert-title&quot;&gt;Click me&lt;/button&gt;
                                                                      </code>
                                                                 </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="title-sweetalert-javascript">
                                                       <div class="code">
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      //A title with a text under
                                                                      if (document.getElementById("sweetalert-title"))
                                                                      document.getElementById("sweetalert-title").addEventListener("click", function () {
                                                                      Swal.fire({         
                                                                           title: "The Internet?",
                                                                           text: 'That thing is still around?',
                                                                           icon: 'question',
                                                                           confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                                                           buttonsStyling: false,
                                                                           showCloseButton: false
                                                                      })
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
                                        <h5 class="card-title mb-3 anchor" id="message">
                                             Message<a class="anchor-link" href="#message">#</a>
                                        </h5>

                                        <div class="hstack gap-2 mb-3">
                                             <button type="button" class="btn btn-success" id="sweetalert-success">Success</button>
                                             <button type="button" class="btn btn-warning" id="sweetalert-warning">Warning</button>
                                             <button type="button" class="btn btn-info" id="sweetalert-info">Info</button>
                                             <button type="button" class="btn btn-danger" id="sweetalert-error">Error</button>
                                        </div>

                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#message-sweetalert-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#message-sweetalert-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="message-sweetalert-html">
                                                       <div class="code">
                                                            <pre class="language-html">
                                                                      <code>
                                                                           &lt;button type=&quot;button&quot; class=&quot;btn btn-success&quot; id=&quot;sweetalert-success&quot;&gt;Success&lt;/button&gt;
                                                                           &lt;button type=&quot;button&quot; class=&quot;btn btn-warning&quot; id=&quot;sweetalert-warning&quot;&gt;Warning&lt;/button&gt;
                                                                           &lt;button type=&quot;button&quot; class=&quot;btn btn-info&quot; id=&quot;sweetalert-info&quot;&gt;Info&lt;/button&gt;
                                                                           &lt;button type=&quot;button&quot; class=&quot;btn btn-danger&quot; id=&quot;sweetalert-error&quot;&gt;Error&lt;/button&gt;
                                                                      </code>
                                                                 </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="message-sweetalert-javascript">
                                                       <div class="code">
                                                            <pre class="language-javascript">
                                                                      <code>
                                                                           
                                                                           //Success Message
                                                                           if (document.getElementById("sweetalert-success"))
                                                                           document.getElementById("sweetalert-success").addEventListener("click", function () {
                                                                                Swal.fire({
                                                                                     title: 'Good job!',
                                                                                     text: 'You clicked the button!',
                                                                                     icon: 'success',
                                                                                     showCancelButton: true,
                                                                                     confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                                                                                     cancelButtonClass: 'btn btn-danger w-xs mt-2',
                                                                                     buttonsStyling: false,
                                                                                     showCloseButton: false
                                                                                })
                                                                           });

                                                                           //error Message
                                                                           if (document.getElementById("sweetalert-error"))
                                                                           document.getElementById("sweetalert-error").addEventListener("click", function () {
                                                                                Swal.fire({
                                                                                     title: 'Oops...',
                                                                                     text: 'Something went wrong!',
                                                                                     icon: 'error',
                                                                                     confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                                                                     buttonsStyling: false,
                                                                                     footer: '&lt;a href=&quot;&quot;&gt;Why do I have this issue?&lt;/a&gt;',
                                                                                     showCloseButton: false
                                                                                })
                                                                           });


                                                                           //info Message
                                                                           if (document.getElementById("sweetalert-info"))
                                                                           document.getElementById("sweetalert-info").addEventListener("click", function () {
                                                                                Swal.fire({
                                                                                     title: 'Oops...',
                                                                                     text: 'Something went wrong!',
                                                                                     icon: 'info',
                                                                                     confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                                                                     buttonsStyling: false,
                                                                                     footer: '&lt;a href=&quot;&quot;&gt;Why do I have this issue?&lt;/a&gt;',
                                                                                     showCloseButton: false
                                                                                })
                                                                           });

                                                                           //Warning Message
                                                                           if (document.getElementById("sweetalert-warning"))
                                                                           document.getElementById("sweetalert-warning").addEventListener("click", function () {
                                                                                Swal.fire({
                                                                                     title: 'Oops...',
                                                                                     text: 'Something went wrong!',
                                                                                     icon: 'warning',
                                                                                     confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                                                                     buttonsStyling: false,
                                                                                     footer: '&lt;a href=&quot;&quot;&gt;Why do I have this issue?&lt;/a&gt;',
                                                                                     showCloseButton: false
                                                                                })
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
                                        <h5 class="card-title mb-3 anchor" id="longcontent">
                                             Long content Images Message<a class="anchor-link" href="#longcontent">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <button type="button" class="btn btn-primary" id="sweetalert-longcontent">Click me</button>
                                        </div>

                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#longcontent-sweetalert-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#longcontent-sweetalert-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="longcontent-sweetalert-html">
                                                       <div class="code">
                                                            <pre class="language-html">
                                                                      <code>
                                                                           &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; id=&quot;sweetalert-longcontent&quot;&gt;Click me&lt;/button&gt;
                                                                      </code>
                                                                 </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="longcontent-sweetalert-javascript">
                                                       <div class="code">
                                                            <pre class="language-javascript">
                                                                      <code>
                                                                           // long content
                                                                           if (document.getElementById("sweetalert-longcontent"))
                                                                           document.getElementById("sweetalert-longcontent").addEventListener("click", function () {
                                                                                Swal.fire({
                                                                                     imageUrl: 'https://placeholder.pics/svg/300x1500',
                                                                                     imageHeight: 1500,
                                                                                     imageAlt: 'A tall image',
                                                                                     confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                                                                     buttonsStyling: false,
                                                                                     showCloseButton: false
                                                                                })
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
                                        <h5 class="card-title mb-3 anchor" id="parameter">
                                             Parameter<a class="anchor-link" href="#parameter">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <button type="button" class="btn btn-primary" id="sweetalert-params">Click me</button>
                                        </div>

                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <ul class="nav nav-pills">
                                                       <li class="nav-item">
                                                            <a href="#parameter-sweetalert-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                                 <span class="fs-12 text-uppercase">html</span>
                                                            </a>
                                                       </li>
                                                       <li class="nav-item">
                                                            <a href="#parameter-sweetalert-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                                 <span class="fs-12 text-uppercase">javascript</span>
                                                            </a>
                                                       </li>
                                                  </ul>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="tab-content">
                                                  <div class="tab-pane show active" id="parameter-sweetalert-html">
                                                       <div class="code">
                                                            <pre class="language-html">
                                                                      <code>
                                                                           &lt;button type=&quot;button&quot; class=&quot;btn btn-primary&quot; id=&quot;sweetalert-params&quot;&gt;Click me&lt;/button&gt;
                                                                      </code>
                                                                 </pre>
                                                       </div>
                                                  </div>
                                                  <div class="tab-pane" id="parameter-sweetalert-javascript">
                                                       <div class="code">
                                                            <pre class="language-javascript">
                                                                 <code>
                                                                      //Parameter
                                                                      if (document.getElementById("sweetalert-params"))
                                                                      document.getElementById("sweetalert-params").addEventListener("click", function () {
                                                                           Swal.fire({
                                                                                title: 'Are you sure?',
                                                                                text: "You won't be able to revert this!",
                                                                                icon: 'warning',
                                                                                showCancelButton: true,
                                                                                confirmButtonText: 'Yes, delete it!',
                                                                                cancelButtonText: 'No, cancel!',
                                                                                confirmButtonClass: 'btn btn-primary w-xs me-2 mt-2',
                                                                                cancelButtonClass: 'btn btn-danger w-xs mt-2',
                                                                                buttonsStyling: false,
                                                                                showCloseButton: false
                                                                           }).then(function (result) {
                                                                                if (result.value) {
                                                                                     Swal.fire({
                                                                                          title: 'Deleted!',
                                                                                          text: 'Your file has been deleted.',
                                                                                          icon: 'success',
                                                                                          confirmButtonClass: 'btn btn-primary w-xs mt-2',
                                                                                          buttonsStyling: false
                                                                                     })
                                                                                } else if (
                                                                                     // Read more about handling dismissals
                                                                                     result.dismiss === Swal.DismissReason.cancel
                                                                                ) {
                                                                                     Swal.fire({
                                                                                          title: 'Cancelled',
                                                                                          text: 'Your imaginary file is safe :)',
                                                                                          icon: 'error',
                                                                                          confirmButtonClass: 'btn btn-primary mt-2',
                                                                                          buttonsStyling: false
                                                                                     })
                                                                                }
                                                                           });
                                                                      });
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
                                             <a href="#basic" class="nav-link">Basic</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#title" class="nav-link">A Title with a Text Under</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#message" class="nav-link">Message</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#longcontent" class="nav-link">long content Images Message</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#parameter" class="nav-link">Parameter</a>
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

     <!-- SweetAlert Demo js -->
     <script src="/js/components/extended-sweetalert.js"></script>

</body>

</html>