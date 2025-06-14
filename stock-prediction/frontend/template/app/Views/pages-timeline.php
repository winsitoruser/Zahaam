<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Timeline")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Timeline")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">

            <!-- Start Container xxl -->
            <div class="container-xxl">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="timeline">

                            <article class="timeline-time">
                                <div class="time-show d-flex align-items-center justify-content-center mt-0">
                                    <h5 class="mb-0 text-uppercase fs-14 fw-semibold">Today</h5>
                                </div>
                            </article>


                            <article class="timeline-item timeline-item-left">
                                <div class="timeline-desk">
                                    <div class="timeline-box clearfix">
                                        <span class="timeline-icon"></span>
                                        <div class="overflow-hidden">
                                            <div class="card d-inline-block">
                                                <div class="card-body">
                                                    <h5 class="mt-0 fs-16">Completed UX design project for our client<span class="badge bg-secondary ms-1 align-items-center">important</span>
                                                    </h5>
                                                    <p class="text-muted mb-0">Dolorum provident rerum aut hic quasi
                                                        placeat iure tempora laudantium ipsa ad debitis unde?</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <article class="timeline-item">
                                <div class="timeline-desk">
                                    <div class="timeline-box clearfix">
                                        <span class="timeline-icon"></span>
                                        <div class="overflow-hidden">
                                            <div class="card d-inline-block">
                                                <div class="card-body">
                                                    <h5 class="mt-0 fs-16">Yes! We are celebrating our first admin release.
                                                    </h5>
                                                    <p class="text-muted mb-0">Consectetur adipisicing elit. Iusto,
                                                        optio, dolorum <b>John deon</b> provident.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <article class="timeline-item timeline-item-left">
                                <div class="timeline-desk">
                                    <div class="timeline-box clearfix">
                                        <span class="timeline-icon"></span>
                                        <div class="overflow-hidden">
                                            <div class="card d-inline-block">
                                                <div class="card-body">
                                                    <h5 class="mt-0 fs-16">We released new version of our theme Larkon.</h5>
                                                    <p class="text-muted mb-0">3 new photo Uploaded on facebook fan page
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <article class="timeline-time">
                                <div class="time-show d-flex align-items-center mt-0">
                                    <h5 class="mb-0 text-uppercase fs-14 fw-semibold">Yesterday</h5>
                                </div>
                            </article>

                            <article class="timeline-item">
                                <div class="timeline-desk">
                                    <div class="timeline-box clearfix">
                                        <span class="timeline-icon"></span>
                                        <div class="overflow-hidden">
                                            <div class="card d-inline-block">
                                                <div class="card-body">
                                                    <h5 class="mt-0 fs-16">We have archieved 25k sales in our themes</h5>
                                                    <p class="text-muted mb-0">Dolorum provident rerum aut hic quasi
                                                        placeat iure tempora laudantium ipsa ad debitis unde?</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <article class="timeline-item timeline-item-left">
                                <div class="timeline-desk">
                                    <div class="timeline-box clearfix">
                                        <span class="timeline-icon"></span>
                                        <div class="overflow-hidden">
                                            <div class="card d-inline-block">
                                                <div class="card-body">
                                                    <h5 class="mt-0 fs-16">Yes! We are celebrating our first admin release.
                                                    </h5>
                                                    <p class="text-muted mb-0">Outdoor visit at California State Route
                                                        85 with John Boltana & Harry Piterson.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>

                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->

                <div class="row">
                    <div class="col-12">
                        <h5 class="card-title mb-3">Left Timeline</h5>
                        <div class="d-flex flex-row fs-18 align-items-center mb-3">
                            <h5 class="mb-0">Today</h5>
                        </div>
                        <ul class="list-unstyled left-timeline">
                            <li class="left-timeline-list">
                                <div class="card d-inline-block">
                                    <div class="card-body">
                                        <h5 class="mt-0 fs-16">Completed UX design project for our client<span class="badge bg-secondary ms-1 align-items-center">important</span></h5>
                                        <p class="text-muted mb-0">Dolorum provident rerum aut hic quasi placeat iure
                                            tempora laudantium ipsa ad debitis unde?</p>
                                    </div>
                                </div>
                            </li>
                            <li class="left-timeline-list">
                                <div class="card d-inline-block">
                                    <div class="card-body">
                                        <h5 class="mt-0 fs-16">Yes! We are celebrating our first admin release.</h5>
                                        <p class="text-muted mb-0">Consectetur adipisicing elit. Iusto, optio, dolorum
                                            <b>John deon</b> provident rerum aut hic quasi placeat iure tempora
                                            laudantium
                                        </p>
                                    </div>
                                </div>
                            </li>
                            <li class="left-timeline-list">
                                <div class="card d-inline-block">
                                    <div class="card-body">
                                        <h5 class="mt-0 fs-16">We released new version of our theme Larkon.</h5>
                                        <p class="text-muted mb-0">3 new photo Uploaded on facebook fan page</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="d-flex flex-row fs-18 align-items-center mb-3">
                            <h5 class="mb-0">Yesterday</h5>
                        </div>
                        <ul class="list-unstyled left-timeline">
                            <li class="left-timeline-list">
                                <div class="card d-inline-block">
                                    <div class="card-body">
                                        <h5 class="mt-0 fs-16">We have archieved 25k sales in our themes</h5>
                                        <p class="text-muted mb-0">Dolorum provident rerum aut hic quasi placeat iure
                                            tempora laudantium ipsa ad debitis unde?</p>
                                    </div>
                                </div>
                            </li>
                            <li class="left-timeline-list">
                                <div class="card d-inline-block">
                                    <div class="card-body">
                                        <h5 class="mt-0 fs-16">Yes! We are celebrating our first admin release.</h5>
                                        <p class="text-muted mb-0">Outdoor visit at California State Route 85 with John
                                            Boltana & Harry Piterson regarding to setup a new show room.</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="d-flex flex-row fs-18 align-items-center mb-3">
                            <h5 class="mb-0">5 days ago</h5>
                        </div>
                        <ul class="list-unstyled left-timeline">
                            <li class="left-timeline-list">
                                <div class="card d-inline-block">
                                    <div class="card-body">
                                        <h5 class="mt-0 fs-16">Join new team member Alex Smith</h5>
                                        <p class="text-muted mb-0">Alex Smith is a Senior Software (Full Stack) engineer
                                            with a deep passion for building usable, functional & pretty web
                                            applications.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="left-timeline-list">
                                <div class="card d-inline-block">
                                    <div class="card-body">
                                        <h5 class="mt-0 fs-16">First release of Larkon admin dashboard template</h5>
                                        <p class="text-muted mb-0">Outdoor visit at California State Route 85 with John
                                            Boltana & Harry Piterson regarding to setup a new show room.</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
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
    <!-- END Wrapper -->

    <?= $this->include("partials/vendor-scripts") ?>

</body>

</html>