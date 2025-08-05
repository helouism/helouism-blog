<?= $this->extend('templates/layout') ?>
<?= $this->section('pageStyles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/default.min.css" integrity="sha512-hasIneQUHlh06VNBe7f6ZcHmeRTLIaQWFd43YriJ0UND19bvYRauxthDg8E4eVNPm9bRUhr5JGeqH7FRFXQu5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/46.0.0/ckeditor5-content.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tocbot/4.36.4/tocbot.min.css" integrity="sha512-/ANQiHMqpRl+E0zEAd250N21OOmJsYbhiJWY0Y8yG0TII47yZn1+gDNobMlf+h/FTyImprLXYgXbX3bCYyq/vg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* Code block styling */
    .post-content {
        max-width: 100%;
        overflow-x: hidden;
    }

    /* make link color inside of post content to use bootstrap color link */
    .post-content a {
        color: #0d6efd !important;
        text-decoration: none !important;
    }

    .post-content img {
        max-width: 100%;
        height: auto;
    }
    
    .post-content a:hover {
        text-decoration: underline !important;
    }

    .post-content a:visited {
        color: purple !important;
    }

    .post-content a:focus {
        outline: none !important;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
    }

    .post-content a:active {
        color: #0a58ca !important;
    }

    .post-content pre {
        max-width: 100%;
        background: #282c34;
        border-radius: 8px;
        padding: 1.5rem;
        margin: 1.5rem 0;
        overflow-x: auto;
        white-space: pre;
    }

    .post-content pre code {
        font-family: 'Consolas', monospace;
        font-size: 0.9rem;
        line-height: 1.5;
        display: block;
        width: fit-content;
        min-width: 100%;
    }

    /* Scrollbar styling for code blocks */
    .post-content pre::-webkit-scrollbar {
        height: 8px;
    }

    .post-content pre::-webkit-scrollbar-track {
        background: #373b44;
        border-radius: 4px;
    }

    .post-content pre::-webkit-scrollbar-thumb {
        background: #666;
        border-radius: 4px;
    }

    .post-content pre::-webkit-scrollbar-thumb:hover {
        background: #888;
    }

    /* CKEditor specific overrides */
    .ck-content pre {
        max-width: 100% !important;
        width: auto !important;
    }

    /* Toast positioning */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1055;
    }

    .toc-container {
        position: sticky;
        top: 90px;
        max-height: min(80vh, calc(100vh - 120px));
        overflow-y: auto;
        max-width: 220px;
        -ms-overflow-style: none;
        scrollbar-width: none;
        height: fit-content;
        /* This is the key addition */
    }


    .toc-container .card {
        margin-bottom: 0;
        /* Remove any bottom margin from the card */
    }

    .toc-container .card-body .card-text:last-child {
        margin-bottom: 0;
        /* Remove bottom margin from last element */
    }


    .toc-container .card-body {
        padding-bottom: 0.5rem;
    }

    .toc-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
        margin-bottom: 0 !important;
    }

    .toc-list .is-collapsible {
        padding-left: 1rem;
    }


    .toc-container::-webkit-scrollbar {
        display: none;
    }




    .js-toc a {
        font-size: 0.95rem;
        color: white;
        display: block;
        padding: 4px 0;
        text-decoration: none;
    }

    .js-toc a:hover {
        color: #0d6efd;
        text-decoration: underline;
    }

    .js-toc .is-active-link {
        font-weight: bold;
        color: #0d6efd !important;
    }

    @media (max-width: 991.98px) {
        #toc-mobile-toggle {
            display: block;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1056;
        }

        #toc-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: max-content;
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.95);
            z-index: 1055;
            padding: 1rem;
            display: none;
        }

        #toc-wrapper .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .toc-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
        }

        .toc-container {
            max-width: 100%;
            max-height: unset;
        }
    }
