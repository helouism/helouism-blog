<!DOCTYPE html>
<html lang="en">

<head>
 
    <meta charset="utf-8">
    <title><?= lang('Errors.badRequest') ?></title>
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/img/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/img/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/img/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= base_url('assets/img/site.webmanifest') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.7/dist/slate/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="canonical" href="<?= current_url() ?>">
    <script type="application/ld+json">
{
"@context": "https://schema.org",
"@type": "BlogPosting",
"mainEntityOfPage": {
"@type": "WebPage",
"@id": "https://helouism.my.id"
},
"headline": "<?= esc($title) ?>",
"image": "<?= isset($post['thumbnail_path']) ? base_url('uploads/thumbnails/' . $post['thumbnail_path']) : base_url('assets/img/helouism.webp') ?>",
"author": {
"@type": "Person",
"name": "Louis",
"url": "https://github.com/helouism"
},
"publisher": {
"@type": "Organization",
"name": "",
"logo": {
"@type": "ImageObject",
"url": ""
}
},
"datePublished": "<?= isset($post['created_at']) ? date('Y-m-d', strtotime($post['created_at'])) : '' ?>",
"dateModified": "<?= isset($post['updated_at']) ? date('Y-m-d', strtotime($post['updated_at'])) : '' ?>"
}
</script>

</head>

<body>
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url("/") ?>">
                <img src="<?= base_url('assets/img/helouism-logo.png') ?>" alt="Helouism Logo" class="img-fluid"
                    style="max-height: 50px;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= $title === 'Home' ? 'active' : '' ?>"
                            href="<?php echo base_url("/") ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $title === 'All Categories' ? 'active' : '' ?>"
                            href="<?php echo base_url("category-list") ?>">Categories</a>
                    </li>
                </ul>
                <!-- Search Form -->
                <form method="get" action="<?= base_url('search') ?>" class="d-flex" id="searchForm" role="search">
                    <div class="input-group">
                        <input type="search" maxlength="150" name="q" placeholder="Search posts..." aria-label="Search"
                            class="form-control me-sm-2" value="<?= old('q') ?: ($query ?? '') ?>" autocomplete="off">

                        <button class="btn btn-secondary my-2 my-sm-0" type="submit" aria-label="Search">
                            <i class="fas fa-search" aria-hidden="true"></i> Search
                        </button>
                    </div>
                </form>

                <!-- Error Toast -->
                <?php $errors = session()->getFlashdata('search_errors'); ?>
                <?php if (!empty($errors['q'])): ?>
                    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
                        <div id="searchErrorToast" class="toast align-items-center text-bg-danger border-0 show"
                            role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true"
                            data-bs-delay="5000">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <?= esc($errors['q']) ?>
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>


    <main class="container-fluid text-center text-white py-5">
        <h1>400</h1>

        <p class="mt-5">
            <?php if (ENVIRONMENT !== 'production'): ?>
                <?= nl2br(esc($message)) ?>
            <?php else: ?>
                <?= lang('Errors.sorryBadRequest') ?>
            <?php endif; ?>
        </p>
    </main>
    <!-- Footer -->
    <footer class="text-center text-lg-start text-white bg-primary">
        <!-- Section: Social media -->
        <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
            <!-- Left -->
            <div class="me-5 d-none d-lg-block">
                <span>Connect with me:</span>
            </div>
            <!-- Left -->

            <!-- Right -->
            <div>

                <a href="https://id.linkedin.com/in/hendrik-louis-mahdi-b0ba67178" class="me-4 text-reset">
                    <i class="bi bi-linkedin"></i>
                </a>
                <a href="https://github.com/helouism" class="me-4 text-reset">
                    <i class="bi bi-github"></i>
                </a>
            </div>
            <!-- Right -->
        </section>
        <!-- Section: Social media -->

        <!-- Section: Links  -->
        <section class="">
            <div class="container text-center text-md-start mt-5">
                <!-- Grid row -->
                <div class="row mt-3">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                        <!-- Content -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            <i class="bi bi-gem me-3"></i>Hendrik Louis Mahdi
                        </h6>
                        <p>
                            Fresh Graduate of Informatics Engineering at Pamulang University.
                            I excels in full stack web development using PHP, Python, and JavaScript and with
                            framework like CodeIgniter 4, Laravel, React, and Django
                        </p>
                    </div>
                    <!-- Grid column -->


                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">
                            Useful links
                        </h6>
                        <p>
                            <a href="https://helouism.github.io" class="text-reset">About Me</a>
                            <br> <br>
                            <a href="<?php echo base_url("privacy-policy") ?>" class="text-reset">Privacy Policy</a>

                            <br> <br>
                            <a href="<?php echo base_url("terms-and-conditions") ?>" class="text-reset">Terms &
                                Conditions</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
                        <p><i class="bi bi-house-fill"></i> Tangerang Selatan, Indonesia</p>
                        <p>
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:hendrikmahdi@gmail.com">hendrikmahdi@gmail.com</a>

                        </p>
                        <p> <i class="bi bi-whatsapp"></i> <a href="https://wa.me/6285156561231">085156561231</a> </p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row -->
            </div>
        </section>
        <!-- Section: Links  -->

        <!-- Copyright -->
        <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
            © <?php echo date('Y') ?> helouism

        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
        crossorigin="anonymous"></script>
       
</body>

</html>