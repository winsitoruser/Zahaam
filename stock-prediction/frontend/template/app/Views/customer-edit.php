<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Customer List")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Customer List")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->

        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container-xxl">

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div>
                                <form class="d-flex flex-wrap align-items-center gap-2">
                                    <label for="inputPassword2" class="visually-hidden">Search</label>
                                    <div class="search-bar me-3">
                                        <span><i class="bx bx-search-alt"></i></span>
                                        <input type="search" class="form-control" id="search" placeholder="Search ...">
                                    </div>

                                    <label for="status-select" class="me-2">Sort By</label>
                                    <div class="me-sm-3">
                                        <select class="form-select my-1 my-md-0" id="status-select">
                                            <option selected="">All</option>
                                            <option value="1">Name</option>
                                            <option value="2">Store Name</option>
                                            <option value="3">Rating</option>
                                            <option value="4">Revenue</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div>
                                <div class="d-flex flex-wrap gap-2 justify-content-md-end align-items-center">
                                    <ul class="nav nav-pills bg-transparent  gap-1 p-0">
                                        <li class="nav-item">
                                            <a href="#seller-grid" class="nav-link" data-bs-toggle="tab" aria-expanded="false" data-bs-toggle="tooltip" data-bs-placement="top" title="Grid">
                                                <i class="bx bx-grid-alt"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#seller-list" class="nav-link active" data-bs-toggle="tab" aria-expanded="false" data-bs-toggle="tooltip" data-bs-placement="top" title="List">
                                                <i class="bx bx-list-ul"></i>
                                            </a>
                                        </li>
                                    </ul>
                                    <a href="#!" class="btn btn-danger">
                                        <i class="bx bx-plus me-1"></i>Add Seller
                                    </a>
                                </div>
                            </div><!-- end col-->
                        </div> <!-- end row -->
                    </div> <!-- end card body -->
                </div> <!-- end card -->


                <div class="tab-content pt-0">
                    <div class="tab-pane show active" id="seller-list">
                        <div class="card overflow-hidden">
                            <div class="table-responsive table-centered text-nowrap">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Seller</th>
                                            <th>Store Name</th>
                                            <th>Rating</th>
                                            <th>Products</th>
                                            <th>Wallet Balance</th>
                                            <th>Create Date</th>
                                            <th>Revenue</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead> <!-- end thead-->
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <img src="s/users/avatar-1.jpg" alt="avatar-1" class="img-fluid avatar-sm rounded-circle avatar-border me-1" />
                                                    <a href="#!">Anna M. Hines</a>
                                                </div>
                                            </td>
                                            <td>Acme</td>
                                            <td><span class="badge badge-soft-success"><i class="bx bxs-star me-1"></i>4.9</span></td>
                                            <td>356</td>
                                            <td>$256.45</td>
                                            <td>09/04/2021</td>
                                            <td>$269.56</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary me-1"><i class="bx bx-edit fs-18"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <img src="s/users/avatar-2.jpg" alt="avatar-2" class="img-fluid avatar-sm rounded-circle avatar-border me-1" />
                                                    <a href="#!">Candice F. Gilmore</a>
                                                </div>
                                            </td>
                                            <td>Globex</td>
                                            <td><span class="badge badge-soft-warning"><i class="bx bxs-star me-1"></i>3.2</span></td>
                                            <td>289</td>
                                            <td>$156.98</td>
                                            <td>13/05/2021</td>
                                            <td>$89.75</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary me-1"><i class="bx bx-edit fs-18"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <img src="s/users/avatar-3.jpg" alt="avatar-3" class="img-fluid avatar-sm rounded-circle avatar-border me-1" />
                                                    <a href="#!">Vanessa R. Davis</a>
                                                </div>
                                            </td>
                                            <td>Soylent</td>
                                            <td><span class="badge badge-soft-success"><i class="bx bxs-star me-1"></i>4.1</span></td>
                                            <td>71</td>
                                            <td>$859.50</td>
                                            <td>21/02/2020</td>
                                            <td>$452.50</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary me-1"><i class="bx bx-edit fs-18"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <img src="s/users/avatar-4.jpg" alt="avatar-4" class="img-fluid avatar-sm rounded-circle avatar-border me-1" />
                                                    <a href="#!">Judith H. Fritsche</a>
                                                </div>
                                            </td>
                                            <td>Initech</td>
                                            <td><span class="badge badge-soft-warning"><i class="bx bxs-star me-1"></i>2.5</span></td>
                                            <td>125</td>
                                            <td>$163.75</td>
                                            <td>09/05/2020</td>
                                            <td>$365</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary me-1"><i class="bx bx-edit fs-18"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <img src="s/users/avatar-5.jpg" alt="avatar-5" class="img-fluid avatar-sm rounded-circle avatar-border me-1" />
                                                    <a href="#!">Peter T. Smith</a>
                                                </div>
                                            </td>
                                            <td>Hooli</td>
                                            <td><span class="badge badge-soft-warning"><i class="bx bxs-star me-1"></i>3.7</span></td>
                                            <td>265</td>
                                            <td>$545</td>
                                            <td>15/06/2019</td>
                                            <td>$465.59</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary me-1"><i class="bx bx-edit fs-18"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <img src="s/users/avatar-6.jpg" alt="avatar-6" class="img-fluid avatar-sm rounded-circle avatar-border me-1" />
                                                    <a href="#!">Emmanuel J. Delcid</a>
                                                </div>
                                            </td>
                                            <td>Vehement</td>
                                            <td><span class="badge badge-soft-success"><i class="bx bxs-star me-1"></i>4.3</span></td>
                                            <td>68</td>
                                            <td>$136.54</td>
                                            <td>11/12/2019</td>
                                            <td>$278.95</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary me-1"><i class="bx bx-edit fs-18"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <img src="s/users/avatar-7.jpg" alt="avatar-7" class="img-fluid avatar-sm rounded-circle avatar-border me-1" />
                                                    <a href="#!">William J. Cook</a>
                                                </div>
                                            </td>
                                            <td>Massive</td>
                                            <td><span class="badge badge-soft-danger"><i class="bx bxs-star me-1"></i>1.8</span></td>
                                            <td>550</td>
                                            <td>$365.85</td>
                                            <td>26/1/2021</td>
                                            <td>$475.96</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary me-1"><i class="bx bx-edit fs-18"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <img src="s/users/avatar-8.jpg" alt="avatar-8" class="img-fluid avatar-sm rounded-circle avatar-border me-1" />
                                                    <a href="#!">Martin R. Peters</a>
                                                </div>
                                            </td>
                                            <td>Fringe</td>
                                            <td><span class="badge badge-soft-warning"><i class="bx bxs-star me-1"></i>3.5</span></td>
                                            <td>123</td>
                                            <td>$95.70</td>
                                            <td>13/04/2020</td>
                                            <td>$142</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary me-1"><i class="bx bx-edit fs-18"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <img src="s/users/avatar-9.jpg" alt="avatar-9" class="img-fluid avatar-sm rounded-circle avatar-border me-1" />
                                                    <a href="#!">Paul M. Schubert</a>
                                                </div>
                                            </td>
                                            <td>Weeds</td>
                                            <td><span class="badge badge-soft-warning"><i class="bx bxs-star me-1"></i>2.4</span></td>
                                            <td>789</td>
                                            <td>$423.40</td>
                                            <td>05/07/2020</td>
                                            <td>$652.90</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary me-1"><i class="bx bx-edit fs-18"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-1">
                                                    <img src="s/users/avatar-10.jpg" alt="avatar-10" class="img-fluid avatar-sm rounded-circle avatar-border me-1" />
                                                    <a href="#!">Janet J. Champine</a>
                                                </div>
                                            </td>
                                            <td>Soylent</td>
                                            <td><span class="badge badge-soft-success"><i class="bx bxs-star me-1"></i>4.6</span></td>
                                            <td>75</td>
                                            <td>$216.80</td>
                                            <td>17/03/2019</td>
                                            <td>$180.75</td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-primary me-1"><i class="bx bx-edit fs-18"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-soft-danger"><i class="bx bx-trash fs-18"></i></a>
                                            </td>
                                        </tr>
                                    </tbody> <!-- end tbody -->
                                </table> <!-- end table -->
                            </div> <!-- table responsive -->
                            <div class="align-items-center justify-content-between row g-0 text-center text-sm-start p-3 border-top">
                                <div class="col-sm">
                                    <div class="text-muted">
                                        Showing <span class="fw-semibold">10</span> of <span class="fw-semibold">269</span> Results
                                    </div>
                                </div>
                                <div class="col-sm-auto mt-3 mt-sm-0">
                                    <ul class="pagination pagination-rounded m-0">
                                        <li class="page-item">
                                            <a href="#" class="page-link"><i class='bx bx-left-arrow-alt'></i></a>
                                        </li>
                                        <li class="page-item active">
                                            <a href="#" class="page-link">1</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link">2</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link">3</a>
                                        </li>
                                        <li class="page-item">
                                            <a href="#" class="page-link"><i class='bx bx-right-arrow-alt'></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="seller-grid">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5 gx-3">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-phone-call me-2"></i>Direct Call
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-envelope-open me-2"></i>E mail
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bxl-linkedin me-2"></i>Linkedin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <a href="javascript:void(0);">
                                                <img src="s/users/avatar-1.jpg" alt="avatar-1" class="img-fluid img-thumbnail avatar-xxl rounded-circle avatar-border" />
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="mb-4 fw-bold">Anna M. Hines</h4>
                                        </div>
                                        <div class="mt-1 border-bottom">
                                            <p class="float-end mb-1">Acme</p>
                                            <p class="mb-1">Store Name</p>
                                        </div>
                                        <div class="mt-1 mb-2 border-bottom">
                                            <p class="float-end mb-1">356</p>
                                            <p class="mb-1">Products</p>
                                        </div>
                                        <a href="#!" class="btn btn-soft-primary w-100">View All Detail</a>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-phone-call me-2"></i>Direct Call
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-envelope-open me-2"></i>E mail
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bxl-linkedin me-2"></i>Linkedin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <a href="javascript:void(0);">
                                                <img src="s/users/avatar-2.jpg" alt="avatar-2" class="img-fluid img-thumbnail avatar-xxl rounded-circle avatar-border" />
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="mb-4 fw-bold">Candice F. Gilmore</h4>
                                        </div>
                                        <div class="mt-1 border-bottom">
                                            <p class="float-end mb-1">Globex</p>
                                            <p class="mb-1">Store Name</p>
                                        </div>
                                        <div class="mt-1 mb-2 border-bottom">
                                            <p class="float-end mb-1">289</p>
                                            <p class="mb-1">Products</p>
                                        </div>
                                        <a href="#!" class="btn btn-soft-primary w-100">View All Detail</a>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-phone-call me-2"></i>Direct Call
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-envelope-open me-2"></i>E mail
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bxl-linkedin me-2"></i>Linkedin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <a href="javascript:void(0);">
                                                <img src="s/users/avatar-3.jpg" alt="avatar-3" class="img-fluid img-thumbnail avatar-xxl rounded-circle avatar-border" />
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="mb-4 fw-bold">Vanessa R. Davis</h4>
                                        </div>
                                        <div class="mt-1 border-bottom">
                                            <p class="float-end mb-1">Prohaska-Ledner</p>
                                            <p class="mb-1">Store Name</p>
                                        </div>
                                        <div class="mt-1 mb-2 border-bottom">
                                            <p class="float-end mb-1">71</p>
                                            <p class="mb-1">Products</p>
                                        </div>
                                        <a href="#!" class="btn btn-soft-primary w-100">View All Detail</a>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-phone-call me-2"></i>Direct Call
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-envelope-open me-2"></i>E mail
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bxl-linkedin me-2"></i>Linkedin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <a href="javascript:void(0);">
                                                <img src="s/users/avatar-4.jpg" alt="avatar-4" class="img-fluid img-thumbnail avatar-xxl rounded-circle avatar-border" />
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="mb-4 fw-bold">Judith H. Fritsche</h4>
                                        </div>
                                        <div class="mt-1 border-bottom">
                                            <p class="float-end mb-1">Initech</p>
                                            <p class="mb-1">Store Name</p>
                                        </div>
                                        <div class="mt-1 mb-2 border-bottom">
                                            <p class="float-end mb-1">125</p>
                                            <p class="mb-1">Products</p>
                                        </div>
                                        <a href="#!" class="btn btn-soft-primary w-100">View All Detail</a>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-phone-call me-2"></i>Direct Call
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-envelope-open me-2"></i>E mail
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bxl-linkedin me-2"></i>Linkedin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <a href="javascript:void(0);">
                                                <img src="s/users/avatar-5.jpg" alt="avatar-5" class="img-fluid img-thumbnail avatar-xxl rounded-circle avatar-border" />
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="mb-4 fw-bold">Peter T. Smith</h4>
                                        </div>
                                        <div class="mt-1 border-bottom">
                                            <p class="float-end mb-1">Hooli</p>
                                            <p class="mb-1">Store Name</p>
                                        </div>
                                        <div class="mt-1 mb-2 border-bottom">
                                            <p class="float-end mb-1">265</p>
                                            <p class="mb-1">Products</p>
                                        </div>
                                        <a href="#!" class="btn btn-soft-primary w-100">View All Detail</a>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-phone-call me-2"></i>Direct Call
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-envelope-open me-2"></i>E mail
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bxl-linkedin me-2"></i>Linkedin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <a href="javascript:void(0);">
                                                <img src="s/users/avatar-6.jpg" alt="avatar-6" class="img-fluid img-thumbnail avatar-xxl rounded-circle avatar-border" />
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="mb-4 fw-bold">Emmanuel J. Delcid</h4>
                                        </div>
                                        <div class="mt-1 border-bottom">
                                            <p class="float-end mb-1">Vehement</p>
                                            <p class="mb-1">Store Name</p>
                                        </div>
                                        <div class="mt-1 mb-2 border-bottom">
                                            <p class="float-end mb-1">68</p>
                                            <p class="mb-1">Products</p>
                                        </div>
                                        <a href="#!" class="btn btn-soft-primary w-100">View All Detail</a>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-phone-call me-2"></i>Direct Call
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-envelope-open me-2"></i>E mail
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bxl-linkedin me-2"></i>Linkedin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <a href="javascript:void(0);">
                                                <img src="s/users/avatar-7.jpg" alt="avatar-7" class="img-fluid img-thumbnail avatar-xxl rounded-circle avatar-border" />
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="mb-4 fw-bold">William J. Cook</h4>
                                        </div>
                                        <div class="mt-1 border-bottom">
                                            <p class="float-end mb-1">Massive</p>
                                            <p class="mb-1">Store Name</p>
                                        </div>
                                        <div class="mt-1 mb-2 border-bottom">
                                            <p class="float-end mb-1">550</p>
                                            <p class="mb-1">Products</p>
                                        </div>
                                        <a href="#!" class="btn btn-soft-primary w-100">View All Detail</a>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-phone-call me-2"></i>Direct Call
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-envelope-open me-2"></i>E mail
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bxl-linkedin me-2"></i>Linkedin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <a href="javascript:void(0);">
                                                <img src="s/users/avatar-8.jpg" alt="avatar-8" class="img-fluid img-thumbnail avatar-xxl rounded-circle avatar-border" />
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="mb-4 fw-bold">Martin R. Peters</h4>
                                        </div>
                                        <div class="mt-1 border-bottom">
                                            <p class="float-end mb-1">Fringe</p>
                                            <p class="mb-1">Store Name</p>
                                        </div>
                                        <div class="mt-1 mb-2 border-bottom">
                                            <p class="float-end mb-1">123</p>
                                            <p class="mb-1">Products</p>
                                        </div>
                                        <a href="#!" class="btn btn-soft-primary w-100">View All Detail</a>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-phone-call me-2"></i>Direct Call
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-envelope-open me-2"></i>E mail
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bxl-linkedin me-2"></i>Linkedin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <a href="javascript:void(0);">
                                                <img src="s/users/avatar-9.jpg" alt="avatar-9" class="img-fluid img-thumbnail avatar-xxl rounded-circle avatar-border" />
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="mb-4 fw-bold">Paul M. Schubert</h4>
                                        </div>
                                        <div class="mt-1 border-bottom">
                                            <p class="float-end mb-1">Weeds</p>
                                            <p class="mb-1">Store Name</p>
                                        </div>
                                        <div class="mt-1 mb-2 border-bottom">
                                            <p class="float-end mb-1">789</p>
                                            <p class="mb-1">Products</p>
                                        </div>
                                        <a href="#!" class="btn btn-soft-primary w-100">View All Detail</a>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a href="javascript:void(0);" class="dropdown-toggle arrow-none text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-phone-call me-2"></i>Direct Call
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bx-envelope-open me-2"></i>E mail
                                                </a>
                                                <a href="javascript:void(0);" class="dropdown-item">
                                                    <i class="bx bxl-linkedin me-2"></i>Linkedin
                                                </a>
                                            </div>
                                        </div>
                                        <div class="text-center mb-3">
                                            <a href="javascript:void(0);">
                                                <img src="s/users/avatar-10.jpg" alt="avatar-10" class="img-fluid img-thumbnail avatar-xxl rounded-circle avatar-border" />
                                            </a>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="mb-4 fw-bold">Janet J. Champine</h4>
                                        </div>
                                        <div class="mt-1 border-bottom">
                                            <p class="float-end mb-1">Soylent</p>
                                            <p class="mb-1">Store Name</p>
                                        </div>
                                        <div class="mt-1 mb-2 border-bottom">
                                            <p class="float-end mb-1">75</p>
                                            <p class="mb-1">Products</p>
                                        </div>
                                        <a href="#!" class="btn btn-soft-primary w-100">View All Detail</a>
                                    </div> <!-- end card body -->
                                </div> <!-- end card -->
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div>

                </div>

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