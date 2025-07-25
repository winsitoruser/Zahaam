<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Gridjs Table")) ?>

    <!-- Gridjs Plugin css -->
    <link href="/vendor/gridjs/theme/mermaid.min.css" rel="stylesheet" type="text/css" />

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Gridjs Table")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->

        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container">

                <div class="row">
                    <div class="col-xl-10">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor mb-1" id="overview">
                                    Overview <a class="btn btn-sm btn-outline-success rounded-2 float-end" href="https://gridjs.io" target="_blank"> Official Website </a>
                                </h5>
                                <p class="sub-header">
                                    Grid.js is a Free and open-source JavaScript table plugin
                                </p>
                                <h5 class="card-title anchor mb-1" id="basic">
                                    Basic<a class="anchor-link" href="#basic">#</a>
                                </h5>
                                <p class="text-muted">The most basic list group is an unordered list with list items and the proper classes. Build upon it with the options that follow, or with your own CSS as needed.</p>
                                <div>
                                    <div class="py-3">
                                        <div id="table-gridjs"></div>
                                    </div>

                                    <div class="highlight border rounded">
                                        <div class="highlight-toolbar border-top-0">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item">
                                                    <a href="#gridjs-basic-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                        <span class="fs-12 text-uppercase">html</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#gridjs-basic-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                        <span class="fs-12 text-uppercase">javascript</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                        </div>
                                        <div class="tab-content">
                                            <div class="tab-pane show active" id="gridjs-basic-html">
                                                <div class="code">
                                                    <pre class="language-html">
                                                        <code>
                                                            &lt;div id=&quot;table-gridjs&quot;&gt;&lt;/div&gt;
                                                        </code>
                                                    </pre>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="gridjs-basic-javascript">
                                                <div class="code">
                                                    <pre class="language-javascript">
                                                    <code>
                                                        // Basic Table
                                                        if (document.getElementById("table-gridjs"))
                                                             new gridjs.Grid({
                                                                  columns: [{
                                                                       name: 'ID',
                                                                       formatter: (function (cell) {
                                                                            return gridjs('<span class="fw-semibold">' + cell + '</span>');
                                                                       })
                                                                  },
                                                                       "Name",
                                                                  {
                                                                       name: 'Email',
                                                                       formatter: (function (cell) {
                                                                            return gridjs('<a href="">' + cell + '</a>');
                                                                       })
                                                                  },
                                                                       "Position", "Company", "Country",
                                                                  {
                                                                       name: 'Actions',
                                                                       width: '120px',
                                                                       formatter: (function (cell) {
                                                                            return gridjs("<a href='#' class='text-reset text-decoration-underline'>" + "Details" + "</a>");
                                                                       })
                                                                  },
                                                                  ],
                                                                  pagination: {
                                                                       limit: 5
                                                                  },
                                                                  sort: true,
                                                                  search: true,
                                                                  data: [
                                                                       ["11", "Alice", "alice@example.com", "Software Engineer", "ABC Company", "United States"],
                                                                       ["12", "Bob", "bob@example.com", "Product Manager", "XYZ Inc", "Canada"],
                                                                       ["13", "Charlie", "charlie@example.com", "Data Analyst", "123 Corp", "Australia"],
                                                                       ["14", "David", "david@example.com", "UI/UX Designer", "456 Ltd", "United Kingdom"],
                                                                       ["15", "Eve", "eve@example.com", "Marketing Specialist", "789 Enterprises", "France"],
                                                                       ["16", "Frank", "frank@example.com", "HR Manager", "ABC Company", "Germany"],
                                                                       ["17", "Grace", "grace@example.com", "Financial Analyst", "XYZ Inc", "Japan"],
                                                                       ["18", "Hannah", "hannah@example.com", "Sales Representative", "123 Corp", "Brazil"],
                                                                       ["19", "Ian", "ian@example.com", "Software Developer", "456 Ltd", "India"],
                                                                       ["20", "Jane", "jane@example.com", "Operations Manager", "789 Enterprises", "China"]
                                                                  ]
                                                             }).render(document.getElementById("table-gridjs"));
                                                    </code>
                                                </pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title anchor mb-1" id="pagination">
                                    Pagination<a class="anchor-link" href="#pagination">#</a>
                                </h5>
                                <p class="text-muted">Pagination can be enabled by setting <code>pagination: true</code>:</p>
                                <div class="py-3">
                                    <div id="table-pagination"></div>
                                </div>

                                <div class="highlight border rounded">
                                    <div class="highlight-toolbar border-top-0">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#gridjs-pagination-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#gridjs-pagination-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="gridjs-pagination-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div id=&quot;table-pagination&quot;&gt;&lt;/div&gt; 
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="gridjs-pagination-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // pagination Table
                                                        if (document.getElementById("table-pagination"))
                                                             new gridjs.Grid({
                                                                  columns: [{
                                                                       name: 'ID',
                                                                       width: '120px',
                                                                       formatter: (function (cell) {
                                                                            return gridjs('<a href="" class="fw-medium">' + cell + '</a>');
                                                                       })
                                                                  }, "Name", "Date", "Total",
                                                                  {
                                                                       name: 'Actions',
                                                                       width: '100px',
                                                                       formatter: (function (cell) {
                                                                            return gridjs("<button type='button' class='btn btn-sm btn-light'>" +
                                                                                 "Details" +
                                                                                 "</button>");
                                                                       })
                                                                  },
                                                                  ],
                                                                  pagination: {
                                                                       limit: 5
                                                                  },
                                              
                                                                  data: [
                                                                       ["#RB2320", "Alice", "07 Oct, 2024", "$24.05"],
                                                                       ["#RB8652", "Bob", "07 Oct, 2024", "$26.15"],
                                                                       ["#RB8520", "Charlie", "06 Oct, 2024", "$21.25"],
                                                                       ["#RB9512", "David", "05 Oct, 2024", "$25.03"],
                                                                       ["#RB7532", "Eve", "05 Oct, 2024", "$22.61"],
                                                                       ["#RB9632", "Frank", "04 Oct, 2024", "$24.05"],
                                                                       ["#RB7456", "Grace", "04 Oct, 2024", "$26.15"],
                                                                       ["#RB3002", "Hannah", "04 Oct, 2024", "$21.25"],
                                                                       ["#RB9857", "Ian", "03 Oct, 2024", "$22.61"],
                                                                       ["#RB2589", "Jane", "03 Oct, 2024", "$25.03"],
                                                                  ]
                                                             }).render(document.getElementById("table-pagination"));
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
                                <h5 class="card-title anchor mb-1" id="search">
                                    Search<a class="anchor-link" href="#search">#</a>
                                </h5>
                                <p class="text-muted">Grid.js supports global search on all rows and columns. Set <code>search: true</code> to enable the search plugin:</p>
                                <div class="py-3">
                                    <div id="table-search"></div>
                                </div>

                                <div class="highlight border rounded">
                                    <div class="highlight-toolbar border-top-0">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#gridjs-search-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#gridjs-search-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="gridjs-search-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div id=&quot;table-search&quot;&gt;&lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="gridjs-search-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // search Table
                                                        if (document.getElementById("table-search"))
                                                             new gridjs.Grid({
                                                                  columns: ["Name", "Email", "Position", "Company", "Country"],
                                                                  pagination: {
                                                                       limit: 5
                                                                  },
                                                                  search: true,
                                                                  data: [
                                                                       ["Alice", "alice@example.com", "Software Engineer", "ABC Company", "United States"],
                                                                       ["Bob", "bob@example.com", "Product Manager", "XYZ Inc", "Canada"],
                                                                       ["Charlie", "charlie@example.com", "Data Analyst", "123 Corp", "Australia"],
                                                                       ["David", "david@example.com", "UI/UX Designer", "456 Ltd", "United Kingdom"],
                                                                       ["Eve", "eve@example.com", "Marketing Specialist", "789 Enterprises", "France"],
                                                                       ["Frank", "frank@example.com", "HR Manager", "ABC Company", "Germany"],
                                                                       ["Grace", "grace@example.com", "Financial Analyst", "XYZ Inc", "Japan"],
                                                                       ["Hannah", "hannah@example.com", "Sales Representative", "123 Corp", "Brazil"],
                                                                       ["Ian", "ian@example.com", "Software Developer", "456 Ltd", "India"],
                                                                       ["Jane", "jane@example.com", "Operations Manager", "789 Enterprises", "China"]
                                                                  ]
                                                             }).render(document.getElementById("table-search"));
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
                                <h5 class="card-title anchor mb-1" id="sorting">
                                    Sorting<a class="anchor-link" href="#sorting">#</a>
                                </h5>
                                <p class="text-muted">To enable sorting, simply add <code>sort: true</code> to your config:</p>
                                <div class="py-3">
                                    <div id="table-sorting"></div>
                                </div>

                                <div class="highlight border rounded">
                                    <div class="highlight-toolbar border-top-0">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#gridjs-sorting-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#gridjs-sorting-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="gridjs-sorting-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div id=&quot;table-sorting&quot;&gt;&lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="gridjs-sorting-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // Sorting Table
                                                        if (document.getElementById("table-sorting"))
                                                             new gridjs.Grid({
                                                                  columns: ["Name", "Email", "Position", "Company", "Country"],
                                                                  pagination: {
                                                                       limit: 5
                                                                  },
                                                                  sort: true,
                                                                  data: [
                                                                       ["Alice", "alice@example.com", "Software Engineer", "ABC Company", "United States"],
                                                                       ["Bob", "bob@example.com", "Product Manager", "XYZ Inc", "Canada"],
                                                                       ["Charlie", "charlie@example.com", "Data Analyst", "123 Corp", "Australia"],
                                                                       ["David", "david@example.com", "UI/UX Designer", "456 Ltd", "United Kingdom"],
                                                                       ["Eve", "eve@example.com", "Marketing Specialist", "789 Enterprises", "France"],
                                                                       ["Frank", "frank@example.com", "HR Manager", "ABC Company", "Germany"],
                                                                       ["Grace", "grace@example.com", "Financial Analyst", "XYZ Inc", "Japan"],
                                                                       ["Hannah", "hannah@example.com", "Sales Representative", "123 Corp", "Brazil"],
                                                                       ["Ian", "ian@example.com", "Software Developer", "456 Ltd", "India"],
                                                                       ["Jane", "jane@example.com", "Operations Manager", "789 Enterprises", "China"]
                                                                  ]
                                                             }).render(document.getElementById("table-sorting"));
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
                                <h5 class="card-title anchor mb-1" id="loading_state">
                                    Loading State<a class="anchor-link" href="#loading_state">#</a>
                                </h5>
                                <p class="text-muted">Grid.js renders a loading bar automatically while it waits for the data to be fetched. Here we are using an async
                                    function to demonstrate this behaviour (e.g. an async function can be a XHR call to a server backend)</p>
                                <div class="py-3">
                                    <div id="table-loading-state"></div>
                                </div>

                                <div class="highlight border rounded">
                                    <div class="highlight-toolbar border-top-0">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#gridjs-loading-state-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#gridjs-loading-state-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="gridjs-loading-state-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div id=&quot;table-loading-state&quot;&gt;&lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="gridjs-loading-state-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // Loading State Table
                                                        if (document.getElementById("table-loading-state"))
                                                            new gridjs.Grid({
                                                                    columns: ["Name", "Email", "Position", "Company", "Country"],
                                                                    pagination: {
                                                                        limit: 5
                                                                    },
                                                                    sort: true,
                                                                    data: function () {
                                                                        return new Promise(function (resolve) {
                                                                            setTimeout(function () {
                                                                                resolve([
                                                                                        ["Alice", "alice@example.com", "Software Engineer", "ABC Company", "United States"],
                                                                                        ["Bob", "bob@example.com", "Product Manager", "XYZ Inc", "Canada"],
                                                                                        ["Charlie", "charlie@example.com", "Data Analyst", "123 Corp", "Australia"],
                                                                                        ["David", "david@example.com", "UI/UX Designer", "456 Ltd", "United Kingdom"],
                                                                                        ["Eve", "eve@example.com", "Marketing Specialist", "789 Enterprises", "France"],
                                                                                        ["Frank", "frank@example.com", "HR Manager", "ABC Company", "Germany"],
                                                                                        ["Grace", "grace@example.com", "Financial Analyst", "XYZ Inc", "Japan"],
                                                                                        ["Hannah", "hannah@example.com", "Sales Representative", "123 Corp", "Brazil"],
                                                                                        ["Ian", "ian@example.com", "Software Developer", "456 Ltd", "India"],
                                                                                        ["Jane", "jane@example.com", "Operations Manager", "789 Enterprises", "China"]
                                                                                ])
                                                                            }, 2000);
                                                                        });
                                                                    }
                                                            }).render(document.getElementById("table-loading-state"));
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
                                <h5 class="card-title anchor mb-1" id="fixed_header">
                                    Fixed Header<a class="anchor-link" href="#fixed_header">#</a>
                                </h5>
                                <p class="text-muted">The most basic list group is an unordered list with list items and the proper classes. Build upon it with the options that follow, or with your own CSS as needed.</p>
                                <div class="py-3">
                                    <div id="table-fixed-header"></div>
                                </div>

                                <div class="highlight border rounded">
                                    <div class="highlight-toolbar border-top-0">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#gridjs-fixed-header-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#gridjs-fixed-header-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="gridjs-fixed-header-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div id=&quot;table-fixed-header&quot;&gt;&lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="gridjs-fixed-header-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // Fixed Header
                                                        if (document.getElementById("table-fixed-header"))
                                                            new gridjs.Grid({
                                                                    columns: ["Name", "Email", "Position", "Company", "Country"],
                                                                    sort: true,
                                                                    pagination: true,
                                                                    fixedHeader: true,
                                                                    height: '400px',
                                                                    data: [
                                                                        ["Alice", "alice@example.com", "Software Engineer", "ABC Company", "United States"],
                                                                        ["Bob", "bob@example.com", "Product Manager", "XYZ Inc", "Canada"],
                                                                        ["Charlie", "charlie@example.com", "Data Analyst", "123 Corp", "Australia"],
                                                                        ["David", "david@example.com", "UI/UX Designer", "456 Ltd", "United Kingdom"],
                                                                        ["Eve", "eve@example.com", "Marketing Specialist", "789 Enterprises", "France"],
                                                                        ["Frank", "frank@example.com", "HR Manager", "ABC Company", "Germany"],
                                                                        ["Grace", "grace@example.com", "Financial Analyst", "XYZ Inc", "Japan"],
                                                                        ["Hannah", "hannah@example.com", "Sales Representative", "123 Corp", "Brazil"],
                                                                        ["Ian", "ian@example.com", "Software Developer", "456 Ltd", "India"],
                                                                        ["Jane", "jane@example.com", "Operations Manager", "789 Enterprises", "China"]
                                                                    ]
                                                            }).render(document.getElementById("table-fixed-header"));
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
                                <h5 class="card-title anchor mb-1" id="hidden_column">
                                    Hidden Columns<a class="anchor-link" href="#hidden_column">#</a>
                                </h5>
                                <p class="text-muted">Add <code>hidden: true</code> to the columns definition to hide them. </p>
                                <div class="py-3">
                                    <div id="table-hidden-column"></div>
                                </div>

                                <div class="highlight border rounded">
                                    <div class="highlight-toolbar border-top-0">
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <a href="#gridjs-hidden-column-html" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                                                    <span class="fs-12 text-uppercase">html</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#gridjs-hidden-column-javascript" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                                                    <span class="fs-12 text-uppercase">javascript</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>
                                    <div class="tab-content">
                                        <div class="tab-pane show active" id="gridjs-hidden-column-html">
                                            <div class="code">
                                                <pre class="language-html">
                                                    <code>
                                                        &lt;div id=&quot;table-hidden-column&quot;&gt;&lt;/div&gt;
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="gridjs-hidden-column-javascript">
                                            <div class="code">
                                                <pre class="language-javascript">
                                                    <code>
                                                        // Hidden Columns
                                                        if (document.getElementById("table-hidden-column"))
                                                            new gridjs.Grid({
                                                                    columns: ["Name", "Email", "Position", "Company",
                                                                        {
                                                                            name: 'Country',
                                                                            hidden: true
                                                                        },
                                                                    ],
                                                                    pagination: {
                                                                        limit: 5
                                                                    },
                                                                    sort: true,
                                                                    data: [
                                                                        ["Alice", "alice@example.com", "Software Engineer", "ABC Company", "United States"],
                                                                        ["Bob", "bob@example.com", "Product Manager", "XYZ Inc", "Canada"],
                                                                        ["Charlie", "charlie@example.com", "Data Analyst", "123 Corp", "Australia"],
                                                                        ["David", "david@example.com", "UI/UX Designer", "456 Ltd", "United Kingdom"],
                                                                        ["Eve", "eve@example.com", "Marketing Specialist", "789 Enterprises", "France"],
                                                                        ["Frank", "frank@example.com", "HR Manager", "ABC Company", "Germany"],
                                                                        ["Grace", "grace@example.com", "Financial Analyst", "XYZ Inc", "Japan"],
                                                                        ["Hannah", "hannah@example.com", "Sales Representative", "123 Corp", "Brazil"],
                                                                        ["Ian", "ian@example.com", "Software Developer", "456 Ltd", "India"],
                                                                        ["Jane", "jane@example.com", "Operations Manager", "789 Enterprises", "China"]
                                                                    ]
                                                            }).render(document.getElementById("table-hidden-column"));
                                                    </code>
                                                </pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!-- end card body -->

                    <div class="col-xl-2">
                        <div class="card docs-nav">
                            <ul class="nav bg-transparent flex-column">
                                <li class="nav-item">
                                    <a href="#overview" class="nav-link">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#basic" class="nav-link">Basic</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#pagination" class="nav-link">Pagination</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#search" class="nav-link">Search</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#sorting" class="nav-link">Sorting</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#loading_state" class="nav-link">Loading State</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#fixed_header" class="nav-link">Fixed Header</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#hidden_column" class="nav-link">Hidden Columns</a>
                                </li>
                            </ul>
                        </div>
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

    <!-- Gridjs Plugin js -->
    <script src="/vendor/gridjs/gridjs.umd.js"></script>

    <!-- Gridjs Demo js -->
    <script src="/js/components/table-gridjs.js"></script>

</body>

</html>