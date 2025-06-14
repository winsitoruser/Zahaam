<!DOCTYPE html>
<html lang="en">

<head>
     <?php echo view("partials/title-meta", array("title" => "Form Select")) ?>

     <?= $this->include("partials/head-css") ?>
</head>

<body>

     <!-- START Wrapper -->
     <div class="wrapper">

          <?php echo view("partials/topbar", array("title" => "Form Select")) ?>
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
                                             Overview <a class="btn btn-sm btn-outline-success rounded-2 float-end" href="https://github.com/Choices-js/Choices" target="_blank"> Official Website </a>
                                        </h5>
                                        <p class="text-muted mb-3">Choices.js is a A vanilla, lightweight, configurable select box/text input plugin. Similar to Select2 and Selectize but without the jQuery dependency.</p>

                                        <h5 class="mt-2">Usage</h5>
                                        <p class="mb-0">Choices.js's CSS and Javascript files are bundled in the <code>vender.min.css</code> and <code>vendor.js</code> and globally included in all pages.</p>
                                   </div><!-- end card-body -->
                              </div><!-- end card -->

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="basic">
                                             Basic Example <a class="anchor-link" href="#basic">#</a>
                                        </h5>
                                        <p class="text-muted">Set <code>data-choices</code> attribute to set a default single select.</p>
                                        <div class="w-50 mb-3">
                                             <select class="form-control" data-choices name="choices-single-default" id="choices-single-default">
                                                  <option value="">This is a placeholder</option>
                                                  <option value="Choice 1">Choice 1</option>
                                                  <option value="Choice 2">Choice 2</option>
                                                  <option value="Choice 3">Choice 3</option>
                                             </select>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;select class=&quot;form-control&quot; data-choices name=&quot;choices-single-default&quot; id=&quot;choices-single-default&quot;&gt;
                                                                 &lt;option value=&quot;&quot;&gt;This is a placeholder&lt;/option&gt;
                                                                 &lt;option value=&quot;Choice 1&quot;&gt;Choice 1&lt;/option&gt;
                                                                 &lt;option value=&quot;Choice 2&quot;&gt;Choice 2&lt;/option&gt;
                                                                 &lt;option value=&quot;Choice 3&quot;&gt;Choice 3&lt;/option&gt;
                                                            &lt;/select&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="options-group">
                                             Option Groups Example <a class="anchor-link" href="#options-group">#</a>
                                        </h5>
                                        <p class="text-muted">Set <code>data-choices data-choices-groups</code> attribute to set option
                                             group</p>

                                        <div class="w-50 mb-3">
                                             <label for="choices-single-groups" class="form-label text-muted">Option Groups</label>
                                             <select class="form-control" id="choices-single-groups" data-choices data-choices-groups data-placeholder="Select City" name="choices-single-groups">
                                                  <option value="">Choose a city</option>
                                                  <optgroup label="UK">
                                                       <option value="London">London</option>
                                                       <option value="Manchester">Manchester</option>
                                                       <option value="Liverpool">Liverpool</option>
                                                  </optgroup>
                                                  <optgroup label="FR">
                                                       <option value="Paris">Paris</option>
                                                       <option value="Lyon">Lyon</option>
                                                       <option value="Marseille">Marseille</option>
                                                  </optgroup>
                                                  <optgroup label="DE" disabled>
                                                       <option value="Hamburg">Hamburg</option>
                                                       <option value="Munich">Munich</option>
                                                       <option value="Berlin">Berlin</option>
                                                  </optgroup>
                                                  <optgroup label="US">
                                                       <option value="New York">New York</option>
                                                       <option value="Washington" disabled>Washington</option>
                                                       <option value="Michigan">Michigan</option>
                                                  </optgroup>
                                                  <optgroup label="SP">
                                                       <option value="Madrid">Madrid</option>
                                                       <option value="Barcelona">Barcelona</option>
                                                       <option value="Malaga">Malaga</option>
                                                  </optgroup>
                                                  <optgroup label="CA">
                                                       <option value="Montreal">Montreal</option>
                                                       <option value="Toronto">Toronto</option>
                                                       <option value="Vancouver">Vancouver</option>
                                                  </optgroup>
                                             </select>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;label for=&quot;choices-single-groups&quot; class=&quot;form-label text-muted&quot;&gt;Option Groups&lt;/label&gt;
                                                            &lt;select class=&quot;form-control&quot; id=&quot;choices-single-groups&quot; data-choices data-choices-groups data-placeholder=&quot;Select City&quot; name=&quot;choices-single-groups&quot;&gt;
                                                                 &lt;option value=&quot;&quot;&gt;Choose a city&lt;/option&gt;
                                                                 &lt;optgroup label=&quot;UK&quot;&gt;
                                                                      &lt;option value=&quot;London&quot;&gt;London&lt;/option&gt;
                                                                      &lt;option value=&quot;Manchester&quot;&gt;Manchester&lt;/option&gt;
                                                                      &lt;option value=&quot;Liverpool&quot;&gt;Liverpool&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                                 &lt;optgroup label=&quot;FR&quot;&gt;
                                                                      &lt;option value=&quot;Paris&quot;&gt;Paris&lt;/option&gt;
                                                                      &lt;option value=&quot;Lyon&quot;&gt;Lyon&lt;/option&gt;
                                                                      &lt;option value=&quot;Marseille&quot;&gt;Marseille&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                                 &lt;optgroup label=&quot;DE&quot; disabled&gt;
                                                                      &lt;option value=&quot;Hamburg&quot;&gt;Hamburg&lt;/option&gt;
                                                                      &lt;option value=&quot;Munich&quot;&gt;Munich&lt;/option&gt;
                                                                      &lt;option value=&quot;Berlin&quot;&gt;Berlin&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                                 &lt;optgroup label=&quot;US&quot;&gt;
                                                                      &lt;option value=&quot;New York&quot;&gt;New York&lt;/option&gt;
                                                                      &lt;option value=&quot;Washington&quot; disabled&gt;Washington&lt;/option&gt;
                                                                      &lt;option value=&quot;Michigan&quot;&gt;Michigan&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                                 &lt;optgroup label=&quot;SP&quot;&gt;
                                                                      &lt;option value=&quot;Madrid&quot;&gt;Madrid&lt;/option&gt;
                                                                      &lt;option value=&quot;Barcelona&quot;&gt;Barcelona&lt;/option&gt;
                                                                      &lt;option value=&quot;Malaga&quot;&gt;Malaga&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                                 &lt;optgroup label=&quot;CA&quot;&gt;
                                                                      &lt;option value=&quot;Montreal&quot;&gt;Montreal&lt;/option&gt;
                                                                      &lt;option value=&quot;Toronto&quot;&gt;Toronto&lt;/option&gt;
                                                                      &lt;option value=&quot;Vancouver&quot;&gt;Vancouver&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                            &lt;/select&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="options-add-no-search">
                                             Options added via config with no search <a class="anchor-link" href="#options-add-no-search">#</a>
                                        </h5>
                                        <p class="text-muted">Set
                                             <code>data-choices data-choices-search-false data-choices-removeItem</code>
                                        </p>

                                        <div class="w-50 mb-3">
                                             <select class="form-control" id="choices-single-no-search" name="choices-single-no-search" data-choices data-choices-search-false data-choices-removeItem>
                                                  <option value="Zero">Zero</option>
                                                  <option value="One">One</option>
                                                  <option value="Two">Two</option>
                                                  <option value="Three">Three</option>
                                                  <option value="Four">Four</option>
                                                  <option value="Five">Five</option>
                                                  <option value="Six">Six</option>
                                             </select>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;select class=&quot;form-control&quot; id=&quot;choices-single-no-search&quot; name=&quot;choices-single-no-search&quot; data-choices data-choices-search-false data-choices-removeItem&gt;
                                                                 &lt;option value=&quot;Zero&quot;&gt;Zero&lt;/option&gt;
                                                                 &lt;option value=&quot;One&quot;&gt;One&lt;/option&gt;
                                                                 &lt;option value=&quot;Two&quot;&gt;Two&lt;/option&gt;
                                                                 &lt;option value=&quot;Three&quot;&gt;Three&lt;/option&gt;
                                                                 &lt;option value=&quot;Four&quot;&gt;Four&lt;/option&gt;
                                                                 &lt;option value=&quot;Five&quot;&gt;Five&lt;/option&gt;
                                                                 &lt;option value=&quot;Six&quot;&gt;Six&lt;/option&gt;
                                                            &lt;/select&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="options-add-no-sorting">
                                             Options added via config with no sorting <a class="anchor-link" href="#options-add-no-sorting">#</a>
                                        </h5>
                                        <p class="text-muted">Set <code>data-choices data-choices-sorting-false</code> attribute.</p>
                                        <div class="w-50 mb-3">
                                             <select class="form-control" id="choices-single-no-sorting" name="choices-single-no-sorting" data-choices data-choices-sorting-false>
                                                  <option value="Madrid">Madrid</option>
                                                  <option value="Toronto">Toronto</option>
                                                  <option value="Vancouver">Vancouver</option>
                                                  <option value="London">London</option>
                                                  <option value="Manchester">Manchester</option>
                                                  <option value="Liverpool">Liverpool</option>
                                                  <option value="Paris">Paris</option>
                                                  <option value="Malaga">Malaga</option>
                                                  <option value="Washington" disabled>Washington</option>
                                                  <option value="Lyon">Lyon</option>
                                                  <option value="Marseille">Marseille</option>
                                                  <option value="Hamburg">Hamburg</option>
                                                  <option value="Munich">Munich</option>
                                                  <option value="Barcelona">Barcelona</option>
                                                  <option value="Berlin">Berlin</option>
                                                  <option value="Montreal">Montreal</option>
                                                  <option value="New York">New York</option>
                                                  <option value="Michigan">Michigan</option>
                                             </select>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;select class=&quot;form-control&quot; id=&quot;choices-single-no-sorting&quot; name=&quot;choices-single-no-sorting&quot; data-choices data-choices-sorting-false&gt;
                                                            &lt;option value=&quot;Madrid&quot;&gt;Madrid&lt;/option&gt;
                                                            &lt;option value=&quot;Toronto&quot;&gt;Toronto&lt;/option&gt;
                                                            &lt;option value=&quot;Vancouver&quot;&gt;Vancouver&lt;/option&gt;
                                                            &lt;option value=&quot;London&quot;&gt;London&lt;/option&gt;
                                                            &lt;option value=&quot;Manchester&quot;&gt;Manchester&lt;/option&gt;
                                                            &lt;option value=&quot;Liverpool&quot;&gt;Liverpool&lt;/option&gt;
                                                            &lt;option value=&quot;Paris&quot;&gt;Paris&lt;/option&gt;
                                                            &lt;option value=&quot;Malaga&quot;&gt;Malaga&lt;/option&gt;
                                                            &lt;option value=&quot;Washington&quot; disabled&gt;Washington&lt;/option&gt;
                                                            &lt;option value=&quot;Lyon&quot;&gt;Lyon&lt;/option&gt;
                                                            &lt;option value=&quot;Marseille&quot;&gt;Marseille&lt;/option&gt;
                                                            &lt;option value=&quot;Hamburg&quot;&gt;Hamburg&lt;/option&gt;
                                                            &lt;option value=&quot;Munich&quot;&gt;Munich&lt;/option&gt;
                                                            &lt;option value=&quot;Barcelona&quot;&gt;Barcelona&lt;/option&gt;
                                                            &lt;option value=&quot;Berlin&quot;&gt;Berlin&lt;/option&gt;
                                                            &lt;option value=&quot;Montreal&quot;&gt;Montreal&lt;/option&gt;
                                                            &lt;option value=&quot;New York&quot;&gt;New York&lt;/option&gt;
                                                            &lt;option value=&quot;Michigan&quot;&gt;Michigan&lt;/option&gt;
                                                       &lt;/select&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="multiple-select">
                                             Multiple select input <a class="anchor-link" href="#multiple-select">#</a>
                                        </h5>
                                        <p class="text-muted">Set <code>data-choices multiple</code> attribute.</p>
                                        <div class="w-50 mb-3">
                                             <select class="form-control" id="choices-multiple-default" data-choices name="choices-multiple-default" multiple>
                                                  <option value="Choice 1" selected>Choice 1</option>
                                                  <option value="Choice 2">Choice 2</option>
                                                  <option value="Choice 3">Choice 3</option>
                                                  <option value="Choice 4" disabled>Choice 4</option>
                                             </select>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;select class=&quot;form-control&quot; id=&quot;choices-multiple-default&quot; data-choices name=&quot;choices-multiple-default&quot; multiple&gt;
                                                            &lt;option value=&quot;Choice 1&quot; selected&gt;Choice 1&lt;/option&gt;
                                                            &lt;option value=&quot;Choice 2&quot;&gt;Choice 2&lt;/option&gt;
                                                            &lt;option value=&quot;Choice 3&quot;&gt;Choice 3&lt;/option&gt;
                                                            &lt;option value=&quot;Choice 4&quot; disabled&gt;Choice 4&lt;/option&gt;
                                                       &lt;/select&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="multiple-select-remove">
                                             Multiple select With remove button input <a class="anchor-link" href="#multiple-select-remove">#</a>
                                        </h5>
                                        <p class="text-muted">Set <code>data-choices data-choices-removeItem multiple</code> attribute.</p>
                                        <div class="w-50 mb-3">
                                             <select class="form-control" id="choices-multiple-remove-button" data-choices data-choices-removeItem name="choices-multiple-remove-button" multiple>
                                                  <option value="Choice 1" selected>Choice 1</option>
                                                  <option value="Choice 2">Choice 2</option>
                                                  <option value="Choice 3">Choice 3</option>
                                                  <option value="Choice 4">Choice 4</option>
                                             </select>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;select class=&quot;form-control&quot; id=&quot;choices-multiple-remove-button&quot; data-choices data-choices-removeItem name=&quot;choices-multiple-remove-button&quot; multiple&gt;
                                                                 &lt;option value=&quot;Choice 1&quot; selected&gt;Choice 1&lt;/option&gt;
                                                                 &lt;option value=&quot;Choice 2&quot;&gt;Choice 2&lt;/option&gt;
                                                                 &lt;option value=&quot;Choice 3&quot;&gt;Choice 3&lt;/option&gt;
                                                                 &lt;option value=&quot;Choice 4&quot;&gt;Choice 4&lt;/option&gt;
                                                            &lt;/select&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>

                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="multiple-groups">
                                             Multiple select With Option groups <a class="anchor-link" href="#default">#</a>
                                        </h5>
                                        <p class="text-muted">Set
                                             <code>data-choices data-choices-multiple-groups="true" multiple</code>
                                             attribute.
                                        </p>

                                        <div class="w-50 mb-3">
                                             <label for="choices-multiple-groups" class="form-label text-muted">Option groups</label>
                                             <select class="form-control" id="choices-multiple-groups" name="choices-multiple-groups" data-choices data-choices-multiple-groups="true" multiple>
                                                  <option value="">Choose a city</option>
                                                  <optgroup label="UK">
                                                       <option value="London">London</option>
                                                       <option value="Manchester">Manchester</option>
                                                       <option value="Liverpool">Liverpool</option>
                                                  </optgroup>
                                                  <optgroup label="FR">
                                                       <option value="Paris">Paris</option>
                                                       <option value="Lyon">Lyon</option>
                                                       <option value="Marseille">Marseille</option>
                                                  </optgroup>
                                                  <optgroup label="DE" disabled>
                                                       <option value="Hamburg">Hamburg</option>
                                                       <option value="Munich">Munich</option>
                                                       <option value="Berlin">Berlin</option>
                                                  </optgroup>
                                                  <optgroup label="US">
                                                       <option value="New York">New York</option>
                                                       <option value="Washington" disabled>Washington</option>
                                                       <option value="Michigan">Michigan</option>
                                                  </optgroup>
                                                  <optgroup label="SP">
                                                       <option value="Madrid">Madrid</option>
                                                       <option value="Barcelona">Barcelona</option>
                                                       <option value="Malaga">Malaga</option>
                                                  </optgroup>
                                                  <optgroup label="CA">
                                                       <option value="Montreal">Montreal</option>
                                                       <option value="Toronto">Toronto</option>
                                                       <option value="Vancouver">Vancouver</option>
                                                  </optgroup>
                                             </select>
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;label for=&quot;choices-multiple-groups&quot; class=&quot;form-label text-muted&quot;&gt;Option groups&lt;/label&gt;
                                                            &lt;select class=&quot;form-control&quot; id=&quot;choices-multiple-groups&quot; name=&quot;choices-multiple-groups&quot; data-choices data-choices-multiple-groups=&quot;true&quot; multiple&gt;
                                                                 &lt;option value=&quot;&quot;&gt;Choose a city&lt;/option&gt;
                                                                 &lt;optgroup label=&quot;UK&quot;&gt;
                                                                      &lt;option value=&quot;London&quot;&gt;London&lt;/option&gt;
                                                                      &lt;option value=&quot;Manchester&quot;&gt;Manchester&lt;/option&gt;
                                                                      &lt;option value=&quot;Liverpool&quot;&gt;Liverpool&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                                 &lt;optgroup label=&quot;FR&quot;&gt;
                                                                      &lt;option value=&quot;Paris&quot;&gt;Paris&lt;/option&gt;
                                                                      &lt;option value=&quot;Lyon&quot;&gt;Lyon&lt;/option&gt;
                                                                      &lt;option value=&quot;Marseille&quot;&gt;Marseille&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                                 &lt;optgroup label=&quot;DE&quot; disabled&gt;
                                                                      &lt;option value=&quot;Hamburg&quot;&gt;Hamburg&lt;/option&gt;
                                                                      &lt;option value=&quot;Munich&quot;&gt;Munich&lt;/option&gt;
                                                                      &lt;option value=&quot;Berlin&quot;&gt;Berlin&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                                 &lt;optgroup label=&quot;US&quot;&gt;
                                                                      &lt;option value=&quot;New York&quot;&gt;New York&lt;/option&gt;
                                                                      &lt;option value=&quot;Washington&quot; disabled&gt;Washington&lt;/option&gt;
                                                                      &lt;option value=&quot;Michigan&quot;&gt;Michigan&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                                 &lt;optgroup label=&quot;SP&quot;&gt;
                                                                      &lt;option value=&quot;Madrid&quot;&gt;Madrid&lt;/option&gt;
                                                                      &lt;option value=&quot;Barcelona&quot;&gt;Barcelona&lt;/option&gt;
                                                                      &lt;option value=&quot;Malaga&quot;&gt;Malaga&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                                 &lt;optgroup label=&quot;CA&quot;&gt;
                                                                      &lt;option value=&quot;Montreal&quot;&gt;Montreal&lt;/option&gt;
                                                                      &lt;option value=&quot;Toronto&quot;&gt;Toronto&lt;/option&gt;
                                                                      &lt;option value=&quot;Vancouver&quot;&gt;Vancouver&lt;/option&gt;
                                                                 &lt;/optgroup&gt;
                                                            &lt;/select&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="text-input">
                                             Text inputs <a class="anchor-link" href="#text-input">#</a>
                                        </h5>
                                        <p class="text-muted">Set <code>data-choices data-choices-limit="Required Limit" data-choices-removeItem</code> attribute.</p>
                                        <div class="w-50 mb-3">
                                             <label for="choices-text-remove-button" class="form-label text-muted">Set limit values with remove button</label>
                                             <input class="form-control" id="choices-text-remove-button" data-choices data-choices-limit="3" data-choices-removeItem type="text" value="Task-1" />
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;label for=&quot;choices-text-remove-button&quot; class=&quot;form-label text-muted&quot;&gt;Set limit values with remove button&lt;/label&gt;
                                                            &lt;input class=&quot;form-control&quot; id=&quot;choices-text-remove-button&quot; data-choices data-choices-limit=&quot;3&quot; data-choices-removeItem type=&quot;text&quot; value=&quot;Task-1&quot; /&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="unique-values">
                                             Text inputs in Unique values only, no pasting <a class="anchor-link" href="#unique-values">#</a>
                                        </h5>
                                        <p class="text-muted">Set <code>data-choices data-choices-text-unique-true</code> attribute.</p>
                                        <div class="w-50 mb-3">
                                             <label for="choices-text-unique-values" class="form-label text-muted">Unique values only, no pasting</label>
                                             <input class="form-control" id="choices-text-unique-values" data-choices data-choices-text-unique-true type="text" value="Project-A, Project-B" />
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;label for=&quot;choices-text-unique-values&quot; class=&quot;form-label text-muted&quot;&gt;Unique values only, no pasting&lt;/label&gt;
                                                            &lt;input class=&quot;form-control&quot; id=&quot;choices-text-unique-values&quot; data-choices data-choices-text-unique-true type=&quot;text&quot; value=&quot;Project-A, Project-B&quot; /&gt;
                                                       </code>
                                                  </pre>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="card">
                                   <div class="card-body">
                                        <h5 class="card-title mb-1 anchor" id="disabled">
                                             Disabled Text Inputs <a class="anchor-link" href="#disabled">#</a>
                                        </h5>
                                        <p class="text-muted">Set <code>data-choices data-choices-text-disabled-true</code> attribute.</p>
                                        <div class="w-50 mb-3">
                                             <label for="choices-text-disabled" class="form-label text-muted">Disabled</label>
                                             <input class="form-control" id="choices-text-disabled" data-choices data-choices-text-disabled-true type="text" value="josh@joshuajohnson.co.uk, joe@bloggs.co.uk" />
                                        </div>
                                        <div class="highlight">
                                             <div class="highlight-toolbar">
                                                  <small class="text-uppercase">html</small>
                                                  <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                             </div>
                                             <div class="code" data-simplebar>
                                                  <pre class="language-html">
                                                       <code>
                                                            &lt;label for=&quot;choices-text-disabled&quot; class=&quot;form-label text-muted&quot;&gt;Disabled&lt;/label&gt;
                                                            &lt;input class=&quot;form-control&quot; id=&quot;choices-text-disabled&quot; data-choices data-choices-text-disabled-true type=&quot;text&quot; value=&quot;josh@joshuajohnson.co.uk, joe@bloggs.co.uk&quot; /&gt;
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
                                             <a href="#overview" class="nav-link">Overview</a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#basic" class="nav-link">Basic Example </a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#options-group" class="nav-link">Option Groups Example </a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#options-add-no-search" class="nav-link">Options added via config with no search </a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#options-add-no-sorting" class="nav-link">Options added via config with no sorting </a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#multiple-select" class="nav-link">Multiple select input </a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#multiple-select-remove" class="nav-link">Multiple select With remove button input </a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#multiple-groups" class="nav-link">Multiple select With Option groups </a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#text-input" class="nav-link">Text inputs </a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#unique-values" class="nav-link">Text inputs in Unique values only, no pasting </a>
                                        </li>
                                        <li class="nav-item">
                                             <a href="#disabled" class="nav-link">Disabled Text Inputs </a>
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