<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Calendar")) ?>

    <!-- Fullcalendar css -->
    <link href="/vendor/fullcalendar/main.min.css" rel="stylesheet" type="text/css" />

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Calendar")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">

            <!-- Start Container -->
            <div class="container-xxl">

                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div class="d-grid">
                                            <button type="button" class="btn btn-primary" id="btn-new-event">
                                                <i class="bx bx-plus fs-18 me-2"></i> Add New Schedule
                                            </button>
                                        </div>
                                        <div id="external-events">
                                            <br>
                                            <p class="text-muted">Drag and drop your event or click in the calendar</p>
                                            <div class="external-event bg-soft-primary text-primary" data-class="bg-primary">
                                                <i class="bx bxs-circle me-2 vertical-middle"></i>Team Building Retreat Meeting
                                            </div>
                                            <div class="external-event bg-soft-info text-info" data-class="bg-info">
                                                <i class="bx bxs-circle me-2 vertical-middle"></i>Product Launch Strategy Meeting
                                            </div>
                                            <div class="external-event bg-soft-success text-success" data-class="bg-success">
                                                <i class="bx bxs-circle me-2 vertical-middle"></i>Monthly Sales Review
                                            </div>
                                            <div class="external-event bg-soft-danger text-danger" data-class="bg-danger">
                                                <i class="bx bxs-circle me-2 vertical-middle"></i>Team Lunch Celebration
                                            </div>
                                            <div class="external-event bg-soft-warning text-warning" data-class="bg-warning">
                                                <i class="bx bxs-circle me-2 vertical-middle"></i>Marketing Campaign Kickoff
                                            </div>
                                        </div>
                                    </div> <!-- end col-->

                                    <div class="col-xl-9">
                                        <div class="mt-4 mt-lg-0">
                                            <div id="calendar"></div>
                                        </div>
                                    </div> <!-- end col -->

                                </div> <!-- end row -->
                            </div> <!-- end card body-->
                        </div> <!-- end card -->

                        <!-- Add New Event MODAL -->
                        <div class="modal fade" id="event-modal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form class="needs-validation" name="event-form" id="forms-event" novalidate>
                                        <div class="modal-header p-3 border-bottom-0">
                                            <h5 class="modal-title" id="modal-title">Event</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body px-3 pb-3 pt-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Event Name</label>
                                                        <input class="form-control" placeholder="Insert Event Name" type="text" name="title" id="event-title" required />
                                                        <div class="invalid-feedback">Please provide a valid event name</div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="control-label form-label">Category</label>
                                                        <select class="form-select" name="category" id="event-category" required>
                                                            <option value="bg-primary">Blue</option>
                                                            <option value="bg-secondary">Gray Dark</option>
                                                            <option value="bg-success">Green</option>
                                                            <option value="bg-info">Cyan</option>
                                                            <option value="bg-warning">Yellow</option>
                                                            <option value="bg-danger">Red</option>
                                                            <option value="bg-dark">Dark</option>
                                                        </select>
                                                        <div class="invalid-feedback">Please select a valid event category</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <button type="button" class="btn btn-danger" id="btn-delete-event">Delete</button>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <button type="button" class="btn btn-light me-1" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary" id="btn-save-event">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div> <!-- end modal-content-->
                            </div> <!-- end modal dialog-->
                        </div> <!-- end modal-->
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

    <!-- Full Calendar -->
    <script src="/vendor/fullcalendar/main.min.js"></script>

    <!-- Page Js -->
    <script src="/js/pages/app-calendar.js"></script>

</body>

</html>