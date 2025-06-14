<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Product Grid")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

<!-- START Wrapper -->
<div class="wrapper">

    <?php echo view("partials/topbar", array("title" => "Product Grid")) ?>
    <?= $this->include('partials/main-nav') ?>

    <!-- ==================================================== -->
    <!-- Start right Content here -->
    <!-- ==================================================== -->
    <div class="page-content">

        <!-- Start Container Fluid -->
        <div class="container-xxl">

            <div class="row">
                <div class="col-lg-3">
                    <div class="card bg-light-subtle">
                        <div class="card-header border-0">
                            <div class="search-bar me-3 mb-1">
                                <span><i class="bx bx-search-alt"></i></span>
                                <input type="search" class="form-control" id="search" placeholder="Search ...">
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body border-light">
                            <a href="#"
                               class="btn-link d-flex align-items-center text-dark bg-light p-2 rounded fw-medium fs-16 mb-0"
                               data-bs-toggle="collapse" data-bs-target="#categories" aria-expanded="false"
                               aria-controls="other">Categories
                                <i class='bx bx-chevron-down ms-auto fs-20'></i>
                            </a>
                            <div id="categories" class="collapse show">
                                <div class="categories-list d-flex flex-column gap-2 mt-2">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="all-categories" checked>
                                        <label class="form-check-label" for="all-categories">All Categories</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="fashion-categories">
                                        <label class="form-check-label" for="fashion-categories">Fashion Men , Women &
                                            Kid's</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="sunglass-categories">
                                        <label class="form-check-label" for="sunglass-categories">Eye Ware &
                                            Sunglass</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="watches-categories">
                                        <label class="form-check-label" for="watches-categories">Watches</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="electronics-categories">
                                        <label class="form-check-label" for="electronics-categories">Electronics
                                            Items</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="furniture-categories">
                                        <label class="form-check-label" for="furniture-categories">Furniture</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="headphones-categories">
                                        <label class="form-check-label" for="headphones-categories">Headphones</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="beauty-categories">
                                        <label class="form-check-label" for="beauty-categories">Beauty & Health</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="shoes-categories">
                                        <label class="form-check-label" for="shoes-categories">Foot Ware</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="#"
                                   class="btn-link d-flex align-items-center text-dark bg-light p-2 rounded fw-medium fs-16 mb-0"
                                   data-bs-toggle="collapse" data-bs-target="#price" aria-expanded="false"
                                   aria-controls="other">Product Price
                                    <i class='bx bx-chevron-down ms-auto fs-20'></i>
                                </a>
                                <div id="price" class="collapse show">
                                    <div class="categories-list d-flex flex-column gap-2 mt-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="all-price">
                                            <label class="form-check-label" for="all-price">All Price</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="price-1">
                                            <label class="form-check-label" for="price-1">Below $200 (145)</label>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="price-2">
                                            <label class="form-check-label" for="price-2">$200 - $500 (1,885)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="price-3">
                                            <label class="form-check-label" for="price-3">$500 - $800 (2,276)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="price-4">
                                            <label class="form-check-label" for="price-4">$800 - $1000 (12,676)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="price-5">
                                            <label class="form-check-label" for="price-5">$1000 - $1100 (13,123)</label>
                                        </div>
                                        <h5 class="text-dark fw-medium mt-3">Custom Price Range :</h5>
                                        <div id="product-price-range" [data-slider-size="md" ] class=""></div>
                                        <div class="formCost d-flex gap-2 align-items-center mt-2">
                                            <input class="form-control form-control-sm text-center" type="text"
                                                   id="minCost" value="0">
                                            <span class="fw-semibold text-muted">to</span>
                                            <input class="form-control form-control-sm text-center" type="text"
                                                   id="maxCost" value="1000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#"
                                   class="btn-link d-flex align-items-center text-dark bg-light p-2 rounded fw-medium fs-16 mb-0"
                                   data-bs-toggle="collapse" data-bs-target="#gender" aria-expanded="false"
                                   aria-controls="other">Gender
                                    <i class='bx bx-chevron-down ms-auto fs-20'></i>
                                </a>
                                <div id="gender" class="collapse show">
                                    <div class="categories-list d-flex flex-column gap-2 mt-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="men">
                                            <label class="form-check-label" for="men">Men (1,834)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="women">
                                            <label class="form-check-label" for="women">Women (2,890)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="kids">
                                            <label class="form-check-label" for="kids">Kid's (1,231)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <a href="#"
                                   class="btn-link d-flex align-items-center text-dark bg-light p-2 rounded fw-medium fs-16 mb-0"
                                   data-bs-toggle="collapse" data-bs-target="#size" aria-expanded="false"
                                   aria-controls="other">Size & Fit
                                    <i class='bx bx-chevron-down ms-auto fs-20'></i>
                                </a>
                                <div id="size" class="collapse show">
                                    <p class="text-muted mt-1">"For better results, select gender and category"</p>
                                    <div class="categories-list d-flex flex-column gap-2 mt-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="size-s">
                                            <label class="form-check-label" for="size-s">S (1,437)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="size-m">
                                            <label class="form-check-label" for="size-m">M (2,675)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="size-l">
                                            <label class="form-check-label" for="size-l">L (4,870)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="size-xl">
                                            <label class="form-check-label" for="size-xl">XL (7,543)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="size-xxl">
                                            <label class="form-check-label" for="size-xxl">XXL (1,099)</label>
                                        </div>
                                        <a href="#!" class="text-dark fw-medium">More</a>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="#"
                                   class="btn-link d-flex align-items-center text-dark bg-light p-2 rounded fw-medium fs-16 mb-0"
                                   data-bs-toggle="collapse" data-bs-target="#rating" aria-expanded="false"
                                   aria-controls="other">Rating
                                    <i class='bx bx-chevron-down ms-auto fs-20'></i>
                                </a>
                                <div id="rating" class="collapse show">
                                    <div class="categories-list d-flex flex-column gap-2 mt-2">
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" name="rating-number"
                                                   id="rate-1">
                                            <label class="form-check-label" for="rate-1">1 <i
                                                        class="bx bxs-star text-warning"></i> & Above (437)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" name="rating-number"
                                                   id="rate-2">
                                            <label class="form-check-label" for="rate-2">2 <i
                                                        class="bx bxs-star text-warning"></i> & Above (657)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" name="rating-number"
                                                   id="rate-3">
                                            <label class="form-check-label" for="rate-3">3 <i
                                                        class="bx bxs-star text-warning"></i> & Above (1,897)</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" class="form-check-input" name="rating-number"
                                                   id="rate-4">
                                            <label class="form-check-label" for="rate-4">4 <i
                                                        class="bx bxs-star text-warning"></i> & Above (3,571)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="#!" class="btn btn-primary w-100">Apply</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="card bg-light-subtle">
                        <div class="card-header border-0">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-lg-6">
                                    <ol class="breadcrumb mb-0">
                                        <li class="breadcrumb-item fw-medium"><a href="javascript: void(0);"
                                                                                 class="text-dark">Categories</a></li>
                                        <li class="breadcrumb-item active">All Product</li>
                                    </ol>
                                    <p class="mb-0 text-muted">Showing all <span
                                                class="text-dark fw-semibold">5,786</span> items results</p>
                                </div>
                                <div class="col-lg-6">
                                    <div class="text-md-end mt-3 mt-md-0">
                                        <button type="button" class="btn btn-outline-secondary me-1"><i
                                                    class="bx bx-cog me-1"></i>More Setting
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary me-1"><i
                                                    class="bx bx-filter-alt me-1"></i> Filters
                                        </button>
                                        <a href="product-add" class="btn btn-success me-1"><i
                                                    class="bx bx-plus"></i> New Product</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-1.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Men Black Slim Fit
                                        T-shirt</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">4.5 <span
                                                        class="text-muted fs-13">(55 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$100</span> $80 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-broken"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-2.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Olive Green Leather
                                        Bag</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">4.1 <span
                                                        class="text-muted fs-13">(143 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$150</span> $136 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>

                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-angle-bold-duotone"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-3.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Women Golden
                                        Dress</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">4.4 <span
                                                        class="text-muted fs-13">(174 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$250</span> $219 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-broken"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-4.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Gray Cap For
                                        Men</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">4.2 <span
                                                        class="text-muted fs-13">(23 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$100</span> $76 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-broken"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-5.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Dark Green Cargo
                                        Pent</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">4.4 <span
                                                        class="text-muted fs-13">(109 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$130</span> $110 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-broken"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-6.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Orange
                                        Headphone</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">4.2 <span
                                                        class="text-muted fs-13">(200 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$250</span> $231 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-broken"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-7.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Kid's Yellow
                                        Shoes</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">4.5 <span
                                                        class="text-muted fs-13">(321 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$100</span> $89 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-angle-bold-duotone"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-8.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Men Dark Brown
                                        Wallet</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">4.1 <span
                                                        class="text-muted fs-13">(190 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$150</span> $132 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-broken"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-9.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Sky Blue Mat
                                        Sunglass</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">3.5 <span
                                                        class="text-muted fs-13">(298 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$100</span> $77 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-angle-bold-duotone"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-10.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Kid's Yellow
                                        T-shirt</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">4.1 <span
                                                        class="text-muted fs-13">(156 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$140</span> $110 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-broken"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-11.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">White Rubber Smart
                                        Watch</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">3.4 <span
                                                        class="text-muted fs-13">(201 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$110</span> $77 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-broken"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <img src="/images/product/p-12.png" alt="" class="img-fluid ">
                                <div class="card-body bg-light-subtle rounded-bottom">
                                    <a href="product-details" class="text-dark fw-medium fs-16">Men Brown Leather
                                        Shoes</a>
                                    <div class="my-1">
                                        <div class="d-flex gap-2 align-items-center">
                                            <ul class="d-flex text-warning m-0 fs-18  list-unstyled">
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star"></i>
                                                </li>
                                                <li>
                                                    <i class="bx bxs-star-half"></i>
                                                </li>
                                            </ul>
                                            <p class="mb-0 fw-medium fs-15 text-dark">4.1 <span
                                                        class="text-muted fs-13">(370 Review)</span></p>
                                        </div>
                                    </div>
                                    <h4 class="fw-semibold text-dark mt-2 d-flex align-items-center gap-2">
                                        <span class="text-muted text-decoration-line-through">$250</span> $222 <small
                                                class="text-muted"> (30% Off)</small>
                                    </h4>
                                    <div class="mt-3">
                                        <div class="d-flex gap-2">
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-soft-primary border border-primary-subtle"
                                                   data-bs-toggle="dropdown" aria-expanded="false"><i
                                                            class='bx bx-dots-horizontal-rounded'></i></a>
                                                <div class="dropdown-menu">
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Edit</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Overview</a>
                                                    <!-- item-->
                                                    <a href="#!" class="dropdown-item">Delete</a>
                                                </div>
                                            </div>
                                            <a href="order-cart"
                                               class="btn btn-outline-dark border border-secondary-subtle d-flex align-items-center justify-content-center gap-1 w-100"><i
                                                        class='bx bx-cart align-middle'></i> Add To Cart</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 p-3">
                                              <button type="button"
                                                      class="btn btn-soft-danger avatar-sm d-inline-flex align-items-center justify-content-center fs-20 rounded-circle"><iconify-icon
                                                          icon="solar:heart-angle-bold-duotone"></iconify-icon></button>
                                         </span>
                            </div>
                        </div>

                    </div>

                    <div class="py-3 border-top">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">Previous</a></li>
                                <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a></li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
                                <li class="page-item"><a class="page-link" href="javascript:void(0);">Next</a></li>
                            </ul>
                        </nav>
                    </div>

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

<script src="js/pages/ecommerce-product.js"></script>
</body>

</html>