</style>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<!-- Toast Container -->
<div class="toast-container">
    <div id="copyLinkToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-check-circle-fill me-2"></i>
                Link copied to clipboard!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="col-lg-4">
        </div>
        <!-- Post Header -->
        <header class="text-light mb-5">
            <h1 class="display-4 fw-bold mb-4"><?= esc($post['title']) ?></h1>
            <p class="text-muted">Posted on <?= date('F j, Y', strtotime($post['created_at'])) ?> by
                <?= esc($post['username']) ?>
            </p>

            <div class="mb-4">
                <div class="d-flex align-items-center gap-3">
                    <span style="margin-left: 2px;" class="badge bg-light">
                        <a href="<?= base_url('category/' . $post['category_slug']) ?>"
                            class="text-decoration-none text-dark">
                            <?= esc($category_name) ?>
                        </a>
                    </span>
                    <span style="margin-left: 2px;">•</span>

                    <?php
                    $wordsPerMinute = 200;
                    $wordCount = str_word_count(strip_tags($post['content']));
                    $minutes = ceil($wordCount / $wordsPerMinute);
                    $est = $minutes . ' min' . ($minutes == 1 ? '' : 's');
                    ?>
                    <span style="margin-left: 2px;"><?= esc($est) ?> read</span>
                </div>
            </div>
        </header> <!-- Featured Image -->
        <?php if ($post['thumbnail_path']): ?>
            <figure class="post-image mb-5 text-center">
                <?php

                $imageProperties = [
                    'src' => base_url('uploads/thumbnails/' . $post['thumbnail_path']),
                    'alt' => esc($post['title']),
                    'class' => 'img-fluid rounded shadow-sm',
                    'fetchpriority' => 'high',
                    'style' => 'width: 100%; height: auto; object-fit: cover;',

                ];

                echo img($imageProperties); ?>

                <figcaption class="figure-caption text-center mt-2">
                    <?= esc($post['thumbnail_caption']) ?>
                </figcaption>
            </figure>
        <?php endif; ?>


        <!-- TOC Toggle Button for Mobile -->
        <button id="toc-mobile-toggle" class="btn btn-primary d-lg-none">
            Table of Contents
        </button>
        <!-- Post Content -->
        <article class="post-content text-light mt-4">
            <?= $post['content'] ?>
        </article>

        <!-- Post Footer -->
        <footer class="post-footer mt-5 pt-4 border-top">

            <div class="d-flex justify-content-between align-items-center">


                <div class="post-info">
                    <?php if ($post['updated_at'] !== $post['created_at']): ?>
                        <small class="text-muted">

                            Last updated : <?= esc(date('F j, Y G:i', strtotime($post['updated_at']) + (7 * 3600))) ?>
                            Western
                            Indonesian Time
                        </small>
                    <?php endif; ?>
                </div>
                <div class="share-buttons">
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= current_url() ?>" target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?= current_url() ?>&text=<?= urlencode($post['title']) ?>"
                            target="_blank" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <button onclick="copyLink()" class="btn btn-sm btn-outline-secondary" title="Copy link">
                            <i class="bi bi-link-45deg"></i>
                        </button>

                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Table of Contents -->
    <div class="col-lg-4">
        <div class="sidebar-widget sticky-top">
            <div class="card text-white bg-primary toc-container mb-4 mt-4 sidebar-widget" id="toc-wrapper">
                <div class="card-header">
                    <span>Table of Contents</span>
                    <button class="toc-close-btn d-lg-none" id="toc-close">&times;</button>
                </div>
                <div class="card-body">
                    <div class="card-text">
                        <nav class="js-toc"></nav>
                    </div>
                </div>
            </div>

        </div>
    </div>


</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tocbot/4.36.4/tocbot.min.js" integrity="sha512-BZUSsJAPNtkd+kdY/bD7hvQKZ5zlgWt++KGy/Pa/5uEJNgs0jZ6JaX6MuclRUanowuxmhb5S3LRPEa3QmrUVfA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= base_url('assets/js/highlight/highlight.min.js') ?>"></script>
<script>hljs.highlightAll();</script>
<script>
    // Initialize highlight.js
    document.addEventListener('DOMContentLoaded', () => {

        // Add IDs to headings if they don't have one
        document.querySelectorAll('.post-content h1, .post-content h2, .post-content h3').forEach(heading => {
            if (!heading.id) {
                heading.id = heading.textContent
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-');
            }
        });

        tocbot.init({
            // Where to render the table of contents.
            tocSelector: '.js-toc',
            // Where to grab the headings to build the table of contents.
            contentSelector: '.post-content',
            // Which headings to grab inside of the contentSelector element.
            headingSelector: 'h1, h2, h3',
            // For headings inside relative or absolute positioned containers within content.
            hasInnerContainers: true,
            includeHtml: false,
            scrollSmooth: true,
            listClass: 'toc-list',
            collapsibleClass: 'is-collapsible',
            collapseDepth: 0,
            activeLinkClass: 'is-active-link',
            orderedList: false,

        });

        // Mobile TOC toggle
        document.getElementById('toc-mobile-toggle')?.addEventListener('click', () => {
            document.getElementById('toc-wrapper').style.display = 'block';
        });

        document.getElementById('toc-close')?.addEventListener('click', () => {
            document.getElementById('toc-wrapper').style.display = 'none';
        });


    });

    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            // Show Bootstrap toast instead of alert
            const toastElement = document.getElementById('copyLinkToast');
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 3000
            });
            toast.show();
        }).catch(err => {
            console.error('Failed to copy link:', err);
            // Fallback to alert if toast fails
            alert('Failed to copy link. Please try again.');
        });
    }


</script>
<?= $this->endSection() ?>