<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo view("partials/title-meta", array("title" => "Nav Tabs")) ?>

    <?= $this->include("partials/head-css") ?>
</head>

<body>

    <!-- START Wrapper -->
    <div class="wrapper">

        <?php echo view("partials/topbar", array("title" => "Nav Tabs")) ?>
        <?= $this->include('partials/main-nav') ?>

        <!-- ==================================================== -->
        <!-- Start right Content here -->
        <!-- ==================================================== -->
        <div class="page-content">

            <!-- Start Container Fluid -->
            <div class="container">

                <!-- start tabs -->
                <div class="row">
                    <div class="col-xl-9">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="default">
                                    Nav Tabs<a class="anchor-link" href="#default">#</a>
                                </h5>
                                <p class="text-muted mb-3">Use the <code>.nav-tabs</code> class to generate a tabbed interface.</p>
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a href="#home" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                            <span class="d-block d-sm-none"><i class="bx bx-home"></i></span>
                                            <span class="d-none d-sm-block">Home</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#profile" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                            <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                            <span class="d-none d-sm-block">Profile</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#messages" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                            <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                                            <span class="d-none d-sm-block">Messages</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content text-muted">
                                    <div class="tab-pane" id="home">
                                        <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                        </p>
                                    </div>
                                    <div class="tab-pane show active" id="profile">
                                        <p class="mb-0">Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc,
                                            litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules.
                                            Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores. At solmen va esser
                                            necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues coalesce, li grammatica del resultant
                                            lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va esser plu simplic e regulari quam
                                            li existent Europan lingues.
                                        </p>
                                    </div>
                                    <div class="tab-pane" id="messages">
                                        <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                        </p>
                                    </div>
                                </div>

                                <div class="highlight mt-3">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>

                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="defaultModal">
                                                &lt;ul class=&quot;nav nav-tabs&quot;&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#home&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;false&quot; class=&quot;nav-link&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-home&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Home&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#profile&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;true&quot; class=&quot;nav-link active&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-user&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Profile&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#messages&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;false&quot; class=&quot;nav-link&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-envelope&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Messages&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                &lt;/ul&gt;
                                                &lt;div class=&quot;tab-content text-muted&quot;&gt;
                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;home&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;tab-pane show active&quot; id=&quot;profile&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc,
                                                            litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules.
                                                            Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores. At solmen va esser
                                                            necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues coalesce, li grammatica del resultant
                                                            lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va esser plu simplic e regulari quam
                                                            li existent Europan lingues.
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;messages&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="static-backdrop">
                                    Tabs Justified <a class="anchor-link" href="#static-backdrop">#</a>
                                </h5>
                                <p class="text-muted">Using class <code>.nav-justified</code>, you can force your tabs menu items to use the full available width.</p>

                                <div class="mb-3">
                                    <ul class="nav nav-tabs nav-justified">
                                        <li class="nav-item">
                                            <a href="#homeTabsJustified" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                                <span class="d-block d-sm-none"><i class="bx bx-home"></i></span>
                                                <span class="d-none d-sm-block">Home</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#profileTabsJustified" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                                <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                                <span class="d-none d-sm-block">Profile</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#messagesTabsJustified" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                                <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                                                <span class="d-none d-sm-block">Messages</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content pt-2 text-muted">
                                        <div class="tab-pane" id="homeTabsJustified">
                                            <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                            </p>
                                        </div>
                                        <div class="tab-pane show active" id="profileTabsJustified">
                                            <p class="mb-0">Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc,
                                                litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules.
                                                Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores. At solmen va esser
                                                necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues coalesce, li grammatica del resultant
                                                lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va esser plu simplic e regulari quam
                                                li existent Europan lingues.
                                            </p>
                                        </div>
                                        <div class="tab-pane" id="messagesTabsJustified">
                                            <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>

                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="static-backdrop">
                                                &lt;ul class=&quot;nav nav-tabs nav-justified&quot;&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#homeTabsJustified&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;false&quot; class=&quot;nav-link&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-home&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Home&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#profileTabsJustified&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;true&quot; class=&quot;nav-link active&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-user&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Profile&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#messagesTabsJustified&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;false&quot; class=&quot;nav-link&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-envelope&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Messages&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                &lt;/ul&gt;
                                                &lt;div class=&quot;tab-content pt-2 text-muted&quot;&gt;
                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;homeTabsJustified&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;tab-pane show active&quot; id=&quot;profileTabsJustified&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc,
                                                            litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules.
                                                            Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores. At solmen va esser
                                                            necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues coalesce, li grammatica del resultant
                                                            lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va esser plu simplic e regulari quam
                                                            li existent Europan lingues.
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;messagesTabsJustified&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="scrolling-long-content">
                                    Nav Pills <a class="anchor-link" href="#scrolling-long-content">#</a>
                                </h5>
                                <p class="text-muted">Use the <code>.nav-pills</code> class to generate a pilled interface.</p>

                                <div class="mb-3">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item">
                                            <a href="#homePill" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                                <span class="d-block d-sm-none"><i class="bx bx-home"></i></span>
                                                <span class="d-none d-sm-block">Home</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#profilePill" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                                <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                                <span class="d-none d-sm-block">Profile</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#messagesPill" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                                <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                                                <span class="d-none d-sm-block">Messages</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content pt-2 text-muted">
                                        <div class="tab-pane" id="homePill">
                                            <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                            </p>
                                        </div>
                                        <div class="tab-pane show active" id="profilePill">
                                            <p class="mb-0">Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc,
                                                litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules.
                                                Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores. At solmen va esser
                                                necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues coalesce, li grammatica del resultant
                                                lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va esser plu simplic e regulari quam
                                                li existent Europan lingues.
                                            </p>
                                        </div>
                                        <div class="tab-pane" id="messagesPill">
                                            <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>

                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="scrolling-long-content">
                                                &lt;ul class=&quot;nav nav-pills&quot;&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#homePill&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;false&quot; class=&quot;nav-link&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-home&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Home&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#profilePill&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;true&quot; class=&quot;nav-link active&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-user&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Profile&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#messagesPill&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;false&quot; class=&quot;nav-link&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-envelope&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Messages&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                &lt;/ul&gt;
                                                &lt;div class=&quot;tab-content pt-2 text-muted&quot;&gt;
                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;homePill&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;tab-pane show active&quot; id=&quot;profilePill&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc,
                                                            litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules.
                                                            Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores. At solmen va esser
                                                            necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues coalesce, li grammatica del resultant
                                                            lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va esser plu simplic e regulari quam
                                                            li existent Europan lingues.
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;messagesPill&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="modal-position">
                                    Pills Justified <a class="anchor-link" href="#modal-position">#</a>
                                </h5>
                                <p class="text-muted">Using class <code>.nav-justified</code>, you can force your pills menu items to use the full available width.</p>


                                <div class="mb-3">
                                    <div class="d-flex flex-wrap gap-2">
                                        <ul class="nav nav-pills nav-justified p-1">
                                            <li class="nav-item">
                                                <a href="#homePillJustified" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                                    <span class="d-block d-sm-none"><i class="bx bx-home"></i></span>
                                                    <span class="d-none d-sm-block">Home</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#profilePillJustified" data-bs-toggle="tab" aria-expanded="true" class="nav-link active">
                                                    <span class="d-block d-sm-none"><i class="bx bx-user"></i></span>
                                                    <span class="d-none d-sm-block">Profile</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#messagesPillJustified" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                                                    <span class="d-block d-sm-none"><i class="bx bx-envelope"></i></span>
                                                    <span class="d-none d-sm-block">Messages</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content pt-2 text-muted">
                                            <div class="tab-pane" id="homePillJustified">
                                                <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                    totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                    ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                    velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                                </p>
                                            </div>
                                            <div class="tab-pane show active" id="profilePillJustified">
                                                <p class="mb-0">Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc,
                                                    litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules.
                                                    Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores. At solmen va esser
                                                    necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues coalesce, li grammatica del resultant
                                                    lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va esser plu simplic e regulari quam
                                                    li existent Europan lingues.
                                                </p>
                                            </div>
                                            <div class="tab-pane" id="messagesPillJustified">
                                                <p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                    totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                    ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                    velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>

                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="modal-position-centered">
                                                &lt;ul class=&quot;nav nav-pills nav-justified p-1&quot;&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#homePillJustified&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;false&quot; class=&quot;nav-link&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-home&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Home&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#profilePillJustified&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;true&quot; class=&quot;nav-link active&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-user&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Profile&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                    &lt;li class=&quot;nav-item&quot;&gt;
                                                        &lt;a href=&quot;#messagesPillJustified&quot; data-bs-toggle=&quot;tab&quot; aria-expanded=&quot;false&quot; class=&quot;nav-link&quot;&gt;
                                                            &lt;span class=&quot;d-block d-sm-none&quot;&gt;&lt;i class=&quot;bx bx-envelope&quot;&gt;&lt;/i&gt;&lt;/span&gt;
                                                            &lt;span class=&quot;d-none d-sm-block&quot;&gt;Messages&lt;/span&gt;
                                                        &lt;/a&gt;
                                                    &lt;/li&gt;
                                                &lt;/ul&gt;
                                                &lt;div class=&quot;tab-content pt-2 text-muted&quot;&gt;
                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;homePillJustified&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;tab-pane show active&quot; id=&quot;profilePillJustified&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Li Europan lingues es membres del sam familie. Lor separat existentie es un myth. Por scientie, musica, sport etc,
                                                            litot Europa usa li sam vocabular. Li lingues differe solmen in li grammatica, li pronunciation e li plu commun vocabules.
                                                            Omnicos directe al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores. At solmen va esser
                                                            necessi far uniform grammatica, pronunciation e plu sommun paroles. Ma quande lingues coalesce, li grammatica del resultant
                                                            lingue es plu simplic e regulari quam ti del coalescent lingues. Li nov lingua franca va esser plu simplic e regulari quam
                                                            li existent Europan lingues.
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;tab-pane&quot; id=&quot;messagesPillJustified&quot;&gt;
                                                        &lt;p class=&quot;mb-0&quot;&gt;Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                                            Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                                                            ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci
                                                            velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem?
                                                        &lt;/p&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="toggle-between-modals">
                                    Tabs Vertical Left <a class="anchor-link" href="#toggle-between-modals">#</a>
                                </h5>
                                <p class="text-muted"> You can stack your navigation by changing the flex item direction with the <code>.flex-column</code> utility.</p>


                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-sm-3 mb-2 mb-sm-0">
                                            <div class="nav flex-column nav-pills" id="vl-pills-tab" role="tablist" aria-orientation="vertical">
                                                <a class="nav-link active show" id="vl-pills-home-tab" data-bs-toggle="pill" href="#vl-pills-home" role="tab" aria-controls="vl-pills-home" aria-selected="true">
                                                    <span>Home</span>
                                                </a>
                                                <a class="nav-link" id="vl-pills-profile-tab" data-bs-toggle="pill" href="#vl-pills-profile" role="tab" aria-controls="vl-pills-profile" aria-selected="false">
                                                    <span>Profile</span>
                                                </a>
                                                <a class="nav-link" id="vl-pills-settings-tab" data-bs-toggle="pill" href="#vl-pills-settings" role="tab" aria-controls="vl-pills-settings" aria-selected="false">
                                                    <span>Settings</span>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="col-sm-9">
                                            <div class="tab-content pt-0" id="vl-pills-tabContent">
                                                <div class="tab-pane fade active show" id="vl-pills-home" role="tabpanel" aria-labelledby="vl-pills-home-tab">
                                                    <p class="mb-0">
                                                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                                        laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                                        architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                                                        sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                                                        voluptatem sequi nesciunt.
                                                    </p>
                                                </div>
                                                <div class="tab-pane fade" id="vl-pills-profile" role="tabpanel" aria-labelledby="vl-pills-profile-tab">
                                                    <p class="mb-0">
                                                        Li Europan lingues es membres del sam familie. Lor separat existentie es un myth.
                                                        Por scientie, musica, sport etc, litot Europa usa li sam vocabular. Li lingues differe
                                                        solmen in li grammatica, li pronunciation e li plu commun vocabules. Omnicos directe
                                                        al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores.
                                                        At solmen va esser necessi far uniform.
                                                    </p>
                                                </div>
                                                <div class="tab-pane fade" id="vl-pills-settings" role="tabpanel" aria-labelledby="vl-pills-settings-tab">
                                                    <p class="mb-0">
                                                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                                        laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                                        architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                                                        sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                                                        voluptatem sequi nesciunt.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>

                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="toggle-between-modals">
                                                &lt;div class=&quot;row&quot;&gt;
                                                    &lt;div class=&quot;col-sm-3 mb-2 mb-sm-0&quot;&gt;
                                                        &lt;div class=&quot;nav flex-column nav-pills&quot; id=&quot;vl-pills-tab&quot; role=&quot;tablist&quot; aria-orientation=&quot;vertical&quot;&gt;
                                                            &lt;a class=&quot;nav-link active show&quot; id=&quot;vl-pills-home-tab&quot; data-bs-toggle=&quot;pill&quot; href=&quot;#vl-pills-home&quot; role=&quot;tab&quot; aria-controls=&quot;vl-pills-home&quot; aria-selected=&quot;true&quot;&gt;
                                                                &lt;span&gt;Home&lt;/span&gt;
                                                            &lt;/a&gt;
                                                            &lt;a class=&quot;nav-link&quot; id=&quot;vl-pills-profile-tab&quot; data-bs-toggle=&quot;pill&quot; href=&quot;#vl-pills-profile&quot; role=&quot;tab&quot; aria-controls=&quot;vl-pills-profile&quot; aria-selected=&quot;false&quot;&gt;
                                                                &lt;span&gt;Profile&lt;/span&gt;
                                                            &lt;/a&gt;
                                                            &lt;a class=&quot;nav-link&quot; id=&quot;vl-pills-settings-tab&quot; data-bs-toggle=&quot;pill&quot; href=&quot;#vl-pills-settings&quot; role=&quot;tab&quot; aria-controls=&quot;vl-pills-settings&quot; aria-selected=&quot;false&quot;&gt;
                                                                &lt;span&gt;Settings&lt;/span&gt;
                                                            &lt;/a&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;

                                                    &lt;div class=&quot;col-sm-9&quot;&gt;
                                                        &lt;div class=&quot;tab-content pt-0&quot; id=&quot;vl-pills-tabContent&quot;&gt;
                                                            &lt;div class=&quot;tab-pane fade active show&quot; id=&quot;vl-pills-home&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;vl-pills-home-tab&quot;&gt;
                                                                &lt;p class=&quot;mb-0&quot;&gt;
                                                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                                                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                                                    architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                                                                    sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                                                                    voluptatem sequi nesciunt.
                                                                &lt;/p&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;tab-pane fade&quot; id=&quot;vl-pills-profile&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;vl-pills-profile-tab&quot;&gt;
                                                                &lt;p class=&quot;mb-0&quot;&gt;
                                                                    Li Europan lingues es membres del sam familie. Lor separat existentie es un myth.
                                                                    Por scientie, musica, sport etc, litot Europa usa li sam vocabular. Li lingues differe
                                                                    solmen in li grammatica, li pronunciation e li plu commun vocabules. Omnicos directe
                                                                    al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores.
                                                                    At solmen va esser necessi far uniform.
                                                                &lt;/p&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;tab-pane fade&quot; id=&quot;vl-pills-settings&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;vl-pills-settings-tab&quot;&gt;
                                                                &lt;p class=&quot;mb-0&quot;&gt;
                                                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                                                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                                                    architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                                                                    sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                                                                    voluptatem sequi nesciunt.
                                                                &lt;/p&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                &lt;/div&gt;
                                            </code>
                                        </pre>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-1 anchor" id="varying-modal-content">
                                    Tabs Vertical Right <a class="anchor-link" href="#varying-modal-content">#</a>
                                </h5>
                                <p class="text-muted"> You can stack your navigation by changing the flex item direction with the <code>.flex-column</code> utility.</p>


                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-sm-9 mb-2 mb-sm-0">
                                            <div class="tab-content pt-0" id="vr-pills-tabContent">
                                                <div class="tab-pane fade active show" id="vr-pills-home" role="tabpanel" aria-labelledby="vr-pills-home-tab">
                                                    <p class="mb-0">
                                                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                                        laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                                        architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                                                        sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                                                        voluptatem sequi nesciunt.
                                                    </p>
                                                </div>
                                                <div class="tab-pane fade" id="vr-pills-profile" role="tabpanel" aria-labelledby="vr-pills-profile-tab">
                                                    <p class="mb-0">
                                                        Li Europan lingues es membres del sam familie. Lor separat existentie es un myth.
                                                        Por scientie, musica, sport etc, litot Europa usa li sam vocabular. Li lingues differe
                                                        solmen in li grammatica, li pronunciation e li plu commun vocabules. Omnicos directe
                                                        al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores.
                                                        At solmen va esser necessi far uniform.
                                                    </p>
                                                </div>
                                                <div class="tab-pane fade" id="vr-pills-settings" role="tabpanel" aria-labelledby="vr-pills-settings-tab">
                                                    <p class="mb-0">
                                                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                                        laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                                        architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                                                        sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                                                        voluptatem sequi nesciunt.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="nav flex-column nav-pills" id="vr-pills-tab" role="tablist" aria-orientation="vertical">
                                                <a class="nav-link active show" id="vr-pills-home-tab" data-bs-toggle="pill" href="#vr-pills-home" role="tab" aria-controls="vr-pills-home" aria-selected="true">
                                                    <span>Home</span>
                                                </a>
                                                <a class="nav-link" id="vr-pills-profile-tab" data-bs-toggle="pill" href="#vr-pills-profile" role="tab" aria-controls="vr-pills-profile" aria-selected="false">
                                                    <span>Profile</span>
                                                </a>
                                                <a class="nav-link" id="vr-pills-settings-tab" data-bs-toggle="pill" href="#vr-pills-settings" role="tab" aria-controls="vr-pills-settings" aria-selected="false">
                                                    <span>Settings</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="highlight">
                                    <div class="highlight-toolbar">
                                        <small class="text-uppercase">html</small>
                                        <button class="btn btn-sm btn-outline-success btn-copy-clipboard" data-clipboard-action="copy">Copy</button>
                                    </div>

                                    <div class="code">
                                        <pre class="language-html">
                                            <code id="varying-modal-content">
                                                &lt;div class=&quot;row&quot;&gt;
                                                    &lt;div class=&quot;col-sm-9 mb-2 mb-sm-0&quot;&gt;
                                                        &lt;div class=&quot;tab-content pt-0&quot; id=&quot;vr-pills-tabContent&quot;&gt;
                                                            &lt;div class=&quot;tab-pane fade active show&quot; id=&quot;vr-pills-home&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;vr-pills-home-tab&quot;&gt;
                                                                &lt;p class=&quot;mb-0&quot;&gt;
                                                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                                                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                                                    architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                                                                    sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                                                                    voluptatem sequi nesciunt.
                                                                &lt;/p&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;tab-pane fade&quot; id=&quot;vr-pills-profile&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;vr-pills-profile-tab&quot;&gt;
                                                                &lt;p class=&quot;mb-0&quot;&gt;
                                                                    Li Europan lingues es membres del sam familie. Lor separat existentie es un myth.
                                                                    Por scientie, musica, sport etc, litot Europa usa li sam vocabular. Li lingues differe
                                                                    solmen in li grammatica, li pronunciation e li plu commun vocabules. Omnicos directe
                                                                    al desirabilite de un nov lingua franca: On refusa continuar payar custosi traductores.
                                                                    At solmen va esser necessi far uniform.
                                                                &lt;/p&gt;
                                                            &lt;/div&gt;
                                                            &lt;div class=&quot;tab-pane fade&quot; id=&quot;vr-pills-settings&quot; role=&quot;tabpanel&quot; aria-labelledby=&quot;vr-pills-settings-tab&quot;&gt;
                                                                &lt;p class=&quot;mb-0&quot;&gt;
                                                                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                                                                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                                                                    architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                                                                    sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                                                                    voluptatem sequi nesciunt.
                                                                &lt;/p&gt;
                                                            &lt;/div&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
                                                    &lt;div class=&quot;col-sm-3&quot;&gt;
                                                        &lt;div class=&quot;nav flex-column nav-pills&quot; id=&quot;vr-pills-tab&quot; role=&quot;tablist&quot; aria-orientation=&quot;vertical&quot;&gt;
                                                            &lt;a class=&quot;nav-link active show&quot; id=&quot;vr-pills-home-tab&quot; data-bs-toggle=&quot;pill&quot; href=&quot;#vr-pills-home&quot; role=&quot;tab&quot; aria-controls=&quot;vr-pills-home&quot; aria-selected=&quot;true&quot;&gt;
                                                                &lt;span&gt;Home&lt;/span&gt;
                                                            &lt;/a&gt;
                                                            &lt;a class=&quot;nav-link&quot; id=&quot;vr-pills-profile-tab&quot; data-bs-toggle=&quot;pill&quot; href=&quot;#vr-pills-profile&quot; role=&quot;tab&quot; aria-controls=&quot;vr-pills-profile&quot; aria-selected=&quot;false&quot;&gt;
                                                                &lt;span&gt;Profile&lt;/span&gt;
                                                            &lt;/a&gt;
                                                            &lt;a class=&quot;nav-link&quot; id=&quot;vr-pills-settings-tab&quot; data-bs-toggle=&quot;pill&quot; href=&quot;#vr-pills-settings&quot; role=&quot;tab&quot; aria-controls=&quot;vr-pills-settings&quot; aria-selected=&quot;false&quot;&gt;
                                                                &lt;span&gt;Settings&lt;/span&gt;
                                                            &lt;/a&gt;
                                                        &lt;/div&gt;
                                                    &lt;/div&gt;
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
                                    <a href="#default" class="nav-link">Nav Tabs</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#static-backdrop" class="nav-link">Static Backdrop</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#scrolling-long-content" class="nav-link">Scrolling Long Content</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#toggle-between-modals" class="nav-link">Toggle Between Modals</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#varying-modal-content" class="nav-link">Varying Modal Content</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> <!-- end row -->
                <!-- end tabs -->

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