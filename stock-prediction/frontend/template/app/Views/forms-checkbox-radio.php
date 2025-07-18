<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Checkbox & Radio")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Checkbox & Radio")) ?>
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
                                        <h5 class="card-title mb-1 anchor" id="checkbox-basic">
                                             Checkbox<a class="anchor-link" href="#checkbox-basic">#</a>
                                        </h5>
                                        <p class="text-muted">
                                             Each checkbox and radio <code>&lt;input&gt;</code> and <code>&lt;label&gt;</code> pairing is wrapped in a <code>&lt;div&gt;</code> to create our custom control. Structurally, this is the same approach as our default <code>.form-check</code>.
                                        </p>

                                        <div class="mb-3">
                                             <div class="form-check">
                                                  <input type="checkbox" class="form-check-input" id="customCheck1">
                                                  <label class="form-check-label" for="customCheck1">Check this custom checkbox</label>
                                             </div>
                                             <div class="form-check">
                                                  <input type="checkbox" class="form-check-input" id="customCheck2">
                                                  <label class="form-check-label" for="customCheck2">Check this custom checkbox</label>
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
                                                            &lt;div class=&quot;form-check&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheck1&quot;&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheck1&quot;&gt;Check this custom checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheck2&quot;&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheck2&quot;&gt;Check this custom checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor mb-3" id="inline-checkbox">
                                             Inline Checkbox<a class="anchor-link" href="#inline-checkbox">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div class="mt-2">
                                                  <div class="form-check form-check-inline">
                                                       <input type="checkbox" class="form-check-input" id="customCheck3">
                                                       <label class="form-check-label" for="customCheck3">Check this custom checkbox</label>
                                                  </div>
                                                  <div class="form-check form-check-inline">
                                                       <input type="checkbox" class="form-check-input" id="customCheck4">
                                                       <label class="form-check-label" for="customCheck4">Check this custom checkbox</label>
                                                  </div>
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
                                                            &lt;div class=&quot;form-check form-check-inline&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheck3&quot;&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheck3&quot;&gt;Check this custom checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-check-inline&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheck4&quot;&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheck4&quot;&gt;Check this custom checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor mb-3" id="disabled-checkbox">
                                             Disabled Checkbox<a class="anchor-link" href="#disabled-checkbox">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div class="form-check form-check-inline">
                                                  <input type="checkbox" class="form-check-input" id="customCheck5" checked disabled>
                                                  <label class="form-check-label" for="customCheck5">Check this custom checkbox</label>
                                             </div>
                                             <div class="form-check form-check-inline">
                                                  <input type="checkbox" class="form-check-input" id="customCheck6" disabled>
                                                  <label class="form-check-label" for="customCheck6">Check this custom checkbox</label>
                                             </div>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <h6 class="text-muted text-uppercase">html</h6>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;div class=&quot;form-check form-check-inline&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheck5&quot; checked disabled&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheck5&quot;&gt;Check this custom checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-check-inline&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheck6&quot; disabled&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheck6&quot;&gt;Check this custom checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->


                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor mb-3" id="colors-checkbox">
                                             Colors Checkbox<a class="anchor-link" href="#colors-checkbox">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <!-- Colors-->
                                             <div class="form-check mb-2">
                                                  <input type="checkbox" class="form-check-input" id="customCheckcolor1" checked>
                                                  <label class="form-check-label" for="customCheckcolor1">Default Checkbox</label>
                                             </div>
                                             <div class="form-check form-checkbox-success mb-2">
                                                  <input type="checkbox" class="form-check-input" id="customCheckcolor2" checked>
                                                  <label class="form-check-label" for="customCheckcolor2">Success Checkbox</label>
                                             </div>
                                             <div class="form-check form-checkbox-info mb-2">
                                                  <input type="checkbox" class="form-check-input" id="customCheckcolor3" checked>
                                                  <label class="form-check-label" for="customCheckcolor3">Info Checkbox</label>
                                             </div>
                                             <div class="form-check form-checkbox-secondary mb-2">
                                                  <input type="checkbox" class="form-check-input" id="customCheckcolor6" checked>
                                                  <label class="form-check-label" for="customCheckcolor6">Secondary Checkbox</label>
                                             </div>
                                             <div class="form-check form-checkbox-warning mb-2">
                                                  <input type="checkbox" class="form-check-input" id="customCheckcolor4" checked>
                                                  <label class="form-check-label" for="customCheckcolor4">Warning Checkbox</label>
                                             </div>
                                             <div class="form-check form-checkbox-danger mb-2">
                                                  <input type="checkbox" class="form-check-input" id="customCheckcolor5" checked>
                                                  <label class="form-check-label" for="customCheckcolor5">Danger Checkbox</label>
                                             </div>
                                             <div class="form-check form-checkbox-dark">
                                                  <input type="checkbox" class="form-check-input" id="customCheckcolor7" checked>
                                                  <label class="form-check-label" for="customCheckcolor7">Dark Checkbox</label>
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
                                                            &lt;div class=&quot;form-check mb-2&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheckcolor1&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheckcolor1&quot;&gt;Default Checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-checkbox-success mb-2&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheckcolor2&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheckcolor2&quot;&gt;Success Checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-checkbox-info mb-2&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheckcolor3&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheckcolor3&quot;&gt;Info Checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-checkbox-secondary mb-2&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheckcolor6&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheckcolor6&quot;&gt;Secondary Checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-checkbox-warning mb-2&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheckcolor4&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheckcolor4&quot;&gt;Warning Checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-checkbox-danger mb-2&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheckcolor5&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheckcolor5&quot;&gt;Danger Checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-checkbox-dark&quot;&gt;
                                                                 &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheckcolor7&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheckcolor7&quot;&gt;Dark Checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="radio-basic">
                                             Radio<a class="anchor-link" href="#radio-basic">#</a>
                                        </h5>
                                        <p class="text-muted font-14">
                                             Each Radio <code>&lt;input&gt;</code> and <code>&lt;label&gt;</code> pairing is wrapped in a <code>&lt;div&gt;</code> to create our custom control. Structurally, this is the same approach as our default <code>.form-check</code>.
                                        </p>

                                        <div class="mb-3">
                                             <div class="form-check">
                                                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                                  <label class="form-check-label" for="flexRadioDefault1">
                                                       Default radio
                                                  </label>
                                             </div>
                                             <div class="form-check">
                                                  <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                                                  <label class="form-check-label" for="flexRadioDefault2">
                                                       Default checked radio
                                                  </label>
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
                                                            &lt;div class=&quot;form-check&quot;&gt;
                                                                 &lt;input class=&quot;form-check-input&quot; type=&quot;radio&quot; name=&quot;flexRadioDefault&quot; id=&quot;flexRadioDefault1&quot;&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;flexRadioDefault1&quot;&gt;
                                                                      Default radio
                                                                 &lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check&quot;&gt;
                                                                 &lt;input class=&quot;form-check-input&quot; type=&quot;radio&quot; name=&quot;flexRadioDefault&quot; id=&quot;flexRadioDefault2&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;flexRadioDefault2&quot;&gt;
                                                                      Default checked radio
                                                                 &lt;/label&gt;
                                                            &lt;/div&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor mb-3" id="inline-radio">
                                             Inline Radio<a class="anchor-link" href="#inline-radio">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div class="form-check form-check-inline">
                                                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                                  <label class="form-check-label" for="inlineRadio1">Check this custom checkbox</label>
                                             </div>
                                             <div class="form-check form-check-inline">
                                                  <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                                  <label class="form-check-label" for="inlineRadio2">Check this custom checkbox</label>
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
                                                            &lt;div class=&quot;form-check form-check-inline&quot;&gt;
                                                                 &lt;input class=&quot;form-check-input&quot; type=&quot;radio&quot; name=&quot;inlineRadioOptions&quot; id=&quot;inlineRadio1&quot; value=&quot;option1&quot;&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;inlineRadio1&quot;&gt;Check this custom checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-check-inline&quot;&gt;
                                                                 &lt;input class=&quot;form-check-input&quot; type=&quot;radio&quot; name=&quot;inlineRadioOptions&quot; id=&quot;inlineRadio2&quot; value=&quot;option2&quot;&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;inlineRadio2&quot;&gt;Check this custom checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor mb-3" id="disabled-radio">
                                             Disabled Radio<a class="anchor-link" href="#disabled-radio">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div class="form-check form-check-inline">
                                                  <input type="radio" class="form-check-input" id="customCheck5" checked disabled>
                                                  <label class="form-check-label" for="customCheck5">Check this custom checkbox</label>
                                             </div>
                                             <div class="form-check form-check-inline">
                                                  <input type="radio" class="form-check-input" id="customCheck6" disabled>
                                                  <label class="form-check-label" for="customCheck6">Check this custom checkbox</label>
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
                                                            &lt;div class=&quot;form-check form-check-inline&quot;&gt;
                                                                 &lt;input type=&quot;radio&quot; class=&quot;form-check-input&quot; id=&quot;customCheck5&quot; checked disabled&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheck5&quot;&gt;Check this custom checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-check-inline&quot;&gt;
                                                                 &lt;input type=&quot;radio&quot; class=&quot;form-check-input&quot; id=&quot;customCheck6&quot; disabled&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customCheck6&quot;&gt;Check this custom checkbox&lt;/label&gt;
                                                            &lt;/div&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor mb-3" id="colors-radio">
                                             Colors Radio<a class="anchor-link" href="#colors-radio">#</a>
                                        </h5>

                                        <div class="mb-3">
                                             <div class="form-check mb-2">
                                                  <input type="radio" id="customRadiocolor1" name="customRadiocolor1" class="form-check-input" checked>
                                                  <label class="form-check-label" for="customRadiocolor1">Default Radio</label>
                                             </div>
                                             <div class="form-check form-radio-success mb-2">
                                                  <input type="radio" id="customRadiocolor2" name="customRadiocolor2" class="form-check-input" checked>
                                                  <label class="form-check-label" for="customRadiocolor2">Success Radio</label>
                                             </div>
                                             <div class="form-check form-radio-info mb-2">
                                                  <input type="radio" id="customRadiocolor3" name="customRadiocolor3" class="form-check-input" checked>
                                                  <label class="form-check-label" for="customRadiocolor3">Info Radio</label>
                                             </div>
                                             <div class="form-check form-radio-secondary mb-2">
                                                  <input type="radio" id="customRadiocolor6" name="customRadiocolor6" class="form-check-input" checked>
                                                  <label class="form-check-label" for="customRadiocolor6">Secondary Radio</label>
                                             </div>
                                             <div class="form-check form-radio-warning mb-2">
                                                  <input type="radio" id="customRadiocolor4" name="customRadiocolor4" class="form-check-input" checked>
                                                  <label class="form-check-label" for="customRadiocolor4">Warning Radio</label>
                                             </div>
                                             <div class="form-check form-radio-danger mb-2">
                                                  <input type="radio" id="customRadiocolor5" name="customRadiocolor5" class="form-check-input" checked>
                                                  <label class="form-check-label" for="customRadiocolor5">Danger Radio</label>
                                             </div>
                                             <div class="form-check form-radio-dark">
                                                  <input type="radio" id="customRadiocolor7" name="customRadiocolor7" class="form-check-input" checked>
                                                  <label class="form-check-label" for="customRadiocolor7">Dark Radio</label>
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
                                                            &lt;div class=&quot;form-check mb-2&quot;&gt;
                                                                 &lt;input type=&quot;radio&quot; id=&quot;customRadiocolor1&quot; name=&quot;customRadiocolor1&quot; class=&quot;form-check-input&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customRadiocolor1&quot;&gt;Default Radio&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-radio-success mb-2&quot;&gt;
                                                                 &lt;input type=&quot;radio&quot; id=&quot;customRadiocolor2&quot; name=&quot;customRadiocolor2&quot; class=&quot;form-check-input&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customRadiocolor2&quot;&gt;Success Radio&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-radio-info mb-2&quot;&gt;
                                                                 &lt;input type=&quot;radio&quot; id=&quot;customRadiocolor3&quot; name=&quot;customRadiocolor3&quot; class=&quot;form-check-input&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customRadiocolor3&quot;&gt;Info Radio&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-radio-secondary mb-2&quot;&gt;
                                                                 &lt;input type=&quot;radio&quot; id=&quot;customRadiocolor6&quot; name=&quot;customRadiocolor6&quot; class=&quot;form-check-input&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customRadiocolor6&quot;&gt;Secondary Radio&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-radio-warning mb-2&quot;&gt;
                                                                 &lt;input type=&quot;radio&quot; id=&quot;customRadiocolor4&quot; name=&quot;customRadiocolor4&quot; class=&quot;form-check-input&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customRadiocolor4&quot;&gt;Warning Radio&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-radio-danger mb-2&quot;&gt;
                                                                 &lt;input type=&quot;radio&quot; id=&quot;customRadiocolor5&quot; name=&quot;customRadiocolor5&quot; class=&quot;form-check-input&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customRadiocolor5&quot;&gt;Danger Radio&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-radio-dark&quot;&gt;
                                                                 &lt;input type=&quot;radio&quot; id=&quot;customRadiocolor7&quot; name=&quot;customRadiocolor7&quot; class=&quot;form-check-input&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;customRadiocolor7&quot;&gt;Dark Radio&lt;/label&gt;
                                                            &lt;/div&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div> <!-- end card body -->
                              </div> <!-- end card -->


                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="switch-basic">
                                             Switch<a class="anchor-link" href="#switch-basic">#</a>
                                        </h5>
                                        <p class="text-muted">
                                             A switch has the markup of a custom checkbox but uses the <code>.form-switch</code> class to render a toggle switch. Switches also support the <code>disabled</code> attribute.
                                        </p>
                                        <div class="mb-3">
                                             <div class="form-check form-switch">
                                                  <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                  <label class="form-check-label" for="flexSwitchCheckDefault">Default switch checkbox input</label>
                                             </div>
                                             <div class="form-check form-switch">
                                                  <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                                                  <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch checkbox input</label>
                                             </div>
                                             <div class="form-check form-switch">
                                                  <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDisabled" disabled>
                                                  <label class="form-check-label" for="flexSwitchCheckDisabled">Disabled switch checkbox input</label>
                                             </div>
                                             <div class="form-check form-switch">
                                                  <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckCheckedDisabled" checked disabled>
                                                  <label class="form-check-label" for="flexSwitchCheckCheckedDisabled">Disabled checked switch checkbox input</label>
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
                                                            &lt;div class=&quot;form-check form-switch&quot;&gt;
                                                                 &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; role=&quot;switch&quot; id=&quot;flexSwitchCheckDefault&quot;&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;flexSwitchCheckDefault&quot;&gt;Default switch checkbox input&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-switch&quot;&gt;
                                                                 &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; role=&quot;switch&quot; id=&quot;flexSwitchCheckChecked&quot; checked&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;flexSwitchCheckChecked&quot;&gt;Checked switch checkbox input&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-switch&quot;&gt;
                                                                 &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; role=&quot;switch&quot; id=&quot;flexSwitchCheckDisabled&quot; disabled&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;flexSwitchCheckDisabled&quot;&gt;Disabled switch checkbox input&lt;/label&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;form-check form-switch&quot;&gt;
                                                                 &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; role=&quot;switch&quot; id=&quot;flexSwitchCheckCheckedDisabled&quot; checked disabled&gt;
                                                                 &lt;label class=&quot;form-check-label&quot; for=&quot;flexSwitchCheckCheckedDisabled&quot;&gt;Disabled checked switch checkbox input&lt;/label&gt;
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
                                   <ul class="nav bg-transparent flex-column">
                                        <li class="nav-item">
                                             <a href="#checkbox-basic" class="nav-link">Checkbox</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#inline-checkbox" class="nav-link">Inline Checkbox</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#disabled-checkbox" class="nav-link">Disabled Checkbox</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#colors-checkbox" class="nav-link">Colors Checkbox</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#radio-basic" class="nav-link">Radio</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#inline-radio" class="nav-link">Inline Radio</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#disabled-radio" class="nav-link">Disabled Radio</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#colors-radio" class="nav-link">Colors Radio</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#switch-basic" class="nav-link">Switch</a>
                                        </li>
                                   </ul>
                              </div><!-- end card -->
                         </div><!-- end col -->
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