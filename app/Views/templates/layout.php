<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
        content="<?= isset($post['meta_description']) ? esc($post['meta_description']) : 'Blog website, technology, crypto' ?>">


    <title><?= esc($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lora:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">



    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">

    <?php if ($title === 'Create Post' || $title === 'Edit Post'): ?>
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <?php endif ?>



    <style>
        .navbar,
        .footer,
        .card,
        h1 {
            font-family: Lora, "Courier New", monospace;
        }
    </style>
    <script>
        (function () {
            // Get theme from localStorage or default to dark
            const storedTheme = localStorage.getItem("bsTheme") || "dark";
            document.documentElement.setAttribute("data-bs-theme", storedTheme);
        })();
    </script>


    <?php if (isset($post['title'])): ?>
        <!-- post.css -->

        <?php echo link_tag('assets/css/post.css'); ?>

    <?php endif ?>


    <?php if (isset($category['name'])): ?>
        <!-- category.css -->
        <?php echo link_tag('assets/css/category.css'); ?>
    <?php endif ?>


    <?php if ($title === 'All Categories'): ?>
        <!-- category-list.css -->
        <?php echo link_tag('assets/css/category-list.css'); ?>
    <?php endif ?>




</head>

<body>

    <?= $this->include("partials/navbar") ?>
    <main class="container-fluid py-5">
        <?= $this->renderSection("content") ?>
    </main>
    <?= $this->include("partials/footer") ?>

    <?php if (auth()->loggedIn()): ?>
        <!-- jQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <!-- Sweet Alert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php endif ?>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>




    <?php if ($title === 'Create Post' || $title === 'Edit Post'): ?>
        <!-- Quill -->
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <?php endif ?>


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


    <?php echo script_tag('assets/js/theme-toggle.js'); ?>
</body>

</html>