<head>
  <meta charset="UTF-8">
  <meta name="robots" content="index, follow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Hendrik Louis Mahdi">

  <meta name="description"
    content="<?= isset($post['meta_description']) ? esc($post['meta_description']) : 'Thoughts and content around coding, digital tools, open projects, and how technology helps us understand, build, and make better decisions.' ?>">
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Helouism Blog">
  <meta property="og:title" content="<?= esc($title) ?>">
  <meta property="twitter:title" content="<?= esc($title) ?>">
  <meta property="og:url" content="<?= current_url() ?>">
  <meta property="twitter:url" content="<?= current_url() ?>">
  <meta property="og:description"
    content="<?= isset($post['meta_description']) ? esc($post['meta_description']) : 'Thoughts and content around coding, digital tools, open projects, and how technology helps us understand, build, and make better decisions.' ?>">
  <meta property="twitter:description"
    content="<?= isset($post['meta_description']) ? esc($post['meta_description']) : 'Thoughts and content around coding, digital tools, open projects, and how technology helps us understand, build, and make better decisions.' ?>">
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
"@id": "<?= current_url() ?>"
},
"headline": "<?= esc($title) ?>",
 "description": "<?= isset($post['meta_description']) ? esc($post['meta_description']) : 'Thoughts and content around coding, digital tools, open projects, and how technology helps us understand, build, and make better decisions.' ?>",
"image": "<?= isset($post['thumbnail_path']) ? base_url('uploads/thumbnails/' . $post['thumbnail_path']) : base_url('assets/img/helouism-logo.webp') ?>",
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

<link rel="icon" href="<?= base_url('assets/img/favicon.ico') ?>" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/img/apple-touch-icon.png') ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/img/favicon-32x32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/img/favicon-16x16.png') ?>">
  <link rel="manifest" href="<?= base_url('assets/img/site.webmanifest') ?>">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.7/dist/slate/bootstrap.min.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

  <?php if (isset($post['title'])): ?>



   
    <noscript>
       
    </noscript>
  <?php endif ?>


  <!-- Clarity -->
  <script type="text/javascript">
    (function (c, l, a, r, i, t, y) {
      c[a] = c[a] || function () {
        (c[a].q = c[a].q || []).push(arguments)
      };
      t = l.createElement(r); t.async = 1; t.src = "https://www.clarity.ms/tag/" + i;
      y = l.getElementsByTagName(r)[0]; y.parentNode.insertBefore(t, y);
    })(window, document, "clarity", "script", "se3j30hfqx");
  </script>
  <?= $this->renderSection("pageStyles") ?>


</head>