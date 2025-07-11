<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Hendrik Louis Mahdi">

    <meta name="description"
        content="<?= isset($post['meta_description']) ? esc($post['meta_description']) : 'Blog website, technology, crypto' ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Helouism Blog">
    <meta property="og:title" content="<?= esc($title) ?>">
    <meta property="twitter:title" content="<?= esc($title) ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="twitter:url" content="<?= current_url() ?>">
    <meta property="og:description"
        content="<?= isset($post['meta_description']) ? esc($post['meta_description']) : 'Blog website, technology, crypto' ?>">
    <meta property="twitter:description"
        content="<?= isset($post['meta_description']) ? esc($post['meta_description']) : 'Blog website, technology, crypto' ?>">
    <?php if (isset($post['created_at'])): ?>
        <meta property="article:author" content="Hendrik Louis Mahdi">

        <meta property="article:published_time" content="<?= date('Y-m-d\TH:i:s', strtotime($post['created_at'])) ?>" />
        <meta property="article:modified_time" content="<?= date('Y-m-d\TH:i:s', strtotime($post['updated_at'])) ?>" />
    <?php endif ?>
    <?php if (isset($post['updated_at'])): ?>
        <meta property="article:modified_time" content="<?= date('Y-m-d\TH:i:s', strtotime($post['updated_at'])) ?>" />
    <?php endif ?>
    <meta name="twitter:site" content="helouism">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="canonical" href="<?= current_url() ?>">

    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BlogPosting",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "https://kootw.serv00.net"
  },
  "headline": "<?= esc($title) ?>",
  "image": "<?= isset($post['thumbnail_path']) ? base_url('uploads/thumbnails/' . $post['thumbnail_path']) : base_url('favicon.ico') ?>",  
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

    <title><?= esc($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lora:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap/bootstrap.min.css') ?>">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        media="print" onload="this.media='all'"
        integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">

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
    <?php if ($title === 'helouism'): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/home.min.css') ?>" media="print" onload="this.media='all'">
        <noscript>
            <link rel="stylesheet" href="<?= base_url('assets/css/home.min.css') ?>">
        </noscript>
    <?php endif ?>
    <?php if (isset($post['title'])): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/post.min.css') ?>" media="print" onload="this.media='all'">
        <noscript>
            <link rel="stylesheet" href="<?= base_url('assets/css/post.min.css') ?>">
        </noscript>
    <?php endif ?>


    <?php if (isset($category['name'])): ?>
        <!-- category.css -->
        <link rel="stylesheet" href="<?= base_url('assets/css/category.min.css') ?>" media="print"
            onload="this.media='all'">
        <noscript>
            <link rel="stylesheet" href="<?= base_url('assets/css/category.min.css') ?>">
        </noscript>
    <?php endif ?>
    <?php if ($title === 'All Categories'): ?>
        <link rel="stylesheet" href="<?= base_url('assets/css/category-list.min.css') ?>" media="print"
            onload="this.media='all'">
        <noscript>
            <link rel="stylesheet" href="<?= base_url('assets/css/category-list.min.css') ?>">
        </noscript>
    <?php endif ?>
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

<body>
    <?= $this->include("partials/navbar") ?>
    <main class="container-fluid py-5">
        <?= $this->renderSection("content") ?>
    </main>

    <?= $this->include("partials/footer") ?>
    <script src="<?= base_url('assets/js/bootstrap/bootstrap.bundle.min.js') ?>"></script>
    <?php echo script_tag('assets/js/theme-toggle.js'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastEl = document.getElementById('searchErrorToast');
            if (toastEl) {
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    </script>
</body>

</html>