<?= $this->extend('templates/layout') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <!-- Post Header -->
        <header class="post-header mb-5">
            <h1 class="display-4 fw-bold mb-4"><?= esc($post['title']) ?></h1>
            <p class="text-muted">Posted on <?= date('F j, Y', strtotime($post['created_at'])) ?> by
                <?= esc($post['username']) ?>
            </p>

            <div class="post-meta text-muted mb-4">
                <div class="d-flex align-items-center gap-3">
                    <span style="margin-left: 2px;" class="badge text-bg-primary">
                        <a href="<?= base_url('category/' . $post['category_slug']) ?>"
                            class="text-decoration-none text-light">
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
                    <span style="margin-left: 2px;"><?php echo $est; ?> read</span>
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

        <!-- Post Content -->
        <article class="post-content">

            <div class="ql-editor">
                <?= $post['content'] ?>
            </div>

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
</div>
<?= $this->endSection() ?>
<?= $this->section('pageScripts') ?>
<script>
    function copyLink() {
        navigator.clipboard.writeText(window.location.href)
            .then(() => {
                // Create a temporary div for the flash message
                const flashDiv = document.createElement('div');
                flashDiv.className = 'alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3';
                flashDiv.style.zIndex = '1050';
                flashDiv.textContent = 'Link copied!';
                document.body.appendChild(flashDiv);

                // Remove the flash message after 2 seconds
                setTimeout(() => {
                    flashDiv.remove();
                }, 2000);
            });
    }

    function enhanceCodeBlocks() {
        const blocks = document.querySelectorAll('.ql-code-block');
        blocks.forEach(block => {
            // Skip if already enhanced
            if (block.parentElement.classList.contains('code-block-wrapper')) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'code-block-wrapper';

            const header = document.createElement('div');
            header.className = 'code-block-header';

            const copyBtn = document.createElement('button');
            copyBtn.className = 'copy-btn';
            copyBtn.innerText = 'Copy';
            copyBtn.onclick = () => {
                navigator.clipboard.writeText(block.innerText).then(() => {
                    copyBtn.innerText = 'Copied!';
                    setTimeout(() => copyBtn.innerText = 'Copy', 2000);
                });
            };

            header.appendChild(copyBtn);
            wrapper.appendChild(header);
            wrapper.appendChild(block.cloneNode(true));
            block.replaceWith(wrapper);
        });
    }

    document.addEventListener("DOMContentLoaded", (event) => {
        enhanceCodeBlocks();
    });




</script>
<?= $this->endSection() ?>