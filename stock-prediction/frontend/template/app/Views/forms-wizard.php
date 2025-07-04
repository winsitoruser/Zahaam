<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Wizard")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Wizard")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->

        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title anchor" id="basic-wizard">Horizontal Wizard<a class="anchor-link" href="#horizontal-wizard">#</a></h5>
                            </div><!-- end card-header -->
                            <div class="card-body">
                                <div class="mb-5">
                                    <form>
                                        <div id="horizontalwizard">
                                            <ul class="nav nav-pills nav-justified icon-wizard form-wizard-header bg-light p-1" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a href="#basictab1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2 active" aria-selected="true" role="tab">
                                                        <iconify-icon icon="iconamoon:profile-circle-duotone" class="fs-26"></iconify-icon>
                                                        Account
                                                    </a><!-- end nav-link -->
                                                </li><!-- end nav-item -->
                                                <li class="nav-item" role="presentation">
                                                    <a href="#basictab2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2" aria-selected="false" role="tab" tabindex="-1">
                                                        <iconify-icon icon="iconamoon:profile-duotone" class="fs-26"></iconify-icon>
                                                        Profile
                                                    </a><!-- end nav-link -->
                                                </li><!-- end nav-item -->
                                                <li class="nav-item" role="presentation">
                                                    <a href="#basictab3" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2" aria-selected="false" tabindex="-1" role="tab">
                                                        <iconify-icon icon="iconamoon:link-fill" class="fs-26"></iconify-icon>
                                                        Social Links
                                                    </a><!-- end nav-link -->
                                                </li><!-- end nav-item -->
                                                <li class="nav-item" role="presentation">
                                                    <a href="#basictab4" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2" aria-selected="false" tabindex="-1" role="tab">
                                                        <iconify-icon icon="iconamoon:check-circle-1-duotone" class="fs-26"></iconify-icon>
                                                        Finish
                                                    </a><!-- end nav-link -->
                                                </li><!-- end nav-item -->
                                            </ul>

                                            <div class="tab-content mb-0">
                                                <div class="tab-pane active show" id="basictab1" role="tabpanel">
                                                    <h4 class="fs-16 fw-semibold mb-1">Account Information</h4>
                                                    <p class="text-muted">Setup your account information</p>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="basicUser" class="form-label">User Name</label>
                                                                <input id="basicUser" type="text" class="form-control" placeholder="Enter User Name">
                                                            </div>
                                                        </div> <!-- end col -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="basicEmail" class="form-label">Email</label>
                                                                <input id="basicEmail" type="email" class="form-control" placeholder="Enter your email">
                                                            </div>
                                                        </div> <!-- end col -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="basicPassworda" class="form-label">Password</label>
                                                                <input id="basicPassworda" type="text" class="form-control" placeholder="Enter Password">
                                                            </div>
                                                        </div> <!-- end col -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="basicConfirmPassword" class="form-label">Confirm Password</label>
                                                                <input id="basicConfirmPassword" type="text" class="form-control" placeholder="Confirm a Password">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                </div><!-- end tab-pane -->

                                                <div class="tab-pane" id="basictab2" role="tabpanel">
                                                    <h4 class="fs-16 fw-semibold mb-1">Profile Information</h4>
                                                    <p class="text-muted">Setup your profile information</p>

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="avatar-lg mb-3">
                                                                <div class="avatar-title bg-body rounded-circle border border-3 border-dashed-light position-relative">
                                                                    <label for="imageInput" class="position-absolute end-0 bottom-0">
                                                                        <div class="avatar-xs cursor-pointer">
                                                                            <span class="avatar-title bg-light text-dark rounded-circle"><i class="bx bx-camera"></i></span>
                                                                        </div>
                                                                    </label>
                                                                    <input class="hidden" type="file" id="imageInput" accept="image/*" onchange="previewImage(event)">

                                                                    <img id="preview" src="/images/users/dummy-avatar.jpg" alt="Preview Image" class="rounded-circle img-fluid">
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="basicFname">First Name</label>
                                                                        <input type="text" id="basicFname" class="form-control" placeholder="Chris">
                                                                    </div>
                                                                </div><!-- end col -->
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="basicLname">Last Name</label>
                                                                        <input type="text" id="basicLname" class="form-control" placeholder="Keller">
                                                                    </div>
                                                                </div><!-- end col -->
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="basicMnumber">Number</label>
                                                                        <input type="number" id="basicMnumber" class="form-control" placeholder="Mobile Number">
                                                                    </div>
                                                                </div><!-- end col -->
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="basicCountry">Country</label>
                                                                        <select id="basicCountry" class="form-select">
                                                                            <option value="United States">United States</option>
                                                                            <option value="Canada">Canada</option>
                                                                            <option value="Australia">Australia</option>
                                                                            <option value="Germany">Germany</option>
                                                                            <option value="Bangladesh">Bangladesh</option>
                                                                            <option value="China">China</option>
                                                                            <option value="Argentina">Argentina</option>
                                                                            <option value="Bharat">Bharat</option>
                                                                            <option value="Afghanistan">Afghanistan</option>
                                                                            <option value="France">France</option>
                                                                            <option value="Brazil">Brazil</option>
                                                                            <option value="Belgium">Belgium</option>
                                                                            <option value="Colombia">Colombia</option>
                                                                            <option value="Albania">Albania</option>
                                                                        </select>
                                                                    </div>
                                                                </div><!-- end col -->
                                                            </div><!-- end row -->
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                </div><!-- end tab-pane -->

                                                <div class="tab-pane" id="basictab3" role="tabpanel">
                                                    <h4 class="fs-16 fw-semibold mb-1">Social Media Links</h4>
                                                    <p class="text-muted">Fill your social media links</p>

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="basicGitLink" class="form-label">GitHub</label>
                                                                <input id="basicGitLink" type="text" class="form-control" placeholder="GitHub Link">
                                                            </div>
                                                        </div> <!-- end col -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="basicGoogleLink" class="form-label">Google</label>
                                                                <input id="basicGoogleLink" type="text" class="form-control" placeholder="Google Link">
                                                            </div>
                                                        </div> <!-- end col -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="basicInstagramLink" class="form-label">Instagram</label>
                                                                <input id="basicInstagramLink" type="text" class="form-control" placeholder="Instagram Link">
                                                            </div>
                                                        </div> <!-- end col -->
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label for="basicSkypeLink" class="form-label">Skype</label>
                                                                <input id="basicSkypeLink" type="text" class="form-control" placeholder="Skype Link">
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div><!-- end row -->
                                                </div><!-- end tab-pane -->

                                                <div class="tab-pane" id="basictab4" role="tabpanel">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="text-center">
                                                                <div class="avatar-md mx-auto mb-3">
                                                                    <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle"><iconify-icon icon="iconamoon:like-duotone" class="fs-36"></iconify-icon></div>
                                                                </div>
                                                                <h3 class="mt-0">Finished !</h3>

                                                                <p class="w-75 mb-2 mx-auto">Filled Data Successfully.</p>

                                                                <div class="mb-3">
                                                                    <div class="form-check d-inline-block">
                                                                        <input type="checkbox" class="form-check-input" id="customCheck1">
                                                                        <label class="form-check-label" for="customCheck1">I agree with the Terms and Conditions</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> <!-- end col -->
                                                    </div> <!-- end row -->
                                                </div><!-- end tab-pane -->

                                                <div class="d-flex flex-wrap align-items-center wizard justify-content-between gap-3 mt-3">
                                                    <div class="first">
                                                        <a href="javascript:void(0);" class="btn btn-soft-primary">
                                                            First
                                                        </a>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <div class="previous">
                                                            <a href="javascript:void(0);" class="btn btn-primary disabled">
                                                                <i class="bx bx-left-arrow-alt me-2"></i>Back To Previous
                                                            </a>
                                                        </div>
                                                        <div class="next">
                                                            <a href="javascript:void(0);" class="btn btn-primary">
                                                                Next Step<i class="bx bx-right-arrow-alt ms-2"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="last">
                                                        <a href="javascript:void(0);" class="btn btn-soft-primary">
                                                            Finish
                                                        </a>
                                                    </div>
                                                </div>
                                            </div> <!-- tab-content -->
                                        </div> <!-- end #horizontal wizard-->
                                    </form>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <ul class="nav nav-pills bg-transparent">
                                            <li class="nav-item">
                                                <a href="#horizontal-wiazrd-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#horizontal-wiazrd-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="horizontal-wiazrd-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;form&gt;
                                                        &lt;div id=&quot;horizontalwizard&quot;&gt;
                                                            &lt;ul class=&quot;nav nav-pills nav-justified icon-wizard form-wizard-header bg-light p-1&quot; role=&quot;tablist&quot;&gt;
                                                                &lt;li class=&quot;nav-item&quot; role=&quot;presentation&quot;&gt;
                                                                    &lt;a href=&quot;#basictab1&quot; data-bs-toggle=&quot;tab&quot; data-toggle=&quot;tab&quot; class=&quot;nav-link rounded-0 py-2 active&quot; aria-selected=&quot;true&quot; role=&quot;tab&quot;&gt;
                                                                        &lt;iconify-icon icon=&quot;iconamoon:profile-circle-duotone&quot; class=&quot;fs-26&quot;&gt;&lt;/iconify-icon&gt;
                                                                        Account
                                                                    &lt;/a&gt;&lt;!-- end nav-link --&gt;
                                                                &lt;/li&gt;&lt;!-- end nav-item --&gt;
                                                                &lt;li class=&quot;nav-item&quot; role=&quot;presentation&quot;&gt;
                                                                    &lt;a href=&quot;#basictab2&quot; data-bs-toggle=&quot;tab&quot; data-toggle=&quot;tab&quot; class=&quot;nav-link rounded-0 py-2&quot; aria-selected=&quot;false&quot; role=&quot;tab&quot; tabindex=&quot;-1&quot;&gt;
                                                                        &lt;iconify-icon icon=&quot;iconamoon:profile-duotone&quot; class=&quot;fs-26&quot;&gt;&lt;/iconify-icon&gt;
                                                                        Profile
                                                                    &lt;/a&gt;&lt;!-- end nav-link --&gt;
                                                                &lt;/li&gt;&lt;!-- end nav-item --&gt;
                                                                &lt;li class=&quot;nav-item&quot; role=&quot;presentation&quot;&gt;
                                                                    &lt;a href=&quot;#basictab3&quot; data-bs-toggle=&quot;tab&quot; data-toggle=&quot;tab&quot; class=&quot;nav-link rounded-0 py-2&quot; aria-selected=&quot;false&quot; tabindex=&quot;-1&quot; role=&quot;tab&quot;&gt;
                                                                        &lt;iconify-icon icon=&quot;iconamoon:link-fill&quot; class=&quot;fs-26&quot;&gt;&lt;/iconify-icon&gt;
                                                                        Social Links
                                                                    &lt;/a&gt;&lt;!-- end nav-link --&gt;
                                                                &lt;/li&gt;&lt;!-- end nav-item --&gt;
                                                                &lt;li class=&quot;nav-item&quot; role=&quot;presentation&quot;&gt;
                                                                    &lt;a href=&quot;#basictab4&quot; data-bs-toggle=&quot;tab&quot; data-toggle=&quot;tab&quot; class=&quot;nav-link rounded-0 py-2&quot; aria-selected=&quot;false&quot; tabindex=&quot;-1&quot; role=&quot;tab&quot;&gt;
                                                                        &lt;iconify-icon icon=&quot;iconamoon:check-circle-1-duotone&quot; class=&quot;fs-26&quot;&gt;&lt;/iconify-icon&gt;
                                                                        Finish
                                                                    &lt;/a&gt;&lt;!-- end nav-link --&gt;
                                                                &lt;/li&gt;&lt;!-- end nav-item --&gt;
                                                            &lt;/ul&gt;
                
                                                            &lt;div class=&quot;tab-content mb-0&quot;&gt;
                                                                &lt;div class=&quot;tab-pane active show&quot; id=&quot;basictab1&quot; role=&quot;tabpanel&quot;&gt;
                                                                    &lt;h4 class=&quot;fs-16 fw-semibold mb-1&quot;&gt;Account Information&lt;/h4&gt;
                                                                    &lt;p class=&quot;text-muted&quot;&gt;Setup your account information&lt;/p&gt;
                
                                                                    &lt;div class=&quot;row&quot;&gt;
                                                                        &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;label for=&quot;basicUser&quot; class=&quot;form-label&quot;&gt;User Name&lt;/label&gt;
                                                                                &lt;input id=&quot;basicUser&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Enter User Name&quot;&gt;
                                                                            &lt;/div&gt;
                                                                        &lt;/div&gt; &lt;!-- end col --&gt;
                                                                        &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;label for=&quot;basicEmail&quot; class=&quot;form-label&quot;&gt;Email&lt;/label&gt;
                                                                                &lt;input id=&quot;basicEmail&quot; type=&quot;email&quot; class=&quot;form-control&quot; placeholder=&quot;Enter your email&quot;&gt;
                                                                            &lt;/div&gt;
                                                                        &lt;/div&gt; &lt;!-- end col --&gt;
                                                                        &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;label for=&quot;basicPassworda&quot; class=&quot;form-label&quot;&gt;Password&lt;/label&gt;
                                                                                &lt;input id=&quot;basicPassworda&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Enter Password&quot;&gt;
                                                                            &lt;/div&gt;
                                                                        &lt;/div&gt; &lt;!-- end col --&gt;
                                                                        &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;label for=&quot;basicConfirmPassword&quot; class=&quot;form-label&quot;&gt;Confirm Password&lt;/label&gt;
                                                                                &lt;input id=&quot;basicConfirmPassword&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Confirm a Password&quot;&gt;
                                                                            &lt;/div&gt;
                                                                        &lt;/div&gt; &lt;!-- end col --&gt;
                                                                    &lt;/div&gt; &lt;!-- end row --&gt;
                                                                &lt;/div&gt;&lt;!-- end tab-pane --&gt;
                
                                                                &lt;div class=&quot;tab-pane&quot; id=&quot;basictab2&quot; role=&quot;tabpanel&quot;&gt;
                                                                    &lt;h4 class=&quot;fs-16 fw-semibold mb-1&quot;&gt;Profile Information&lt;/h4&gt;
                                                                    &lt;p class=&quot;text-muted&quot;&gt;Setup your profile information&lt;/p&gt;
                
                                                                    &lt;div class=&quot;row&quot;&gt;
                                                                        &lt;div class=&quot;col-12&quot;&gt;
                                                                            &lt;div class=&quot;avatar-lg mb-3&quot;&gt;
                                                                                &lt;div class=&quot;avatar-title bg-body rounded-circle border border-3 border-dashed-light position-relative&quot;&gt;
                                                                                    &lt;label for=&quot;imageInput&quot; class=&quot;position-absolute end-0 bottom-0&quot;&gt;
                                                                                        &lt;div class=&quot;avatar-xs cursor-pointer&quot;&gt;
                                                                                            &lt;span class=&quot;avatar-title bg-light text-dark rounded-circle&quot;&gt;&lt;i class=&quot;bx bx-camera&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                                                        &lt;/div&gt;
                                                                                    &lt;/label&gt;
                                                                                    &lt;input class=&quot;hidden&quot; type=&quot;file&quot; id=&quot;imageInput&quot; accept=&quot;image/*&quot; onchange=&quot;previewImage(event)&quot;&gt;
                
                                                                                    &lt;img id=&quot;preview&quot; src=&quot;assets/images/users/dummy-avatar.jpg&quot; alt=&quot;Preview Image&quot; class=&quot;rounded-circle img-fluid&quot;&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt;
                
                                                                            &lt;div class=&quot;row&quot;&gt;
                                                                                &lt;div class=&quot;col-md-6&quot;&gt;
                                                                                    &lt;div class=&quot;mb-3&quot;&gt;
                                                                                        &lt;label class=&quot;form-label&quot; for=&quot;basicFname&quot;&gt;First Name&lt;/label&gt;
                                                                                        &lt;input type=&quot;text&quot; id=&quot;basicFname&quot; class=&quot;form-control&quot; placeholder=&quot;Chris&quot;&gt;
                                                                                    &lt;/div&gt;
                                                                                &lt;/div&gt;&lt;!-- end col --&gt;
                                                                                &lt;div class=&quot;col-md-6&quot;&gt;
                                                                                    &lt;div class=&quot;mb-3&quot;&gt;
                                                                                        &lt;label class=&quot;form-label&quot; for=&quot;basicLname&quot;&gt;Last Name&lt;/label&gt;
                                                                                        &lt;input type=&quot;text&quot; id=&quot;basicLname&quot; class=&quot;form-control&quot; placeholder=&quot;Keller&quot;&gt;
                                                                                    &lt;/div&gt;
                                                                                &lt;/div&gt;&lt;!-- end col --&gt;
                                                                                &lt;div class=&quot;col-md-6&quot;&gt;
                                                                                    &lt;div class=&quot;mb-3&quot;&gt;
                                                                                        &lt;label class=&quot;form-label&quot; for=&quot;basicMnumber&quot;&gt;Number&lt;/label&gt;
                                                                                        &lt;input type=&quot;number&quot; id=&quot;basicMnumber&quot; class=&quot;form-control&quot; placeholder=&quot;Mobile Number&quot;&gt;
                                                                                    &lt;/div&gt;
                                                                                &lt;/div&gt;&lt;!-- end col --&gt;
                                                                                &lt;div class=&quot;col-md-6&quot;&gt;
                                                                                    &lt;div class=&quot;mb-3&quot;&gt;
                                                                                        &lt;label class=&quot;form-label&quot; for=&quot;basicCountry&quot;&gt;Country&lt;/label&gt;
                                                                                        &lt;select id=&quot;basicCountry&quot; class=&quot;form-select&quot;&gt;
                                                                                            &lt;option value=&quot;United States&quot;&gt;United States&lt;/option&gt;
                                                                                            &lt;option value=&quot;Canada&quot;&gt;Canada&lt;/option&gt;
                                                                                            &lt;option value=&quot;Australia&quot;&gt;Australia&lt;/option&gt;
                                                                                            &lt;option value=&quot;Germany&quot;&gt;Germany&lt;/option&gt;
                                                                                            &lt;option value=&quot;Bangladesh&quot;&gt;Bangladesh&lt;/option&gt;
                                                                                            &lt;option value=&quot;China&quot;&gt;China&lt;/option&gt;
                                                                                            &lt;option value=&quot;Argentina&quot;&gt;Argentina&lt;/option&gt;
                                                                                            &lt;option value=&quot;Bharat&quot;&gt;Bharat&lt;/option&gt;
                                                                                            &lt;option value=&quot;Afghanistan&quot;&gt;Afghanistan&lt;/option&gt;
                                                                                            &lt;option value=&quot;France&quot;&gt;France&lt;/option&gt;
                                                                                            &lt;option value=&quot;Brazil&quot;&gt;Brazil&lt;/option&gt;
                                                                                            &lt;option value=&quot;Belgium&quot;&gt;Belgium&lt;/option&gt;
                                                                                            &lt;option value=&quot;Colombia&quot;&gt;Colombia&lt;/option&gt;
                                                                                            &lt;option value=&quot;Albania&quot;&gt;Albania&lt;/option&gt;
                                                                                        &lt;/select&gt;
                                                                                    &lt;/div&gt;
                                                                                &lt;/div&gt;&lt;!-- end col --&gt;
                                                                            &lt;/div&gt;&lt;!-- end row --&gt;
                                                                        &lt;/div&gt; &lt;!-- end col --&gt;
                                                                    &lt;/div&gt; &lt;!-- end row --&gt;
                                                                &lt;/div&gt;&lt;!-- end tab-pane --&gt;
                
                                                                &lt;div class=&quot;tab-pane&quot; id=&quot;basictab3&quot; role=&quot;tabpanel&quot;&gt;
                                                                    &lt;h4 class=&quot;fs-16 fw-semibold mb-1&quot;&gt;Social Media Links&lt;/h4&gt;
                                                                    &lt;p class=&quot;text-muted&quot;&gt;Fill your social media links&lt;/p&gt;
                
                                                                    &lt;div class=&quot;row&quot;&gt;
                                                                        &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;label for=&quot;basicGitLink&quot; class=&quot;form-label&quot;&gt;GitHub&lt;/label&gt;
                                                                                &lt;input id=&quot;basicGitLink&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;GitHub Link&quot;&gt;
                                                                            &lt;/div&gt;
                                                                        &lt;/div&gt; &lt;!-- end col --&gt;
                                                                        &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;label for=&quot;basicGoogleLink&quot; class=&quot;form-label&quot;&gt;Google&lt;/label&gt;
                                                                                &lt;input id=&quot;basicGoogleLink&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Google Link&quot;&gt;
                                                                            &lt;/div&gt;
                                                                        &lt;/div&gt; &lt;!-- end col --&gt;
                                                                        &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;label for=&quot;basicInstagramLink&quot; class=&quot;form-label&quot;&gt;Instagram&lt;/label&gt;
                                                                                &lt;input id=&quot;basicInstagramLink&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Instagram Link&quot;&gt;
                                                                            &lt;/div&gt;
                                                                        &lt;/div&gt; &lt;!-- end col --&gt;
                                                                        &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                            &lt;div class=&quot;mb-3&quot;&gt;
                                                                                &lt;label for=&quot;basicSkypeLink&quot; class=&quot;form-label&quot;&gt;Skype&lt;/label&gt;
                                                                                &lt;input id=&quot;basicSkypeLink&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Skype Link&quot;&gt;
                                                                            &lt;/div&gt;
                                                                        &lt;/div&gt; &lt;!-- end col --&gt;
                                                                    &lt;/div&gt;&lt;!-- end row --&gt;
                                                                &lt;/div&gt;&lt;!-- end tab-pane --&gt;
                
                                                                &lt;div class=&quot;tab-pane&quot; id=&quot;basictab4&quot; role=&quot;tabpanel&quot;&gt;
                                                                    &lt;div class=&quot;row&quot;&gt;
                                                                        &lt;div class=&quot;col-12&quot;&gt;
                                                                            &lt;div class=&quot;text-center&quot;&gt;
                                                                                &lt;div class=&quot;avatar-md mx-auto mb-3&quot;&gt;
                                                                                    &lt;div class=&quot;avatar-title bg-primary bg-opacity-10 text-primary rounded-circle&quot;&gt;&lt;iconify-icon icon=&quot;iconamoon:like-duotone&quot; class=&quot;fs-36&quot;&gt;&lt;/iconify-icon&gt;&lt;/div&gt;
                                                                                &lt;/div&gt;
                                                                                &lt;h3 class=&quot;mt-0&quot;&gt;Finished !&lt;/h3&gt;
                
                                                                                &lt;p class=&quot;w-75 mb-2 mx-auto&quot;&gt;Filled Data Successfully.&lt;/p&gt;
                
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                    &lt;div class=&quot;form-check d-inline-block&quot;&gt;
                                                                                        &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheck1&quot;&gt;
                                                                                        &lt;label class=&quot;form-check-label&quot; for=&quot;customCheck1&quot;&gt;I agree with the Terms and Conditions&lt;/label&gt;
                                                                                    &lt;/div&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt;
                                                                        &lt;/div&gt; &lt;!-- end col --&gt;
                                                                    &lt;/div&gt; &lt;!-- end row --&gt;
                                                                &lt;/div&gt;&lt;!-- end tab-pane --&gt;
                
                                                                &lt;div class=&quot;d-flex flex-wrap align-items-center wizard justify-content-between gap-3 mt-3&quot;&gt;
                                                                    &lt;div class=&quot;first&quot;&gt;
                                                                        &lt;a href=&quot;javascript:void(0);&quot; class=&quot;btn btn-soft-primary&quot;&gt;
                                                                            First
                                                                        &lt;/a&gt;
                                                                    &lt;/div&gt;
                                                                    &lt;div class=&quot;d-flex gap-2&quot;&gt;
                                                                        &lt;div class=&quot;previous&quot;&gt;
                                                                            &lt;a href=&quot;javascript:void(0);&quot; class=&quot;btn btn-primary disabled&quot;&gt;
                                                                                &lt;i class=&quot;bx bx-left-arrow-alt me-2&quot;&gt;&lt;/i&gt;Back To Previous
                                                                            &lt;/a&gt;
                                                                        &lt;/div&gt;
                                                                        &lt;div class=&quot;next&quot;&gt;
                                                                            &lt;a href=&quot;javascript:void(0);&quot; class=&quot;btn btn-primary&quot;&gt;
                                                                                Next Step&lt;i class=&quot;bx bx-right-arrow-alt ms-2&quot;&gt;&lt;/i&gt;
                                                                            &lt;/a&gt;
                                                                        &lt;/div&gt;
                                                                    &lt;/div&gt;
                                                                    &lt;div class=&quot;last&quot;&gt;
                                                                        &lt;a href=&quot;javascript:void(0);&quot; class=&quot;btn btn-soft-primary&quot;&gt;
                                                                            Finish
                                                                        &lt;/a&gt;
                                                                    &lt;/div&gt;
                                                                &lt;/div&gt;
                                                            &lt;/div&gt; &lt;!-- tab-content --&gt;
                                                        &lt;/div&gt; &lt;!-- end #horizontal wizard--&gt;
                                                    &lt;/form&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="horizontal-wiazrd-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        new Wizard('#horizontalwizard');
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->

                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title anchor" id="basic-wizard">Vertical Wizard<a class="anchor-link" href="#vertical-wizard">#</a></h5>
                            </div><!-- end card-header -->
                            <div class="card-body">
                                <div class="mb-5">
                                    <form id="verticalwizard">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <ul class="nav nav-pills nav-justified flex-column icon-wizard form-wizard-header bg-light p-1" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <a href="#basictabHorizontal1" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2 active" aria-selected="true" role="tab">
                                                            <iconify-icon icon="iconamoon:profile-circle-duotone" class="fs-26"></iconify-icon>
                                                            Account
                                                        </a><!-- end nav-link -->
                                                    </li><!-- end nav-item -->
                                                    <li class="nav-item" role="presentation">
                                                        <a href="#basictabHorizontal2" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2" aria-selected="false" role="tab" tabindex="-1">
                                                            <iconify-icon icon="iconamoon:profile-duotone" class="fs-26"></iconify-icon>
                                                            Profile
                                                        </a><!-- end nav-link -->
                                                    </li><!-- end nav-item -->
                                                    <li class="nav-item" role="presentation">
                                                        <a href="#basictabHorizontal3" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2" aria-selected="false" tabindex="-1" role="tab">
                                                            <iconify-icon icon="iconamoon:link-fill" class="fs-26"></iconify-icon>
                                                            Social Links
                                                        </a><!-- end nav-link -->
                                                    </li><!-- end nav-item -->
                                                    <li class="nav-item" role="presentation">
                                                        <a href="#basictabHorizontal4" data-bs-toggle="tab" data-toggle="tab" class="nav-link rounded-0 py-2" aria-selected="false" tabindex="-1" role="tab">
                                                            <iconify-icon icon="iconamoon:check-circle-1-duotone" class="fs-26"></iconify-icon>
                                                            Finish
                                                        </a><!-- end nav-link -->
                                                    </li><!-- end nav-item -->
                                                </ul>
                                            </div>

                                            <div class="col-lg-9">
                                                <div class="tab-content mb-0">
                                                    <div class="tab-pane active show" id="basictabHorizontal1" role="tabpanel">
                                                        <h4 class="fs-16 fw-semibold mb-1">Account Information</h4>
                                                        <p class="text-muted">Setup your account information</p>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="basicUser2" class="form-label">User Name</label>
                                                                    <input id="basicUser2" type="text" class="form-control" placeholder="Enter User Name">
                                                                </div>
                                                            </div> <!-- end col -->
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="basicEmail2" class="form-label">Email</label>
                                                                    <input id="basicEmail2" type="email" class="form-control" placeholder="Enter your email">
                                                                </div>
                                                            </div> <!-- end col -->
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="basicPassworda2" class="form-label">Password</label>
                                                                    <input id="basicPassworda2" type="text" class="form-control" placeholder="Enter Password">
                                                                </div>
                                                            </div> <!-- end col -->
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="basicConfirmPassword2" class="form-label">Confirm Password</label>
                                                                    <input id="basicConfirmPassword2" type="text" class="form-control" placeholder="Confirm a Password">
                                                                </div>
                                                            </div> <!-- end col -->
                                                        </div> <!-- end row -->
                                                    </div><!-- end tab-pane -->

                                                    <div class="tab-pane" id="basictabHorizontal2" role="tabpanel">
                                                        <h4 class="fs-16 fw-semibold mb-1">Profile Information</h4>
                                                        <p class="text-muted">Setup your profile information</p>

                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="avatar-lg mb-3">
                                                                    <div class="avatar-title bg-body rounded-circle border border-3 border-dashed-light position-relative">
                                                                        <label for="imageInput" class="position-absolute end-0 bottom-0">
                                                                            <div class="avatar-xs cursor-pointer">
                                                                                <span class="avatar-title bg-light text-dark rounded-circle"><i class="bx bx-camera"></i></span>
                                                                            </div>
                                                                        </label>
                                                                        <input class="hidden" type="file" id="imageInput" accept="image/*" onchange="previewImage(event)">

                                                                        <img id="preview" src="/images/users/dummy-avatar.jpg" alt="Preview Image" class="rounded-circle img-fluid">
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label class="form-label" for="basicFname2">First Name</label>
                                                                            <input type="text" id="basicFname2" class="form-control" placeholder="Chris">
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label class="form-label" for="basicLname2">Last Name</label>
                                                                            <input type="text" id="basicLname2" class="form-control" placeholder="Keller">
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label class="form-label" for="basicMnumber2">Number</label>
                                                                            <input type="number" id="basicMnumber2" class="form-control" placeholder="Mobile Number">
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                    <div class="col-md-6">
                                                                        <div class="mb-3">
                                                                            <label class="form-label" for="basicCountry2">Country</label>
                                                                            <select id="basicCountry2" class="form-select">
                                                                                <option value="United States">United States</option>
                                                                                <option value="Canada">Canada</option>
                                                                                <option value="Australia">Australia</option>
                                                                                <option value="Germany">Germany</option>
                                                                                <option value="Bangladesh">Bangladesh</option>
                                                                                <option value="China">China</option>
                                                                                <option value="Argentina">Argentina</option>
                                                                                <option value="Bharat">Bharat</option>
                                                                                <option value="Afghanistan">Afghanistan</option>
                                                                                <option value="France">France</option>
                                                                                <option value="Brazil">Brazil</option>
                                                                                <option value="Belgium">Belgium</option>
                                                                                <option value="Colombia">Colombia</option>
                                                                                <option value="Albania">Albania</option>
                                                                            </select>
                                                                        </div>
                                                                    </div><!-- end col -->
                                                                </div><!-- end row -->
                                                            </div> <!-- end col -->
                                                        </div> <!-- end row -->
                                                    </div><!-- end tab-pane -->

                                                    <div class="tab-pane" id="basictabHorizontal3" role="tabpanel">
                                                        <h4 class="fs-16 fw-semibold mb-1">Social Media Links</h4>
                                                        <p class="text-muted">Fill your social media links</p>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="basicGitLink2" class="form-label">GitHub</label>
                                                                    <input id="basicGitLink2" type="text" class="form-control" placeholder="GitHub Link">
                                                                </div>
                                                            </div> <!-- end col -->
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="basicGoogleLink2" class="form-label">Google</label>
                                                                    <input id="basicGoogleLink2" type="text" class="form-control" placeholder="Google Link">
                                                                </div>
                                                            </div> <!-- end col -->
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="basicInstagramLink3" class="form-label">Instagram</label>
                                                                    <input id="basicInstagramLink3" type="text" class="form-control" placeholder="Instagram Link">
                                                                </div>
                                                            </div> <!-- end col -->
                                                            <div class="col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="basicSkypeLink4" class="form-label">Skype</label>
                                                                    <input id="basicSkypeLink4" type="text" class="form-control" placeholder="Skype Link">
                                                                </div>
                                                            </div> <!-- end col -->
                                                        </div><!-- end row -->
                                                    </div><!-- end tab-pane -->

                                                    <div class="tab-pane" id="basictabHorizontal4" role="tabpanel">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="text-center">
                                                                    <div class="avatar-md mx-auto mb-3">
                                                                        <div class="avatar-title bg-primary bg-opacity-10 text-primary rounded-circle"><iconify-icon icon="iconamoon:like-duotone" class="fs-36"></iconify-icon></div>
                                                                    </div>
                                                                    <h3 class="mt-0">Finished !</h3>

                                                                    <p class="w-75 mb-2 mx-auto">Filled Data Successfully.</p>

                                                                    <div class="mb-3">
                                                                        <div class="form-check d-inline-block">
                                                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                                                            <label class="form-check-label" for="customCheck2">I agree with the Terms and Conditions</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- end col -->
                                                        </div> <!-- end row -->
                                                    </div><!-- end tab-pane -->

                                                    <div class="d-flex flex-wrap align-items-center wizard justify-content-between gap-3 mt-3">
                                                        <div class="first">
                                                            <a href="javascript:void(0);" class="btn btn-soft-primary">
                                                                First
                                                            </a>
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            <div class="previous">
                                                                <a href="javascript:void(0);" class="btn btn-primary disabled">
                                                                    <i class="bx bx-left-arrow-alt me-2"></i>Back To Previous
                                                                </a>
                                                            </div>
                                                            <div class="next">
                                                                <a href="javascript:void(0);" class="btn btn-primary">
                                                                    Next Step<i class="bx bx-right-arrow-alt ms-2"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="last">
                                                            <a href="javascript:void(0);" class="btn btn-soft-primary">
                                                                Finish
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div> <!-- tab-content -->
                                            </div>
                                        </div> <!-- end #horizontalwizard-->
                                    </form>
                                </div>
                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <ul class="nav nav-pills bg-transparent">
                                            <li class="nav-item">
                                                <a href="#vertical-wiazrd-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#vertical-wiazrd-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="vertical-wiazrd-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;form id=&quot;verticalwizard&quot;&gt;
                                                        &lt;div class=&quot;row&quot;&gt;
                                                            &lt;div class=&quot;col-lg-3&quot;&gt;
                                                                &lt;ul class=&quot;nav nav-pills nav-justified flex-column icon-wizard form-wizard-header bg-light p-1&quot; role=&quot;tablist&quot;&gt;
                                                                    &lt;li class=&quot;nav-item&quot; role=&quot;presentation&quot;&gt;
                                                                        &lt;a href=&quot;#basictabHorizontal1&quot; data-bs-toggle=&quot;tab&quot; data-toggle=&quot;tab&quot; class=&quot;nav-link rounded-0 py-2 active&quot; aria-selected=&quot;true&quot; role=&quot;tab&quot;&gt;
                                                                            &lt;iconify-icon icon=&quot;iconamoon:profile-circle-duotone&quot; class=&quot;fs-26&quot;&gt;&lt;/iconify-icon&gt;
                                                                            Account
                                                                        &lt;/a&gt;&lt;!-- end nav-link --&gt;
                                                                    &lt;/li&gt;&lt;!-- end nav-item --&gt;
                                                                    &lt;li class=&quot;nav-item&quot; role=&quot;presentation&quot;&gt;
                                                                        &lt;a href=&quot;#basictabHorizontal2&quot; data-bs-toggle=&quot;tab&quot; data-toggle=&quot;tab&quot; class=&quot;nav-link rounded-0 py-2&quot; aria-selected=&quot;false&quot; role=&quot;tab&quot; tabindex=&quot;-1&quot;&gt;
                                                                            &lt;iconify-icon icon=&quot;iconamoon:profile-duotone&quot; class=&quot;fs-26&quot;&gt;&lt;/iconify-icon&gt;
                                                                            Profile
                                                                        &lt;/a&gt;&lt;!-- end nav-link --&gt;
                                                                    &lt;/li&gt;&lt;!-- end nav-item --&gt;
                                                                    &lt;li class=&quot;nav-item&quot; role=&quot;presentation&quot;&gt;
                                                                        &lt;a href=&quot;#basictabHorizontal3&quot; data-bs-toggle=&quot;tab&quot; data-toggle=&quot;tab&quot; class=&quot;nav-link rounded-0 py-2&quot; aria-selected=&quot;false&quot; tabindex=&quot;-1&quot; role=&quot;tab&quot;&gt;
                                                                            &lt;iconify-icon icon=&quot;iconamoon:link-fill&quot; class=&quot;fs-26&quot;&gt;&lt;/iconify-icon&gt;
                                                                            Social Links
                                                                        &lt;/a&gt;&lt;!-- end nav-link --&gt;
                                                                    &lt;/li&gt;&lt;!-- end nav-item --&gt;
                                                                    &lt;li class=&quot;nav-item&quot; role=&quot;presentation&quot;&gt;
                                                                        &lt;a href=&quot;#basictabHorizontal4&quot; data-bs-toggle=&quot;tab&quot; data-toggle=&quot;tab&quot; class=&quot;nav-link rounded-0 py-2&quot; aria-selected=&quot;false&quot; tabindex=&quot;-1&quot; role=&quot;tab&quot;&gt;
                                                                            &lt;iconify-icon icon=&quot;iconamoon:check-circle-1-duotone&quot; class=&quot;fs-26&quot;&gt;&lt;/iconify-icon&gt;
                                                                            Finish
                                                                        &lt;/a&gt;&lt;!-- end nav-link --&gt;
                                                                    &lt;/li&gt;&lt;!-- end nav-item --&gt;
                                                                &lt;/ul&gt;
                                                            &lt;/div&gt;
                
                                                            &lt;div class=&quot;col-lg-9&quot;&gt;
                                                                &lt;div class=&quot;tab-content mb-0&quot;&gt;
                                                                    &lt;div class=&quot;tab-pane active show&quot; id=&quot;basictabHorizontal1&quot; role=&quot;tabpanel&quot;&gt;
                                                                        &lt;h4 class=&quot;fs-16 fw-semibold mb-1&quot;&gt;Account Information&lt;/h4&gt;
                                                                        &lt;p class=&quot;text-muted&quot;&gt;Setup your account information&lt;/p&gt;
                
                                                                        &lt;div class=&quot;row&quot;&gt;
                                                                            &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                    &lt;label for=&quot;basicUser2&quot; class=&quot;form-label&quot;&gt;User Name&lt;/label&gt;
                                                                                    &lt;input id=&quot;basicUser2&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Enter User Name&quot;&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt; &lt;!-- end col --&gt;
                                                                            &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                    &lt;label for=&quot;basicEmail2&quot; class=&quot;form-label&quot;&gt;Email&lt;/label&gt;
                                                                                    &lt;input id=&quot;basicEmail2&quot; type=&quot;email&quot; class=&quot;form-control&quot; placeholder=&quot;Enter your email&quot;&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt; &lt;!-- end col --&gt;
                                                                            &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                    &lt;label for=&quot;basicPassworda2&quot; class=&quot;form-label&quot;&gt;Password&lt;/label&gt;
                                                                                    &lt;input id=&quot;basicPassworda2&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Enter Password&quot;&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt; &lt;!-- end col --&gt;
                                                                            &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                    &lt;label for=&quot;basicConfirmPassword2&quot; class=&quot;form-label&quot;&gt;Confirm Password&lt;/label&gt;
                                                                                    &lt;input id=&quot;basicConfirmPassword2&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Confirm a Password&quot;&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt; &lt;!-- end col --&gt;
                                                                        &lt;/div&gt; &lt;!-- end row --&gt;
                                                                    &lt;/div&gt;&lt;!-- end tab-pane --&gt;
                
                                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;basictabHorizontal2&quot; role=&quot;tabpanel&quot;&gt;
                                                                        &lt;h4 class=&quot;fs-16 fw-semibold mb-1&quot;&gt;Profile Information&lt;/h4&gt;
                                                                        &lt;p class=&quot;text-muted&quot;&gt;Setup your profile information&lt;/p&gt;
                
                                                                        &lt;div class=&quot;row&quot;&gt;
                                                                            &lt;div class=&quot;col-12&quot;&gt;
                                                                                &lt;div class=&quot;avatar-lg mb-3&quot;&gt;
                                                                                    &lt;div class=&quot;avatar-title bg-body rounded-circle border border-3 border-dashed-light position-relative&quot;&gt;
                                                                                        &lt;label for=&quot;imageInput&quot; class=&quot;position-absolute end-0 bottom-0&quot;&gt;
                                                                                            &lt;div class=&quot;avatar-xs cursor-pointer&quot;&gt;
                                                                                                &lt;span class=&quot;avatar-title bg-light text-dark rounded-circle&quot;&gt;&lt;i class=&quot;bx bx-camera&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                                                            &lt;/div&gt;
                                                                                        &lt;/label&gt;
                                                                                        &lt;input class=&quot;hidden&quot; type=&quot;file&quot; id=&quot;imageInput&quot; accept=&quot;image/*&quot; onchange=&quot;previewImage(event)&quot;&gt;
                
                                                                                        &lt;img id=&quot;preview&quot; src=&quot;assets/images/users/dummy-avatar.jpg&quot; alt=&quot;Preview Image&quot; class=&quot;rounded-circle img-fluid&quot;&gt;
                                                                                    &lt;/div&gt;
                                                                                &lt;/div&gt;
                
                                                                                &lt;div class=&quot;row&quot;&gt;
                                                                                    &lt;div class=&quot;col-md-6&quot;&gt;
                                                                                        &lt;div class=&quot;mb-3&quot;&gt;
                                                                                            &lt;label class=&quot;form-label&quot; for=&quot;basicFname2&quot;&gt;First Name&lt;/label&gt;
                                                                                            &lt;input type=&quot;text&quot; id=&quot;basicFname2&quot; class=&quot;form-control&quot; placeholder=&quot;Chris&quot;&gt;
                                                                                        &lt;/div&gt;
                                                                                    &lt;/div&gt;&lt;!-- end col --&gt;
                                                                                    &lt;div class=&quot;col-md-6&quot;&gt;
                                                                                        &lt;div class=&quot;mb-3&quot;&gt;
                                                                                            &lt;label class=&quot;form-label&quot; for=&quot;basicLname2&quot;&gt;Last Name&lt;/label&gt;
                                                                                            &lt;input type=&quot;text&quot; id=&quot;basicLname2&quot; class=&quot;form-control&quot; placeholder=&quot;Keller&quot;&gt;
                                                                                        &lt;/div&gt;
                                                                                    &lt;/div&gt;&lt;!-- end col --&gt;
                                                                                    &lt;div class=&quot;col-md-6&quot;&gt;
                                                                                        &lt;div class=&quot;mb-3&quot;&gt;
                                                                                            &lt;label class=&quot;form-label&quot; for=&quot;basicMnumber2&quot;&gt;Number&lt;/label&gt;
                                                                                            &lt;input type=&quot;number&quot; id=&quot;basicMnumber2&quot; class=&quot;form-control&quot; placeholder=&quot;Mobile Number&quot;&gt;
                                                                                        &lt;/div&gt;
                                                                                    &lt;/div&gt;&lt;!-- end col --&gt;
                                                                                    &lt;div class=&quot;col-md-6&quot;&gt;
                                                                                        &lt;div class=&quot;mb-3&quot;&gt;
                                                                                            &lt;label class=&quot;form-label&quot; for=&quot;basicCountry2&quot;&gt;Country&lt;/label&gt;
                                                                                            &lt;select id=&quot;basicCountry2&quot; class=&quot;form-select&quot;&gt;
                                                                                                &lt;option value=&quot;United States&quot;&gt;United States&lt;/option&gt;
                                                                                                &lt;option value=&quot;Canada&quot;&gt;Canada&lt;/option&gt;
                                                                                                &lt;option value=&quot;Australia&quot;&gt;Australia&lt;/option&gt;
                                                                                                &lt;option value=&quot;Germany&quot;&gt;Germany&lt;/option&gt;
                                                                                                &lt;option value=&quot;Bangladesh&quot;&gt;Bangladesh&lt;/option&gt;
                                                                                                &lt;option value=&quot;China&quot;&gt;China&lt;/option&gt;
                                                                                                &lt;option value=&quot;Argentina&quot;&gt;Argentina&lt;/option&gt;
                                                                                                &lt;option value=&quot;Bharat&quot;&gt;Bharat&lt;/option&gt;
                                                                                                &lt;option value=&quot;Afghanistan&quot;&gt;Afghanistan&lt;/option&gt;
                                                                                                &lt;option value=&quot;France&quot;&gt;France&lt;/option&gt;
                                                                                                &lt;option value=&quot;Brazil&quot;&gt;Brazil&lt;/option&gt;
                                                                                                &lt;option value=&quot;Belgium&quot;&gt;Belgium&lt;/option&gt;
                                                                                                &lt;option value=&quot;Colombia&quot;&gt;Colombia&lt;/option&gt;
                                                                                                &lt;option value=&quot;Albania&quot;&gt;Albania&lt;/option&gt;
                                                                                            &lt;/select&gt;
                                                                                        &lt;/div&gt;
                                                                                    &lt;/div&gt;&lt;!-- end col --&gt;
                                                                                &lt;/div&gt;&lt;!-- end row --&gt;
                                                                            &lt;/div&gt; &lt;!-- end col --&gt;
                                                                        &lt;/div&gt; &lt;!-- end row --&gt;
                                                                    &lt;/div&gt;&lt;!-- end tab-pane --&gt;
                
                                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;basictabHorizontal3&quot; role=&quot;tabpanel&quot;&gt;
                                                                        &lt;h4 class=&quot;fs-16 fw-semibold mb-1&quot;&gt;Social Media Links&lt;/h4&gt;
                                                                        &lt;p class=&quot;text-muted&quot;&gt;Fill your social media links&lt;/p&gt;
                
                                                                        &lt;div class=&quot;row&quot;&gt;
                                                                            &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                    &lt;label for=&quot;basicGitLink2&quot; class=&quot;form-label&quot;&gt;GitHub&lt;/label&gt;
                                                                                    &lt;input id=&quot;basicGitLink2&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;GitHub Link&quot;&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt; &lt;!-- end col --&gt;
                                                                            &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                    &lt;label for=&quot;basicGoogleLink2&quot; class=&quot;form-label&quot;&gt;Google&lt;/label&gt;
                                                                                    &lt;input id=&quot;basicGoogleLink2&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Google Link&quot;&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt; &lt;!-- end col --&gt;
                                                                            &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                    &lt;label for=&quot;basicInstagramLink3&quot; class=&quot;form-label&quot;&gt;Instagram&lt;/label&gt;
                                                                                    &lt;input id=&quot;basicInstagramLink3&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Instagram Link&quot;&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt; &lt;!-- end col --&gt;
                                                                            &lt;div class=&quot;col-lg-6&quot;&gt;
                                                                                &lt;div class=&quot;mb-3&quot;&gt;
                                                                                    &lt;label for=&quot;basicSkypeLink4&quot; class=&quot;form-label&quot;&gt;Skype&lt;/label&gt;
                                                                                    &lt;input id=&quot;basicSkypeLink4&quot; type=&quot;text&quot; class=&quot;form-control&quot; placeholder=&quot;Skype Link&quot;&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt; &lt;!-- end col --&gt;
                                                                        &lt;/div&gt;&lt;!-- end row --&gt;
                                                                    &lt;/div&gt;&lt;!-- end tab-pane --&gt;
                
                                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;basictabHorizontal4&quot; role=&quot;tabpanel&quot;&gt;
                                                                        &lt;div class=&quot;row&quot;&gt;
                                                                            &lt;div class=&quot;col-12&quot;&gt;
                                                                                &lt;div class=&quot;text-center&quot;&gt;
                                                                                    &lt;div class=&quot;avatar-md mx-auto mb-3&quot;&gt;
                                                                                        &lt;div class=&quot;avatar-title bg-primary bg-opacity-10 text-primary rounded-circle&quot;&gt;&lt;iconify-icon icon=&quot;iconamoon:like-duotone&quot; class=&quot;fs-36&quot;&gt;&lt;/iconify-icon&gt;&lt;/div&gt;
                                                                                    &lt;/div&gt;
                                                                                    &lt;h3 class=&quot;mt-0&quot;&gt;Finished !&lt;/h3&gt;
                
                                                                                    &lt;p class=&quot;w-75 mb-2 mx-auto&quot;&gt;Filled Data Successfully.&lt;/p&gt;
                
                                                                                    &lt;div class=&quot;mb-3&quot;&gt;
                                                                                        &lt;div class=&quot;form-check d-inline-block&quot;&gt;
                                                                                            &lt;input type=&quot;checkbox&quot; class=&quot;form-check-input&quot; id=&quot;customCheck2&quot;&gt;
                                                                                            &lt;label class=&quot;form-check-label&quot; for=&quot;customCheck2&quot;&gt;I agree with the Terms and Conditions&lt;/label&gt;
                                                                                        &lt;/div&gt;
                                                                                    &lt;/div&gt;
                                                                                &lt;/div&gt;
                                                                            &lt;/div&gt; &lt;!-- end col --&gt;
                                                                        &lt;/div&gt; &lt;!-- end row --&gt;
                                                                    &lt;/div&gt;&lt;!-- end tab-pane --&gt;
                
                                                                    &lt;div class=&quot;d-flex flex-wrap align-items-center wizard justify-content-between gap-3 mt-3&quot;&gt;
                                                                        &lt;div class=&quot;first&quot;&gt;
                                                                            &lt;a href=&quot;javascript:void(0);&quot; class=&quot;btn btn-soft-primary&quot;&gt;
                                                                                First
                                                                            &lt;/a&gt;
                                                                        &lt;/div&gt;
                                                                        &lt;div class=&quot;d-flex gap-2&quot;&gt;
                                                                            &lt;div class=&quot;previous&quot;&gt;
                                                                                &lt;a href=&quot;javascript:void(0);&quot; class=&quot;btn btn-primary disabled&quot;&gt;
                                                                                    &lt;i class=&quot;bx bx-left-arrow-alt me-2&quot;&gt;&lt;/i&gt;Back To Previous
                                                                                &lt;/a&gt;
                                                                            &lt;/div&gt;
                                                                            &lt;div class=&quot;next&quot;&gt;
                                                                                &lt;a href=&quot;javascript:void(0);&quot; class=&quot;btn btn-primary&quot;&gt;
                                                                                    Next Step&lt;i class=&quot;bx bx-right-arrow-alt ms-2&quot;&gt;&lt;/i&gt;
                                                                                &lt;/a&gt;
                                                                            &lt;/div&gt;
                                                                        &lt;/div&gt;
                                                                        &lt;div class=&quot;last&quot;&gt;
                                                                            &lt;a href=&quot;javascript:void(0);&quot; class=&quot;btn btn-soft-primary&quot;&gt;
                                                                                Finish
                                                                            &lt;/a&gt;
                                                                        &lt;/div&gt;
                                                                    &lt;/div&gt;
                                                                &lt;/div&gt; &lt;!-- tab-content --&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt; &lt;!-- end #verticalwizard--&gt;
                                                    &lt;/form&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="vertical-wiazrd-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        new Wizard('#verticalwizard');
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> <!-- end card body -->
                        </div> <!-- end card -->
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

    <script src="/js/components/form-wizard.js"></script>

</body>

</html>