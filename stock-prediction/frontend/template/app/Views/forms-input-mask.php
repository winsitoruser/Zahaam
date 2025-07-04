<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Input Mask")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Input Mask")) ?>
          <?= $this->include('partials/main-nav') ?>

          <!-- ==================================================== -->
          <!-- Start right Content here -->
          <!-- ==================================================== -->
          <div class="page-content">

               <!-- Start Container Fluid -->
               <div class="container">

                    <div class="row">
                         <div class="col-xl-12">
                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title anchor" id="overview">
                                             Overview <a class="btn btn-sm btn-outline-success rounded-2 float-end" href="https://github.com/RobinHerbots/Inputmask" target="_blank"> Official Website </a>
                                        </h5>
                                        <p class="text-muted mb-3">Inputmask is a javascript library that creates an input mask.</p>

                                        <h5 class="mt-2">Usage</h5>
                                        <p class="mb-0">Input Masks's Javascript files are bundled in the <code>vendor.js</code> and globally included in all pages.</p>
                                   </div><!-- end card-body -->
                              </div><!-- end card -->

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title anchor" id="default">
                                             Input Masks<a class="anchor-link" href="#default">#</a>
                                        </h5>
                                        <p class="text-muted">A Java-Script Plugin to make masks on form fields and HTML elements.</p>
                                        <div>
                                             <!-- Input masks -->
                                             <div class="row">
                                                  <div class="col-md-6">
                                                       <form action="#">
                                                            <div class="mb-3">
                                                                 <label class="form-label">Date</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="00/00/0000">
                                                                 <span class="fs-13 text-muted">e.g "DD/MM/YYYY"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="00/00/0000"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">Hour</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="00:00:00">
                                                                 <span class="fs-13 text-muted">e.g "HH:MM:SS"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="00:00:00"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">Date & Hour</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="00/00/0000 00:00:00">
                                                                 <span class="fs-13 text-muted">e.g "DD/MM/YYYY HH:MM:SS"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="00/00/0000 00:00:00"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">ZIP Code</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="00000-000">
                                                                 <span class="fs-13 text-muted">e.g "xxxxx-xxx"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="00000-000"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">Crazy Zip Code</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="0-00-00-00">
                                                                 <span class="fs-13 text-muted">e.g "x-xx-xx-xx"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="0-00-00-00"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">Money</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="000.000.000.000.000,00" data-reverse="true">
                                                                 <span class="fs-13 text-muted">e.g "Your money"</span>
                                                                 <p class="mt-1">Add attribute <code>data-mask-format="000.000.000.000.000,00" data-reverse="true"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">Money 2</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="#.##0,00" data-reverse="true">
                                                                 <span class="fs-13 text-muted">e.g "#.##0,00"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="#.##0,00" data-reverse="true"</code></p>
                                                            </div>

                                                       </form>
                                                  </div> <!-- end col -->

                                                  <div class="col-md-6">
                                                       <form action="#">
                                                            <div class="mb-3">
                                                                 <label class="form-label">Telephone</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="0000-0000">
                                                                 <span class="fs-13 text-muted">e.g "xxxx-xxxx"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="0000-0000"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">Telephone with Code Area</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="(00) 0000-0000">
                                                                 <span class="fs-13 text-muted">e.g "(xx) xxxx-xxxx"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="(00) 0000-0000"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">US Telephone</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="(000) 000-0000">
                                                                 <span class="fs-13 text-muted">e.g "(xxx) xxx-xxxx"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="(000) 000-0000"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">São Paulo Celphones</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="(00) 00000-0000">
                                                                 <span class="fs-13 text-muted">e.g "(xx) xxxxx-xxxx"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="(00) 00000-0000"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">CPF</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="000.000.000-00" data-reverse="true">
                                                                 <span class="fs-13 text-muted">e.g "xxx.xxx.xxxx-xx"</span>
                                                                 <p class="mt-1">Add attribute <code>data-mask-format="000.000.000-00" data-reverse="true"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">CNPJ</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="00.000.000/0000-00" data-reverse="true">
                                                                 <span class="fs-13 text-muted">e.g "xx.xxx.xxx/xxxx-xx"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="00.000.000/0000-00" data-reverse="true"</code></p>
                                                            </div>
                                                            <div class="mb-3">
                                                                 <label class="form-label">IP Address</label>
                                                                 <input type="text" class="form-control" data-toggle="input-mask" data-mask-format="099.099.099.099" data-reverse="true">
                                                                 <span class="fs-13 text-muted">e.g "xxx.xxx.xxx.xxx"</span>
                                                                 <p class="mt-1">Add attribute <code>data-toggle="input-mask" data-mask-format="099.099.099.099" data-reverse="true"</code></p>
                                                            </div>
                                                       </form>
                                                  </div> <!-- end col -->
                                             </div>
                                        </div>

                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                                      <code>
                                                                           &lt;!-- Input masks --&gt;
                                                                           &lt;form action=&quot;#&quot;&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;Date&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;00/00/0000&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;DD/MM/YYYY&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;Hour&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;00:00:00&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;HH:MM:SS&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;Date &amp; Hour&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;00/00/0000 00:00:00&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;DD/MM/YYYY HH:MM:SS&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;ZIP Code&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;00000-000&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;xxxxx-xxx&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;Crazy Zip Code&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;0-00-00-00&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;x-xx-xx-xx&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;Money&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;000.000.000.000.000,00&quot; data-reverse=&quot;true&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;Your money&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;Money 2&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;#.##0,00&quot; data-reverse=&quot;true&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;#.##0,00&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
     
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;Telephone&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;0000-0000&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;xxxx-xxxx&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;Telephone with Code Area&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;(00) 0000-0000&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;(xx) xxxx-xxxx&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;US Telephone&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;(000) 000-0000&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;(xxx) xxx-xxxx&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;S&atilde;o Paulo Celphones&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;(00) 00000-0000&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;(xx) xxxxx-xxxx&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;CPF&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;000.000.000-00&quot; data-reverse=&quot;true&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;xxx.xxx.xxxx-xx&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;CNPJ&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;00.000.000/0000-00&quot; data-reverse=&quot;true&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;xx.xxx.xxx/xxxx-xx&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                     &lt;label class=&quot;form-label&quot;&gt;IP Address&lt;/label&gt;
                                                                                     &lt;input type=&quot;text&quot; class=&quot;form-control&quot; data-toggle=&quot;input-mask&quot; data-mask-format=&quot;099.099.099.099&quot; data-reverse=&quot;true&quot;&gt;
                                                                                     &lt;span class=&quot;fs-13 text-muted&quot;&gt;e.g &quot;xxx.xxx.xxx.xxx&quot;&lt;/span&gt;
                                                                                &lt;/div&gt;
                                                                           &lt;/form&gt;
                                                                      </code>
                                                                 </pre>
                                             </div>
                                        </div>
                                   </div><!-- end card body -->
                              </div> <!-- end card -->
                         </div> <!-- end col -->
                    </div> <!-- end row -->
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