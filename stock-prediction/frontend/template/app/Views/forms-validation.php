<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Form Validation")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Form Validation")) ?>
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
                                <h5 class="card-title mb-1 anchor" id="browser-defaults">
                                    Browser Default<a class="anchor-link" href="#default">#</a>
                                </h5>
                                <p class="text-muted">Depending on your browser and OS, you’ll see a slightly different style of feedback.</p>
                                <div class="mb-3">
                                    <form class="row g-3">
                                        <div class="col-md-4">
                                            <label for="validationDefault01" class="form-label">First name</label>
                                            <input type="text" class="form-control" id="validationDefault01" value="Mark" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="validationDefault02" class="form-label">Last name</label>
                                            <input type="text" class="form-control" id="validationDefault02" value="Otto" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="validationDefaultUsername" class="form-label">Username</label>
                                            <div class="input-group">
                                                <span class="input-group-text" id="inputGroupPrepend2">@</span>
                                                <input type="text" class="form-control" id="validationDefaultUsername" aria-describedby="inputGroupPrepend2" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="validationDefault03" class="form-label">City</label>
                                            <input type="text" class="form-control" id="validationDefault03" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="validationDefault04" class="form-label">State</label>
                                            <select class="form-select" id="validationDefault04" required>
                                                <option selected disabled value="">Choose...</option>
                                                <option>...</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="validationDefault05" class="form-label">Zip</label>
                                            <input type="text" class="form-control" id="validationDefault05" required>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                                                <label class="form-check-label" for="invalidCheck2">
                                                    Agree to terms and conditions
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Submit form</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code id="buttonBadge">
                                                    &lt;form class=&quot;row g-3&quot;&gt;
                                                        &lt;div class=&quot;col-md-4&quot;&gt;
                                                            &lt;label for=&quot;validationDefault01&quot; class=&quot;form-label&quot;&gt;First name&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationDefault01&quot; value=&quot;Mark&quot; required&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-4&quot;&gt;
                                                            &lt;label for=&quot;validationDefault02&quot; class=&quot;form-label&quot;&gt;Last name&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationDefault02&quot; value=&quot;Otto&quot; required&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-4&quot;&gt;
                                                            &lt;label for=&quot;validationDefaultUsername&quot; class=&quot;form-label&quot;&gt;Username&lt;/label&gt;
                                                            &lt;div class=&quot;input-group&quot;&gt;
                                                                &lt;span class=&quot;input-group-text&quot; id=&quot;inputGroupPrepend2&quot;&gt;@&lt;/span&gt;
                                                                &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationDefaultUsername&quot; aria-describedby=&quot;inputGroupPrepend2&quot; required&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-6&quot;&gt;
                                                            &lt;label for=&quot;validationDefault03&quot; class=&quot;form-label&quot;&gt;City&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationDefault03&quot; required&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-3&quot;&gt;
                                                            &lt;label for=&quot;validationDefault04&quot; class=&quot;form-label&quot;&gt;State&lt;/label&gt;
                                                            &lt;select class=&quot;form-select&quot; id=&quot;validationDefault04&quot; required&gt;
                                                                &lt;option selected disabled value=&quot;&quot;&gt;Choose...&lt;/option&gt;
                                                                &lt;option&gt;...&lt;/option&gt;
                                                            &lt;/select&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-3&quot;&gt;
                                                            &lt;label for=&quot;validationDefault05&quot; class=&quot;form-label&quot;&gt;Zip&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationDefault05&quot; required&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-12&quot;&gt;
                                                            &lt;div class=&quot;form-check&quot;&gt;
                                                                &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;invalidCheck2&quot; required&gt;
                                                                &lt;label class=&quot;form-check-label&quot; for=&quot;invalidCheck2&quot;&gt;
                                                                    Agree to terms and conditions
                                                                &lt;/label&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-12&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary&quot; type=&quot;submit&quot;&gt;Submit form&lt;/button&gt;
                                                        &lt;/div&gt;
                                                    &lt;/form&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="custom-styles">
                                    Custom styles<a class="anchor-link" href="#custom-styles">#</a>
                                </h5>
                                <p class="text-muted">For custom Bootstrap form validation messages, you’ll need to add the <code>novalidate</code> boolean attribute to your <code>&lt;form&gt;</code>. This disables the browser default feedback tooltips, but still provides access to the form validation APIs in JavaScript. When attempting to submit, you’ll see the <code>:invalid</code> and <code>:valid</code> styles applied to your form controls.</p>
                                <div class="mb-3">
                                    <form class="row g-3 needs-validation" novalidate>
                                        <div class="col-md-4">
                                            <label for="validationCustom01" class="form-label">First name</label>
                                            <input type="text" class="form-control" id="validationCustom01" value="Mark" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="validationCustom02" class="form-label">Last name</label>
                                            <input type="text" class="form-control" id="validationCustom02" value="Otto" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="validationCustomUsername" class="form-label">Username</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="text" class="form-control" id="validationCustomUsername" aria-describedby="inputGroupPrepend" required>
                                                <div class="invalid-feedback">
                                                    Please choose a username.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="validationCustom03" class="form-label">City</label>
                                            <input type="text" class="form-control" id="validationCustom03" required>
                                            <div class="invalid-feedback">
                                                Please provide a valid city.
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="validationCustom04" class="form-label">State</label>
                                            <select class="form-select" id="validationCustom04" required>
                                                <option selected disabled value="">Choose...</option>
                                                <option>...</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select a valid state.
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="validationCustom05" class="form-label">Zip</label>
                                            <input type="text" class="form-control" id="validationCustom05" required>
                                            <div class="invalid-feedback">
                                                Please provide a valid zip.
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                                <label class="form-check-label" for="invalidCheck">
                                                    Agree to terms and conditions
                                                </label>
                                                <div class="invalid-feedback">
                                                    You must agree before submitting.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Submit form</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                            <code>
                                                &lt;label for=&quot;cleave-date&quot; class=&quot;form-label&quot;&gt;Date&lt;/label&gt;
                                                &lt;input type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;DD-MM-YYYY&quot; id=&quot;cleave-date&quot;&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="server-side">
                                    Server side<a class="anchor-link" href="#server-side">#</a>
                                </h5>
                                <p class="text-muted">We recommend using client-side validation, but in case you require server-side validation, you can indicate invalid and valid form fields with <code>.is-invalid</code> and <code>.is-valid</code>. Note that <code>.invalid-feedback</code> is also supported with these classes.</p>
                                <div class="mb-3">
                                    <form class="row g-3">
                                        <div class="col-md-4">
                                            <label for="validationServer01" class="form-label">First name</label>
                                            <input type="text" class="form-control is-valid" id="validationServer01" value="Mark" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="validationServer02" class="form-label">Last name</label>
                                            <input type="text" class="form-control is-valid" id="validationServer02" value="Otto" required>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="validationServerUsername" class="form-label">Username</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend3">@</span>
                                                <input type="text" class="form-control is-invalid" id="validationServerUsername" aria-describedby="inputGroupPrepend3 validationServerUsernameFeedback" required>
                                                <div id="validationServerUsernameFeedback" class="invalid-feedback">
                                                    Please choose a username.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="validationServer03" class="form-label">City</label>
                                            <input type="text" class="form-control is-invalid" id="validationServer03" aria-describedby="validationServer03Feedback" required>
                                            <div id="validationServer03Feedback" class="invalid-feedback">
                                                Please provide a valid city.
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="validationServer04" class="form-label">State</label>
                                            <select class="form-select is-invalid" id="validationServer04" aria-describedby="validationServer04Feedback" required>
                                                <option selected disabled value="">Choose...</option>
                                                <option>...</option>
                                            </select>
                                            <div id="validationServer04Feedback" class="invalid-feedback">
                                                Please select a valid state.
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="validationServer05" class="form-label">Zip</label>
                                            <input type="text" class="form-control is-invalid" id="validationServer05" aria-describedby="validationServer05Feedback" required>
                                            <div id="validationServer05Feedback" class="invalid-feedback">
                                                Please provide a valid zip.
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input is-invalid" type="checkbox" value="" id="invalidCheck3" aria-describedby="invalidCheck3Feedback" required>
                                                <label class="form-check-label" for="invalidCheck3">
                                                    Agree to terms and conditions
                                                </label>
                                                <div id="invalidCheck3Feedback" class="invalid-feedback">
                                                    You must agree before submitting.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Submit form</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code id="buttonBadge">
                                                    &lt;form class=&quot;row g-3&quot;&gt;
                                                        &lt;div class=&quot;col-md-4&quot;&gt;
                                                            &lt;label for=&quot;validationServer01&quot; class=&quot;form-label&quot;&gt;First name&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control is-valid&quot; id=&quot;validationServer01&quot; value=&quot;Mark&quot; required&gt;
                                                            &lt;div class=&quot;valid-feedback&quot;&gt;
                                                                Looks good!
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-4&quot;&gt;
                                                            &lt;label for=&quot;validationServer02&quot; class=&quot;form-label&quot;&gt;Last name&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control is-valid&quot; id=&quot;validationServer02&quot; value=&quot;Otto&quot; required&gt;
                                                            &lt;div class=&quot;valid-feedback&quot;&gt;
                                                                Looks good!
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-4&quot;&gt;
                                                            &lt;label for=&quot;validationServerUsername&quot; class=&quot;form-label&quot;&gt;Username&lt;/label&gt;
                                                            &lt;div class=&quot;input-group has-validation&quot;&gt;
                                                                &lt;span class=&quot;input-group-text&quot; id=&quot;inputGroupPrepend3&quot;&gt;@&lt;/span&gt;
                                                                &lt;input type=&quot;text&quot; class=&quot;form-control is-invalid&quot; id=&quot;validationServerUsername&quot; aria-describedby=&quot;inputGroupPrepend3 validationServerUsernameFeedback&quot; required&gt;
                                                                &lt;div id=&quot;validationServerUsernameFeedback&quot; class=&quot;invalid-feedback&quot;&gt;
                                                                    Please choose a username.
                                                                &lt;/div&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-6&quot;&gt;
                                                            &lt;label for=&quot;validationServer03&quot; class=&quot;form-label&quot;&gt;City&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control is-invalid&quot; id=&quot;validationServer03&quot; aria-describedby=&quot;validationServer03Feedback&quot; required&gt;
                                                            &lt;div id=&quot;validationServer03Feedback&quot; class=&quot;invalid-feedback&quot;&gt;
                                                                Please provide a valid city.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-3&quot;&gt;
                                                            &lt;label for=&quot;validationServer04&quot; class=&quot;form-label&quot;&gt;State&lt;/label&gt;
                                                            &lt;select class=&quot;form-select is-invalid&quot; id=&quot;validationServer04&quot; aria-describedby=&quot;validationServer04Feedback&quot; required&gt;
                                                                &lt;option selected disabled value=&quot;&quot;&gt;Choose...&lt;/option&gt;
                                                                &lt;option&gt;...&lt;/option&gt;
                                                            &lt;/select&gt;
                                                            &lt;div id=&quot;validationServer04Feedback&quot; class=&quot;invalid-feedback&quot;&gt;
                                                                Please select a valid state.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-3&quot;&gt;
                                                            &lt;label for=&quot;validationServer05&quot; class=&quot;form-label&quot;&gt;Zip&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control is-invalid&quot; id=&quot;validationServer05&quot; aria-describedby=&quot;validationServer05Feedback&quot; required&gt;
                                                            &lt;div id=&quot;validationServer05Feedback&quot; class=&quot;invalid-feedback&quot;&gt;
                                                                Please provide a valid zip.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-12&quot;&gt;
                                                            &lt;div class=&quot;form-check&quot;&gt;
                                                                &lt;input class=&quot;form-check-input is-invalid&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;invalidCheck3&quot; aria-describedby=&quot;invalidCheck3Feedback&quot; required&gt;
                                                                &lt;label class=&quot;form-check-label&quot; for=&quot;invalidCheck3&quot;&gt;
                                                                    Agree to terms and conditions
                                                                &lt;/label&gt;
                                                                &lt;div id=&quot;invalidCheck3Feedback&quot; class=&quot;invalid-feedback&quot;&gt;
                                                                    You must agree before submitting.
                                                                &lt;/div&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-12&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary&quot; type=&quot;submit&quot;&gt;Submit form&lt;/button&gt;
                                                        &lt;/div&gt;
                                                    &lt;/form&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="supported-elements">
                                    Supported elements<a class="anchor-link" href="#supported-elements">#</a>
                                </h5>
                                <p class="text-muted">Validation styles are available for the following form controls and components:</p>
                                <ul>
                                    <li><code>&lt;input&gt;</code>s and <code>&lt;textarea&gt;</code>s with <code>.form-control</code> (including up to one <code>.form-control</code> in input groups)</li>
                                    <li><code>&lt;select&gt;</code>s with <code>.form-select</code></li>
                                    <li><code>.form-check</code>s</li>
                                </ul>
                                <div class="mb-3">
                                    <form class="was-validated">
                                        <div class="mb-3">
                                            <label for="validationTextarea" class="form-label">Textarea</label>
                                            <textarea class="form-control" id="validationTextarea" placeholder="Required example textarea" required></textarea>
                                            <div class="invalid-feedback">
                                                Please enter a message in the textarea.
                                            </div>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="validationFormCheck1" required>
                                            <label class="form-check-label" for="validationFormCheck1">Check this checkbox</label>
                                            <div class="invalid-feedback">Example invalid feedback text</div>
                                        </div>

                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" id="validationFormCheck2" name="radio-stacked" required>
                                            <label class="form-check-label" for="validationFormCheck2">Toggle this radio</label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input type="radio" class="form-check-input" id="validationFormCheck3" name="radio-stacked" required>
                                            <label class="form-check-label" for="validationFormCheck3">Or toggle this other radio</label>
                                            <div class="invalid-feedback">More example invalid feedback text</div>
                                        </div>

                                        <div class="mb-3">
                                            <select class="form-select" required aria-label="select example">
                                                <option value="">Open this select menu</option>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </select>
                                            <div class="invalid-feedback">Example invalid select feedback</div>
                                        </div>

                                        <div class="mb-3">
                                            <input type="file" class="form-control" aria-label="file example" required>
                                            <div class="invalid-feedback">Example invalid form file feedback</div>
                                        </div>

                                        <div class="mb-3">
                                            <button class="btn btn-primary" type="submit">Submit form</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code id="buttonBadge">
                                                    &lt;form class=&quot;row g-3&quot;&gt;
                                                        &lt;div class=&quot;col-md-4&quot;&gt;
                                                            &lt;label for=&quot;validationDefault01&quot; class=&quot;form-label&quot;&gt;First name&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationDefault01&quot; value=&quot;Mark&quot; required&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-4&quot;&gt;
                                                            &lt;label for=&quot;validationDefault02&quot; class=&quot;form-label&quot;&gt;Last name&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationDefault02&quot; value=&quot;Otto&quot; required&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-4&quot;&gt;
                                                            &lt;label for=&quot;validationDefaultUsername&quot; class=&quot;form-label&quot;&gt;Username&lt;/label&gt;
                                                            &lt;div class=&quot;input-group&quot;&gt;
                                                                &lt;span class=&quot;input-group-text&quot; id=&quot;inputGroupPrepend2&quot;&gt;@&lt;/span&gt;
                                                                &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationDefaultUsername&quot; aria-describedby=&quot;inputGroupPrepend2&quot; required&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-6&quot;&gt;
                                                            &lt;label for=&quot;validationDefault03&quot; class=&quot;form-label&quot;&gt;City&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationDefault03&quot; required&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-3&quot;&gt;
                                                            &lt;label for=&quot;validationDefault04&quot; class=&quot;form-label&quot;&gt;State&lt;/label&gt;
                                                            &lt;select class=&quot;form-select&quot; id=&quot;validationDefault04&quot; required&gt;
                                                                &lt;option selected disabled value=&quot;&quot;&gt;Choose...&lt;/option&gt;
                                                                &lt;option&gt;...&lt;/option&gt;
                                                            &lt;/select&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-3&quot;&gt;
                                                            &lt;label for=&quot;validationDefault05&quot; class=&quot;form-label&quot;&gt;Zip&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationDefault05&quot; required&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-12&quot;&gt;
                                                            &lt;div class=&quot;form-check&quot;&gt;
                                                                &lt;input class=&quot;form-check-input&quot; type=&quot;checkbox&quot; value=&quot;&quot; id=&quot;invalidCheck2&quot; required&gt;
                                                                &lt;label class=&quot;form-check-label&quot; for=&quot;invalidCheck2&quot;&gt;
                                                                    Agree to terms and conditions
                                                                &lt;/label&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-12&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary&quot; type=&quot;submit&quot;&gt;Submit form&lt;/button&gt;
                                                        &lt;/div&gt;
                                                    &lt;/form&gt;
                                                </code>
                                            </pre>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="tooltips">
                                    Tooltips<a class="anchor-link" href="#tooltips">#</a>
                                </h5>
                                <p class="text-muted">If your form layout allows it, you can swap the <code>.{valid|invalid}-feedback</code> classes for <code>.{valid|invalid}-tooltip</code> classes to display validation feedback in a styled tooltip. Be sure to have a parent with <code>position: relative</code> on it for tooltip positioning. In the example below, our column classes have this already, but your project may require an alternative setup.</p>
                                <div class="mb-3">
                                    <form class="row g-3 needs-validation" novalidate>
                                        <div class="col-md-4 position-relative">
                                            <label for="validationTooltip01" class="form-label">First name</label>
                                            <input type="text" class="form-control" id="validationTooltip01" value="Mark" required>
                                            <div class="valid-tooltip">
                                                Looks good!
                                            </div>
                                        </div>
                                        <div class="col-md-4 position-relative">
                                            <label for="validationTooltip02" class="form-label">Last name</label>
                                            <input type="text" class="form-control" id="validationTooltip02" value="Otto" required>
                                            <div class="valid-tooltip">
                                                Looks good!
                                            </div>
                                        </div>
                                        <div class="col-md-4 position-relative">
                                            <label for="validationTooltipUsername" class="form-label">Username</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="validationTooltipUsernamePrepend">@</span>
                                                <input type="text" class="form-control" id="validationTooltipUsername" aria-describedby="validationTooltipUsernamePrepend" required>
                                                <div class="invalid-tooltip">
                                                    Please choose a unique and valid username.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 position-relative">
                                            <label for="validationTooltip03" class="form-label">City</label>
                                            <input type="text" class="form-control" id="validationTooltip03" required>
                                            <div class="invalid-tooltip">
                                                Please provide a valid city.
                                            </div>
                                        </div>
                                        <div class="col-md-3 position-relative">
                                            <label for="validationTooltip04" class="form-label">State</label>
                                            <select class="form-select" id="validationTooltip04" required>
                                                <option selected disabled value="">Choose...</option>
                                                <option>...</option>
                                            </select>
                                            <div class="invalid-tooltip">
                                                Please select a valid state.
                                            </div>
                                        </div>
                                        <div class="col-md-3 position-relative">
                                            <label for="validationTooltip05" class="form-label">Zip</label>
                                            <input type="text" class="form-control" id="validationTooltip05" required>
                                            <div class="invalid-tooltip">
                                                Please provide a valid zip.
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">Submit form</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="code">
                                        <pre class="language-html">
                                                <code>
                                                    &lt;form class=&quot;row g-3 needs-validation&quot; novalidate&gt;
                                                        &lt;div class=&quot;col-md-4 position-relative&quot;&gt;
                                                            &lt;label for=&quot;validationTooltip01&quot; class=&quot;form-label&quot;&gt;First name&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationTooltip01&quot; value=&quot;Mark&quot; required&gt;
                                                            &lt;div class=&quot;valid-tooltip&quot;&gt;
                                                                Looks good!
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-4 position-relative&quot;&gt;
                                                            &lt;label for=&quot;validationTooltip02&quot; class=&quot;form-label&quot;&gt;Last name&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationTooltip02&quot; value=&quot;Otto&quot; required&gt;
                                                            &lt;div class=&quot;valid-tooltip&quot;&gt;
                                                                Looks good!
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-4 position-relative&quot;&gt;
                                                            &lt;label for=&quot;validationTooltipUsername&quot; class=&quot;form-label&quot;&gt;Username&lt;/label&gt;
                                                            &lt;div class=&quot;input-group has-validation&quot;&gt;
                                                                &lt;span class=&quot;input-group-text&quot; id=&quot;validationTooltipUsernamePrepend&quot;&gt;@&lt;/span&gt;
                                                                &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationTooltipUsername&quot; aria-describedby=&quot;validationTooltipUsernamePrepend&quot; required&gt;
                                                                &lt;div class=&quot;invalid-tooltip&quot;&gt;
                                                                    Please choose a unique and valid username.
                                                                &lt;/div&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-6 position-relative&quot;&gt;
                                                            &lt;label for=&quot;validationTooltip03&quot; class=&quot;form-label&quot;&gt;City&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationTooltip03&quot; required&gt;
                                                            &lt;div class=&quot;invalid-tooltip&quot;&gt;
                                                                Please provide a valid city.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-3 position-relative&quot;&gt;
                                                            &lt;label for=&quot;validationTooltip04&quot; class=&quot;form-label&quot;&gt;State&lt;/label&gt;
                                                            &lt;select class=&quot;form-select&quot; id=&quot;validationTooltip04&quot; required&gt;
                                                                &lt;option selected disabled value=&quot;&quot;&gt;Choose...&lt;/option&gt;
                                                                &lt;option&gt;...&lt;/option&gt;
                                                            &lt;/select&gt;
                                                            &lt;div class=&quot;invalid-tooltip&quot;&gt;
                                                                Please select a valid state.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-md-3 position-relative&quot;&gt;
                                                            &lt;label for=&quot;validationTooltip05&quot; class=&quot;form-label&quot;&gt;Zip&lt;/label&gt;
                                                            &lt;input type=&quot;text&quot; class=&quot;form-control&quot; id=&quot;validationTooltip05&quot; required&gt;
                                                            &lt;div class=&quot;invalid-tooltip&quot;&gt;
                                                                Please provide a valid zip.
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                        &lt;div class=&quot;col-12&quot;&gt;
                                                            &lt;button class=&quot;btn btn-primary&quot; type=&quot;submit&quot;&gt;Submit form&lt;/button&gt;
                                                        &lt;/div&gt;
                                                    &lt;/form&gt;
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
                                    <a href="#browser-defaults" class="nav-link">Browser Defaults</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#custom-styles" class="nav-link">Custom Styles</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#server-side" class="nav-link">Server side</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#supported-elements" class="nav-link">Supported Elements</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tooltips" class="nav-link">Tooltips</a>
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