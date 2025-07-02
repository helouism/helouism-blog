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
    <meta name="twitter:site" content="helouism">
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
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
        <?php echo link_tag('assets/css/home.min.css'); ?>
    <?php endif ?>
    <?php if (isset($post['title'])): ?>
        <!-- post.css -->
        <?php echo link_tag('assets/css/post.min.css'); ?>
    <?php endif ?>


    <?php if (isset($category['name'])): ?>
        <!-- category.css -->
        <?php echo link_tag('assets/css/category.min.css'); ?>
    <?php endif ?>
    <?php if ($title === 'All Categories'): ?>
        <!-- category-list.css -->
        <?php echo link_tag('assets/css/category-list.min.css'); ?>
    <?php endif ?>
</head>

<body>
    <?= $this->include("partials/navbar") ?>
    <main class="container-fluid py-5">
        <?= $this->renderSection("content") ?>
    </main>
    <?= $this->include("partials/footer") ?>
    <?php echo script_tag('assets/js/theme-toggle.js'); ?>
</body>

</html>