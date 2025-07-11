<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= esc($title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <?php echo link_tag('assets/admin/css/styles.css'); ?>
    <?php if ($title === 'Create Post' || $title === 'Edit Post'): ?>

        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

    <?php endif; ?>
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <?= $this->renderSection('pageStyles') ?>
    <!-- Matomo -->
    <script>
        var _paq = window._paq = window._paq || [];
        /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function () {
            var u = "//stats12.serv00.com/";
            _paq.push(['setTrackerUrl', u + 'matomo.php']);
            _paq.push(['setSiteId', '249']);
            var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.async = true; g.src = u + 'matomo.js'; s.parentNode.insertBefore(g, s);
        })();
    </script>
    <!-- End Matomo Code -->



</head>

<body class="sb-nav-fixed">
    <?= $this->include("admin/partials/navbar") ?>
    <div id="layoutSidenav">
        <?= $this->include("admin/partials/sidebar") ?>
        <div id="layoutSidenav_content">
            <?= $this->renderSection("adminContent") ?>

            <?= $this->include("admin/partials/footer") ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>

    <?php echo script_tag('assets/admin/js/scripts.js'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"
        crossorigin="anonymous"></script>
    <script src=""></script>
    <?php echo script_tag('assets/admin/js/datatables-simple-demo.js'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if ($title === 'Create Post' || $title === 'Edit Post'): ?>
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
        <!-- FilePond Image Resize Plugin -->
        <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.min.js"></script>

        <!-- FilePond Image Transform Plugin -->
        <script
            src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.min.js"></script>

    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <!-- Sweetalert success message -->
        <script>
            Swal.fire({
                toast: true,
                position: "top-end",
                title: "Success",
                showConfirmButton: false,
                timer: 3000,
                html: `<?= session()->getFlashdata('success') ?>`,
                icon: "success"
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <!-- Sweetalert error message -->
        <script>
            Swal.fire({
                toast: true,
                position: "top-end",
                title: "Error",
                showConfirmButton: false,
                timer: 3000,
                html: `<?= session()->getFlashdata('error') ?>`,
                icon: "error"
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('info')): ?>
        <!-- Sweetalert error message -->
        <script>
            Swal.fire({
                toast: true,
                position: "top-end",
                title: "Info",
                showConfirmButton: false,
                timer: 3000,
                html: `<?= session()->getFlashdata('info') ?>`,
                icon: "info"
            });
        </script>
    <?php endif; ?>
    <?= $this->renderSection('scripts') ?>
</body>

</html>